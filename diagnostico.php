<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

echo "<h1>SkillBridge Diagnostic</h1>";
echo "<p><strong>PHP version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Project path:</strong> " . __DIR__ . "</p>";

try {
    require_once __DIR__ . "/config/conexion.php";
    echo "<p style='color:green'><strong>Database connection:</strong> OK</p>";

    $tables = ["usuarios", "empresas", "vacantes", "postulaciones"];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($table));
        echo "<p><strong>Table {$table}:</strong> " . ($stmt->fetchColumn() ? "OK" : "MISSING") . "</p>";
    }

    $columns = [
        "usuarios" => ["idioma_preferido", "modo_oscuro", "alto_contraste", "modo_lectura", "escala_texto"],
        "vacantes" => ["titulo_en", "titulo_es", "descripcion_en", "descripcion_es", "responsabilidades_en", "responsabilidades_es", "requisitos_en", "requisitos_es", "habilidades_en", "habilidades_es"]
    ];

    foreach ($columns as $table => $cols) {
        foreach ($cols as $col) {
            $stmt = $pdo->query("SHOW COLUMNS FROM {$table} LIKE " . $pdo->quote($col));
            echo "<p><strong>{$table}.{$col}:</strong> " . ($stmt->fetchColumn() ? "OK" : "MISSING") . "</p>";
        }
    }
} catch (Throwable $error) {
    echo "<p style='color:red'><strong>Error:</strong> " . htmlspecialchars($error->getMessage()) . "</p>";
}
?>
