<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class NotificationRepository
{
    public function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO `notification` (user_id, title, message, is_read, created_at)
             VALUES (?, ?, ?, 0, ?)'
        );
        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['message'],
            $data['created_at'] ?? (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        return (int) Database::pdo()->lastInsertId();
    }

    /** @return object[] */
    public function findByUser(int $userId, int $limit = 20): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM `notification` WHERE user_id = ? ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map($this->mapNotification(...), $stmt->fetchAll());
    }

    public function countUnreadByUser(int $userId): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM `notification` WHERE user_id = ? AND is_read = 0'
        );
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    public function markAllAsReadByUser(int $userId): bool
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE `notification` SET is_read = 1 WHERE user_id = ?'
        );
        return $stmt->execute([$userId]);
    }

    private function mapNotification(array $row): object
    {
        return (object) [
            'id' => (int) $row['id'],
            'userId' => (int) $row['user_id'],
            'title' => $row['title'],
            'message' => $row['message'],
            'isRead' => (int) $row['is_read'] === 1,
            'createdAt' => $row['created_at'] ? new \DateTimeImmutable($row['created_at']) : null,
        ];
    }
}
