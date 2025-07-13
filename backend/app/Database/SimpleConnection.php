<?php

namespace App\Database;

use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\Driver\Command;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class SimpleConnection
{
    private static $instance = null;
    private $manager;
    private $databaseName = 'task_management';

    private function __construct()
    {
        $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton';

        try {
            $this->manager = new Manager($mongoUri);

            // Test the connection
            $command = new Command(['ping' => 1]);
            $this->manager->executeCommand('admin', $command);
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

    public function insertUser($userData)
    {
        $bulk = new BulkWrite();

        $document = [
            '_id' => new ObjectId(),
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'role' => $userData['role'] ?? 'user',
            'created_at' => new UTCDateTime()
        ];

        $bulk->insert($document);

        $result = $this->manager->executeBulkWrite($this->databaseName . '.users', $bulk);

        return [
            'success' => $result->getInsertedCount() > 0,
            'id' => (string) $document['_id']
        ];
    }

    public function findUserByEmail($email)
    {
        $query = new Query(['email' => $email]);
        $cursor = $this->manager->executeQuery($this->databaseName . '.users', $query);

        $users = $cursor->toArray();
        return !empty($users) ? $users[0] : null;
    }

    public function findUserById($id)
    {
        try {
            $objectId = new ObjectId($id);
            $query = new Query(['_id' => $objectId]);
            $cursor = $this->manager->executeQuery($this->databaseName . '.users', $query);

            $users = $cursor->toArray();
            return !empty($users) ? $users[0] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserTasks($userId)
    {
        try {
            $objectId = new ObjectId($userId);
            $query = new Query(['assigned_to' => $objectId]);
            $cursor = $this->manager->executeQuery($this->databaseName . '.tasks', $query);

            return $cursor->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function updateTaskStatus($taskId, $status)
    {
        try {
            $bulk = new BulkWrite();
            $objectId = new ObjectId($taskId);

            $bulk->update(
                ['_id' => $objectId],
                ['$set' => ['status' => $status, 'updated_at' => new UTCDateTime()]]
            );

            $result = $this->manager->executeBulkWrite($this->databaseName . '.tasks', $bulk);

            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllUsers()
    {
        $query = new Query([]);
        $cursor = $this->manager->executeQuery($this->databaseName . '.users', $query);
        
        return $cursor->toArray();
    }

    public function updateUser($userId, $userData)
    {
        try {
            $bulk = new BulkWrite();
            $objectId = new ObjectId($userId);
            
            $updateData = ['updated_at' => new UTCDateTime()];
            
            // Only add fields that are provided
            if (isset($userData['name'])) $updateData['name'] = $userData['name'];
            if (isset($userData['email'])) $updateData['email'] = $userData['email'];
            if (isset($userData['role'])) $updateData['role'] = $userData['role'];
            if (isset($userData['password'])) {
                $updateData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            }
            
            $bulk->update(
                ['_id' => $objectId],
                ['$set' => $updateData]
            );
            
            $result = $this->manager->executeBulkWrite($this->databaseName . '.users', $bulk);
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteUser($userId)
    {
        try {
            $bulk = new BulkWrite();
            $objectId = new ObjectId($userId);
            
            $bulk->delete(['_id' => $objectId]);
            
            $result = $this->manager->executeBulkWrite($this->databaseName . '.users', $bulk);
            
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function insertTask($taskData)
    {
        $bulk = new BulkWrite();
        
        $document = [
            '_id' => new ObjectId(),
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'assigned_to' => new ObjectId($taskData['assignedTo'] ?? $taskData['assigned_to']),
            'deadline' => new UTCDateTime(strtotime($taskData['deadline']) * 1000),
            'status' => $taskData['status'] ?? 'Pending',
            'created_at' => new UTCDateTime(),
            'updated_at' => new UTCDateTime()
        ];
        
        $bulk->insert($document);
        
        $result = $this->manager->executeBulkWrite($this->databaseName . '.tasks', $bulk);
        
        return [
            'success' => $result->getInsertedCount() > 0,
            'id' => (string)$document['_id']
        ];
    }

    public function getAllTasks()
    {
        $query = new Query([]);
        $cursor = $this->manager->executeQuery($this->databaseName . '.tasks', $query);
        
        return $cursor->toArray();
    }

    public function updateTask($taskId, $taskData)
    {
        try {
            $bulk = new BulkWrite();
            $objectId = new ObjectId($taskId);
            
            $updateData = ['updated_at' => new UTCDateTime()];
            
            if (isset($taskData['title'])) $updateData['title'] = $taskData['title'];
            if (isset($taskData['description'])) $updateData['description'] = $taskData['description'];
            if (isset($taskData['assigned_to'])) $updateData['assigned_to'] = new ObjectId($taskData['assigned_to']);
            if (isset($taskData['deadline'])) $updateData['deadline'] = new UTCDateTime(strtotime($taskData['deadline']) * 1000);
            if (isset($taskData['status'])) $updateData['status'] = $taskData['status'];
            
            $bulk->update(
                ['_id' => $objectId],
                ['$set' => $updateData]
            );
            
            $result = $this->manager->executeBulkWrite($this->databaseName . '.tasks', $bulk);
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteTask($taskId)
    {
        try {
            $bulk = new BulkWrite();
            $objectId = new ObjectId($taskId);
            
            $bulk->delete(['_id' => $objectId]);
            
            $result = $this->manager->executeBulkWrite($this->databaseName . '.tasks', $bulk);
            
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
