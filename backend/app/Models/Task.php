<?php

namespace App\Models;

use App\Database\Connection;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Task
{
    private $collection;

    public function __construct()
    {
        $db = Connection::getInstance();
        $this->collection = $db->getCollection('tasks');
    }

    public function create($taskData)
    {
        $taskData['created_at'] = new UTCDateTime();
        $taskData['updated_at'] = new UTCDateTime();

        // Convert deadline to MongoDB date
        if (isset($taskData['deadline'])) {
            $taskData['deadline'] = new UTCDateTime(strtotime($taskData['deadline']) * 1000);
        }

        $result = $this->collection->insertOne($taskData);
        return $result->getInsertedId();
    }

    public function findById($id)
    {
        return $this->collection->findOne(['_id' => new ObjectId($id)]);
    }

    public function findByUserId($userId)
    {
        return $this->collection->find(['assignedTo' => $userId])->toArray();
    }

    public function getAll()
    {
        return $this->collection->find()->toArray();
    }

    public function update($id, $taskData)
    {
        $taskData['updated_at'] = new UTCDateTime();

        // Convert deadline to MongoDB date if provided
        if (isset($taskData['deadline'])) {
            $taskData['deadline'] = new UTCDateTime(strtotime($taskData['deadline']) * 1000);
        }

        return $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $taskData]
        );
    }

    public function updateStatus($id, $status)
    {
        return $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            [
                '$set' => [
                    'status' => $status,
                    'updated_at' => new UTCDateTime()
                ]
            ]
        );
    }

    public function delete($id)
    {
        return $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    public function getStatsByUser($userId)
    {
        $tasks = $this->findByUserId($userId);
        $stats = [
            'total' => count($tasks),
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0
        ];

        foreach ($tasks as $task) {
            switch (strtolower($task['status'])) {
                case 'pending':
                    $stats['pending']++;
                    break;
                case 'in progress':
                    $stats['in_progress']++;
                    break;
                case 'completed':
                    $stats['completed']++;
                    break;
            }
        }

        return $stats;
    }
}
