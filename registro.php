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

function agregarIdiomaUrl($url, $idioma) {
    $partes = parse_url($url);
    $ruta = $partes["path"] ?? "perfil.php";
    $query = [];

    if (!empty($partes["query"])) {
        parse_str($partes["query"], $query);
    }

    $query["lang"] = $idioma;

    return $ruta . "?" . http_build_query($query);
}

function redirectSeguroPorRol($redirect, $tipoUsuario) {
    $ruta = parse_url($redirect, PHP_URL_PATH);
    $ruta = basename($ruta ?: "perfil.php");

    $rutasCandidato = [
        "empleos.php",
        "detalle-empleo.php",
        "postulaciones.php",
        "perfil.php",
        "editar-perfil.php",
        "index.php",
        "empresas.php"
    ];

    $rutasEmpresa = [
        "publicarvacante.php",
        "postulaciones-empresa.php",
        "perfil.php",
        "editar-perfil.php",
        "index.php",
        "empresas.php"
    ];

    if ($tipoUsuario === "empresa" && !in_array($ruta, $rutasEmpresa, true)) {
        return "perfil.php";
    }

    if ($tipoUsuario === "candidato" && !in_array($ruta, $rutasCandidato, true)) {
        return "perfil.php";
    }

    return $redirect;
}

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif (!empty($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idiomaActual = $_COOKIE["skillbridgeLanguage"];
} elseif (!empty($_SESSION["idioma_preferido"]) && in_array($_SESSION["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_SESSION["idioma_preferido"];
} else {
    $idiomaActual = "en";
}

$_SESSION["idioma_preferido"] = $idiomaActual;
setcookie("skillbridgeLanguage", $idiomaActual, time() + (365 * 24 * 60 * 60), "/");

$translations = [
    "en" => [
        "page_title" => "Create Account | SkillBridge",
        "meta_description" => "Create your SkillBridge account and connect with accessible job opportunities.",

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
        "user_icon" => "User icon",
        "users_icon" => "Users icon",
        "email_icon" => "Email icon",
        "lock_icon" => "Password icon",
        "warning_icon" => "Warning icon",
        "check_icon" => "Success icon",
        "eye_icon" => "Show or hide password icon",
        "add_user_icon" => "Create account icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "error_required" => "Please complete all required fields.",
        "error_name_short" => "The name must be at least 3 characters long.",
        "error_email" => "Enter a valid email address.",
        "error_type" => "Please select a valid account type.",
        "error_password" => "The password must be at least 8 characters long.",
        "error_terms" => "You must accept the Terms of Service and Privacy Policy.",
        "error_email_exists" => "This email address is already registered.",
        "error_create" => "There was an error creating your account. Please try again.",

        "sidebar_badge" => "Accessible platform",
        "sidebar_title" => "Start your professional journey without barriers.",
        "sidebar_text" => "Create your account to use SkillBridge according to your role: candidates can apply to jobs and companies can publish vacancies.",
        "feature_one" => "Separate profiles for candidates and companies.",
        "feature_two" => "Candidates can search and apply to accessible job opportunities.",
        "feature_three" => "Companies can publish vacancies and review applications.",

        "signup_label" => "Sign up for free",
        "form_title" => "Create your account",
        "form_subtitle" => "Join SkillBridge and start using the platform with the correct account type.",
        "name_label" => "Full name or company name",
        "name_placeholder" => "Example: María Hernández or Innovatech SV",
        "name_help" => "The name must be at least 3 characters long.",
        "email_label" => "Email address",
        "email_placeholder" => "email@example.com",
        "email_help" => "Enter a valid email address.",
        "type_label" => "Account type",
        "select_option" => "Select an option",
        "candidate_option" => "I am looking for a job",
        "company_option" => "I am hiring",
        "type_help" => "Choose candidate if you want to apply. Choose company if you want to publish vacancies.",
        "password_label" => "Password",
        "password_placeholder" => "At least 8 characters",
        "show_password" => "Show or hide password",
        "password_strength" => "Password strength",
        "password_help" => "The password must be at least 8 characters long.",
        "terms_start" => "I accept the",
        "terms_service" => "Terms of Service",
        "terms_and" => "and the",
        "privacy_policy" => "Privacy Policy",
        "create_button" => "Create Account",
        "already_have" => "Already have an account?",
        "login_here" => "Log in here",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
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
        "page_title" => "Crear cuenta | SkillBridge",
        "meta_description" => "Crea tu cuenta de SkillBridge y conecta con oportunidades laborales accesibles.",

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
        "user_icon" => "Ícono de usuario",
        "users_icon" => "Ícono de usuarios",
        "email_icon" => "Ícono de correo electrónico",
        "lock_icon" => "Ícono de contraseña",
        "warning_icon" => "Ícono de advertencia",
        "check_icon" => "Ícono de éxito",
        "eye_icon" => "Ícono de mostrar u ocultar contraseña",
        "add_user_icon" => "Ícono de crear cuenta",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "error_required" => "Completa todos los campos obligatorios.",
        "error_name_short" => "El nombre debe tener al menos 3 caracteres.",
        "error_email" => "Ingresa un correo electrónico válido.",
        "error_type" => "Selecciona un tipo de cuenta válido.",
        "error_password" => "La contraseña debe tener al menos 8 caracteres.",
        "error_terms" => "Debes aceptar los Términos de Servicio y la Política de Privacidad.",
        "error_email_exists" => "Este correo electrónico ya está registrado.",
        "error_create" => "Hubo un error al crear tu cuenta. Inténtalo nuevamente.",

        "sidebar_badge" => "Plataforma accesible",
        "sidebar_title" => "Inicia tu camino profesional sin barreras.",
        "sidebar_text" => "Crea tu cuenta para usar SkillBridge según tu rol: los candidatos pueden aplicar a empleos y las empresas pueden publicar vacantes.",
        "feature_one" => "Perfiles separados para candidatos y empresas.",
        "feature_two" => "Los candidatos pueden buscar y aplicar a oportunidades laborales accesibles.",
        "feature_three" => "Las empresas pueden publicar vacantes y revisar postulaciones.",

        "signup_label" => "Regístrate gratis",
        "form_title" => "Crea tu cuenta",
        "form_subtitle" => "Únete a SkillBridge y empieza a usar la plataforma con el tipo de cuenta correcto.",
        "name_label" => "Nombre completo o nombre de empresa",
        "name_placeholder" => "Ejemplo: María Hernández o Innovatech SV",
        "name_help" => "El nombre debe tener al menos 3 caracteres.",
        "email_label" => "Correo electrónico",
        "email_placeholder" => "correo@ejemplo.com",
        "email_help" => "Ingresa un correo electrónico válido.",
        "type_label" => "Tipo de cuenta",
        "select_option" => "Selecciona una opción",
        "candidate_option" => "Busco empleo",
        "company_option" => "Estoy contratando",
        "type_help" => "Elige candidato si quieres aplicar. Elige empresa si quieres publicar vacantes.",
        "password_label" => "Contraseña",
        "password_placeholder" => "Mínimo 8 caracteres",
        "show_password" => "Mostrar u ocultar contraseña",
        "password_strength" => "Seguridad de la contraseña",
        "password_help" => "La contraseña debe tener al menos 8 caracteres.",
        "terms_start" => "Acepto los",
        "terms_service" => "Términos de Servicio",
        "terms_and" => "y la",
        "privacy_policy" => "Política de Privacidad",
        "create_button" => "Crear cuenta",
        "already_have" => "¿Ya tienes una cuenta?",
        "login_here" => "Inicia sesión aquí",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
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

$paginaActual = basename($_SERVER["PHP_SELF"]);

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

$redirectPermitido = obtenerRedirectSeguro($_GET["redirect"] ?? "perfil.php");

if (isset($_SESSION["id_usuario"])) {
    header("Location: " . agregarIdiomaUrl("perfil.php", $idiomaActual));
    exit;
}

$errorMessage = "";
$nombreValue = "";
$correoValue = "";
$tipoUsuarioValue = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = strtolower(trim($_POST["correo"] ?? ""));
    $tipoUsuarioFormulario = trim($_POST["tipo_usuario"] ?? "");
    $password = $_POST["password"] ?? "";
    $terms = $_POST["terms"] ?? "";
    $idiomaPost = $_POST["idioma"] ?? $idiomaActual;

    if (in_array($idiomaPost, $idiomasPermitidos, true)) {
        $idiomaActual = $idiomaPost;
        $_SESSION["idioma_preferido"] = $idiomaActual;
        setcookie("skillbridgeLanguage", $idiomaActual, time() + (365 * 24 * 60 * 60), "/");
    }

    $redirectPermitido = obtenerRedirectSeguro($_POST["redirect"] ?? "perfil.php");

    $nombreValue = $nombre;
    $correoValue = $correo;
    $tipoUsuarioValue = $tipoUsuarioFormulario;

    if ($nombre === "" || $correo === "" || $tipoUsuarioFormulario === "" || $password === "") {
        $errorMessage = t("error_required");
    } elseif (strlen($nombre) < 3) {
        $errorMessage = t("error_name_short");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = t("error_email");
    } elseif (!in_array($tipoUsuarioFormulario, ["candidato", "empresa"], true)) {
        $errorMessage = t("error_type");
    } elseif (strlen($password) < 8) {
        $errorMessage = t("error_password");
    } elseif ($terms !== "on") {
        $errorMessage = t("error_terms");
    } else {
        try {
            $buscarCorreo = $pdo->prepare("
                SELECT id_usuario
                FROM usuarios
                WHERE correo = :correo
                LIMIT 1
            ");

            $buscarCorreo->execute([
                ":correo" => $correo
            ]);

            if ($buscarCorreo->fetch()) {
                $errorMessage = t("error_email_exists");
            } else {
                $pdo->beginTransaction();

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $sqlUsuario = "
                    INSERT INTO usuarios (
                        nombre,
                        correo,
                        password_hash,
                        tipo_usuario,
                        idioma_preferido,
                        estado
                    ) VALUES (
                        :nombre,
                        :correo,
                        :password_hash,
                        :tipo_usuario,
                        :idioma_preferido,
                        1
                    )
                ";

                $stmtUsuario = $pdo->prepare($sqlUsuario);
                $stmtUsuario->execute([
                    ":nombre" => $nombre,
                    ":correo" => $correo,
                    ":password_hash" => $passwordHash,
                    ":tipo_usuario" => $tipoUsuarioFormulario,
                    ":idioma_preferido" => $idiomaActual
                ]);

                $idUsuarioNuevo = $pdo->lastInsertId();

                if ($tipoUsuarioFormulario === "empresa") {
                    $buscarEmpresa = $pdo->prepare("
                        SELECT id_empresa
                        FROM empresas
                        WHERE nombre = :nombre
                        LIMIT 1
                    ");

                    $buscarEmpresa->execute([
                        ":nombre" => $nombre
                    ]);

                    if (!$buscarEmpresa->fetch()) {
                        $crearEmpresa = $pdo->prepare("
                            INSERT INTO empresas (
                                nombre,
                                lema,
                                descripcion,
                                ubicacion,
                                colaboradores,
                                estado
                            ) VALUES (
                                :nombre,
                                :lema,
                                :descripcion,
                                :ubicacion,
                                :colaboradores,
                                1
                            )
                        ");

                        $crearEmpresa->execute([
                            ":nombre" => $nombre,
                            ":lema" => null,
                            ":descripcion" => null,
                            ":ubicacion" => null,
                            ":colaboradores" => null
                        ]);
                    }
                }

                $pdo->commit();

                session_regenerate_id(true);

                $_SESSION["id_usuario"] = $idUsuarioNuevo;
                $_SESSION["nombre"] = $nombre;
                $_SESSION["correo"] = $correo;
                $_SESSION["tipo_usuario"] = $tipoUsuarioFormulario;
                $_SESSION["idioma_preferido"] = $idiomaActual;

                setcookie("skillbridgeLanguage", $idiomaActual, time() + (365 * 24 * 60 * 60), "/");

                $redirectFinal = redirectSeguroPorRol($redirectPermitido, $tipoUsuarioFormulario);
                $redirectFinal = agregarIdiomaUrl($redirectFinal, $idiomaActual);

                header("Location: " . $redirectFinal);
                exit;
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errorMessage = t("error_create");
        }
    }
}

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$loginLink = "login.php?lang=" . urlencode($idiomaActual);

if ($redirectPermitido !== "perfil.php") {
    $loginLink .= "&redirect=" . urlencode($redirectPermitido);
}

$registroAction = "registro.php?lang=" . urlencode($idiomaActual);

if ($redirectPermitido !== "perfil.php") {
    $registroAction .= "&redirect=" . urlencode($redirectPermitido);
}

$languageUrl = "registro.php?lang=" . urlencode($idiomaSiguiente);

if ($redirectPermitido !== "perfil.php") {
    $languageUrl .= "&redirect=" . urlencode($redirectPermitido);
}
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
                <a href="<?php echo limpiar($loginLink); ?>" class="login-link">
                    <?php echo limpiar(t("nav_login")); ?>
                </a>

                <a href="registro.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-primary button-small">
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

    <main class="auth-container">

        <section class="auth-split-wrapper">

            <div class="auth-sidebar">
                <div class="sidebar-content">
                    <span class="auth-badge">
                        <i class="fa-solid fa-universal-access"
                            role="img"
                            aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                            title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        <?php echo limpiar(t("sidebar_badge")); ?>
                    </span>

                    <h1><?php echo limpiar(t("sidebar_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("sidebar_text")); ?>
                    </p>

                    <div class="sidebar-features">
                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <span><?php echo limpiar(t("feature_one")); ?></span>
                        </div>

                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <span><?php echo limpiar(t("feature_two")); ?></span>
                        </div>

                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <span><?php echo limpiar(t("feature_three")); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-container">

                <div class="auth-box">

                    <div class="auth-header">
                        <span class="section-label">
                            <?php echo limpiar(t("signup_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("form_title")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("form_subtitle")); ?>
                        </p>
                    </div>

                    <?php if (!empty($errorMessage)): ?>
                        <div class="auth-alert error">
                            <i class="fa-solid fa-triangle-exclamation"
                                role="img"
                                aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                            <span><?php echo limpiar($errorMessage); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo limpiar($registroAction); ?>"
                        method="POST" id="registerForm" class="auth-form">

                        <input type="hidden" name="redirect"
                            value="<?php echo limpiar($redirectPermitido); ?>">

                        <input type="hidden" name="idioma"
                            value="<?php echo limpiar($idiomaActual); ?>">

                        <div class="form-group-custom">
                            <label for="regName">
                                <?php echo limpiar(t("name_label")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-user input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                    title="<?php echo limpiar(t("user_icon")); ?>"></i>

                                <input type="text" id="regName" name="nombre"
                                    placeholder="<?php echo limpiar(t("name_placeholder")); ?>"
                                    value="<?php echo limpiar($nombreValue); ?>"
                                    autocomplete="name"
                                    required minlength="3">

                                <i class="fa-solid fa-circle-exclamation error-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                    title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                            </div>

                            <span class="error-text">
                                <?php echo limpiar(t("name_help")); ?>
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="regEmail">
                                <?php echo limpiar(t("email_label")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-envelope input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("email_icon")); ?>"
                                    title="<?php echo limpiar(t("email_icon")); ?>"></i>

                                <input type="email" id="regEmail" name="correo"
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
                                <?php echo limpiar(t("email_help")); ?>
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="userType">
                                <?php echo limpiar(t("type_label")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-users input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("users_icon")); ?>"
                                    title="<?php echo limpiar(t("users_icon")); ?>"></i>

                                <select id="userType" name="tipo_usuario" required>
                                    <option value="" disabled <?php echo $tipoUsuarioValue === "" ? "selected" : ""; ?>>
                                        <?php echo limpiar(t("select_option")); ?>
                                    </option>

                                    <option value="candidato" <?php echo $tipoUsuarioValue === "candidato" ? "selected" : ""; ?>>
                                        <?php echo limpiar(t("candidate_option")); ?>
                                    </option>

                                    <option value="empresa" <?php echo $tipoUsuarioValue === "empresa" ? "selected" : ""; ?>>
                                        <?php echo limpiar(t("company_option")); ?>
                                    </option>
                                </select>
                            </div>

                            <span class="error-text">
                                <?php echo limpiar(t("type_help")); ?>
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="regPassword">
                                <?php echo limpiar(t("password_label")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("lock_icon")); ?>"
                                    title="<?php echo limpiar(t("lock_icon")); ?>"></i>

                                <input type="password" id="regPassword" name="password"
                                    placeholder="<?php echo limpiar(t("password_placeholder")); ?>"
                                    autocomplete="new-password"
                                    required minlength="8">

                                <button type="button" class="toggle-password-button"
                                    id="togglePassword"
                                    aria-label="<?php echo limpiar(t("show_password")); ?>"
                                    title="<?php echo limpiar(t("show_password")); ?>">
                                    <i class="fa-solid fa-eye"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                        title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                                </button>
                            </div>

                            <div class="password-strength-meter">
                                <div class="meter-bar"></div>
                                <span class="meter-label">
                                    <?php echo limpiar(t("password_strength")); ?>
                                </span>
                            </div>

                            <span class="error-text">
                                <?php echo limpiar(t("password_help")); ?>
                            </span>
                        </div>

                        <div class="form-group-checkbox">
                            <label class="checkbox-container">
                                <input type="checkbox" id="termsCheckbox" name="terms" required>

                                <span class="checkmark"></span>

                                <span class="terms-label">
                                    <?php echo limpiar(t("terms_start")); ?>
                                    <a href="terminos.php?lang=<?php echo limpiar($idiomaActual); ?>">
                                        <?php echo limpiar(t("terms_service")); ?>
                                    </a>
                                    <?php echo limpiar(t("terms_and")); ?>
                                    <a href="privacidad.php?lang=<?php echo limpiar($idiomaActual); ?>">
                                        <?php echo limpiar(t("privacy_policy")); ?>
                                    </a>.
                                </span>
                            </label>
                        </div>

                        <button type="submit" class="button button-primary btn-block" id="btnRegisterSubmit">
                            <span><?php echo limpiar(t("create_button")); ?></span>
                            <i class="fa-solid fa-user-plus"
                                role="img"
                                aria-label="<?php echo limpiar(t("add_user_icon")); ?>"
                                title="<?php echo limpiar(t("add_user_icon")); ?>"></i>
                        </button>

                        <p class="auth-switch-text">
                            <?php echo limpiar(t("already_have")); ?>
                            <a href="<?php echo limpiar($loginLink); ?>">
                                <?php echo limpiar(t("login_here")); ?>
                            </a>
                        </p>

                    </form>

                </div>

            </div>

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
        } catch (error) {
            console.warn("SkillBridge language could not be stored locally.");
        }
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
    <script src="java/auth.js?v=20260904footerfinal"></script>
</body>

</html>
