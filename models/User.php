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

    public function findByIdentityAndRole(
        string $identity,
        string $requestedRole
    ): ?array {
        $identity = trim($identity);

        if ($identity === '') {
            return null;
        }

        // Tentukan sumber identitas berdasarkan role.
        if ($requestedRole === 'mahasiswa') {
            $sql = "
            SELECT
                u.id,
                u.login_id,
                u.name,
                u.password,
                u.role,
                u.is_active
            FROM profil_mahasiswa p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.nim = :identity
              AND u.is_active = TRUE
              AND (
                    u.role = :requested_role
                    OR EXISTS (
                        SELECT 1
                        FROM user_roles ur
                        WHERE ur.user_id = u.id
                          AND ur.role = :assigned_role
                    )
              )
            LIMIT 1
        ";
        } elseif (
            in_array(
                $requestedRole,
                ['dosen', 'koordinator_magang'],
                true
            )
        ) {
            $sql = "
            SELECT
                u.id,
                u.login_id,
                u.name,
                u.password,
                u.role,
                u.is_active,
                p.id AS dosen_profile_id
            FROM profil_dosen p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.nidn = :identity
              AND u.is_active = TRUE
              AND (
                    u.role = :requested_role
                    OR EXISTS (
                        SELECT 1
                        FROM user_roles ur
                        WHERE ur.user_id = u.id
                          AND ur.role = :assigned_role
                    )
              )
            LIMIT 1
        ";
        } elseif ($requestedRole === 'tendik') {
            $sql = "
        SELECT
            u.id,
            u.login_id,
            u.name,
            u.password,
            u.role,
            u.is_active,
            pt.nip
        FROM profil_tendik pt
        INNER JOIN users u ON u.id = pt.user_id
        WHERE pt.nip = :identity
          AND u.is_active = TRUE
          AND (
                u.role = :requested_role
                OR EXISTS (
                    SELECT 1
                    FROM user_roles ur
                    WHERE ur.user_id = u.id
                      AND ur.role = :assigned_role
                )
          )
        LIMIT 1
    ";
        } else {
            return null;
        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'identity'       => $identity,
            'requested_role' => $requestedRole,
            'assigned_role'  => $requestedRole,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        // Tetap gunakan mekanisme multi-role yang sudah ada.
        $user['roles'] = $this->getRolesForUser(
            (int) $user['id'],
            (string) $user['role']
        );

        if ($requestedRole === 'koordinator_magang') {
            $user['has_dosen_profile'] = (int) ($user['dosen_profile_id'] ?? 0) > 0;
            if (
                $user['has_dosen_profile']
                && $user['role'] === 'koordinator_magang'
                && !in_array('dosen', $user['roles'], true)
            ) {
                $user['roles'][] = 'dosen';
            }
        }

        if (
            $requestedRole === 'koordinator_magang'
            && !in_array('dosen', $user['roles'], true)
        ) {
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
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($items === []) {
            return [];
        }

        $userIds = array_map(static fn(array $item): int => (int) $item['id'], $items);

        $rolesByUser = [];
        $allowedRoles = self::allowedRoles();
        try {
            $rolesStmt = $this->db->prepare(
                'SELECT user_id, role
                 FROM user_roles
                 WHERE user_id = ANY(CAST(:user_ids AS BIGINT[]))'
            );
            $rolesStmt->execute(['user_ids' => '{' . implode(',', $userIds) . '}']);
            foreach ($rolesStmt->fetchAll(PDO::FETCH_ASSOC) as $roleRow) {
                $userId = (int) $roleRow['user_id'];
                $role = (string) $roleRow['role'];
                if (in_array($role, $allowedRoles, true)) {
                    $rolesByUser[$userId][] = $role;
                }
            }
        } catch (PDOException $exception) {
            if (!self::isMissingUserRolesTable($exception)) {
                throw $exception;
            }
        }

        foreach ($items as &$item) {
            $userId = (int) $item['id'];
            $item['roles'] = array_values(array_unique(array_merge(
                [(string) $item['primary_role']],
                $rolesByUser[$userId] ?? []
            )));
        }
        unset($item);

        return $items;
    }


    public function createManaged(array $data, int $actorId): int
    {
        $name = trim((string) ($data['name'] ?? ''));
        $nim = trim((string) ($data['nim'] ?? ''));
        $nidn = trim((string) ($data['nidn'] ?? ''));
        $nip = trim((string) ($data['nip'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $submittedRoles = $data['roles'] ?? [];
        if (!is_array($submittedRoles)) {
            throw new InvalidArgumentException('Pilihan role tidak valid.');
        }
        foreach ($submittedRoles as $role) {
            if (!is_string($role) || !in_array($role, self::managedCreationRoles(), true)) {
                throw new InvalidArgumentException('Role yang dipilih tidak didukung untuk pembuatan user.');
            }
        }
        $roles = array_values(array_intersect(
            self::managedCreationRoles(),
            array_unique($submittedRoles)
        ));
        if (!self::isSupportedManagedRoleSelection($roles)) {
            throw new InvalidArgumentException('Pilih mahasiswa, dosen, tendik, atau dosen bersama Koordinator Magang. Role lain atau kombinasi tersebut tidak didukung.');
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
        if (in_array('mahasiswa', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nim)) {
            throw new InvalidArgumentException('NIM wajib diisi, 3–30 karakter, dan hanya boleh berisi huruf, angka, titik, atau tanda hubung.');
        }
        if (in_array('dosen', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nidn)) {
            throw new InvalidArgumentException('NIDN wajib diisi, 3–30 karakter, dan hanya boleh berisi huruf, angka, titik, atau tanda hubung.');
        }
        if (in_array('tendik', $roles, true) && ($nip === '' || mb_strlen($nip) > 30)) {
            throw new InvalidArgumentException('NIP wajib diisi dan maksimal 30 karakter.');
        }
        // The profile identity is also the required legacy login_id. Never create
        // a synthetic login identifier: current login and reset flows resolve the
        // role identity from its profile while retaining users.login_id.
        $loginId = in_array('mahasiswa', $roles, true)
            ? $nim
            : (in_array('dosen', $roles, true) ? $nidn : $nip);
        $primaryRole = in_array('koordinator_magang', $roles, true)
            ? 'koordinator_magang'
            : $roles[0];
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
            if (in_array('dosen', $roles, true)) {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_dosen (user_id, nidn)
                     VALUES (:user_id, :nidn)'
                );
                $profile->execute(['user_id' => $userId, 'nidn' => $nidn]);
            }
            if (in_array('tendik', $roles, true)) {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_tendik (user_id, nip)
                     VALUES (:user_id, :nip)'
                );
                $profile->execute(['user_id' => $userId, 'nip' => $nip]);
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
        $name = trim((string) ($data['name'] ?? ''));
        $nim = trim((string) ($data['nim'] ?? ''));
        $nidn = trim((string) ($data['nidn'] ?? ''));
        $nip = trim((string) ($data['nip'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $submittedRoles = $data['roles'] ?? [];
        if (!is_array($submittedRoles)) {
            throw new InvalidArgumentException('Pilihan role tidak valid.');
        }
        foreach ($submittedRoles as $role) {
            if (!is_string($role) || !in_array($role, self::managedCreationRoles(), true)) {
                throw new InvalidArgumentException('Role yang dipilih tidak didukung untuk manajemen user.');
            }
        }
        $roles = array_values(array_intersect(
            self::managedCreationRoles(),
            array_unique($submittedRoles)
        ));
        $isActive = filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $isActive = $isActive ?? false;
        if (!self::isSupportedManagedRoleSelection($roles)) {
            throw new InvalidArgumentException('Pilih mahasiswa, dosen, tendik, atau dosen bersama Koordinator Magang.');
        }
        if ($name === '' || mb_strlen($name) > 150) {
            throw new InvalidArgumentException('Data user tidak valid.');
        }
        if (in_array('mahasiswa', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{1,30}$/', $nim)) {
            throw new InvalidArgumentException('NIM wajib diisi dan maksimal 30 karakter alfanumerik.');
        }
        if (in_array('dosen', $roles, true) && !preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nidn)) {
            throw new InvalidArgumentException('NIDN wajib diisi, 3–30 karakter, dan hanya boleh berisi huruf, angka, titik, atau tanda hubung.');
        }
        if (in_array('tendik', $roles, true) && ($nip === '' || mb_strlen($nip) > 30)) {
            throw new InvalidArgumentException('NIP wajib diisi dan maksimal 30 karakter.');
        }
        if ($password !== '' && mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Password minimal 8 karakter jika diubah.');
        }
        if ($id === $actorId && !$isActive) {
            throw new InvalidArgumentException('Anda tidak dapat menonaktifkan akun sendiri.');
        }

        if (in_array('mahasiswa', $roles, true)) {
            $identity = $nim;
        } elseif (in_array('dosen', $roles, true)) {
            $identity = $nidn;
        } else {
            $identity = $nip;
        }

        $this->db->beginTransaction();
        try {
            $sql = 'UPDATE users SET login_id = :login_id, name = :name, role = :role, is_active = :is_active';
            if ($password !== '') {
                $sql .= ', password = :password';
            }
            $sql .= ', updated_at = CURRENT_TIMESTAMP WHERE id = :id';
            $primaryRole = in_array('koordinator_magang', $roles, true)
                ? 'koordinator_magang'
                : $roles[0];
            $params = ['login_id' => $identity, 'name' => $name, 'role' => $primaryRole, 'is_active' => $isActive, 'id' => $id];
            if ($password !== '') {
                $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->db->prepare($sql)->execute($params);
            $this->replaceRoles($id, $roles, $actorId);

            if (in_array('mahasiswa', $roles, true)) {
                $this->upsertManagedProfileIdentity('profil_mahasiswa', 'nim', $id, $nim);
            }
            if (in_array('dosen', $roles, true)) {
                $this->upsertManagedProfileIdentity('profil_dosen', 'nidn', $id, $nidn);
            }
            if (in_array('tendik', $roles, true)) {
                $this->upsertManagedProfileIdentity('profil_tendik', 'nip', $id, $nip);
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
        if (!self::isSupportedManagedRoleSelection($roles)) {
            throw new InvalidArgumentException('Kombinasi role pengguna tidak valid.');
        }
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

    private function upsertManagedProfileIdentity(
        string $table,
        string $identityColumn,
        int $userId,
        string $identity
    ): void {
        $allowedProfiles = [
            'profil_mahasiswa' => 'nim',
            'profil_dosen' => 'nidn',
            'profil_tendik' => 'nip',
        ];
        if (($allowedProfiles[$table] ?? null) !== $identityColumn) {
            throw new LogicException('Profil identitas tidak valid.');
        }

        $statement = $this->db->prepare(
            "SELECT id FROM {$table} WHERE user_id = :user_id LIMIT 1"
        );
        $statement->execute(['user_id' => $userId]);

        if ($statement->fetchColumn() === false) {
            $this->db->prepare(
                "INSERT INTO {$table} (user_id, {$identityColumn}) VALUES (:user_id, :identity)"
            )->execute(['user_id' => $userId, 'identity' => $identity]);
            return;
        }

        $this->db->prepare(
            "UPDATE {$table}
             SET {$identityColumn} = :identity, updated_at = CURRENT_TIMESTAMP
             WHERE user_id = :user_id"
        )->execute(['identity' => $identity, 'user_id' => $userId]);
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

    /**
     * Roles with verified profile identity columns and persistence.
     */
    public static function managedCreationRoles(): array
    {
        return ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik'];
    }

    public static function isSupportedManagedRoleSelection(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!is_string($role) || !in_array($role, self::managedCreationRoles(), true)) {
                return false;
            }
        }
        $roles = array_values(array_unique($roles));
        $roles = array_values(array_intersect(self::managedCreationRoles(), $roles));

        return in_array($roles, [
            ['mahasiswa'],
            ['dosen'],
            ['tendik'],
            ['dosen', 'koordinator_magang'],
        ], true);
    }
}
