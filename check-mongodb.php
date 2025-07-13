<?php
echo "=== MongoDB Extension Check ===" . PHP_EOL;

if (extension_loaded('mongodb')) {
    echo "✅ MongoDB extension is loaded" . PHP_EOL;
    echo "Extension version: " . phpversion('mongodb') . PHP_EOL;

    // Check available classes
    $classes = get_declared_classes();
    $mongoClasses = array_filter($classes, function ($class) {
        return strpos($class, 'MongoDB') === 0;
    });

    echo "Available MongoDB classes:" . PHP_EOL;
    foreach ($mongoClasses as $class) {
        echo "  - $class" . PHP_EOL;
    }

    // Check specific problematic classes
    echo PHP_EOL . "=== Checking specific classes ===" . PHP_EOL;
    $checkClasses = [
        'MongoDB\BSON\UTCDateTime',
        'MongoDB\Model\BSONArray',
        'MongoDB\BSON\Serializable'
    ];

    foreach ($checkClasses as $class) {
        if (class_exists($class)) {
            echo "✅ $class exists" . PHP_EOL;
        } else {
            echo "❌ $class NOT found" . PHP_EOL;
        }
    }

} else {
    echo "❌ MongoDB extension is NOT loaded" . PHP_EOL;
}

echo PHP_EOL . "=== PHP Version ===" . PHP_EOL;
echo "PHP Version: " . PHP_VERSION . PHP_EOL;
?>