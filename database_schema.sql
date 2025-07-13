-- ===============================================
-- TaskFlow - Task Management System
-- Database: MongoDB Collections Structure
-- Author: Brian Mayoga
-- Date: July 11, 2025
-- ===============================================

-- This file contains the MongoDB collection structures and sample data
-- for the TaskFlow Task Management System

-- Note: MongoDB is a NoSQL database, so this is a representation
-- of the document structure rather than traditional SQL

-- ===============================================
-- USERS COLLECTION STRUCTURE
-- ===============================================

-- Collection: users
-- Purpose: Store user information including administrators and regular users

{
  "_id": ObjectId("generated-unique-id"),
  "name": "Full Name",
  "email": "email@example.com",
  "password": "hashed-password-using-php-password_hash",
  "role": "admin" | "user",
  "created_at": ISODate("2025-07-11T00:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T00:00:00.000Z")
}

-- Sample Users Data:

-- Administrator Account
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef0"),
  "name": "Administrator",
  "email": "admin@admin.com",
  "password": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", -- admin123
  "role": "admin",
  "created_at": ISODate("2025-01-01T00:00:00.000Z"),
  "updated_at": ISODate("2025-01-01T00:00:00.000Z")
}

-- Demo User Account
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef1"),
  "name": "Demo User",
  "email": "user@user.com",
  "password": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", -- user123
  "role": "user",
  "created_at": ISODate("2025-01-15T00:00:00.000Z"),
  "updated_at": ISODate("2025-01-15T00:00:00.000Z")
}

-- Additional Sample Users
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef2"),
  "name": "John Doe",
  "email": "john@example.com",
  "password": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", -- password123
  "role": "user",
  "created_at": ISODate("2025-02-01T00:00:00.000Z"),
  "updated_at": ISODate("2025-02-01T00:00:00.000Z")
}

{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef3"),
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", -- password123
  "role": "user",
  "created_at": ISODate("2025-02-15T00:00:00.000Z"),
  "updated_at": ISODate("2025-02-15T00:00:00.000Z")
}

-- ===============================================
-- TASKS COLLECTION STRUCTURE
-- ===============================================

-- Collection: tasks
-- Purpose: Store task information with assignments and status tracking

{
  "_id": ObjectId("generated-unique-id"),
  "title": "Task Title",
  "description": "Detailed task description",
  "assignedTo": "user-id-reference",
  "deadline": ISODate("2025-07-18T23:59:59.000Z"),
  "status": "Pending" | "In Progress" | "Completed",
  "created_at": ISODate("2025-07-11T00:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T00:00:00.000Z")
}

-- Sample Tasks Data:

-- Task 1: Documentation Task
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef4"),
  "title": "Complete Project Documentation",
  "description": "Write comprehensive documentation for the new project including API docs and user guide. This should cover all endpoints, authentication methods, and provide clear examples for developers.",
  "assignedTo": "60a1b2c3d4e5f6789abcdef1", -- Demo User ID
  "deadline": ISODate("2025-07-18T23:59:59.000Z"),
  "status": "Pending",
  "created_at": ISODate("2025-07-11T09:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T09:00:00.000Z")
}

-- Task 2: Code Review Task
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef5"),
  "title": "Review Code Changes",
  "description": "Review and approve the latest code changes in the development branch. Focus on security, performance, and code quality standards.",
  "assignedTo": "60a1b2c3d4e5f6789abcdef1", -- Demo User ID
  "deadline": ISODate("2025-07-14T17:00:00.000Z"),
  "status": "In Progress",
  "created_at": ISODate("2025-07-11T10:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T14:30:00.000Z")
}

-- Task 3: Environment Setup Task
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef6"),
  "title": "Setup Development Environment",
  "description": "Configure the local development environment with all necessary tools and dependencies. Include Docker, MongoDB, PHP, and all required extensions.",
  "assignedTo": "60a1b2c3d4e5f6789abcdef1", -- Demo User ID
  "deadline": ISODate("2025-07-12T12:00:00.000Z"),
  "status": "Completed",
  "created_at": ISODate("2025-07-10T08:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T16:45:00.000Z")
}

-- Task 4: Database Optimization
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef7"),
  "title": "Database Optimization",
  "description": "Optimize database queries and improve performance for better user experience. Create indexes, optimize aggregation pipelines, and implement caching strategies.",
  "assignedTo": "60a1b2c3d4e5f6789abcdef2", -- John Doe ID
  "deadline": ISODate("2025-07-16T18:00:00.000Z"),
  "status": "In Progress",
  "created_at": ISODate("2025-07-11T11:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T15:20:00.000Z")
}

-- Task 5: UI/UX Testing
{
  "_id": ObjectId("60a1b2c3d4e5f6789abcdef8"),
  "title": "UI/UX Testing",
  "description": "Conduct comprehensive testing of the user interface and user experience. Test responsiveness, accessibility, and cross-browser compatibility.",
  "assignedTo": "60a1b2c3d4e5f6789abcdef3", -- Jane Smith ID
  "deadline": ISODate("2025-07-21T16:00:00.000Z"),
  "status": "Pending",
  "created_at": ISODate("2025-07-11T13:00:00.000Z"),
  "updated_at": ISODate("2025-07-11T13:00:00.000Z")
}

-- ===============================================
-- MONGODB INDEXES (for performance optimization)
-- ===============================================

-- Users Collection Indexes
db.users.createIndex({ "email": 1 }, { unique: true })
db.users.createIndex({ "role": 1 })
db.users.createIndex({ "created_at": -1 })

-- Tasks Collection Indexes
db.tasks.createIndex({ "assignedTo": 1 })
db.tasks.createIndex({ "status": 1 })
db.tasks.createIndex({ "deadline": 1 })
db.tasks.createIndex({ "created_at": -1 })
db.tasks.createIndex({ "assignedTo": 1, "status": 1 })

-- ===============================================
-- MONGODB VALIDATION RULES
-- ===============================================

-- Users Collection Validation
db.createCollection("users", {
   validator: {
      $jsonSchema: {
         bsonType: "object",
         required: ["name", "email", "password", "role"],
         properties: {
            name: {
               bsonType: "string",
               description: "must be a string and is required"
            },
            email: {
               bsonType: "string",
               pattern: "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$",
               description: "must be a valid email address and is required"
            },
            password: {
               bsonType: "string",
               minLength: 6,
               description: "must be a string of at least 6 characters and is required"
            },
            role: {
               enum: ["admin", "user"],
               description: "can only be 'admin' or 'user'"
            }
         }
      }
   }
})

-- Tasks Collection Validation
db.createCollection("tasks", {
   validator: {
      $jsonSchema: {
         bsonType: "object",
         required: ["title", "description", "assignedTo", "deadline", "status"],
         properties: {
            title: {
               bsonType: "string",
               description: "must be a string and is required"
            },
            description: {
               bsonType: "string",
               description: "must be a string and is required"
            },
            assignedTo: {
               bsonType: "string",
               description: "must be a string (user ID) and is required"
            },
            deadline: {
               bsonType: "date",
               description: "must be a date and is required"
            },
            status: {
               enum: ["Pending", "In Progress", "Completed"],
               description: "can only be 'Pending', 'In Progress', or 'Completed'"
            }
         }
      }
   }
})

-- ===============================================
-- AGGREGATION QUERIES (for reporting)
-- ===============================================

-- Get task statistics by user
db.tasks.aggregate([
  {
    $group: {
      _id: "$assignedTo",
      totalTasks: { $sum: 1 },
      pendingTasks: {
        $sum: { $cond: [{ $eq: ["$status", "Pending"] }, 1, 0] }
      },
      inProgressTasks: {
        $sum: { $cond: [{ $eq: ["$status", "In Progress"] }, 1, 0] }
      },
      completedTasks: {
        $sum: { $cond: [{ $eq: ["$status", "Completed"] }, 1, 0] }
      }
    }
  },
  {
    $lookup: {
      from: "users",
      localField: "_id",
      foreignField: "_id",
      as: "user"
    }
  }
])

-- Get overdue tasks
db.tasks.find({
  "deadline": { $lt: new Date() },
  "status": { $ne: "Completed" }
})

-- Get tasks created in the last 7 days
db.tasks.find({
  "created_at": {
    $gte: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)
  }
})

-- ===============================================
-- DATABASE CONFIGURATION
-- ===============================================

-- Database Name: task_management
-- Connection String: mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton

-- Collections:
-- 1. users - Stores user accounts and authentication data
-- 2. tasks - Stores task information and assignments

-- Security Features:
-- - Passwords are hashed using PHP password_hash() function
-- - Email validation and uniqueness enforced
-- - Role-based access control (admin/user)
-- - Input validation on all fields

-- Performance Features:
-- - Indexes on frequently queried fields
-- - Efficient aggregation pipelines for statistics
-- - Optimized queries for user tasks and admin views

-- ===============================================
-- BACKUP AND RESTORE COMMANDS
-- ===============================================

-- Backup database
mongodump --uri="mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/task_management" --out ./backup

-- Restore database
mongorestore --uri="mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/task_management" ./backup/task_management

-- ===============================================
-- MAINTENANCE QUERIES
-- ===============================================

-- Clean up old completed tasks (older than 30 days)
db.tasks.deleteMany({
  "status": "Completed",
  "updated_at": {
    $lt: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
  }
})

-- Update all pending tasks that are overdue to show warning
db.tasks.updateMany(
  {
    "status": "Pending",
    "deadline": { $lt: new Date() }
  },
  {
    $set: { "isOverdue": true }
  }
)

-- ===============================================
-- END OF DATABASE SCHEMA
-- ===============================================
