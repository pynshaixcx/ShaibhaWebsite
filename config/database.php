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
    $error_message = "Database connection error: " . $e->getMessage() . "\n";
    $error_message .= "File: " . $e->getFile() . "\n";
    $error_message .= "Line: " . $e->getLine() . "\n";
    $error_message .= "Trace: " . $e->getTraceAsString() . "\n\n";
    file_put_contents(ROOT_PATH . '/logs/database_errors.log', $error_message, FILE_APPEND);
    
    // Display a user-friendly error message
    if (strpos($_SERVER['REQUEST_URI'], '/shop') !== false || strpos($_SERVER['REQUEST_URI'], '/cart') !== false) {
        // For shop and cart pages, show a specific message
        die("<h1>We're sorry, but there was a problem connecting to our database. Please try again later.</h1>");
    } else {
        // For other pages, show a generic error
        die("<h1>An unexpected error occurred. Please try again later.</h1>");
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