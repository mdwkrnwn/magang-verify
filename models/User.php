<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByLoginId(string $loginId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, login_id, name, password, role, is_active
             FROM users
             WHERE login_id = :login_id
             LIMIT 1'
        );
        $stmt->execute(['login_id' => $loginId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        $user['roles'] = $this->getRolesForUser((int) $user['id'], (string) $user['role']);
        return $user;
    }

    /**
     * Resolve a Mitra account using the official company code on its profile.
     */
    public function findActiveMitraByCode(string $companyCode): ?array
    {
        $sql = "
            SELECT u.id, u.login_id, u.name, u.role, u.is_active,
                   m.is_active AS mitra_is_active
            FROM mitra m
            INNER JOIN users u ON u.id = m.user_id
            WHERE m.kode_perusahaan = :kode_perusahaan
              AND u.is_active = TRUE
              AND m.is_active = TRUE
              AND (
                    u.role = 'mitra'
                    OR EXISTS (
                        SELECT 1 FROM user_roles ur
                        WHERE ur.user_id = u.id AND ur.role = 'mitra'
                    )
                  )
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kode_perusahaan' => $companyCode]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        $user['roles'] = $this->getRolesForUser((int) $user['id'], (string) $user['role']);
        if (!in_array('mitra', $user['roles'], true)) {
            return null;
        }
        return $user;
    }

    private function getRolesForUser(int $userId, string $primaryRole): array
    {
        $roles = [$primaryRole];
        try {
            $statement = $this->db->prepare('SELECT role FROM user_roles WHERE user_id = :user_id');
            $statement->execute(['user_id' => $userId]);
            foreach ($statement->fetchAll(PDO::FETCH_COLUMN) as $role) {
                if (is_string($role) && in_array($role, self::allowedRoles(), true)) {
                    $roles[] = $role;
                }
            }
        } catch (PDOException $exception) {
            if (!self::isMissingUserRolesTable($exception)) {
                throw $exception;
            }
        }
        return array_values(array_unique($roles));
    }

    public static function isMissingUserRolesTable(PDOException $exception): bool
    {
        // Preserve legacy single-role login when migration 030 has not been installed.
        // SQLSTATE 42P01 is PostgreSQL undefined_table. Postgres appends quoted
        // relation name in the message, so check case-insensitively for both variants.
        $code = (string) $exception->getCode();
        $message = strtolower($exception->getMessage());
        if (in_array($code, ['42P01', 'HY000'], true)) {
            return true;
        }
        return str_contains($message, 'user_roles')
            || str_contains($message, 'does not exist')
            || str_contains($message, 'no such table');
    }

    public function findById(int $id): ?array
    {
        $sql = "
        SELECT
            id,
            login_id,
            name,
            password,
            role,
            is_active
        FROM users
        WHERE id = :id
        LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        $user['roles'] = $this->getRolesForUser($id, (string) $user['role']);
        return $user;
    }


    public function updatePassword(
        int $id,
        string $passwordHash
    ): bool {
        $sql = "
        UPDATE users
        SET
            password = :password,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'password' => $passwordHash,
            'id' => $id,
        ]);
    }


    public function getManagementDetail(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, role, is_active, created_at
         FROM users
         WHERE id = :id
         LIMIT 1'
        );

        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        $user['roles'] = $this->getRolesForUser(
            $id,
            (string) $user['role']
        );

        $user['mahasiswa'] = null;
        $user['dosen'] = null;

        if (in_array('mahasiswa', $user['roles'], true)) {
            $stmt = $this->db->prepare(
                'SELECT nim, program_studi, angkatan, email, no_telepon
             FROM profil_mahasiswa
             WHERE user_id = :user_id
             LIMIT 1'
            );

            $stmt->execute(['user_id' => $id]);
            $user['mahasiswa'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        if (
            in_array('dosen', $user['roles'], true) ||
            in_array('koordinator_magang', $user['roles'], true)
        ) {
            $stmt = $this->db->prepare(
                'SELECT nidn, email, no_telepon
             FROM profil_dosen
             WHERE user_id = :user_id
             LIMIT 1'
            );

            $stmt->execute(['user_id' => $id]);
            $user['dosen'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        return $user;
    }

    public function updateManagementProfile(
        int $id,
        array $data
    ): void {
        $user = $this->getManagementDetail($id);

        if (!$user) {
            throw new InvalidArgumentException('User tidak ditemukan.');
        }

        $this->db->beginTransaction();

        try {
            // Profil mahasiswa
            if (in_array('mahasiswa', $user['roles'], true)) {
                $nim = trim((string) ($data['nim'] ?? ''));
                $programStudi = trim((string) ($data['program_studi'] ?? ''));
                $angkatan = trim((string) ($data['angkatan'] ?? ''));

                if (
                    $nim === '' ||
                    mb_strlen($nim) > 30 ||
                    mb_strlen($programStudi) > 150 ||
                    ($angkatan !== '' &&
                        (!ctype_digit($angkatan) ||
                            (int) $angkatan < 2000 ||
                            (int) $angkatan > 2100))
                ) {
                    throw new InvalidArgumentException(
                        'NIM, program studi, atau angkatan tidak valid.'
                    );
                }

                $stmt = $this->db->prepare(
                    'SELECT id FROM profil_mahasiswa
                 WHERE user_id = :user_id
                 LIMIT 1'
                );
                $stmt->execute(['user_id' => $id]);

                if ($stmt->fetchColumn() !== false) {
                    $stmt = $this->db->prepare(
                        'UPDATE profil_mahasiswa
                     SET nim = :nim,
                         program_studi = :program_studi,
                         angkatan = :angkatan,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE user_id = :user_id'
                    );
                } else {
                    $stmt = $this->db->prepare(
                        'INSERT INTO profil_mahasiswa
                     (user_id, nim, program_studi, angkatan)
                     VALUES
                     (:user_id, :nim, :program_studi, :angkatan)'
                    );
                }

                $stmt->execute([
                    'user_id' => $id,
                    'nim' => $nim,
                    'program_studi' => $programStudi !== '' ? $programStudi : null,
                    'angkatan' => $angkatan !== '' ? (int) $angkatan : null,
                ]);
            }

            // Profil dosen dan koordinator magang
            if (
                in_array('dosen', $user['roles'], true) ||
                in_array('koordinator_magang', $user['roles'], true)
            ) {
                $nidn = trim((string) ($data['nidn'] ?? ''));

                if (mb_strlen($nidn) > 30) {
                    throw new InvalidArgumentException('NIDN maksimal 30 karakter.');
                }

                $stmt = $this->db->prepare(
                    'SELECT id FROM profil_dosen
                 WHERE user_id = :user_id
                 LIMIT 1'
                );
                $stmt->execute(['user_id' => $id]);

                if ($stmt->fetchColumn() !== false) {
                    $stmt = $this->db->prepare(
                        'UPDATE profil_dosen
                     SET nidn = :nidn,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE user_id = :user_id'
                    );
                } else {
                    $stmt = $this->db->prepare(
                        'INSERT INTO profil_dosen (user_id, nidn)
                     VALUES (:user_id, :nidn)'
                    );
                }

                $stmt->execute([
                    'user_id' => $id,
                    'nidn' => $nidn !== '' ? $nidn : null,
                ]);
            }

            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }



    public function getForManagement(array $filters = []): array
    {
        $where = [];
        $params = [];

        $search = trim((string) ($filters['search'] ?? ''));
        $role = trim((string) ($filters['role'] ?? ''));
        $status = trim((string) ($filters['status'] ?? ''));

        // Pencarian nama atau ID login
        if ($search !== '') {
            $where[] = '(u.login_id ILIKE :search_login OR u.name ILIKE :search_name)';
            $params['search_login'] = '%' . $search . '%';
            $params['search_name'] = '%' . $search . '%';
        }

        // Filter berdasarkan role
        if (in_array($role, self::allowedRoles(), true)) {
            if ($this->hasUserRolesTable()) {
                $where[] = '(
                u.role = :primary_role
                OR EXISTS (
                    SELECT 1
                    FROM user_roles ur
                    WHERE ur.user_id = u.id
                    AND ur.role = :assigned_role
                )
            )';

                $params['primary_role'] = $role;
                $params['assigned_role'] = $role;
            } else {
                $where[] = 'u.role = :primary_role';
                $params['primary_role'] = $role;
            }
        }

        // Filter status akun
        if (in_array($status, ['active', 'inactive'], true)) {
            $where[] = 'u.is_active = :is_active';
            $params['is_active'] = $status === 'active';
        }

        $whereSql = $where
            ? 'WHERE ' . implode(' AND ', $where)
            : '';

        $stmt = $this->db->prepare(
            "SELECT
            u.id,
            u.login_id,
            u.name,
            u.role AS primary_role,
            u.is_active
         FROM users u
         {$whereSql}
         ORDER BY u.created_at DESC, u.id DESC"
        );

        $stmt->execute($params);

        $items = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $item['roles'] = $this->getRolesForUser(
                (int) $item['id'],
                (string) $item['primary_role']
            );

            $items[] = $item;
        }

        return $items;
    }


    public function createManaged(array $data, int $actorId): int
    {
        $loginId = trim((string) ($data['login_id'] ?? ''));
        $name = trim((string) ($data['name'] ?? ''));
        $nim = trim((string) ($data['nim'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $roles = array_values(array_unique(array_intersect(self::allowedRoles(), (array) ($data['roles'] ?? []))));
        if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $loginId)) {
            throw new InvalidArgumentException('ID login 3–50 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.');
        }
        if ($name === '' || mb_strlen($name) > 150) {
            throw new InvalidArgumentException('Nama wajib diisi dan maksimal 150 karakter.');
        }
        if (mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Password awal minimal 8 karakter.');
        }
        if ($roles === []) {
            throw new InvalidArgumentException('Pilih minimal satu role.');
        }
        if (in_array('mahasiswa', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{1,30}$/', $nim)) {
            throw new InvalidArgumentException('NIM wajib diisi dan maksimal 30 karakter alfanumerik.');
        }
        $primaryRole = $roles[0];
        $this->db->beginTransaction();
        try {
            $insert = $this->db->prepare(
                'INSERT INTO users (login_id, name, password, role)
                 VALUES (:login_id, :name, :password, :role)
                 RETURNING id'
            );
            $insert->execute([
                'login_id' => $loginId,
                'name' => $name,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $primaryRole,
            ]);
            $userId = (int) $insert->fetchColumn();
            if (!$this->hasUserRolesTable()) {
                if (count($roles) > 1) {
                    throw new RuntimeException('Skema multi-role belum tersedia. Terapkan migration 030 sebelum membuat user dengan lebih dari satu role.');
                }
            } else {
                try {
                    $roleInsert = $this->db->prepare(
                        'INSERT INTO user_roles (user_id, role, ditetapkan_oleh)
                         VALUES (:user_id, :role, :actor_id)'
                    );
                    foreach ($roles as $selectedRole) {
                        $roleInsert->execute([
                            'user_id' => $userId,
                            'role' => $selectedRole,
                            'actor_id' => $actorId,
                        ]);
                    }
                } catch (PDOException $exception) {
                    if (count($roles) > 1 || !self::isMissingUserRolesTable($exception)) {
                        throw new RuntimeException('Skema multi-role belum tersedia. Terapkan migration 030 sebelum membuat user dengan lebih dari satu role.', 0, $exception);
                    }
                }
            }
            if (in_array('mahasiswa', $roles, true)) {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_mahasiswa (user_id, nim)
                     VALUES (:user_id, :nim)'
                );
                $profile->execute(['user_id' => $userId, 'nim' => $nim]);
            }
            $this->db->commit();
            return $userId;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function updateManaged(int $id, array $data, int $actorId): void
    {
        $current = $this->findById($id);
        if (!$current) {
            throw new InvalidArgumentException('User tidak ditemukan.');
        }
        $loginId = trim((string) ($data['login_id'] ?? ''));
        $name = trim((string) ($data['name'] ?? ''));
        $nim = trim((string) ($data['nim'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $roles = array_values(array_unique(array_intersect(self::allowedRoles(), (array) ($data['roles'] ?? []))));
        $isActive = filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $isActive = $isActive ?? false;
        if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $loginId) || $name === '' || mb_strlen($name) > 150 || $roles === []) {
            throw new InvalidArgumentException('Data user tidak valid.');
        }
        if (in_array('mahasiswa', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{1,30}$/', $nim)) {
            throw new InvalidArgumentException('NIM wajib diisi dan maksimal 30 karakter alfanumerik.');
        }
        if ($password !== '' && mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Password minimal 8 karakter jika diubah.');
        }
        if ($id === $actorId && !$isActive) {
            throw new InvalidArgumentException('Anda tidak dapat menonaktifkan akun sendiri.');
        }
        $this->db->beginTransaction();
        try {
            $sql = 'UPDATE users SET login_id = :login_id, name = :name, role = :role, is_active = :is_active';
            if ($password !== '') {
                $sql .= ', password = :password';
            }
            $sql .= ', updated_at = CURRENT_TIMESTAMP WHERE id = :id';
            $params = ['login_id' => $loginId, 'name' => $name, 'role' => $roles[0], 'is_active' => $isActive, 'id' => $id];
            if ($password !== '') {
                $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->db->prepare($sql)->execute($params);
            $this->replaceRoles($id, $roles, $actorId);
            if (in_array('mahasiswa', $roles, true)) {
                $profile = $this->db->prepare('SELECT id FROM profil_mahasiswa WHERE user_id = :user_id LIMIT 1');
                $profile->execute(['user_id' => $id]);
                if ($profile->fetchColumn() === false) {
                    $this->db->prepare('INSERT INTO profil_mahasiswa (user_id, nim) VALUES (:user_id, :nim)')
                        ->execute(['user_id' => $id, 'nim' => $nim]);
                } elseif ($nim !== '') {
                    $this->db->prepare('UPDATE profil_mahasiswa SET nim = :nim, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id')
                        ->execute(['nim' => $nim, 'user_id' => $id]);
                }
            }
            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function setActive(int $id, bool $active, int $actorId): void
    {
        if ($id === $actorId && !$active) {
            throw new InvalidArgumentException('Anda tidak dapat menonaktifkan akun sendiri.');
        }
        $stmt = $this->db->prepare('UPDATE users SET is_active = :is_active, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['is_active' => $active, 'id' => $id]);
        if ($stmt->rowCount() !== 1) {
            throw new InvalidArgumentException('User tidak ditemukan.');
        }
    }

    private function replaceRoles(int $userId, array $roles, int $actorId): void
    {
        if (!$this->hasUserRolesTable()) {
            if (count($roles) > 1) {
                throw new RuntimeException('Skema multi-role belum tersedia. Terapkan migration 030 sebelum mengubah role.');
            }
            return;
        }
        $this->db->prepare('DELETE FROM user_roles WHERE user_id = :user_id')->execute(['user_id' => $userId]);
        $insert = $this->db->prepare('INSERT INTO user_roles (user_id, role, ditetapkan_oleh) VALUES (:user_id, :role, :actor_id)');
        foreach ($roles as $role) {
            $insert->execute(['user_id' => $userId, 'role' => $role, 'actor_id' => $actorId]);
        }
    }

    private function hasUserRolesTable(): bool
    {
        $statement = $this->db->prepare("SELECT to_regclass('public.user_roles')");
        $statement->execute();
        $value = $statement->fetchColumn();
        return is_string($value) && $value !== '';
    }

    public static function allowedRoles(): array
    {
        return ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik', 'mitra'];
    }
}
