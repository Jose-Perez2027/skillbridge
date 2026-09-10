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
        } else {
            session_unset();
            session_destroy();
            $usuarioLogueado = false;
            $tipoUsuario = "";
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
        "page_title" => "About Us | SkillBridge",
        "meta_description" => "Learn more about SkillBridge, an accessible employment platform that connects candidates and companies.",

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
        "user_icon" => "User icon",
        "users_icon" => "Users icon",
        "building_icon" => "Company icon",
        "bridge_icon" => "Bridge icon",
        "check_icon" => "Check icon",
        "eye_icon" => "Eye icon",
        "lightbulb_icon" => "Idea icon",
        "shield_icon" => "Protection icon",
        "growth_icon" => "Growth icon",
        "handshake_icon" => "Handshake icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "hero_label" => "About SkillBridge",
        "hero_title" => "Connecting talent with accessible opportunities.",
        "hero_text" => "SkillBridge is an accessible employment platform created to help candidates discover job opportunities and help companies publish vacancies with a more respectful hiring approach.",
        "hero_explore_jobs" => "Explore jobs",
        "hero_view_companies" => "View companies",
        "hero_post_job" => "Post a job",
        "hero_review_applications" => "Review applications",
        "hero_card_title" => "Why SkillBridge?",
        "hero_card_text" => "Because employment opportunities should be clearer, more accessible, and easier to connect with the right people.",

        "purpose_label" => "Our purpose",
        "purpose_title" => "What SkillBridge does",
        "purpose_text" => "The platform helps organize the job search process for candidates and companies.",
        "candidate_title" => "For candidates",
        "candidate_text" => "Candidates can create a profile, upload their curriculum, explore vacancies, apply to jobs, and track their applications.",
        "company_title" => "For companies",
        "company_text" => "Companies can publish job openings, share requirements, and review applications from candidates interested in their opportunities.",
        "inclusion_title" => "For inclusion",
        "inclusion_text" => "SkillBridge promotes accessible tools, clear information, and inclusive hiring practices for a better employment experience.",

        "direction_label" => "Mission and vision",
        "direction_title" => "Our direction",
        "direction_text" => "SkillBridge was created with a practical and inclusive purpose.",
        "mission_title" => "Mission",
        "mission_text" => "To connect candidates with companies through an accessible employment platform that organizes profiles, vacancies, applications, and inclusive job opportunities.",
        "vision_title" => "Vision",
        "vision_text" => "To become a digital bridge that supports more inclusive hiring processes and helps people find opportunities according to their skills, interests, and goals.",
        "objective_title" => "Project objective",
        "objective_text" => "The objective of SkillBridge is to demonstrate how technology can support inclusive employment by combining job search tools, company profiles, candidate applications, accessibility options, and profile management.",

        "values_label" => "Values",
        "values_title" => "What guides the platform",
        "values_text" => "These values represent the purpose behind SkillBridge.",
        "value_inclusion" => "Inclusion",
        "value_inclusion_text" => "We believe that every person deserves access to clear and respectful employment opportunities.",
        "value_accessibility" => "Accessibility",
        "value_accessibility_text" => "The platform includes tools that help users adapt the experience to their needs.",
        "value_responsibility" => "Responsibility",
        "value_responsibility_text" => "Candidates and companies should use the platform with truthful, updated, and respectful information.",
        "value_opportunity" => "Opportunity",
        "value_opportunity_text" => "SkillBridge helps people discover options that can support their professional growth.",
        "value_respect" => "Respect",
        "value_respect_text" => "The platform promotes respectful communication between candidates and companies.",
        "value_growth" => "Growth",
        "value_growth_text" => "Users can improve their profiles, apply to jobs, and learn through available resources.",

        "process_label" => "How it works",
        "process_title" => "Simple process",
        "process_text" => "SkillBridge organizes the employment process in clear steps.",
        "step1_title" => "1. Candidates create a profile",
        "step1_text" => "Candidates register, complete their information, upload their curriculum, and prepare their profile for companies to review.",
        "step2_title" => "2. Companies publish vacancies",
        "step2_text" => "Companies create job openings with category, modality, location, salary, responsibilities, requirements, skills, and deadline.",
        "step3_title" => "3. Candidates apply",
        "step3_text" => "Candidates can send an application message and use their saved curriculum or upload a different document for a specific application.",
        "step4_title" => "4. Companies review applications",
        "step4_text" => "Companies can review candidates, view application information, open curriculum files, and update the application status.",
        "step5_title" => "5. Users follow the process",
        "step5_text" => "Candidates can check their applications, and companies can manage their received applications in one organized section.",
        "go_profile" => "Go to profile",
        "create_account" => "Create account",
        "contact_us" => "Contact us",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and advice",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact_team" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Quiénes somos | SkillBridge",
        "meta_description" => "Conoce más sobre SkillBridge, una plataforma de empleo accesible que conecta candidatos y empresas.",

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
        "user_icon" => "Ícono de usuario",
        "users_icon" => "Ícono de personas",
        "building_icon" => "Ícono de empresa",
        "bridge_icon" => "Ícono de puente",
        "check_icon" => "Ícono de verificación",
        "eye_icon" => "Ícono de visualización",
        "lightbulb_icon" => "Ícono de idea",
        "shield_icon" => "Ícono de protección",
        "growth_icon" => "Ícono de crecimiento",
        "handshake_icon" => "Ícono de colaboración",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "hero_label" => "Quiénes somos",
        "hero_title" => "Conectamos talento con oportunidades accesibles.",
        "hero_text" => "SkillBridge es una plataforma de empleo accesible creada para ayudar a los candidatos a descubrir oportunidades laborales y ayudar a las empresas a publicar vacantes con un enfoque de contratación más respetuoso.",
        "hero_explore_jobs" => "Explorar empleos",
        "hero_view_companies" => "Ver empresas",
        "hero_post_job" => "Publicar vacante",
        "hero_review_applications" => "Revisar postulaciones",
        "hero_card_title" => "¿Por qué SkillBridge?",
        "hero_card_text" => "Porque las oportunidades laborales deben ser más claras, accesibles y fáciles de conectar con las personas adecuadas.",

        "purpose_label" => "Nuestro propósito",
        "purpose_title" => "Qué hace SkillBridge",
        "purpose_text" => "La plataforma ayuda a organizar el proceso de búsqueda laboral para candidatos y empresas.",
        "candidate_title" => "Para candidatos",
        "candidate_text" => "Los candidatos pueden crear un perfil, subir su currículum, explorar vacantes, aplicar a empleos y dar seguimiento a sus postulaciones.",
        "company_title" => "Para empresas",
        "company_text" => "Las empresas pueden publicar vacantes, compartir requisitos y revisar postulaciones de candidatos interesados en sus oportunidades.",
        "inclusion_title" => "Para la inclusión",
        "inclusion_text" => "SkillBridge promueve herramientas accesibles, información clara y prácticas de contratación inclusivas para una mejor experiencia laboral.",

        "direction_label" => "Misión y visión",
        "direction_title" => "Nuestra dirección",
        "direction_text" => "SkillBridge fue creado con un propósito práctico e inclusivo.",
        "mission_title" => "Misión",
        "mission_text" => "Conectar candidatos con empresas mediante una plataforma de empleo accesible que organiza perfiles, vacantes, postulaciones y oportunidades laborales inclusivas.",
        "vision_title" => "Visión",
        "vision_text" => "Convertirnos en un puente digital que apoye procesos de contratación más inclusivos y ayude a las personas a encontrar oportunidades según sus habilidades, intereses y metas.",
        "objective_title" => "Objetivo del proyecto",
        "objective_text" => "El objetivo de SkillBridge es demostrar cómo la tecnología puede apoyar el empleo inclusivo mediante herramientas de búsqueda laboral, perfiles de empresa, postulaciones de candidatos, opciones de accesibilidad y gestión de perfiles.",

        "values_label" => "Valores",
        "values_title" => "Qué guía la plataforma",
        "values_text" => "Estos valores representan el propósito detrás de SkillBridge.",
        "value_inclusion" => "Inclusión",
        "value_inclusion_text" => "Creemos que toda persona merece acceso a oportunidades laborales claras y respetuosas.",
        "value_accessibility" => "Accesibilidad",
        "value_accessibility_text" => "La plataforma incluye herramientas que ayudan a los usuarios a adaptar la experiencia según sus necesidades.",
        "value_responsibility" => "Responsabilidad",
        "value_responsibility_text" => "Los candidatos y las empresas deben usar la plataforma con información verdadera, actualizada y respetuosa.",
        "value_opportunity" => "Oportunidad",
        "value_opportunity_text" => "SkillBridge ayuda a las personas a descubrir opciones que pueden apoyar su crecimiento profesional.",
        "value_respect" => "Respeto",
        "value_respect_text" => "La plataforma promueve una comunicación respetuosa entre candidatos y empresas.",
        "value_growth" => "Crecimiento",
        "value_growth_text" => "Los usuarios pueden mejorar sus perfiles, aplicar a empleos y aprender mediante los recursos disponibles.",

        "process_label" => "Cómo funciona",
        "process_title" => "Proceso simple",
        "process_text" => "SkillBridge organiza el proceso laboral en pasos claros.",
        "step1_title" => "1. Los candidatos crean un perfil",
        "step1_text" => "Los candidatos se registran, completan su información, suben su currículum y preparan su perfil para que las empresas lo revisen.",
        "step2_title" => "2. Las empresas publican vacantes",
        "step2_text" => "Las empresas crean vacantes con categoría, modalidad, ubicación, salario, responsabilidades, requisitos, habilidades y fecha límite.",
        "step3_title" => "3. Los candidatos aplican",
        "step3_text" => "Los candidatos pueden enviar un mensaje de postulación y usar su currículum guardado o subir un documento diferente para una postulación específica.",
        "step4_title" => "4. Las empresas revisan postulaciones",
        "step4_text" => "Las empresas pueden revisar candidatos, ver información de postulación, abrir archivos de currículum y actualizar el estado de la postulación.",
        "step5_title" => "5. Los usuarios dan seguimiento al proceso",
        "step5_text" => "Los candidatos pueden revisar sus postulaciones y las empresas pueden gestionar las postulaciones recibidas en una sección organizada.",
        "go_profile" => "Ir al perfil",
        "create_account" => "Crear cuenta",
        "contact_us" => "Contáctanos",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_create_profile" => "Crear perfil",
        "footer_resources" => "Recursos y consejos",
        "footer_find_talent" => "Encontrar talento",
        "footer_business_plans" => "Planes empresariales",
        "footer_contact_team" => "Contactar al equipo",
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

    return "quienessomos.php?" . http_build_query($params);
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;
$mostrarColumnaCandidatos = !$esEmpresa || $esAdmin;

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
                    <span class="section-label"><?php echo limpiar(t("hero_label")); ?></span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_text")); ?>
                    </p>

                    <div class="hero-actions">
                        <?php if ($esEmpresa): ?>
                            <a href="publicarvacante.php" class="button button-primary">
                                <?php echo limpiar(t("hero_post_job")); ?>
                                <i class="fa-solid fa-arrow-right"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                            </a>

                            <a href="postulaciones-empresa.php" class="button button-secondary">
                                <?php echo limpiar(t("hero_review_applications")); ?>
                            </a>
                        <?php else: ?>
                            <a href="empleos.php" class="button button-primary">
                                <?php echo limpiar(t("hero_explore_jobs")); ?>
                                <i class="fa-solid fa-arrow-right"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                            </a>

                            <a href="empresas.php" class="button button-secondary">
                                <?php echo limpiar(t("hero_view_companies")); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="extra-hero-card">
                    <i class="fa-solid fa-bridge"
                        role="img"
                        aria-label="<?php echo limpiar(t("bridge_icon")); ?>"
                        title="<?php echo limpiar(t("bridge_icon")); ?>"></i>

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
                        <span class="section-label"><?php echo limpiar(t("purpose_label")); ?></span>

                        <h2><?php echo limpiar(t("purpose_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("purpose_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="extra-grid">

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-users"
                                role="img"
                                aria-label="<?php echo limpiar(t("users_icon")); ?>"
                                title="<?php echo limpiar(t("users_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("candidate_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("candidate_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-building"
                                role="img"
                                aria-label="<?php echo limpiar(t("building_icon")); ?>"
                                title="<?php echo limpiar(t("building_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("company_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("company_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("inclusion_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("inclusion_text")); ?>
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="contact-wrapper">

                    <div class="auth-header">
                        <span class="section-label"><?php echo limpiar(t("direction_label")); ?></span>

                        <h2><?php echo limpiar(t("direction_title")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("direction_text")); ?>
                        </p>
                    </div>

                    <div class="profile-description">

                        <h3><?php echo limpiar(t("mission_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("mission_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("vision_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("vision_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("objective_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("objective_text")); ?>
                        </p>

                    </div>

                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("values_label")); ?></span>

                        <h2><?php echo limpiar(t("values_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("values_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="extra-grid">

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-handshake-angle"
                                role="img"
                                aria-label="<?php echo limpiar(t("handshake_icon")); ?>"
                                title="<?php echo limpiar(t("handshake_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_inclusion")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_inclusion_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-eye"
                                role="img"
                                aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_accessibility")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_accessibility_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_responsibility")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_responsibility_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-lightbulb"
                                role="img"
                                aria-label="<?php echo limpiar(t("lightbulb_icon")); ?>"
                                title="<?php echo limpiar(t("lightbulb_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_opportunity")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_opportunity_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-shield-heart"
                                role="img"
                                aria-label="<?php echo limpiar(t("shield_icon")); ?>"
                                title="<?php echo limpiar(t("shield_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_respect")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_respect_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-chart-line"
                                role="img"
                                aria-label="<?php echo limpiar(t("growth_icon")); ?>"
                                title="<?php echo limpiar(t("growth_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("value_growth")); ?></h3>

                        <p>
                            <?php echo limpiar(t("value_growth_text")); ?>
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="contact-wrapper">

                    <div class="auth-header">
                        <span class="section-label"><?php echo limpiar(t("process_label")); ?></span>

                        <h2><?php echo limpiar(t("process_title")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("process_text")); ?>
                        </p>
                    </div>

                    <div class="profile-description">

                        <h3><?php echo limpiar(t("step1_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step1_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("step2_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step2_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("step3_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step3_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("step4_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step4_text")); ?>
                        </p>

                        <br>

                        <h3><?php echo limpiar(t("step5_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step5_text")); ?>
                        </p>

                        <br>

                        <div class="hero-actions">
                            <?php if ($usuarioLogueado): ?>
                                <a href="perfil.php" class="button button-primary">
                                    <?php echo limpiar(t("go_profile")); ?>
                                    <i class="fa-solid fa-user"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                        title="<?php echo limpiar(t("user_icon")); ?>"></i>
                                </a>
                            <?php else: ?>
                                <a href="registro.php" class="button button-primary">
                                    <?php echo limpiar(t("create_account")); ?>
                                    <i class="fa-solid fa-user-plus"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                        title="<?php echo limpiar(t("user_icon")); ?>"></i>
                                </a>
                            <?php endif; ?>

                            <a href="contacto.php" class="button button-secondary">
                                <?php echo limpiar(t("contact_us")); ?>
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
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
</body>

</html>
