<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class VehicleRepository
{
    public function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO vehicle (vehicle_id, vehicle_no, owner_id, model, chassi_no, eng_no)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['vehicle_id'],
            $data['vehicle_no'],
            $data['owner_id'],
            $data['model'],
            $data['chassi_no'],
            $data['eng_no'],
        ]);

        return (int) Database::pdo()->lastInsertId();
    }

    /** @return object[] */
    public function findByOwnerId(int $ownerId): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM vehicle WHERE owner_id = ?');
        $stmt->execute([$ownerId]);

        return array_map(map_vehicle(...), $stmt->fetchAll());
    }

    public function countAll(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM vehicle')->fetchColumn();
    }
}
