<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Collection;

class Connection
{
    private static $instance = null;
    private $client;
    private $database;

    private function __construct()
    {
        $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton';
        
        try {
            $this->client = new Client($mongoUri);
            $this->database = $this->client->selectDatabase('task_management');
            
            // Test the connection
            $this->database->command(['ping' => 1]);
        } catch (\Exception $e) {
            throw new \Exception('MongoDB connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getCollection($collectionName): Collection
    {
        return $this->database->selectCollection($collectionName);
    }
}
