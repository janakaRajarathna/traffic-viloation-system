<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class ViolationRepository
{
    public function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO violation (vehicle_id, driver_id, violation_type, fine_amount, status, description, location, vehicle_number, incident_date, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['vehicle_id'],
            $data['driver_id'],
            $data['violation_type'],
            $data['fine_amount'],
            $data['status'],
            $data['description'],
            $data['location'],
            $data['vehicle_number'],
            $data['incident_date'],
            $data['created_at'],
        ]);

        return (int) Database::pdo()->lastInsertId();
    }

    /** @return object[] */
    public function findRecentByDriver(int $driverId, int $limit = 10): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM violation WHERE driver_id = ? ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $driverId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(map_violation(...), $stmt->fetchAll());
    }

    public function sumOutstandingByDriver(int $driverId): int
    {
        $stmt = Database::pdo()->prepare(
            "SELECT COALESCE(SUM(fine_amount), 0) FROM violation WHERE driver_id = ? AND status IN ('Unpaid', 'Pending')"
        );
        $stmt->execute([$driverId]);

        return (int) $stmt->fetchColumn();
    }

    public function sumOutstandingByAll(): int
    {
        $stmt = Database::pdo()->query(
            "SELECT COALESCE(SUM(fine_amount), 0) FROM violation WHERE status IN ('Unpaid', 'Pending')"
        );

        return (int) $stmt->fetchColumn();
    }

    public function countPendingByDriver(int $driverId): int
    {
        $stmt = Database::pdo()->prepare(
            "SELECT COUNT(*) FROM violation WHERE driver_id = ? AND status IN ('Unpaid', 'Pending')"
        );
        $stmt->execute([$driverId]);

        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM violation')->fetchColumn();
    }

    public function countPending(): int
    {
        $stmt = Database::pdo()->query(
            "SELECT COUNT(*) FROM violation WHERE status IN ('Unpaid', 'Pending', 'Pending Review')"
        );

        return (int) $stmt->fetchColumn();
    }

    public function countPaid(): int
    {
        $stmt = Database::pdo()->query("SELECT COUNT(*) FROM violation WHERE status = 'Paid'");

        return (int) $stmt->fetchColumn();
    }

    public function countSince(\DateTimeInterface $since): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM violation WHERE created_at >= ?');
        $stmt->execute([$since->format('Y-m-d H:i:s')]);

        return (int) $stmt->fetchColumn();
    }

    public function countBetween(\DateTimeInterface $from, \DateTimeInterface $to): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM violation WHERE created_at >= ? AND created_at < ?'
        );
        $stmt->execute([$from->format('Y-m-d H:i:s'), $to->format('Y-m-d H:i:s')]);

        return (int) $stmt->fetchColumn();
    }

    /** @return object[] */
    public function findRecent(int $limit = 10): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM violation ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(map_violation(...), $stmt->fetchAll());
    }

    public function getMostCommonLocation(): ?string
    {
        $stmt = Database::pdo()->query(
            "SELECT location, COUNT(*) AS report_count FROM violation
             WHERE location IS NOT NULL AND location != ''
             GROUP BY location ORDER BY report_count DESC LIMIT 1"
        );
        $row = $stmt->fetch();

        return $row ? (string) $row['location'] : null;
    }

    /** @return object[] */
    public function findRecentLimited(int $limit = 5): array
    {
        return $this->findRecent($limit);
    }
}
