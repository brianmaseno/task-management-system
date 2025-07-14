<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Info - TaskMaster Pro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .section { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .section h3 { margin-top: 0; color: #333; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .status { padding: 5px 10px; border-radius: 3px; display: inline-block; margin: 2px; }
        .ok { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Azure Debug Information</h1>
        <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        
        <div class="section">
            <h3>📊 System Status</h3>
            <div class="status <?php echo extension_loaded('mongodb') ? 'ok' : 'error'; ?>">
                MongoDB Extension: <?php echo extension_loaded('mongodb') ? 'LOADED' : 'NOT LOADED'; ?>
            </div>
            <div class="status <?php echo file_exists(__DIR__ . '/../.env') ? 'ok' : 'error'; ?>">
                .env File: <?php echo file_exists(__DIR__ . '/../.env') ? 'EXISTS' : 'MISSING'; ?>
            </div>
            <div class="status <?php echo file_exists(__DIR__ . '/../vendor/autoload.php') ? 'ok' : 'error'; ?>">
                Composer Autoload: <?php echo file_exists(__DIR__ . '/../vendor/autoload.php') ? 'EXISTS' : 'MISSING'; ?>
            </div>
            <div class="status <?php echo session_status() === PHP_SESSION_ACTIVE ? 'ok' : 'warning'; ?>">
                PHP Sessions: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'ACTIVE' : 'INACTIVE'; ?>
            </div>
        </div>

        <div class="section">
            <h3>🌐 Server Environment</h3>
            <pre><?php
            echo "PHP Version: " . PHP_VERSION . "\n";
            echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
            echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
            echo "Script Filename: " . (__FILE__) . "\n";
            echo "Working Directory: " . getcwd() . "\n";
            echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
            echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "\n";
            ?></pre>
        </div>

        <div class="section">
            <h3>📝 Environment Variables</h3>
            <pre><?php
            // Load .env file
            if (file_exists(__DIR__ . '/../.env')) {
                $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                echo "Environment variables from .env:\n";
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, '=') !== false) {
                        list($name, $value) = explode('=', $line, 2);
                        $name = trim($name);
                        if (in_array($name, ['DB_PASSWORD', 'MAIL_PASSWORD', 'JWT_SECRET'])) {
                            echo "$name=***HIDDEN***\n";
                        } else {
                            echo "$name=" . trim($value) . "\n";
                        }
                    }
                }
            } else {
                echo "❌ .env file not found\n";
            }
            ?></pre>
        </div>

        <div class="section">
            <h3>🔍 MongoDB Connection Test</h3>
            <pre><?php
            try {
                if (extension_loaded('mongodb')) {
                    // Try to connect to MongoDB
                    if (file_exists(__DIR__ . '/../.env')) {
                        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $mongodb_uri = '';
                        foreach ($lines as $line) {
                            if (strpos($line, 'MONGODB_URI=') === 0) {
                                $mongodb_uri = trim(substr($line, 12));
                                break;
                            }
                        }
                        
                        if ($mongodb_uri) {
                            echo "Attempting MongoDB connection...\n";
                            $client = new MongoDB\Client($mongodb_uri);
                            $db = $client->task_management;
                            $collection = $db->users;
                            $count = $collection->countDocuments();
                            echo "✅ MongoDB connection successful!\n";
                            echo "User count: $count\n";
                        } else {
                            echo "❌ MONGODB_URI not found in .env\n";
                        }
                    } else {
                        echo "❌ .env file not found\n";
                    }
                } else {
                    echo "❌ MongoDB extension not loaded\n";
                }
            } catch (Exception $e) {
                echo "❌ MongoDB connection failed: " . $e->getMessage() . "\n";
            }
            ?></pre>
        </div>

        <div class="section">
            <h3>📋 Debug Log (Last 50 lines)</h3>
            <pre><?php
            $debug_log_file = __DIR__ . '/../debug.log';
            if (file_exists($debug_log_file)) {
                $lines = file($debug_log_file);
                $last_lines = array_slice($lines, -50);
                echo htmlspecialchars(implode('', $last_lines));
            } else {
                echo "No debug log found yet.";
            }
            ?></pre>
        </div>

        <div class="section">
            <h3>🗂️ File Structure Check</h3>
            <pre><?php
            function checkFile($path, $description) {
                $full_path = __DIR__ . '/../' . $path;
                $exists = file_exists($full_path);
                $status = $exists ? '✅' : '❌';
                echo "$status $description: $path " . ($exists ? '(EXISTS)' : '(MISSING)') . "\n";
            }
            
            checkFile('backend/app/Database/Connection.php', 'Database Connection');
            checkFile('backend/app/Controllers/AuthController.php', 'Auth Controller');
            checkFile('backend/routes/api.php', 'API Routes');
            checkFile('vendor/autoload.php', 'Composer Autoload');
            checkFile('.env', 'Environment File');
            checkFile('public/index.html', 'Frontend HTML');
            ?></pre>
        </div>

        <div class="section">
            <h3>🔧 Quick Actions</h3>
            <p><a href="/" style="color: #007bff;">← Back to TaskMaster Pro</a></p>
            <p><a href="debug.php" style="color: #007bff;">🔄 Refresh Debug Info</a></p>
        </div>
    </div>
</body>
</html>
