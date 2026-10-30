<?php
declare(strict_types=1);
// Update these values if your local MySQL credentials differ.
const DB_HOST='127.0.0.1';
const DB_NAME='helpingplate';
const DB_USER='root';
const DB_PASS='';
function db(): PDO {
    static $pdo=null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn='mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
    try { $pdo=new PDO($dsn,DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]); }
    catch (PDOException $e) { error_log($e->getMessage()); http_response_code(500); exit('Database connection failed. Check that MySQL is running and backend/config/db.php has the correct credentials.'); }
    return $pdo;
}
