<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class UserRepository
{
    public function findById(int $id): ?object
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM `user` WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? map_user($row) : null;
    }

    public function findByNicAndPassword(int $nic, string $password): ?object
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM `user` WHERE nic = ? AND password = ? LIMIT 1');
        $stmt->execute([$nic, $password]);
        $row = $stmt->fetch();

        return $row ? map_user($row) : null;
    }

    public function findByNic(int $nic): ?object
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM `user` WHERE nic = ? LIMIT 1');
        $stmt->execute([$nic]);
        $row = $stmt->fetch();

        return $row ? map_user($row) : null;
    }


    public function findByLicenceNo(string $licenceNo): ?object
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM `user` WHERE licence_no = ? LIMIT 1');
        $stmt->execute([$licenceNo]);
        $row = $stmt->fetch();

        return $row ? map_user($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO `user` (full_name, password, role, licence_no, nic, tel_no, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['full_name'],
            $data['password'],
            $data['role'],
            $data['licence_no'],
            $data['nic'],
            $data['tel_no'],
            $data['created_at'],
        ]);

        return (int) Database::pdo()->lastInsertId();
    }

    public function countAll(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM `user`')->fetchColumn();
    }

    /** @return object[] */
    public function findRecent(int $limit = 5): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM `user` ORDER BY id DESC LIMIT ?');
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(map_user(...), $stmt->fetchAll());
    }

    public function updateProfile(int $id, string $fullName, ?string $licenceNo, int $telNo, ?string $profilePic = null, bool $updateProfilePic = false): bool
    {
        if ($updateProfilePic) {
            $stmt = Database::pdo()->prepare(
                'UPDATE `user` 
                 SET full_name = ?, licence_no = ?, tel_no = ?, profile_pic = ? 
                 WHERE id = ?'
            );
            return $stmt->execute([$fullName, $licenceNo, $telNo, $profilePic, $id]);
        } else {
            $stmt = Database::pdo()->prepare(
                'UPDATE `user` 
                 SET full_name = ?, licence_no = ?, tel_no = ? 
                 WHERE id = ?'
            );
            return $stmt->execute([$fullName, $licenceNo, $telNo, $id]);
        }
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE `user` 
             SET password = ? 
             WHERE id = ?'
        );
        return $stmt->execute([$password, $id]);
    }
}

