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

        if (!in_array($requestedRole, self::allowedRoles(), true)) {

            return null;
        }

        if ($requestedRole === 'mahasiswa') {

            $table = 'profil_mahasiswa';

            $identityColumn = 'nim';
        } elseif (in_array($requestedRole, ['dosen', 'koordinator_magang'], true)) {

            $table = 'profil_dosen';

            $identityColumn = 'nidn';
        } elseif ($requestedRole === 'tendik') {

            $table = 'profil_tendik';

            $identityColumn = 'nip';
        } elseif ($requestedRole === 'mitra') {
            return null;
        } else {
            return null;
        }
        $sql = "
        SELECT
            u.id,
            u.login_id,
            u.name,
            u.password,
            u.role,
            u.is_active,
            TRUE AS has_dosen_profile
        FROM {$table} p
        INNER JOIN users u ON u.id = p.user_id
        WHERE p.{$identityColumn} = :identity
        AND u.is_active = TRUE
        AND (
                u.role = :requested_role
                OR EXISTS (
                    SELECT 1 FROM user_roles ur
                    WHERE ur.user_id = u.id
                    AND ur.role = :assigned_role
                )
        )
        LIMIT 1
         ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'identity' => $identity,
            'requested_role' => $requestedRole,
            'assigned_role' => $requestedRole,

        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        // Role dikembalikan sesuai data sebenarnya; tidak ada role tambahan otomatis.
        $user['roles'] = $this->getRolesForUser(
            (int) $user['id'],
            (string) $user['role']

        );
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
            'SELECT id, login_id, name, role, is_active, created_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        $user['roles'] = $this->getRolesForUser($id, (string) $user['role']);
        $user['mahasiswa'] = null;
        $user['dosen'] = null;
        $user['tendik'] = null;

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

        if (in_array('tendik', $user['roles'], true)) {
            $stmt = $this->db->prepare(
                'SELECT nip, email, no_telepon
                 FROM profil_tendik
                 WHERE user_id = :user_id
                 LIMIT 1'
            );
            $stmt->execute(['user_id' => $id]);
            $user['tendik'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        return $user;
    }

    public function updateManagementProfile(int $id, array $data): void
    {
        $user = $this->getManagementDetail($id);

        if (!$user) {
            throw new InvalidArgumentException('User tidak ditemukan.');
        }

        $roles = $user['roles'];
        $name = trim((string) ($data['name'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $noTelepon = trim((string) ($data['no_telepon'] ?? ''));

        if (
            (in_array('dosen', $roles, true) ||
                in_array('koordinator_magang', $roles, true) ||
                in_array('tendik', $roles, true))
        ) {
            $digits = preg_replace('/\D+/', '', $noTelepon);
            if ($noTelepon === '' || $digits === null || strlen($digits) < 8 || strlen($digits) > 15) {
                throw new InvalidArgumentException('Nomor telepon wajib diisi dan harus valid (8–15 digit).');
            }
        } elseif ($noTelepon !== '') {
            $digits = preg_replace('/\D+/', '', $noTelepon);
            if ($digits === null || strlen($digits) < 8 || strlen($digits) > 15) {
                throw new InvalidArgumentException('Nomor telepon tidak valid (8–15 digit).');
            }
        }

        $identity = (string) ($user['login_id'] ?? '');
        $profileTable = null;
        $identityColumn = null;
        $profileData = [];

        if (in_array('mahasiswa', $roles, true)) {
            $identity = trim((string) ($data['nim'] ?? ''));
            $programStudi = trim((string) ($data['program_studi'] ?? ''));
            $angkatan = trim((string) ($data['angkatan'] ?? ''));

            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $identity)) {
                throw new InvalidArgumentException('NIM wajib diisi dan harus valid.');
            }
            if ($programStudi === '' || mb_strlen($programStudi) > 150) {
                throw new InvalidArgumentException('Program studi wajib diisi dan maksimal 150 karakter.');
            }
            if ($angkatan === '' || !ctype_digit($angkatan) || (int) $angkatan < 2000 || (int) $angkatan > 2100) {
                throw new InvalidArgumentException('Angkatan tidak valid.');
            }

            $profileTable = 'profil_mahasiswa';
            $identityColumn = 'nim';
            $profileData = [
                'nim' => $identity,
                'program_studi' => $programStudi,
                'angkatan' => (int) $angkatan,
                'email' => $email,
                'no_telepon' => $noTelepon !== '' ? $noTelepon : null,
            ];
        } elseif (
            in_array('dosen', $roles, true) ||
            in_array('koordinator_magang', $roles, true)
        ) {
            $identity = trim((string) ($data['nidn'] ?? ''));
            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $identity)) {
                throw new InvalidArgumentException('NIDN wajib diisi dan harus valid.');
            }

            $profileTable = 'profil_dosen';
            $identityColumn = 'nidn';
            $profileData = [
                'nidn' => $identity,
                'email' => $email,
                'no_telepon' => $noTelepon,
            ];
        } elseif (in_array('tendik', $roles, true)) {
            $identity = trim((string) ($data['nip'] ?? ''));
            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $identity)) {
                throw new InvalidArgumentException('NIP wajib diisi dan harus valid.');
            }

            $profileTable = 'profil_tendik';
            $identityColumn = 'nip';
            $profileData = [
                'nip' => $identity,
                'email' => $email,
                'no_telepon' => $noTelepon,
            ];
        } else {
            throw new InvalidArgumentException('Role pengguna ini tidak dapat dikelola oleh Tendik.');
        }

        $this->db->beginTransaction();

        try {
            $updateUser = $this->db->prepare(
                'UPDATE users
                 SET login_id = :login_id,
                     name = :name,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $updateUser->execute([
                'login_id' => $identity,
                'name' => $name,
                'id' => $id,
            ]);

            $columns = array_keys($profileData);
            $select = $this->db->prepare(
                "SELECT id FROM {$profileTable} WHERE user_id = :user_id LIMIT 1"
            );
            $select->execute(['user_id' => $id]);

            if ($select->fetchColumn() !== false) {
                $setParts = [];
                foreach ($columns as $column) {
                    $setParts[] = "{$column} = :{$column}";
                }
                $setParts[] = 'updated_at = CURRENT_TIMESTAMP';

                $sql = "UPDATE {$profileTable}
                        SET " . implode(', ', $setParts) . "
                        WHERE user_id = :user_id";
            } else {
                $insertColumns = array_merge(['user_id'], $columns);
                $valueColumns = array_merge([':user_id'], array_map(
                    static fn(string $column): string => ':' . $column,
                    $columns
                ));
                $sql = "INSERT INTO {$profileTable} (" . implode(', ', $insertColumns) . ")
                        VALUES (" . implode(', ', $valueColumns) . ")";
            }

            $profileStmt = $this->db->prepare($sql);
            $profileStmt->execute(array_merge(
                ['user_id' => $id],
                $profileData
            ));

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
        $email = trim((string) ($data['email'] ?? ''));
        $role = trim((string) ($data['role'] ?? ''));
        $nim = trim((string) ($data['nim'] ?? ''));
        $nidn = trim((string) ($data['nidn'] ?? ''));
        $nip = trim((string) ($data['nip'] ?? ''));
        $programStudi = $role === 'mahasiswa'
            ? trim((string) ($data['program_studi'] ?? ''))
            : '';

        $angkatan = $role === 'mahasiswa'
            ? trim((string) ($data['angkatan'] ?? ''))
            : '';
        $noTelepon = trim((string) ($data['no_telepon'] ?? ''));
        if (!in_array($role, self::managedCreationRoles(), true)) {

            throw new InvalidArgumentException('Hak akses pengguna tidak valid.');
        }


        if ($role === 'mahasiswa') {

            $identity = $nim;

            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nim)) {

                throw new InvalidArgumentException('NIM wajib diisi dan harus valid.');
            }

            if ($programStudi === '' || mb_strlen($programStudi) > 150) {

                throw new InvalidArgumentException('Program studi wajib diisi.');
            }

            if ($angkatan === '' || !ctype_digit($angkatan) || (int) $angkatan < 2000 || (int) $angkatan > 2100) {

                throw new InvalidArgumentException('Angkatan tidak valid.');
            }
        } elseif (in_array($role, ['dosen', 'koordinator_magang'], true)) {

            $identity = $nidn;

            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nidn)) {

                throw new InvalidArgumentException('NIDN wajib diisi dan harus valid.');
            }
        } else {

            $identity = $nip;

            if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $nip)) {

                throw new InvalidArgumentException('NIP wajib diisi dan harus valid.');
            }
        }

        if (in_array($role, ['dosen', 'koordinator_magang', 'tendik'], true)) {

            $digits = preg_replace('/\D+/', '', $noTelepon);

            if ($noTelepon === '' || $digits === null || strlen($digits) < 8 || strlen($digits) > 15) {

                throw new InvalidArgumentException('Nomor telepon wajib diisi dan harus valid (8–15 digit).');
            }
        }

        // Password awal otomatis sama dengan identitas login dan selalu di-hash.

        $passwordHash = password_hash($identity, PASSWORD_DEFAULT);

        if ($passwordHash === false) {

            throw new RuntimeException('Gagal membuat password awal pengguna.');
        }

        $this->db->beginTransaction();
        try {
            $insert = $this->db->prepare(
                'INSERT INTO users (login_id, name, password, role)
                 VALUES (:login_id, :name, :password, :role)
                 RETURNING id'
            );

            $insert->execute([
                'login_id' => $identity,
                'name' => $name,
                'password' => $passwordHash,
                'role' => $role,
            ]);

            $userId = (int) $insert->fetchColumn();

            if ($this->hasUserRolesTable()) {
                $roleInsert = $this->db->prepare(
                    'INSERT INTO user_roles (user_id, role, ditetapkan_oleh)
                     VALUES (:user_id, :role, :actor_id)'
                );

                $roleInsert->execute([
                    'user_id' => $userId,
                    'role' => $role,
                    'actor_id' => $actorId,
                ]);
            }

            if ($role === 'mahasiswa') {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_mahasiswa
                        (user_id, nim, email, program_studi, angkatan)
                     VALUES
                        (:user_id, :nim, :email, :program_studi, :angkatan)'
                );

                $profile->execute([
                    'user_id' => $userId,
                    'nim' => $nim,
                    'email' => $email,
                    'program_studi' => $programStudi,
                    'angkatan' => (int) $angkatan,
                ]);
            } elseif (in_array($role, ['dosen', 'koordinator_magang'], true)) {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_dosen (user_id, nidn, email, no_telepon)
                     VALUES (:user_id, :nidn, :email, :no_telepon)'
                );

                $profile->execute([
                    'user_id' => $userId,
                    'nidn' => $nidn,
                    'email' => $email,
                    'no_telepon' => $noTelepon,
                ]);
            } else {
                $profile = $this->db->prepare(
                    'INSERT INTO profil_tendik (user_id, nip, email, no_telepon)
                     VALUES (:user_id, :nip, :email, :no_telepon)'
                );

                $profile->execute([
                    'user_id' => $userId,
                    'nip' => $nip,
                    'email' => $email,
                    'no_telepon' => $noTelepon,

                ]);
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
        $role = trim((string) ($data['role'] ?? ''));
        $isActive = filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOL);

        if (!in_array($role, self::managedCreationRoles(), true)) {

            throw new InvalidArgumentException('Hak akses pengguna tidak valid.');
        }

        if ($name === '' || mb_strlen($name) > 150) {

            throw new InvalidArgumentException('Nama pengguna tidak valid.');
        }

        if ($role === 'mahasiswa') {

            $identity = $nim;
        } elseif (in_array($role, ['dosen', 'koordinator_magang'], true)) {

            $identity = $nidn;
        } else {

            $identity = $nip;
        }

        if ($identity === '' || mb_strlen($identity) > 30) {

            throw new InvalidArgumentException('Identitas pengguna tidak valid.');
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
            $params = [
                'login_id' => $identity,
                'name' => $name,
                'role' => $role,
                'is_active' => $isActive,
                'id' => $id,
            ];

            if ($password !== '') {

                $sql .= ', password = :password';

                $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $sql .= ', updated_at = CURRENT_TIMESTAMP WHERE id = :id';
            $this->db->prepare($sql)->execute($params);
            $this->replaceRoles($id, [$role], $actorId);

            if ($role === 'mahasiswa') {

                $this->upsertManagedProfileIdentity('profil_mahasiswa', 'nim', $id, $nim);
            } elseif (in_array($role, ['dosen', 'koordinator_magang'], true)) {

                $this->upsertManagedProfileIdentity('profil_dosen', 'nidn', $id, $nidn);
            } else {

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
        return count($roles) === 1
            && is_string($roles[0])
            && in_array($roles[0], self::managedCreationRoles(), true);
    }
}
