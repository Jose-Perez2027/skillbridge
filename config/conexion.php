<?php
$host = getenv("DB_HOST") ?: "localhost";
$database = getenv("DB_NAME") ?: "skillbridge_db";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD") ?: "";
$port = getenv("DB_PORT") ?: "3306";
$charset = "utf8mb4";

$dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$sslCaPath = getenv("DB_SSL_CA");

if ($sslCaPath && file_exists($sslCaPath)) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCaPath;
}

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $error) {
    error_log("SkillBridge database connection error: " . $error->getMessage());
    exit("Error connecting to the database.");
}
?>
