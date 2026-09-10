<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$idUsuario = $_SESSION["id_usuario"] ?? null;
$tipoUsuario = $_SESSION["tipo_usuario"] ?? "";
$paginaActual = basename($_SERVER["PHP_SELF"]);
$usuarioActual = null;

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

$idiomasPermitidos = ["en", "es"];

if ($usuarioLogueado) {
    try {
        $stmtUsuario = $pdo->prepare("
            SELECT *
            FROM usuarios
            WHERE id_usuario = :id_usuario
            LIMIT 1
        ");

        $stmtUsuario->execute([
            ":id_usuario" => $idUsuario
        ]);

        $usuarioActual = $stmtUsuario->fetch();

        if ($usuarioActual) {
            $tipoUsuario = $usuarioActual["tipo_usuario"] ?? $tipoUsuario;
            $_SESSION["tipo_usuario"] = $tipoUsuario;
            $_SESSION["nombre"] = $usuarioActual["nombre"] ?? ($_SESSION["nombre"] ?? "");
            $_SESSION["correo"] = $usuarioActual["correo"] ?? ($_SESSION["correo"] ?? "");
        } else {
            session_unset();
            session_destroy();
            $usuarioLogueado = false;
            $idUsuario = null;
            $tipoUsuario = "";
        }
    } catch (PDOException $e) {
        $usuarioActual = null;
    }
}

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif ($usuarioActual && !empty($usuarioActual["idioma_preferido"])) {
    $idiomaActual = $usuarioActual["idioma_preferido"];
} elseif (!empty($_SESSION["idioma_preferido"]) && in_array($_SESSION["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_SESSION["idioma_preferido"];
} elseif (!empty($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idiomaActual = $_COOKIE["skillbridgeLanguage"];
} else {
    $idiomaActual = "en";
}

if (!in_array($idiomaActual, $idiomasPermitidos, true)) {
    $idiomaActual = "en";
}

$_SESSION["idioma_preferido"] = $idiomaActual;
setcookie("skillbridgeLanguage", $idiomaActual, time() + (365 * 24 * 60 * 60), "/");

if ($usuarioLogueado && $usuarioActual && isset($_GET["lang"])) {
    try {
        $actualizarIdioma = $pdo->prepare("
            UPDATE usuarios
            SET idioma_preferido = :idioma
            WHERE id_usuario = :id_usuario
        ");

        $actualizarIdioma->execute([
            ":idioma" => $idiomaActual,
            ":id_usuario" => $idUsuario
        ]);

        $usuarioActual["idioma_preferido"] = $idiomaActual;
    } catch (PDOException $e) {
        $_SESSION["idioma_preferido"] = $idiomaActual;
    }
}

$translations = [
    "en" => [
        "page_title" => "Contact | SkillBridge",
        "meta_description" => "Contact the SkillBridge team for support, questions, or suggestions.",
        "skip_main" => "Skip to main content",

        "nav_home" => "Home",
        "nav_find_jobs" => "Find jobs",
        "nav_companies" => "Companies",
        "nav_my_applications" => "My applications",
        "nav_post_job" => "Post a job",
        "nav_received_applications" => "Received applications",
        "nav_profile" => "Profile",
        "nav_my_profile" => "My profile",
        "nav_logout" => "Log out",
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
        "user_icon" => "User icon",
        "email_icon" => "Email icon",
        "message_icon" => "Message icon",
        "send_icon" => "Send icon",
        "check_icon" => "Success icon",
        "warning_icon" => "Warning icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",
        "briefcase_icon" => "Job icon",
        "building_icon" => "Company icon",

        "success_sent" => "Your message was sent successfully.",
        "error_name_required" => "Please enter your full name.",
        "error_name_short" => "Your name must contain at least 3 characters.",
        "error_email_required" => "Please enter your email address.",
        "error_email_invalid" => "Please enter a valid email address.",
        "error_message_required" => "Please write your message.",
        "error_message_short" => "Your message must contain at least 10 characters.",

        "hero_label" => "Contact us",
        "hero_title" => "Contact the SkillBridge team.",
        "hero_text" => "Send us your questions, suggestions, or support requests. We are here to help candidates and companies use the platform.",
        "hero_card_title" => "Support and guidance",
        "hero_card_text" => "Our goal is to make accessible job opportunities easier to understand, publish, and apply for.",

        "form_label" => "Message",
        "form_title" => "Send us a message",
        "form_text" => "Complete the form and the SkillBridge team will receive your request.",
        "name_label" => "Full name",
        "name_placeholder" => "Your name",
        "email_label" => "Email address",
        "email_placeholder" => "email@example.com",
        "message_label" => "Message",
        "message_placeholder" => "Write your message here...",
        "send_message" => "Send message",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_find_jobs" => "Find jobs",
        "footer_create_profile" => "Create profile",
        "footer_my_applications" => "My applications",
        "footer_resources" => "Resources and tips",
        "footer_post_job" => "Post a job",
        "footer_received_applications" => "Received applications",
        "footer_companies" => "Companies",
        "footer_contact" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Contacto | SkillBridge",
        "meta_description" => "Contacta al equipo de SkillBridge para recibir apoyo, hacer preguntas o enviar sugerencias.",
        "skip_main" => "Saltar al contenido principal",

        "nav_home" => "Inicio",
        "nav_find_jobs" => "Buscar empleos",
        "nav_companies" => "Empresas",
        "nav_my_applications" => "Mis postulaciones",
        "nav_post_job" => "Publicar vacante",
        "nav_received_applications" => "Postulaciones recibidas",
        "nav_profile" => "Perfil",
        "nav_my_profile" => "Mi perfil",
        "nav_logout" => "Cerrar sesión",
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
        "user_icon" => "Ícono de usuario",
        "email_icon" => "Ícono de correo",
        "message_icon" => "Ícono de mensaje",
        "send_icon" => "Ícono de enviar",
        "check_icon" => "Ícono de éxito",
        "warning_icon" => "Ícono de advertencia",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",
        "briefcase_icon" => "Ícono de empleo",
        "building_icon" => "Ícono de empresa",

        "success_sent" => "Tu mensaje se envió correctamente.",
        "error_name_required" => "Ingresa tu nombre completo.",
        "error_name_short" => "Tu nombre debe tener al menos 3 caracteres.",
        "error_email_required" => "Ingresa tu correo electrónico.",
        "error_email_invalid" => "Ingresa un correo electrónico válido.",
        "error_message_required" => "Escribe tu mensaje.",
        "error_message_short" => "Tu mensaje debe tener al menos 10 caracteres.",

        "hero_label" => "Contáctanos",
        "hero_title" => "Contacta al equipo de SkillBridge.",
        "hero_text" => "Envíanos tus preguntas, sugerencias o solicitudes de apoyo. Estamos aquí para ayudar a candidatos y empresas a usar la plataforma.",
        "hero_card_title" => "Apoyo y orientación",
        "hero_card_text" => "Nuestro objetivo es hacer que las oportunidades laborales accesibles sean más fáciles de entender, publicar y solicitar.",

        "form_label" => "Mensaje",
        "form_title" => "Envíanos un mensaje",
        "form_text" => "Completa el formulario y el equipo de SkillBridge recibirá tu solicitud.",
        "name_label" => "Nombre completo",
        "name_placeholder" => "Tu nombre",
        "email_label" => "Correo electrónico",
        "email_placeholder" => "correo@ejemplo.com",
        "message_label" => "Mensaje",
        "message_placeholder" => "Escribe tu mensaje aquí...",
        "send_message" => "Enviar mensaje",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_find_jobs" => "Buscar empleos",
        "footer_create_profile" => "Crear perfil",
        "footer_my_applications" => "Mis postulaciones",
        "footer_resources" => "Recursos y consejos",
        "footer_post_job" => "Publicar vacante",
        "footer_received_applications" => "Postulaciones recibidas",
        "footer_companies" => "Empresas",
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

function urlConIdioma($idioma) {
    $params = $_GET;
    $params["lang"] = $idioma;

    return "contacto.php?" . http_build_query($params);
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;
$mostrarEnlacesCandidato = !$usuarioLogueado || $esCandidato || $esAdmin;
$mostrarEnlacesEmpresa = !$usuarioLogueado || $esEmpresa || $esAdmin;

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = $usuarioActual && !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = $usuarioActual && !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = $usuarioActual && !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = $usuarioActual && isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$bodyClasses = ["auth-body"];

if ($modoOscuro) {
    $bodyClasses[] = "dark-mode";
}

if ($altoContraste) {
    $bodyClasses[] = "high-contrast";
}

if ($modoLectura) {
    $bodyClasses[] = "reader-mode-enabled";
}

$bodyClassText = implode(" ", $bodyClasses);

$mensajeEnviado = false;
$errores = [];

$nombre = $_SESSION["nombre"] ?? "";
$correo = $_SESSION["correo"] ?? "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = strtolower(trim($_POST["correo"] ?? ""));
    $mensaje = trim($_POST["mensaje"] ?? "");

    if ($nombre === "") {
        $errores[] = t("error_name_required");
    } elseif (mb_strlen($nombre) < 3) {
        $errores[] = t("error_name_short");
    }

    if ($correo === "") {
        $errores[] = t("error_email_required");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = t("error_email_invalid");
    }

    if ($mensaje === "") {
        $errores[] = t("error_message_required");
    } elseif (mb_strlen($mensaje) < 10) {
        $errores[] = t("error_message_short");
    }

    if (empty($errores)) {
        $mensajeEnviado = true;
        $nombre = $_SESSION["nombre"] ?? "";
        $correo = $_SESSION["correo"] ?? "";
        $mensaje = "";
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo limpiar($idiomaActual); ?>" style="--font-scale: <?php echo number_format($escalaTexto, 2); ?>;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo limpiar(t("page_title")); ?></title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description" content="<?php echo limpiar(t("meta_description")); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/auth.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/paginas-extra.css?v=20260904footerfinal">
</head>

<body class="<?php echo limpiar($bodyClassText); ?>">

    <a href="#mainContent" class="skip-link">
        <?php echo limpiar(t("skip_main")); ?>
    </a>

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

            <a href="index.php" class="logo"
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
                    <a href="index.php"<?php echo enlaceActivo("index.php"); ?>>
                        <?php echo limpiar(t("nav_home")); ?>
                    </a>
                </li>

                <?php if (!$esEmpresa): ?>
                    <li>
                        <a href="empleos.php"<?php echo enlaceActivo("empleos.php"); ?>>
                            <?php echo limpiar(t("nav_find_jobs")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="empresas.php"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>

                <?php if ($usuarioLogueado && ($esCandidato || $esAdmin)): ?>
                    <li>
                        <a href="postulaciones.php"<?php echo enlaceActivo("postulaciones.php"); ?>>
                            <?php echo limpiar(t("nav_my_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado && $esEmpresaOAdmin): ?>
                    <li>
                        <a href="publicarvacante.php"<?php echo enlaceActivo("publicarvacante.php"); ?>>
                            <?php echo limpiar(t("nav_post_job")); ?>
                        </a>
                    </li>

                    <li>
                        <a href="postulaciones-empresa.php"<?php echo enlaceActivo("postulaciones-empresa.php"); ?>>
                            <?php echo limpiar(t("nav_received_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado): ?>
                    <li>
                        <a href="perfil.php"<?php echo enlaceActivo("perfil.php"); ?>>
                            <?php echo limpiar(t("nav_profile")); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if ($usuarioLogueado): ?>
                    <a href="perfil.php" class="login-link">
                        <?php echo limpiar(t("nav_my_profile")); ?>
                    </a>

                    <a href="logout.php" class="button button-primary button-small">
                        <?php echo limpiar(t("nav_logout")); ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="login-link">
                        <?php echo limpiar(t("nav_login")); ?>
                    </a>

                    <a href="registro.php" class="button button-primary button-small">
                        <?php echo limpiar(t("nav_create_account")); ?>
                    </a>
                <?php endif; ?>

                <a href="<?php echo limpiar(urlConIdioma($idiomaSiguiente)); ?>"
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

    <main class="extra-page-main" id="mainContent">

        <section class="extra-hero">
            <div class="container extra-hero-content">

                <div>
                    <span class="section-label">
                        <?php echo limpiar(t("hero_label")); ?>
                    </span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_text")); ?>
                    </p>
                </div>

                <div class="extra-hero-card">
                    <i class="fa-solid fa-envelope-open-text"
                        role="img"
                        aria-label="<?php echo limpiar(t("email_icon")); ?>"
                        title="<?php echo limpiar(t("email_icon")); ?>"></i>

                    <h3><?php echo limpiar(t("hero_card_title")); ?></h3>

                    <p>
                        <?php echo limpiar(t("hero_card_text")); ?>
                    </p>
                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="contact-wrapper">

                    <div class="auth-header">
                        <span class="section-label">
                            <?php echo limpiar(t("form_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("form_title")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("form_text")); ?>
                        </p>
                    </div>

                    <?php if ($mensajeEnviado): ?>
                        <div class="simple-alert-success">
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <span><?php echo limpiar(t("success_sent")); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errores)): ?>
                        <div class="auth-alert error">
                            <i class="fa-solid fa-triangle-exclamation"
                                role="img"
                                aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                title="<?php echo limpiar(t("warning_icon")); ?>"></i>

                            <div>
                                <?php foreach ($errores as $error): ?>
                                    <p><?php echo limpiar($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="contacto.php?lang=<?php echo limpiar($idiomaActual); ?>" method="POST" class="auth-form" novalidate>

                        <div class="form-group-custom">
                            <label for="nombre"><?php echo limpiar(t("name_label")); ?></label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-user input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                    title="<?php echo limpiar(t("user_icon")); ?>"></i>

                                <input type="text" id="nombre" name="nombre"
                                    value="<?php echo limpiar($nombre); ?>"
                                    placeholder="<?php echo limpiar(t("name_placeholder")); ?>" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="correo"><?php echo limpiar(t("email_label")); ?></label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-envelope input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("email_icon")); ?>"
                                    title="<?php echo limpiar(t("email_icon")); ?>"></i>

                                <input type="email" id="correo" name="correo"
                                    value="<?php echo limpiar($correo); ?>"
                                    placeholder="<?php echo limpiar(t("email_placeholder")); ?>" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="mensaje"><?php echo limpiar(t("message_label")); ?></label>

                            <div class="input-wrapper textarea-wrapper">
                                <i class="fa-solid fa-message input-icon textarea-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("message_icon")); ?>"
                                    title="<?php echo limpiar(t("message_icon")); ?>"></i>

                                <textarea id="mensaje" name="mensaje" rows="5"
                                    placeholder="<?php echo limpiar(t("message_placeholder")); ?>" required><?php echo limpiar($mensaje); ?></textarea>
                            </div>
                        </div>

                        <button type="submit" class="button button-primary btn-block">
                            <?php echo limpiar(t("send_message")); ?>
                            <i class="fa-solid fa-paper-plane"
                                role="img"
                                aria-label="<?php echo limpiar(t("send_icon")); ?>"
                                title="<?php echo limpiar(t("send_icon")); ?>"></i>
                        </button>

                    </form>

                </div>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            isLoggedIn: <?php echo $usuarioLogueado ? "true" : "false"; ?>,
            darkMode: <?php echo $modoOscuro ? "true" : "false"; ?>,
            highContrast: <?php echo $altoContraste ? "true" : "false"; ?>,
            readMode: <?php echo $modoLectura ? "true" : "false"; ?>,
            fontScale: <?php echo number_format($escalaTexto, 2); ?>
        };

        try {
            localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);

            if (window.SkillBridgeUserPreferences.isLoggedIn) {
                localStorage.setItem("skillbridgeDarkMode", window.SkillBridgeUserPreferences.darkMode ? "true" : "false");
                localStorage.setItem("skillbridgeHighContrast", window.SkillBridgeUserPreferences.highContrast ? "true" : "false");
                localStorage.setItem("skillbridgeReadMode", window.SkillBridgeUserPreferences.readMode ? "true" : "false");
                localStorage.setItem("skillbridgeFontScale", String(window.SkillBridgeUserPreferences.fontScale));
            }
        } catch (error) {
            console.warn("SkillBridge preferences could not be stored locally.");
        }
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
</body>

</html>
