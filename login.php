<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function obtenerRedirectSeguro($valor, $predeterminado = "perfil.php") {
    $valor = trim($valor ?? "");

    if ($valor === "") {
        return $predeterminado;
    }

    $ruta = parse_url($valor, PHP_URL_PATH);

    if (
        strpos($valor, "://") !== false ||
        substr($valor, 0, 1) === "/" ||
        $ruta === false ||
        pathinfo($ruta, PATHINFO_EXTENSION) !== "php"
    ) {
        return $predeterminado;
    }

    return $valor;
}

function agregarIdiomaARuta($ruta, $idioma) {
    $ruta = obtenerRedirectSeguro($ruta, "perfil.php");
    $partes = parse_url($ruta);
    $path = $partes["path"] ?? "perfil.php";
    $queryActual = [];

    if (!empty($partes["query"])) {
        parse_str($partes["query"], $queryActual);
    }

    $queryActual["lang"] = $idioma;
    $query = http_build_query($queryActual);

    return $path . ($query !== "" ? "?" . $query : "");
}

function ajustarRedirectPorRol($redirect, $tipoUsuario) {
    $redirect = obtenerRedirectSeguro($redirect, "perfil.php");
    $path = parse_url($redirect, PHP_URL_PATH) ?: "perfil.php";

    if ($tipoUsuario === "empresa") {
        $rutasCandidato = [
            "empleos.php",
            "detalle-empleo.php",
            "postulaciones.php"
        ];

        if (in_array($path, $rutasCandidato, true)) {
            return "perfil.php";
        }
    }

    if ($tipoUsuario === "candidato") {
        $rutasEmpresa = [
            "publicarvacante.php",
            "postulaciones-empresa.php"
        ];

        if (in_array($path, $rutasEmpresa, true)) {
            return "perfil.php";
        }
    }

    return $redirect;
}

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
    $idiomaSeleccionadoManualmente = true;
} elseif (!empty($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idiomaActual = $_COOKIE["skillbridgeLanguage"];
    $idiomaSeleccionadoManualmente = false;
} elseif (!empty($_SESSION["idioma_preferido"]) && in_array($_SESSION["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_SESSION["idioma_preferido"];
    $idiomaSeleccionadoManualmente = false;
} else {
    $idiomaActual = "en";
    $idiomaSeleccionadoManualmente = false;
}

$translations = [
    "en" => [
        "page_title" => "Log In | SkillBridge",
        "meta_description" => "Log in to SkillBridge to manage your applications and job opportunities.",

        "nav_home" => "Home",
        "nav_find_jobs" => "Find jobs",
        "nav_companies" => "Companies",
        "nav_login" => "Log in",
        "nav_create_account" => "Create Account",

        "aria_logo" => "SkillBridge",
        "logo_alt" => "SkillBridge logo",
        "aria_open_menu" => "Open navigation menu",
        "aria_open_accessibility" => "Open accessibility tools",
        "aria_close_accessibility" => "Close accessibility tools",
        "aria_switch_language" => "Switch to Spanish",

        "accessibility_title" => "Accessibility",
        "accessibility_text" => "Adjust the website experience to your needs.",
        "increase_text" => "Increase text size",
        "decrease_text" => "Decrease text size",
        "dark_mode" => "Dark mode",
        "high_contrast" => "High contrast",
        "read_mode" => "Read mode",
        "stop_reading" => "Stop reading",

        "accessibility_icon" => "Accessibility icon",
        "close_icon" => "Close icon",
        "menu_icon" => "Menu icon",
        "language_icon" => "Language icon",
        "email_icon" => "Email icon",
        "password_icon" => "Password icon",
        "warning_icon" => "Warning icon",
        "success_icon" => "Success icon",
        "login_icon" => "Log in icon",
        "eye_icon" => "Show or hide password icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "logout_success" => "You have logged out successfully.",
        "error_required" => "Please complete all required fields.",
        "error_email" => "Enter a valid email address.",
        "error_wrong" => "The email address or password is incorrect.",
        "error_login" => "There was an error logging in. Please try again.",

        "welcome_back" => "Welcome back",
        "login_title" => "Log in to your account",
        "login_subtitle" => "Manage your professional profile, review your applications, or manage your active job openings.",
        "email_address" => "Email address",
        "email_placeholder" => "email@example.com",
        "password" => "Password",
        "forgot_password" => "Forgot your password?",
        "password_placeholder" => "Enter your password",
        "show_hide_password" => "Show or hide password",
        "password_empty" => "The password cannot be empty.",
        "remember_me" => "Remember me on this device.",
        "login_button" => "Log In",
        "no_account" => "Don’t have an account yet?",
        "signup_free" => "Sign up for free",

        "footer_description" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_find_jobs" => "Find jobs",
        "footer_create_profile" => "Create profile",
        "footer_my_applications" => "My applications",
        "footer_resources" => "Resources and advice",
        "footer_post_job" => "Post a job",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Iniciar sesión | SkillBridge",
        "meta_description" => "Inicia sesión en SkillBridge para administrar tus postulaciones y oportunidades laborales.",

        "nav_home" => "Inicio",
        "nav_find_jobs" => "Buscar empleos",
        "nav_companies" => "Empresas",
        "nav_login" => "Iniciar sesión",
        "nav_create_account" => "Crear cuenta",

        "aria_logo" => "SkillBridge",
        "logo_alt" => "Logo de SkillBridge",
        "aria_open_menu" => "Abrir menú de navegación",
        "aria_open_accessibility" => "Abrir herramientas de accesibilidad",
        "aria_close_accessibility" => "Cerrar herramientas de accesibilidad",
        "aria_switch_language" => "Cambiar a inglés",

        "accessibility_title" => "Accesibilidad",
        "accessibility_text" => "Ajusta la experiencia del sitio según tus necesidades.",
        "increase_text" => "Aumentar tamaño del texto",
        "decrease_text" => "Disminuir tamaño del texto",
        "dark_mode" => "Modo oscuro",
        "high_contrast" => "Alto contraste",
        "read_mode" => "Modo lectura",
        "stop_reading" => "Detener lectura",

        "accessibility_icon" => "Ícono de accesibilidad",
        "close_icon" => "Ícono de cerrar",
        "menu_icon" => "Ícono de menú",
        "language_icon" => "Ícono de idioma",
        "email_icon" => "Ícono de correo electrónico",
        "password_icon" => "Ícono de contraseña",
        "warning_icon" => "Ícono de advertencia",
        "success_icon" => "Ícono de éxito",
        "login_icon" => "Ícono de iniciar sesión",
        "eye_icon" => "Ícono para mostrar u ocultar contraseña",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "logout_success" => "Has cerrado sesión correctamente.",
        "error_required" => "Completa todos los campos obligatorios.",
        "error_email" => "Ingresa un correo electrónico válido.",
        "error_wrong" => "El correo electrónico o la contraseña son incorrectos.",
        "error_login" => "Hubo un error al iniciar sesión. Inténtalo nuevamente.",

        "welcome_back" => "Bienvenido de nuevo",
        "login_title" => "Inicia sesión en tu cuenta",
        "login_subtitle" => "Administra tu perfil profesional, revisa tus postulaciones o gestiona tus vacantes activas.",
        "email_address" => "Correo electrónico",
        "email_placeholder" => "correo@ejemplo.com",
        "password" => "Contraseña",
        "forgot_password" => "¿Olvidaste tu contraseña?",
        "password_placeholder" => "Ingresa tu contraseña",
        "show_hide_password" => "Mostrar u ocultar contraseña",
        "password_empty" => "La contraseña no puede estar vacía.",
        "remember_me" => "Recordarme en este dispositivo.",
        "login_button" => "Iniciar sesión",
        "no_account" => "¿Aún no tienes una cuenta?",
        "signup_free" => "Regístrate gratis",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_find_jobs" => "Buscar empleos",
        "footer_create_profile" => "Crear perfil",
        "footer_my_applications" => "Mis postulaciones",
        "footer_resources" => "Recursos y consejos",
        "footer_post_job" => "Publicar vacante",
        "footer_find_talent" => "Encontrar talento",
        "footer_business_plans" => "Planes empresariales",
        "footer_contact" => "Contactar al equipo",
        "footer_about" => "Quiénes somos",
        "footer_accessibility" => "Accesibilidad",
        "footer_privacy" => "Privacidad",
        "footer_terms" => "Términos y condiciones",
        "footer_rights" => "© 2026 SkillBridge. Todos los derechos reservados."
    ]
];

function t($key) {
    global $translations, $idiomaActual;

    return $translations[$idiomaActual][$key]
        ?? $translations["en"][$key]
        ?? $key;
}

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

$paginaActual = basename($_SERVER["PHP_SELF"]);
$redirectPermitido = obtenerRedirectSeguro($_GET["redirect"] ?? "perfil.php");

if (isset($_SESSION["id_usuario"])) {
    $idiomaSesion = $_SESSION["idioma_preferido"] ?? $idiomaActual;

    if (!in_array($idiomaSesion, ["en", "es"], true)) {
        $idiomaSesion = $idiomaActual;
    }

    header("Location: " . agregarIdiomaARuta("perfil.php", $idiomaSesion));
    exit;
}

$errorMessage = "";
$successMessage = "";
$correoValue = "";

if (isset($_GET["logout"]) && $_GET["logout"] === "1") {
    $successMessage = t("logout_success");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = strtolower(trim($_POST["correo"] ?? ""));
    $password = $_POST["password"] ?? "";
    $redirectPermitido = obtenerRedirectSeguro($_POST["redirect"] ?? "perfil.php");
    $idiomaPost = $_POST["idioma"] ?? $idiomaActual;
    $idiomaManualPost = ($_POST["idioma_manual"] ?? "0") === "1";

    if (!in_array($idiomaPost, $idiomasPermitidos, true)) {
        $idiomaPost = $idiomaActual;
    }

    $correoValue = $correo;

    if ($correo === "" || $password === "") {
        $errorMessage = t("error_required");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = t("error_email");
    } else {
        try {
            $sql = "
                SELECT *
                FROM usuarios
                WHERE correo = :correo
                  AND estado = 1
                LIMIT 1
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":correo" => $correo
            ]);

            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario["password_hash"])) {
                session_regenerate_id(true);

                $tipoUsuarioFinal = $usuario["tipo_usuario"] ?? "candidato";
                $idiomaCuenta = $usuario["idioma_preferido"] ?? "";

                if ($idiomaManualPost) {
                    $idiomaFinal = $idiomaPost;
                } elseif (in_array($idiomaCuenta, $idiomasPermitidos, true)) {
                    $idiomaFinal = $idiomaCuenta;
                } else {
                    $idiomaFinal = $idiomaActual;
                }

                if (!in_array($idiomaFinal, $idiomasPermitidos, true)) {
                    $idiomaFinal = "en";
                }

                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["correo"] = $usuario["correo"];
                $_SESSION["tipo_usuario"] = $tipoUsuarioFinal;
                $_SESSION["idioma_preferido"] = $idiomaFinal;

                setcookie("skillbridgeLanguage", $idiomaFinal, time() + (365 * 24 * 60 * 60), "/");

                if ($idiomaManualPost || empty($idiomaCuenta)) {
                    try {
                        $actualizarIdioma = $pdo->prepare("
                            UPDATE usuarios
                            SET idioma_preferido = :idioma
                            WHERE id_usuario = :id_usuario
                        ");

                        $actualizarIdioma->execute([
                            ":idioma" => $idiomaFinal,
                            ":id_usuario" => $usuario["id_usuario"]
                        ]);
                    } catch (PDOException $e) {
                        error_log("SkillBridge login language update error: " . $e->getMessage());
                    }
                }

                $redirectRol = ajustarRedirectPorRol($redirectPermitido, $tipoUsuarioFinal);

                header("Location: " . agregarIdiomaARuta($redirectRol, $idiomaFinal));
                exit;
            }

            $errorMessage = t("error_wrong");
        } catch (PDOException $e) {
            $errorMessage = t("error_login");
        }
    }
}

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);
$registroLink = "registro.php";

if ($redirectPermitido !== "perfil.php") {
    $registroLink .= "?redirect=" . urlencode($redirectPermitido) . "&lang=" . urlencode($idiomaActual);
} else {
    $registroLink .= "?lang=" . urlencode($idiomaActual);
}

$formAction = "login.php";
$formParams = ["lang" => $idiomaActual];

if ($redirectPermitido !== "perfil.php") {
    $formParams["redirect"] = $redirectPermitido;
}

$formAction .= "?" . http_build_query($formParams);
$languageUrl = agregarIdiomaARuta("login.php" . ($redirectPermitido !== "perfil.php" ? "?redirect=" . urlencode($redirectPermitido) : ""), $idiomaSiguiente);
?>
<!DOCTYPE html>
<html lang="<?php echo limpiar($idiomaActual); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo limpiar(t("page_title")); ?></title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description" content="<?php echo limpiar(t("meta_description")); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/auth.css?v=20260904footerfinal">
</head>

<body class="auth-body">

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <button class="accessibility-button" id="accessibilityButton"
        aria-label="<?php echo limpiar(t("aria_open_accessibility")); ?>"
        title="<?php echo limpiar(t("aria_open_accessibility")); ?>">
        <i class="fa-solid fa-universal-access"
            role="img"
            aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
            title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
    </button>

    <aside class="accessibility-panel" id="accessibilityPanel"
        aria-label="<?php echo limpiar(t("accessibility_title")); ?>">
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3><?php echo limpiar(t("accessibility_title")); ?></h3>
            </div>

            <button id="closeAccessibility"
                aria-label="<?php echo limpiar(t("aria_close_accessibility")); ?>"
                title="<?php echo limpiar(t("aria_close_accessibility")); ?>">
                <i class="fa-solid fa-xmark"
                    role="img"
                    aria-label="<?php echo limpiar(t("close_icon")); ?>"
                    title="<?php echo limpiar(t("close_icon")); ?>"></i>
            </button>
        </div>

        <p class="accessibility-text">
            <?php echo limpiar(t("accessibility_text")); ?>
        </p>

        <div class="accessibility-options">

            <button class="accessibility-option" id="increaseFont"
                aria-label="<?php echo limpiar(t("increase_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-plus"
                    role="img"
                    aria-label="<?php echo limpiar(t("increase_text")); ?>"
                    title="<?php echo limpiar(t("increase_text")); ?>"></i>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont"
                aria-label="<?php echo limpiar(t("decrease_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-minus"
                    role="img"
                    aria-label="<?php echo limpiar(t("decrease_text")); ?>"
                    title="<?php echo limpiar(t("decrease_text")); ?>"></i>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode"
                aria-label="<?php echo limpiar(t("dark_mode")); ?>">
                <i class="fa-solid fa-moon"
                    role="img"
                    aria-label="<?php echo limpiar(t("dark_mode")); ?>"
                    title="<?php echo limpiar(t("dark_mode")); ?>"></i>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast"
                aria-label="<?php echo limpiar(t("high_contrast")); ?>">
                <i class="fa-solid fa-circle-half-stroke"
                    role="img"
                    aria-label="<?php echo limpiar(t("high_contrast")); ?>"
                    title="<?php echo limpiar(t("high_contrast")); ?>"></i>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage"
                aria-label="<?php echo limpiar(t("read_mode")); ?>">
                <i class="fa-solid fa-volume-high"
                    role="img"
                    aria-label="<?php echo limpiar(t("read_mode")); ?>"
                    title="<?php echo limpiar(t("read_mode")); ?>"></i>
                <span><?php echo limpiar(t("read_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading"
                aria-label="<?php echo limpiar(t("stop_reading")); ?>">
                <i class="fa-solid fa-volume-xmark"
                    role="img"
                    aria-label="<?php echo limpiar(t("stop_reading")); ?>"
                    title="<?php echo limpiar(t("stop_reading")); ?>"></i>
                <span><?php echo limpiar(t("stop_reading")); ?></span>
            </button>

        </div>
    </aside>

    <header class="header">
        <nav class="navbar container">

            <a href="index.php?lang=<?php echo limpiar($idiomaActual); ?>" class="logo"
                aria-label="<?php echo limpiar(t("aria_logo")); ?>"
                title="<?php echo limpiar(t("aria_logo")); ?>">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="<?php echo limpiar(t("logo_alt")); ?>">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button class="mobile-menu-button" id="mobileMenuButton"
                aria-label="<?php echo limpiar(t("aria_open_menu")); ?>"
                title="<?php echo limpiar(t("aria_open_menu")); ?>">
                <i class="fa-solid fa-bars"
                    role="img"
                    aria-label="<?php echo limpiar(t("menu_icon")); ?>"
                    title="<?php echo limpiar(t("menu_icon")); ?>"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="index.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("index.php"); ?>>
                        <?php echo limpiar(t("nav_home")); ?>
                    </a>
                </li>

                <li>
                    <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("empleos.php"); ?>>
                        <?php echo limpiar(t("nav_find_jobs")); ?>
                    </a>
                </li>

                <li>
                    <a href="empresas.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>
            </ul>

            <div class="nav-actions">
                <a href="login.php?lang=<?php echo limpiar($idiomaActual); ?>" class="login-link active">
                    <?php echo limpiar(t("nav_login")); ?>
                </a>

                <a href="<?php echo limpiar($registroLink); ?>" class="button button-primary button-small">
                    <?php echo limpiar(t("nav_create_account")); ?>
                </a>

                <a href="<?php echo limpiar($languageUrl); ?>"
                    class="language-toggle button button-secondary button-small"
                    aria-label="<?php echo limpiar(t("aria_switch_language")); ?>"
                    title="<?php echo limpiar(t("aria_switch_language")); ?>">
                    <i class="fa-solid fa-language"
                        role="img"
                        aria-label="<?php echo limpiar(t("language_icon")); ?>"
                        title="<?php echo limpiar(t("language_icon")); ?>"></i>
                    <?php echo limpiar($etiquetaIdiomaSiguiente); ?>
                </a>
            </div>

        </nav>
    </header>

    <main class="auth-container simple-center">

        <section class="auth-box central-box">

            <div class="auth-header">
                <span class="section-label">
                    <?php echo limpiar(t("welcome_back")); ?>
                </span>

                <h1><?php echo limpiar(t("login_title")); ?></h1>

                <p class="auth-subtitle">
                    <?php echo limpiar(t("login_subtitle")); ?>
                </p>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="auth-alert success">
                    <i class="fa-solid fa-circle-check"
                        role="img"
                        aria-label="<?php echo limpiar(t("success_icon")); ?>"
                        title="<?php echo limpiar(t("success_icon")); ?>"></i>
                    <span><?php echo limpiar($successMessage); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="auth-alert error">
                    <i class="fa-solid fa-triangle-exclamation"
                        role="img"
                        aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                        title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                    <span><?php echo limpiar($errorMessage); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo limpiar($formAction); ?>"
                method="POST" id="loginForm" class="auth-form">

                <input type="hidden" name="redirect" value="<?php echo limpiar($redirectPermitido); ?>">
                <input type="hidden" name="idioma" value="<?php echo limpiar($idiomaActual); ?>">
                <input type="hidden" name="idioma_manual" value="<?php echo $idiomaSeleccionadoManualmente ? "1" : "0"; ?>">

                <div class="form-group-custom">
                    <label for="loginEmail"><?php echo limpiar(t("email_address")); ?></label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("email_icon")); ?>"
                            title="<?php echo limpiar(t("email_icon")); ?>"></i>

                        <input type="email" id="loginEmail" name="correo"
                            placeholder="<?php echo limpiar(t("email_placeholder")); ?>"
                            value="<?php echo limpiar($correoValue); ?>"
                            autocomplete="email"
                            required>

                        <i class="fa-solid fa-circle-exclamation error-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                            title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                    </div>

                    <span class="error-text">
                        <?php echo limpiar(t("error_email")); ?>
                    </span>
                </div>

                <div class="form-group-custom">
                    <div class="label-row">
                        <label for="loginPassword"><?php echo limpiar(t("password")); ?></label>

                        <a href="recuperar-password.php?lang=<?php echo limpiar($idiomaActual); ?>" class="forgot-password">
                            <?php echo limpiar(t("forgot_password")); ?>
                        </a>
                    </div>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("password_icon")); ?>"
                            title="<?php echo limpiar(t("password_icon")); ?>"></i>

                        <input type="password" id="loginPassword" name="password"
                            placeholder="<?php echo limpiar(t("password_placeholder")); ?>"
                            autocomplete="current-password"
                            required>

                        <button type="button" class="toggle-password-button"
                            id="togglePasswordLogin"
                            aria-label="<?php echo limpiar(t("show_hide_password")); ?>"
                            title="<?php echo limpiar(t("show_hide_password")); ?>">
                            <i class="fa-solid fa-eye"
                                role="img"
                                aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                        </button>
                    </div>

                    <span class="error-text">
                        <?php echo limpiar(t("password_empty")); ?>
                    </span>
                </div>

                <div class="form-group-checkbox">
                    <label class="checkbox-container">
                        <input type="checkbox" id="rememberMe" name="recordar">

                        <span class="checkmark"></span>

                        <span class="terms-label">
                            <?php echo limpiar(t("remember_me")); ?>
                        </span>
                    </label>
                </div>

                <button type="submit" class="button button-primary btn-block" id="btnLoginSubmit">
                    <span><?php echo limpiar(t("login_button")); ?></span>
                    <i class="fa-solid fa-arrow-right-to-bracket"
                        role="img"
                        aria-label="<?php echo limpiar(t("login_icon")); ?>"
                        title="<?php echo limpiar(t("login_icon")); ?>"></i>
                </button>

                <p class="auth-switch-text">
                    <?php echo limpiar(t("no_account")); ?>
                    <a href="<?php echo limpiar($registroLink); ?>">
                        <?php echo limpiar(t("signup_free")); ?>
                    </a>
                </p>

            </form>

        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            isLoggedIn: false
        };

        try {
            localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);
            document.cookie = "skillbridgeLanguage=" + window.SkillBridgeUserPreferences.language + "; path=/; max-age=31536000";
        } catch (error) {
            console.warn("SkillBridge language preference could not be stored locally.");
        }
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
    <script src="java/auth.js?v=20260904footerfinal"></script>
</body>

</html>
