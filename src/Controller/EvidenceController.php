<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;

final class EvidenceController
{
    public function __construct(
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
    ) {
    }

    public function byReport(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $report = $this->reports->findById($id);

        if (!$report || !$report->evidencePath) {
            http_response_code(404);
            echo 'Evidence file not found.';
            exit;
        }

        $this->serveFile($this->resolveEvidencePath($report->evidencePath));
    }

    public function show(array $params): void
    {
        $filename = $params['filename'] ?? '';
        $this->serveFile($this->resolveEvidencePath('/uploads/evidence/' . $filename));
    }

    private function serveFile(string $path): void
    {
        if (!is_file($path)) {
            http_response_code(404);
            echo 'Evidence file not found.';
            exit;
        }

        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'mp4' => 'video/mp4',
            default => 'application/octet-stream',
        };

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        readfile($path);
        exit;
    }

    private function resolveEvidencePath(string $evidencePath): string
    {
        $uploadDir = APP_ROOT . 'public/uploads/evidence';
        $filename = basename($evidencePath);
        $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (is_file($fullPath)) {
            return $fullPath;
        }

        $files = glob($uploadDir . DIRECTORY_SEPARATOR . '*') ?: [];
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            $base = basename($file);
            if (str_starts_with($base, $filename) || str_contains($base, $filename)) {
                return $file;
            }
        }

        http_response_code(404);
        echo 'Evidence file not found.';
        exit;
    }
}
