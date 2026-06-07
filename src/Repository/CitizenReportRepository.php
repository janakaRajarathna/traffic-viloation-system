<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class CitizenReportRepository
{
    public function findById(int $id): ?object
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM citizen_report WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? map_report($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO citizen_report (incident_date, location, description, evidence_path, status, created_at, user_id)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['incident_date'],
            $data['location'],
            $data['description'],
            $data['evidence_path'],
            $data['status'],
            $data['created_at'],
            $data['user_id'],
        ]);

        return (int) Database::pdo()->lastInsertId();
    }

    /** @return object[] */
    public function findRecent(int $limit = 10): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM citizen_report ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(map_report(...), $stmt->fetchAll());
    }

    /** @return object[] */
    public function findRecentByUser(int $userId, int $limit = 10): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM citizen_report WHERE user_id = ? ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(map_report(...), $stmt->fetchAll());
    }

    public function countByUser(int $userId): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM citizen_report WHERE user_id = ?');
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM citizen_report')->fetchColumn();
    }

    public function countPendingReview(): int
    {
        $stmt = Database::pdo()->query(
            "SELECT COUNT(*) FROM citizen_report WHERE status = 'Pending Review'"
        );

        return (int) $stmt->fetchColumn();
    }
}
