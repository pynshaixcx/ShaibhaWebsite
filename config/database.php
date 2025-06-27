<?php
// Database configuration
$db_host = 'sql308.yzz.me';
$db_name = 'yzzme_39280937_shaibha_db';
$db_user = 'yzzme_39280937';
$db_pass = 'DariDaling1';
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create database connection
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log the error to a file
    $error_message = "[" . date('Y-m-d H:i:s') . "] Database connection error: " . $e->getMessage() . "\n";
    $error_message .= "DSN: " . $dsn . "\n";
    $error_message .= "File: " . $e->getFile() . "\n";
    $error_message .= "Line: " . $e->getLine() . "\n";
    $error_message .= "Trace: " . $e->getTraceAsString() . "\n\n";
    
    // Ensure logs directory exists
    $log_dir = ROOT_PATH . '/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . '/database_errors.log';
    @file_put_contents($log_file, $error_message, FILE_APPEND);
    
    // For development, show detailed error
    $debug_mode = true; // Set to false in production
    
    if ($debug_mode) {
        // Show detailed error in development
        echo "<h1>Database Connection Error</h1>";
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>DSN: " . htmlspecialchars($dsn) . "</p>";
        echo "<p>Error logged to: " . htmlspecialchars($log_file) . "</p>";
        if (file_exists($log_file) && is_readable($log_file)) {
            echo "<h3>Last 5 errors:</h3><pre>" . 
                 htmlspecialchars(implode("\n", array_slice(file($log_file), -5))) . 
                 "</pre>";
        }
        exit;
    } else {
        // Show user-friendly message in production
        if (isset($_SERVER['REQUEST_URI']) && 
            (strpos($_SERVER['REQUEST_URI'], '/shop') !== false || 
             strpos($_SERVER['REQUEST_URI'], '/cart') !== false)) {
            // For shop and cart pages
            die("<h1>We're sorry, but there was a problem connecting to our database. Please try again later.</h1>");
        } else {
            // For other pages
            die("<h1>An unexpected error occurred. Please try again later.</h1>");
        }
    }
}

// Database helper functions
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        $error_message = "Database query error: " . $e->getMessage() . "\n";
        $error_message .= "Query: " . $sql . "\n";
        $error_message .= "Params: " . json_encode($params) . "\n";
        $error_message .= "File: " . $e->getFile() . "\n";
        $error_message .= "Line: " . $e->getLine() . "\n";
        $error_message .= "Trace: " . $e->getTraceAsString() . "\n\n";
        file_put_contents(ROOT_PATH . '/logs/database_errors.log', $error_message, FILE_APPEND);
        return false;
    }
}

function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}

function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}
?>