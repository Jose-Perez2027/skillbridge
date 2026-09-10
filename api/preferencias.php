<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/conexion.php';

$idUsuario = $_SESSION['id_usuario'] ?? null;

function responder(array $datos, int $codigoHttp = 200): void {
    http_response_code($codigoHttp);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function limpiarIdioma($valor): string {
    $valor = is_string($valor) ? strtolower(trim($valor)) : '';
    return in_array($valor, ['en', 'es'], true) ? $valor : 'en';
}

function limpiarBooleano($valor): int {
    if (is_bool($valor)) {
        return $valor ? 1 : 0;
    }

    if (is_string($valor)) {
        $valor = strtolower(trim($valor));
        return in_array($valor, ['1', 'true', 'on', 'yes', 'si', 'sí'], true) ? 1 : 0;
    }

    return ((int)$valor) === 1 ? 1 : 0;
}

function limpiarEscala($valor): float {
    if ($valor === null || $valor === '') {
        return 1.00;
    }

    $numero = (float)$valor;

    if ($numero < 0.85 || $numero > 1.25) {
        return 1.00;
    }

    return round($numero, 2);
}

function valorEntrada(array $entrada, array $claves, $valorActual) {
    foreach ($claves as $clave) {
        if (array_key_exists($clave, $entrada)) {
            return $entrada[$clave];
        }
    }

    return $valorActual;
}

function preferenciasPorDefecto(): array {
    $idiomaCookie = $_COOKIE['skillbridgeLanguage'] ?? ($_SESSION['idioma_preferido'] ?? 'en');

    return [
        'idioma_preferido' => limpiarIdioma($idiomaCookie),
        'modo_oscuro' => 0,
        'alto_contraste' => 0,
        'modo_lectura' => 0,
        'escala_texto' => 1.00
    ];
}

function normalizarPreferencias(array $fila): array {
    return [
        'idioma_preferido' => limpiarIdioma($fila['idioma_preferido'] ?? 'en'),
        'modo_oscuro' => limpiarBooleano($fila['modo_oscuro'] ?? 0),
        'alto_contraste' => limpiarBooleano($fila['alto_contraste'] ?? 0),
        'modo_lectura' => limpiarBooleano($fila['modo_lectura'] ?? 0),
        'escala_texto' => limpiarEscala($fila['escala_texto'] ?? 1.00)
    ];
}

function leerPreferenciasUsuario(PDO $pdo, int $idUsuario): ?array {
    $stmt = $pdo->prepare("\n        SELECT idioma_preferido, modo_oscuro, alto_contraste, modo_lectura, escala_texto\n        FROM usuarios\n        WHERE id_usuario = :id_usuario\n        LIMIT 1\n    ");

    $stmt->execute([
        ':id_usuario' => $idUsuario
    ]);

    $preferencias = $stmt->fetch();

    return $preferencias ? normalizarPreferencias($preferencias) : null;
}

if (!$idUsuario) {
    responder([
        'success' => true,
        'logged_in' => false,
        'preferences' => preferenciasPorDefecto()
    ]);
}

$idUsuario = (int)$idUsuario;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $preferencias = leerPreferenciasUsuario($pdo, $idUsuario);

        if (!$preferencias) {
            responder([
                'success' => false,
                'logged_in' => false,
                'preferences' => preferenciasPorDefecto()
            ], 404);
        }

        $_SESSION['idioma_preferido'] = $preferencias['idioma_preferido'];

        setcookie(
            'skillbridgeLanguage',
            $preferencias['idioma_preferido'],
            time() + (365 * 24 * 60 * 60),
            '/'
        );

        responder([
            'success' => true,
            'logged_in' => true,
            'preferences' => $preferencias
        ]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entrada = json_decode(file_get_contents('php://input'), true);

        if (!is_array($entrada)) {
            $entrada = $_POST;
        }

        $preferenciasActuales = leerPreferenciasUsuario($pdo, $idUsuario);

        if (!$preferenciasActuales) {
            responder([
                'success' => false,
                'logged_in' => false,
                'message' => 'User preferences were not found.'
            ], 404);
        }

        $idiomaPreferido = limpiarIdioma(
            valorEntrada($entrada, ['idioma_preferido', 'language', 'lang'], $preferenciasActuales['idioma_preferido'])
        );

        $modoOscuro = limpiarBooleano(
            valorEntrada($entrada, ['modo_oscuro', 'dark_mode'], $preferenciasActuales['modo_oscuro'])
        );

        $altoContraste = limpiarBooleano(
            valorEntrada($entrada, ['alto_contraste', 'high_contrast'], $preferenciasActuales['alto_contraste'])
        );

        $modoLectura = limpiarBooleano(
            valorEntrada($entrada, ['modo_lectura', 'read_mode'], $preferenciasActuales['modo_lectura'])
        );

        $escalaTexto = limpiarEscala(
            valorEntrada($entrada, ['escala_texto', 'text_scale', 'font_scale'], $preferenciasActuales['escala_texto'])
        );

        $stmt = $pdo->prepare("\n            UPDATE usuarios\n            SET\n                idioma_preferido = :idioma_preferido,\n                modo_oscuro = :modo_oscuro,\n                alto_contraste = :alto_contraste,\n                modo_lectura = :modo_lectura,\n                escala_texto = :escala_texto\n            WHERE id_usuario = :id_usuario\n            LIMIT 1\n        ");

        $stmt->execute([
            ':idioma_preferido' => $idiomaPreferido,
            ':modo_oscuro' => $modoOscuro,
            ':alto_contraste' => $altoContraste,
            ':modo_lectura' => $modoLectura,
            ':escala_texto' => $escalaTexto,
            ':id_usuario' => $idUsuario
        ]);

        $preferenciasGuardadas = [
            'idioma_preferido' => $idiomaPreferido,
            'modo_oscuro' => $modoOscuro,
            'alto_contraste' => $altoContraste,
            'modo_lectura' => $modoLectura,
            'escala_texto' => $escalaTexto
        ];

        $_SESSION['idioma_preferido'] = $idiomaPreferido;

        setcookie(
            'skillbridgeLanguage',
            $idiomaPreferido,
            time() + (365 * 24 * 60 * 60),
            '/'
        );

        responder([
            'success' => true,
            'logged_in' => true,
            'preferences' => $preferenciasGuardadas
        ]);
    }

    responder([
        'success' => false,
        'logged_in' => true,
        'message' => 'Method not allowed.'
    ], 405);
} catch (Throwable $error) {
    error_log('SkillBridge preferences error: ' . $error->getMessage());

    responder([
        'success' => false,
        'logged_in' => (bool)$idUsuario,
        'message' => 'Preferences could not be saved.'
    ], 500);
}
?>
