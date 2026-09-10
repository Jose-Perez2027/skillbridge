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

if ($usuarioLogueado) {
    try {
        $stmtUsuario = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1");
        $stmtUsuario->execute([":id_usuario" => $idUsuario]);
        $usuarioActual = $stmtUsuario->fetch();

        if ($usuarioActual) {
            $tipoUsuario = $usuarioActual["tipo_usuario"] ?? $tipoUsuario;
            $_SESSION["tipo_usuario"] = $tipoUsuario;
            $_SESSION["nombre"] = $usuarioActual["nombre"] ?? ($_SESSION["nombre"] ?? "");
        } else {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        $usuarioActual = null;
    }
}

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif ($usuarioActual && !empty($usuarioActual["idioma_preferido"])) {
    $idiomaActual = $usuarioActual["idioma_preferido"];
} elseif (!empty($_SESSION["idioma_preferido"])) {
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
        $actualizarIdioma = $pdo->prepare("UPDATE usuarios SET idioma_preferido = :idioma WHERE id_usuario = :id_usuario");
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
        "page_title" => "Accessibility | SkillBridge",
        "meta_description" => "Accessibility features and inclusive design information for SkillBridge.",
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

        "aria_logo" => "Go to SkillBridge home",
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
        "arrow_icon" => "Arrow icon",
        "book_icon" => "Resource icon",
        "text_size_icon" => "Text size icon",
        "contrast_icon" => "Contrast icon",
        "moon_icon" => "Dark mode icon",
        "volume_icon" => "Reading icon",
        "keyboard_icon" => "Keyboard icon",
        "eye_icon" => "Visual structure icon",
        "check_icon" => "Check icon",
        "briefcase_icon" => "Job icon",
        "contact_icon" => "Contact icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "hero_label" => "Accessibility",
        "hero_title" => "SkillBridge is designed for accessible use.",
        "hero_text" => "Accessibility is part of our platform because job opportunities should be easier to explore, understand, and use for everyone.",
        "open_tools" => "Open accessibility tools",
        "view_resources" => "View resources",
        "hero_card_title" => "Accessible experience",
        "hero_card_text" => "The platform includes tools to support readability, contrast, keyboard navigation, and content understanding.",

        "features_label" => "Features",
        "features_title" => "Accessibility tools available",
        "features_text" => "These tools help users adapt the website according to their needs.",
        "feature_text_size_title" => "Text size controls",
        "feature_text_size_text" => "Users can increase or decrease the text size to improve readability.",
        "feature_contrast_title" => "High contrast mode",
        "feature_contrast_text" => "High contrast helps users distinguish text, buttons, cards, and important sections more clearly.",
        "feature_dark_title" => "Dark mode",
        "feature_dark_text" => "Dark mode offers a different visual experience that can reduce visual discomfort for some users.",
        "feature_read_title" => "Read mode",
        "feature_read_text" => "The page reader can read content aloud using the browser speech tools.",
        "feature_keyboard_title" => "Keyboard navigation",
        "feature_keyboard_text" => "Buttons, links, forms, and menus are designed to be usable with keyboard navigation.",
        "feature_visual_title" => "Clear visual structure",
        "feature_visual_text" => "SkillBridge uses headings, labels, cards, icons, and spacing to make information easier to scan.",

        "commitment_label" => "Commitment",
        "commitment_title" => "Our accessibility commitment",
        "commitment_text" => "SkillBridge was created as an accessible employment platform.",
        "commitment_1_title" => "1. Accessible job search",
        "commitment_1_text" => "Candidates can explore jobs using filters such as category, work arrangement, salary, experience level, and accessible opportunities.",
        "commitment_2_title" => "2. Clear information",
        "commitment_2_text" => "Job details are organized into sections such as description, responsibilities, requirements, skills, salary, company information, and application deadline.",
        "commitment_3_title" => "3. Accessible forms",
        "commitment_3_text" => "Forms include labels, placeholders, clear buttons, validation messages, and simple fields so users can create accounts, complete profiles, publish vacancies, and apply to jobs.",
        "commitment_4_title" => "4. Respectful use of disability information",
        "commitment_4_text" => "Accessibility or disability information should be used only to support inclusion and better communication, never to discriminate against candidates.",
        "commitment_5_title" => "5. Continuous improvement",
        "commitment_5_text" => "SkillBridge can continue improving accessibility by testing the platform, listening to users, and updating design, content, and functionality.",

        "explore_jobs" => "Explore jobs",
        "report_issue" => "Report an accessibility issue",
        "post_job" => "Post a job",
        "review_applications" => "Review applications",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and advice",
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
        "page_title" => "Accesibilidad | SkillBridge",
        "meta_description" => "Funciones de accesibilidad y diseño inclusivo de SkillBridge.",
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

        "aria_logo" => "Ir al inicio de SkillBridge",
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
        "arrow_icon" => "Ícono de flecha",
        "book_icon" => "Ícono de recursos",
        "text_size_icon" => "Ícono de tamaño de texto",
        "contrast_icon" => "Ícono de contraste",
        "moon_icon" => "Ícono de modo oscuro",
        "volume_icon" => "Ícono de lectura",
        "keyboard_icon" => "Ícono de teclado",
        "eye_icon" => "Ícono de estructura visual",
        "check_icon" => "Ícono de verificación",
        "briefcase_icon" => "Ícono de empleo",
        "contact_icon" => "Ícono de contacto",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "hero_label" => "Accesibilidad",
        "hero_title" => "SkillBridge está diseñado para un uso accesible.",
        "hero_text" => "La accesibilidad forma parte de nuestra plataforma porque las oportunidades laborales deben ser más fáciles de explorar, comprender y usar para todas las personas.",
        "open_tools" => "Abrir herramientas de accesibilidad",
        "view_resources" => "Ver recursos",
        "hero_card_title" => "Experiencia accesible",
        "hero_card_text" => "La plataforma incluye herramientas para apoyar la lectura, el contraste, la navegación con teclado y la comprensión del contenido.",

        "features_label" => "Funciones",
        "features_title" => "Herramientas de accesibilidad disponibles",
        "features_text" => "Estas herramientas ayudan a adaptar el sitio web según las necesidades de cada usuario.",
        "feature_text_size_title" => "Control de tamaño del texto",
        "feature_text_size_text" => "Los usuarios pueden aumentar o disminuir el tamaño del texto para mejorar la lectura.",
        "feature_contrast_title" => "Modo de alto contraste",
        "feature_contrast_text" => "El alto contraste ayuda a distinguir mejor textos, botones, tarjetas y secciones importantes.",
        "feature_dark_title" => "Modo oscuro",
        "feature_dark_text" => "El modo oscuro ofrece una experiencia visual diferente que puede reducir la incomodidad visual para algunos usuarios.",
        "feature_read_title" => "Modo lectura",
        "feature_read_text" => "El lector de página puede leer el contenido en voz alta usando las herramientas de voz del navegador.",
        "feature_keyboard_title" => "Navegación con teclado",
        "feature_keyboard_text" => "Los botones, enlaces, formularios y menús están diseñados para usarse con navegación por teclado.",
        "feature_visual_title" => "Estructura visual clara",
        "feature_visual_text" => "SkillBridge usa títulos, etiquetas, tarjetas, íconos y espacios para que la información sea más fácil de revisar.",

        "commitment_label" => "Compromiso",
        "commitment_title" => "Nuestro compromiso con la accesibilidad",
        "commitment_text" => "SkillBridge fue creado como una plataforma de empleo accesible.",
        "commitment_1_title" => "1. Búsqueda de empleo accesible",
        "commitment_1_text" => "Los candidatos pueden explorar empleos usando filtros como categoría, modalidad, salario, nivel de experiencia y oportunidades accesibles.",
        "commitment_2_title" => "2. Información clara",
        "commitment_2_text" => "Los detalles de cada empleo están organizados en secciones como descripción, responsabilidades, requisitos, habilidades, salario, información de la empresa y fecha límite.",
        "commitment_3_title" => "3. Formularios accesibles",
        "commitment_3_text" => "Los formularios incluyen etiquetas, textos de ayuda, botones claros, mensajes de validación y campos simples para crear cuentas, completar perfiles, publicar vacantes y aplicar a empleos.",
        "commitment_4_title" => "4. Uso respetuoso de la información sobre discapacidad",
        "commitment_4_text" => "La información de accesibilidad o discapacidad debe usarse solo para apoyar la inclusión y una mejor comunicación, nunca para discriminar a los candidatos.",
        "commitment_5_title" => "5. Mejora continua",
        "commitment_5_text" => "SkillBridge puede seguir mejorando su accesibilidad probando la plataforma, escuchando a los usuarios y actualizando diseño, contenido y funcionamiento.",

        "explore_jobs" => "Explorar empleos",
        "report_issue" => "Reportar un problema de accesibilidad",
        "post_job" => "Publicar vacante",
        "review_applications" => "Revisar postulaciones",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_create_profile" => "Crear perfil",
        "footer_resources" => "Recursos y consejos",
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

function urlConIdioma($idioma) {
    return "accesibilidad.php?lang=" . urlencode($idioma);
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = $usuarioActual && !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = $usuarioActual && !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = $usuarioActual && !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = $usuarioActual && isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$bodyClasses = [];

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
            <button class="accessibility-option" id="increaseFont" aria-label="<?php echo limpiar(t("increase_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-plus"
                    role="img"
                    aria-label="<?php echo limpiar(t("increase_text")); ?>"
                    title="<?php echo limpiar(t("increase_text")); ?>"></i>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont" aria-label="<?php echo limpiar(t("decrease_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-minus"
                    role="img"
                    aria-label="<?php echo limpiar(t("decrease_text")); ?>"
                    title="<?php echo limpiar(t("decrease_text")); ?>"></i>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode" aria-label="<?php echo limpiar(t("dark_mode")); ?>">
                <i class="fa-solid fa-moon"
                    role="img"
                    aria-label="<?php echo limpiar(t("moon_icon")); ?>"
                    title="<?php echo limpiar(t("moon_icon")); ?>"></i>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast" aria-label="<?php echo limpiar(t("high_contrast")); ?>">
                <i class="fa-solid fa-circle-half-stroke"
                    role="img"
                    aria-label="<?php echo limpiar(t("contrast_icon")); ?>"
                    title="<?php echo limpiar(t("contrast_icon")); ?>"></i>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage" aria-label="<?php echo limpiar(t("read_mode")); ?>">
                <i class="fa-solid fa-volume-high"
                    role="img"
                    aria-label="<?php echo limpiar(t("volume_icon")); ?>"
                    title="<?php echo limpiar(t("volume_icon")); ?>"></i>
                <span><?php echo limpiar(t("read_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading" aria-label="<?php echo limpiar(t("stop_reading")); ?>">
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
                    <span class="section-label"><?php echo limpiar(t("hero_label")); ?></span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_text")); ?>
                    </p>

                    <div class="hero-actions">
                        <button type="button" class="button button-primary" id="openAccessibilityFromSection">
                            <?php echo limpiar(t("open_tools")); ?>
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </button>

                        <?php if ($esEmpresa): ?>
                            <a href="publicarvacante.php" class="button button-secondary">
                                <?php echo limpiar(t("post_job")); ?>
                            </a>
                        <?php else: ?>
                            <a href="recursos.php" class="button button-secondary">
                                <?php echo limpiar(t("view_resources")); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="extra-hero-card">
                    <i class="fa-solid fa-universal-access"
                        role="img"
                        aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                        title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>

                    <h3><?php echo limpiar(t("hero_card_title")); ?></h3>

                    <p>
                        <?php echo limpiar(t("hero_card_text")); ?>
                    </p>
                </div>
            </div>
        </section>

        <section class="extra-section">
            <div class="container">
                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("features_label")); ?></span>

                        <h2><?php echo limpiar(t("features_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("features_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="extra-grid">
                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-magnifying-glass-plus"
                                role="img"
                                aria-label="<?php echo limpiar(t("text_size_icon")); ?>"
                                title="<?php echo limpiar(t("text_size_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_text_size_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_text_size_text")); ?></p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-circle-half-stroke"
                                role="img"
                                aria-label="<?php echo limpiar(t("contrast_icon")); ?>"
                                title="<?php echo limpiar(t("contrast_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_contrast_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_contrast_text")); ?></p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-moon"
                                role="img"
                                aria-label="<?php echo limpiar(t("moon_icon")); ?>"
                                title="<?php echo limpiar(t("moon_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_dark_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_dark_text")); ?></p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-volume-high"
                                role="img"
                                aria-label="<?php echo limpiar(t("volume_icon")); ?>"
                                title="<?php echo limpiar(t("volume_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_read_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_read_text")); ?></p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-keyboard"
                                role="img"
                                aria-label="<?php echo limpiar(t("keyboard_icon")); ?>"
                                title="<?php echo limpiar(t("keyboard_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_keyboard_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_keyboard_text")); ?></p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-eye"
                                role="img"
                                aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("feature_visual_title")); ?></h3>
                        <p><?php echo limpiar(t("feature_visual_text")); ?></p>
                    </article>
                </div>
            </div>
        </section>

        <section class="extra-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="auth-header">
                        <span class="section-label"><?php echo limpiar(t("commitment_label")); ?></span>

                        <h2><?php echo limpiar(t("commitment_title")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("commitment_text")); ?>
                        </p>
                    </div>

                    <div class="profile-description">
                        <h3><?php echo limpiar(t("commitment_1_title")); ?></h3>
                        <p><?php echo limpiar(t("commitment_1_text")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("commitment_2_title")); ?></h3>
                        <p><?php echo limpiar(t("commitment_2_text")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("commitment_3_title")); ?></h3>
                        <p><?php echo limpiar(t("commitment_3_text")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("commitment_4_title")); ?></h3>
                        <p><?php echo limpiar(t("commitment_4_text")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("commitment_5_title")); ?></h3>
                        <p><?php echo limpiar(t("commitment_5_text")); ?></p>

                        <br>

                        <div class="hero-actions">
                            <?php if ($esEmpresa): ?>
                                <a href="postulaciones-empresa.php" class="button button-primary">
                                    <?php echo limpiar(t("review_applications")); ?>
                                    <i class="fa-solid fa-briefcase"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                        title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                                </a>
                            <?php else: ?>
                                <a href="empleos.php" class="button button-primary">
                                    <?php echo limpiar(t("explore_jobs")); ?>
                                    <i class="fa-solid fa-arrow-right"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                        title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                </a>
                            <?php endif; ?>

                            <a href="contacto.php" class="button button-secondary">
                                <?php echo limpiar(t("report_issue")); ?>
                            </a>
                        </div>
                    </div>
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

        document.addEventListener("DOMContentLoaded", () => {
            const openAccessibilityFromSection = document.getElementById("openAccessibilityFromSection");
            const accessibilityButton = document.getElementById("accessibilityButton");

            if (openAccessibilityFromSection && accessibilityButton) {
                openAccessibilityFromSection.addEventListener("click", () => {
                    accessibilityButton.click();
                });
            }
        });
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
</body>

</html>
