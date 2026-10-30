
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>HelpingPlate Application Database Test</h2>";

require_once __DIR__ . '/includes/bootstrap.php';

try {
    $pdo = db();

    echo "<h3 style='color:green;'>Application Database Connected Successfully!</h3>";

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    echo "<p>Total tables: " . count($tables) . "</p>";

    echo "<h3>Tables:</h3>";

    foreach ($tables as $table) {
        echo htmlspecialchars($table) . "<br>";
    }

} catch (Throwable $e) {
    echo "<h3 style='color:red;'>Error:</h3>";
    echo htmlspecialchars($e->getMessage());
}
