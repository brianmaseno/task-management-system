<?php

namespace App\Controllers;

use App\Services\Logger;

class LogController
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function getLogs()
    {
        try {
            // Check if user is admin
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
                http_response_code(403);
                return json_encode([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.'
                ]);
            }

            $lines = $_GET['lines'] ?? 100;
            $lines = min(1000, max(10, intval($lines))); // Limit between 10-1000 lines

            $logs = $this->logger->getRecentLogs($lines);

            return json_encode([
                'success' => true,
                'logs' => $logs,
                'total_lines' => count($logs),
                'log_file' => basename($this->logger->getLogPath())
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Failed to retrieve logs', ['error' => $e->getMessage()]);

            http_response_code(500);
            return json_encode([
                'success' => false,
                'message' => 'Failed to retrieve logs: ' . $e->getMessage()
            ]);
        }
    }

    public function clearLogs()
    {
        try {
            // Check if user is admin
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
                http_response_code(403);
                return json_encode([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.'
                ]);
            }

            $logPath = $this->logger->getLogPath();
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
                $this->logger->info('Log file cleared by admin', ['admin_id' => $_SESSION['user_id']]);
            }

            return json_encode([
                'success' => true,
                'message' => 'Logs cleared successfully'
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode([
                'success' => false,
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ]);
        }
    }
}
