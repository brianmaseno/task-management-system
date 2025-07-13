<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer()
    {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
            $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 587;

            // Default from address
            $this->mailer->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@taskflow.com',
                $_ENV['MAIL_FROM_NAME'] ?? 'TaskFlow System'
            );
        } catch (Exception $e) {
            error_log('Mailer configuration error: ' . $e->getMessage());
        }
    }

    public function sendTaskAssignmentEmail($user, $task)
    {
        try {
            // Only send if email credentials are configured
            if (empty($_ENV['MAIL_USERNAME']) || empty($_ENV['MAIL_PASSWORD'])) {
                error_log('Email credentials not configured - skipping email notification');
                return false;
            }

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user['email'], $user['name']);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'New Task Assigned: ' . $task['title'];
            
            $body = $this->getTaskAssignmentEmailTemplate($user, $task);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = $this->getTaskAssignmentEmailTextVersion($user, $task);

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    private function getTaskAssignmentEmailTemplate($user, $task)
    {
        $appName = $_ENV['APP_NAME'] ?? 'TaskFlow';
        $appUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>New Task Assignment</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
                .content { line-height: 1.6; color: #333; }
                .task-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff; }
                .button { display: inline-block; background: linear-gradient(45deg, #007bff, #0056b3); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📋 New Task Assigned</h1>
                </div>
                
                <div class='content'>
                    <h2>Hello {$user['name']},</h2>
                    
                    <p>You have been assigned a new task in {$appName}. Here are the details:</p>
                    
                    <div class='task-details'>
                        <h3>📌 {$task['title']}</h3>
                        <p><strong>Description:</strong><br>{$task['description']}</p>
                        <p><strong>⏰ Deadline:</strong> " . date('F j, Y g:i A', strtotime($task['deadline'])) . "</p>
                    </div>
                    
                    <p>Please log in to your dashboard to view the complete task details and update your progress.</p>
                    
                    <div style='text-align: center;'>
                        <a href='{$appUrl}/app.html' class='button'>View Task Dashboard</a>
                    </div>
                    
                    <p>If you have any questions about this task, please contact your administrator.</p>
                    
                    <p>Best regards,<br>The {$appName} Team</p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message from {$appName}. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function getTaskAssignmentEmailTextVersion($user, $task)
    {
        $appName = $_ENV['APP_NAME'] ?? 'TaskFlow';
        $appUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';
        
        return "
Hello {$user['name']},

You have been assigned a new task in {$appName}.

Task Details:
- Title: {$task['title']}
- Description: {$task['description']}
- Deadline: " . date('F j, Y g:i A', strtotime($task['deadline'])) . "

Please log in to your dashboard to view the complete task details and update your progress.

Dashboard URL: {$appUrl}/app.html

If you have any questions about this task, please contact your administrator.

Best regards,
The {$appName} Team

---
This is an automated message from {$appName}. Please do not reply to this email.
        ";
    }

    public function sendTaskStatusUpdateEmail($user, $task, $oldStatus, $newStatus)
    {
        try {
            if (empty($_ENV['MAIL_USERNAME']) || empty($_ENV['MAIL_PASSWORD'])) {
                return false;
            }

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user['email'], $user['name']);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Task Status Updated: ' . $task['title'];
            
            $body = $this->getTaskStatusUpdateEmailTemplate($user, $task, $oldStatus, $newStatus);
            $this->mailer->Body = $body;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    private function getTaskStatusUpdateEmailTemplate($user, $task, $oldStatus, $newStatus)
    {
        $appName = $_ENV['APP_NAME'] ?? 'TaskFlow';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Task Status Updated</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
                .content { line-height: 1.6; color: #333; }
                .status-change { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
                .status { padding: 5px 15px; border-radius: 20px; font-weight: bold; margin: 0 10px; }
                .status-pending { background: #fff3cd; color: #856404; }
                .status-in-progress { background: #d1ecf1; color: #0c5460; }
                .status-completed { background: #d4edda; color: #155724; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📈 Task Status Updated</h1>
                </div>
                
                <div class='content'>
                    <h2>Hello {$user['name']},</h2>
                    
                    <p>The status of your task <strong>\"{$task['title']}\"</strong> has been updated.</p>
                    
                    <div class='status-change'>
                        <p>Status changed from:</p>
                        <span class='status status-" . strtolower(str_replace(' ', '-', $oldStatus)) . "'>{$oldStatus}</span>
                        <span style='font-size: 20px;'>→</span>
                        <span class='status status-" . strtolower(str_replace(' ', '-', $newStatus)) . "'>{$newStatus}</span>
                    </div>
                    
                    <p>Keep up the great work!</p>
                    
                    <p>Best regards,<br>The {$appName} Team</p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated message from {$appName}.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
