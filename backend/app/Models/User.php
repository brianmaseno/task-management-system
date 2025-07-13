<?php

namespace App\Models;

use App\Database\Connection;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class User
{
    private $collection;

    public function __construct()
    {
        $db = Connection::getInstance();
        $this->collection = $db->getCollection('users');
    }

    public function create($userData)
    {
        // Hash password
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['created_at'] = new UTCDateTime();
        $userData['updated_at'] = new UTCDateTime();

        $result = $this->collection->insertOne($userData);
        return $result->getInsertedId();
    }

    public function findByEmail($email)
    {
        return $this->collection->findOne(['email' => $email]);
    }

    public function findById($id)
    {
        return $this->collection->findOne(['_id' => new ObjectId($id)]);
    }

    public function getAll()
    {
        return $this->collection->find()->toArray();
    }

    public function update($id, $userData)
    {
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        } else {
            unset($userData['password']);
        }

        $userData['updated_at'] = new UTCDateTime();

        return $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $userData]
        );
    }

    public function delete($id)
    {
        return $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function createDefaultAdmin()
    {
        // Check if admin user exists
        $adminExists = $this->findByEmail('admin@taskmaster.com');
        if (!$adminExists) {
            $this->create([
                'name' => 'System Administrator',
                'email' => 'admin@taskmaster.com',
                'password' => 'admin123456',
                'role' => 'admin'
            ]);
            return true;
        }
        return false;
    }

    /**
     * Authenticate user login
     */
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!$this->verifyPassword($password, $user['password'])) {
            return false;
        }

        // Update last login
        $this->collection->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['last_login' => new UTCDateTime()]]
        );

        return $user;
    }

    /**
     * Register new user with validation
     */
    public function register($userData)
    {
        // Validate required fields
        $required = ['name', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($userData[$field])) {
                throw new \Exception("$field is required");
            }
        }

        // Validate email format
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }

        // Check if email already exists
        if ($this->findByEmail($userData['email'])) {
            throw new \Exception('Email already exists');
        }

        // Set default role as 'user'
        $userData['role'] = 'user';
        $userData['is_active'] = true;

        return $this->create($userData);
    }
}
