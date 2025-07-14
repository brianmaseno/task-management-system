<?php

namespace App\Services;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Logger extends AbstractLogger
{
    private $logPath;
    private $logLevel;
    private $maxFileSize;

    public function __construct($logPath = null, $logLevel = LogLevel::INFO, $maxFileSize = 10485760) // 10MB
    {
        // HARDCODED log path for Azure deployment
        $this->logPath = $logPath ?: '/tmp/taskmaster-app.log';
        $this->logLevel = $logLevel;
        $this->maxFileSize = $maxFileSize;

        // Create logs directory if it doesn't exist
        $logDir = dirname($this->logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    public function log($level, $message, array $context = []): void
    {
        // Check if we should log this level
        if (!$this->shouldLog($level)) {
            return;
        }

        // Rotate log file if too large
        $this->rotateLogFile();

        // Format the log entry
        $timestamp = date('Y-m-d H:i:s');
        $levelUpper = strtoupper($level);

        // Add context information
        $contextString = '';
        if (!empty($context)) {
            $contextString = ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        }

        // Add request information for web requests
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $requestInfo = [
                'method' => $_SERVER['REQUEST_METHOD'],
                'uri' => $_SERVER['REQUEST_URI'] ?? '',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ];
            $contextString .= ' REQUEST:' . json_encode($requestInfo);
        }

        $logEntry = "[{$timestamp}] {$levelUpper}: {$message}{$contextString}" . PHP_EOL;

        // Write to file
        file_put_contents($this->logPath, $logEntry, FILE_APPEND | LOCK_EX);

        // Also log to system log for production
        error_log($logEntry);
    }

    private function shouldLog($level): bool
    {
        $levels = [
            LogLevel::DEBUG => 0,
            LogLevel::INFO => 1,
            LogLevel::NOTICE => 2,
            LogLevel::WARNING => 3,
            LogLevel::ERROR => 4,
            LogLevel::CRITICAL => 5,
            LogLevel::ALERT => 6,
            LogLevel::EMERGENCY => 7,
        ];

        $currentLevel = $levels[$this->logLevel] ?? 1;
        $messageLevel = $levels[$level] ?? 1;

        return $messageLevel >= $currentLevel;
    }

    private function rotateLogFile(): void
    {
        if (file_exists($this->logPath) && filesize($this->logPath) > $this->maxFileSize) {
            $rotatedPath = $this->logPath . '.' . date('Y-m-d-H-i-s');
            rename($this->logPath, $rotatedPath);
        }
    }

    public function getLogPath(): string
    {
        return $this->logPath;
    }

    public function getRecentLogs($lines = 100): array
    {
        if (!file_exists($this->logPath)) {
            return [];
        }

        $logs = [];
        $file = new \SplFileObject($this->logPath);
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $startLine = max(0, $totalLines - $lines);
        $file->seek($startLine);

        while (!$file->eof()) {
            $line = trim($file->fgets());
            if (!empty($line)) {
                $logs[] = $line;
            }
        }

        return $logs;
    }
}
