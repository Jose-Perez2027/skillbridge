<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function idiomaValidoLogout($idioma) {
    $idioma = strtolower(trim($idioma ?? ""));
    return in_array($idioma, ["en", "es"], true) ? $idioma : "en";
}

$idiomaActual = idiomaValidoLogout(
    $_GET["lang"]
        ?? $_SESSION["idioma_preferido"]
        ?? $_COOKIE["skillbridgeLanguage"]
        ?? "en"
);

setcookie(
    "skillbridgeLanguage",
    $idiomaActual,
    time() + (365 * 24 * 60 * 60),
    "/",
    "",
    false,
    false
);

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: login.php?logout=1&lang=" . urlencode($idiomaActual));
exit;
?>
