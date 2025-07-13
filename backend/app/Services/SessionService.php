<?php

namespace App\Services;

class SessionService
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function login($user)
    {
        self::start();
        $_SESSION['user_id'] = (string) $user['_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }
    
    public static function logout()
    {
        self::start();
        session_unset();
        session_destroy();
    }
    
    public static function isLoggedIn()
    {
        self::start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public static function getCurrentUser()
    {
        self::start();
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
            'login_time' => $_SESSION['login_time']
        ];
    }
    
    public static function isAdmin()
    {
        $user = self::getCurrentUser();
        return $user && $user['role'] === 'admin';
    }
    
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }
    
    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Admin access required']);
            exit;
        }
    }
}
