<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    require_once "config/conexion.php";

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
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        $usuarioActual = null;
    }
}

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

if ($usuarioLogueado && $usuarioActual && isset($_GET["lang"]) && isset($pdo)) {
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
        "page_title" => "Resources | SkillBridge",
        "meta_description" => "Career resources, profile tips, curriculum advice, and interview preparation for SkillBridge candidates and companies.",
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
        "book_icon" => "Book icon",
        "user_check_icon" => "Profile check icon",
        "file_icon" => "File icon",
        "comments_icon" => "Comments icon",
        "search_icon" => "Search strategy icon",
        "send_icon" => "Send icon",
        "check_icon" => "Check icon",
        "clipboard_icon" => "Checklist icon",
        "handshake_icon" => "Handshake icon",
        "building_user_icon" => "Workplace icon",
        "arrow_icon" => "Arrow icon",
        "user_icon" => "User icon",
        "user_plus_icon" => "Create profile icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "hero_label" => "Career resources",
        "hero_title" => "Tools and tips to prepare for better job opportunities.",
        "hero_text" => "Find simple resources to improve your profile, prepare for interviews, write a stronger curriculum, and apply to accessible job opportunities with confidence.",
        "hero_text_company" => "Find simple recommendations to publish clearer job openings, review applications, and promote accessible hiring processes.",
        "explore_jobs" => "Explore jobs",
        "complete_profile" => "Complete profile",
        "create_account" => "Create account",
        "post_job" => "Post a job",
        "review_applications" => "Review applications",
        "professional_growth" => "Professional growth",
        "professional_growth_text" => "SkillBridge helps candidates and companies connect through clear, accessible, and inclusive information.",

        "guides_label" => "Guides",
        "guides_title" => "Useful resources",
        "guides_text" => "Start with these recommendations before applying to a job opening or publishing one.",
        "profile_tips" => "Profile tips",
        "profile_tips_text" => "Keep your information updated, clear, and focused on your strengths. Add your phone number, location, description, curriculum, and profile photo.",
        "curriculum_advice" => "Curriculum advice",
        "curriculum_advice_text" => "Use simple language and include your skills, education, experience, contact information, and achievements that match the job opening.",
        "interview_preparation" => "Interview preparation",
        "interview_preparation_text" => "Practice short answers about who you are, what you can do, what you have learned, and how you can contribute to the company.",
        "accessibility_card" => "Accessibility",
        "accessibility_card_text" => "Share accessibility needs clearly and respectfully so companies can provide better support during the selection process.",
        "job_search_strategy" => "Job search strategy",
        "job_search_strategy_text" => "Search by title, category, work arrangement, and experience level. Review the job details carefully before applying.",
        "application_message" => "Application message",
        "application_message_text" => "Write a short message explaining why you are interested in the position and which skills make you a good candidate.",

        "checklist_label" => "Checklist",
        "checklist_title" => "Before you apply",
        "checklist_text" => "Use this quick checklist to make your application stronger.",
        "step_1_title" => "1. Complete your profile",
        "step_1_text" => "Add your name, phone number, location, profile description, profile photo, and curriculum. A complete profile helps companies understand your professional information faster.",
        "step_2_title" => "2. Read the job requirements",
        "step_2_text" => "Before applying, review the responsibilities, skills, experience level, work arrangement, and application deadline.",
        "step_3_title" => "3. Prepare your curriculum",
        "step_3_text" => "Upload a PDF, DOC, or DOCX curriculum with updated information. Make sure the file is clear and easy to read.",
        "step_4_title" => "4. Write a direct message",
        "step_4_text" => "Your application message should be short, respectful, and connected to the job opening. Mention your interest and your strongest skills.",
        "step_5_title" => "5. Follow your applications",
        "step_5_text" => "After applying, visit the My applications section to check whether your application is pending, reviewed, accepted, or rejected.",
        "go_my_profile" => "Go to my profile",
        "create_my_profile" => "Create my profile",

        "companies_label" => "For companies",
        "companies_title" => "Inclusive hiring recommendations",
        "companies_text" => "Companies can also use SkillBridge to publish clearer and more accessible job opportunities.",
        "clear_requirements" => "Clear requirements",
        "clear_requirements_text" => "Write requirements that are necessary for the position and avoid confusing language.",
        "accessible_process" => "Accessible process",
        "accessible_process_text" => "Offer respectful communication and consider accessibility needs during interviews.",
        "inclusive_culture" => "Inclusive culture",
        "inclusive_culture_text" => "Promote a workplace where people can participate, learn, and grow with dignity.",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_newsletter" => "Newsletter",
        "footer_platform" => "Platform",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and tips",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_newsletter_text" => "Receive new job openings and professional tips.",
        "footer_email_address" => "Email address",
        "footer_email_placeholder" => "Your email address",
        "footer_subscribe" => "Subscribe",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Recursos | SkillBridge",
        "meta_description" => "Recursos laborales, consejos para el perfil, currículum y preparación para entrevistas en SkillBridge.",
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
        "book_icon" => "Ícono de libro",
        "user_check_icon" => "Ícono de perfil verificado",
        "file_icon" => "Ícono de archivo",
        "comments_icon" => "Ícono de comentarios",
        "search_icon" => "Ícono de estrategia de búsqueda",
        "send_icon" => "Ícono de enviar",
        "check_icon" => "Ícono de verificación",
        "clipboard_icon" => "Ícono de lista",
        "handshake_icon" => "Ícono de apoyo",
        "building_user_icon" => "Ícono de ambiente laboral",
        "arrow_icon" => "Ícono de flecha",
        "user_icon" => "Ícono de usuario",
        "user_plus_icon" => "Ícono de crear perfil",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "hero_label" => "Recursos laborales",
        "hero_title" => "Herramientas y consejos para prepararte mejor para nuevas oportunidades.",
        "hero_text" => "Encuentra recursos simples para mejorar tu perfil, prepararte para entrevistas, escribir un currículum más fuerte y aplicar con confianza a oportunidades laborales accesibles.",
        "hero_text_company" => "Encuentra recomendaciones simples para publicar vacantes más claras, revisar postulaciones y promover procesos de contratación accesibles.",
        "explore_jobs" => "Explorar empleos",
        "complete_profile" => "Completar perfil",
        "create_account" => "Crear cuenta",
        "post_job" => "Publicar vacante",
        "review_applications" => "Revisar postulaciones",
        "professional_growth" => "Crecimiento profesional",
        "professional_growth_text" => "SkillBridge ayuda a candidatos y empresas a conectarse mediante información clara, accesible e inclusiva.",

        "guides_label" => "Guías",
        "guides_title" => "Recursos útiles",
        "guides_text" => "Comienza con estas recomendaciones antes de aplicar a una vacante o publicar una oportunidad.",
        "profile_tips" => "Consejos para el perfil",
        "profile_tips_text" => "Mantén tu información actualizada, clara y enfocada en tus fortalezas. Agrega teléfono, ubicación, descripción, currículum y foto de perfil.",
        "curriculum_advice" => "Consejos para el currículum",
        "curriculum_advice_text" => "Usa lenguaje sencillo e incluye habilidades, educación, experiencia, datos de contacto y logros relacionados con la vacante.",
        "interview_preparation" => "Preparación para entrevistas",
        "interview_preparation_text" => "Practica respuestas cortas sobre quién eres, qué puedes hacer, qué has aprendido y cómo puedes aportar a la empresa.",
        "accessibility_card" => "Accesibilidad",
        "accessibility_card_text" => "Comparte tus necesidades de accesibilidad de forma clara y respetuosa para que las empresas puedan brindar mejor apoyo durante el proceso.",
        "job_search_strategy" => "Estrategia de búsqueda",
        "job_search_strategy_text" => "Busca por título, categoría, modalidad y nivel de experiencia. Revisa cuidadosamente los detalles antes de aplicar.",
        "application_message" => "Mensaje de postulación",
        "application_message_text" => "Escribe un mensaje corto explicando por qué te interesa el puesto y qué habilidades te hacen buen candidato.",

        "checklist_label" => "Lista de revisión",
        "checklist_title" => "Antes de aplicar",
        "checklist_text" => "Usa esta lista rápida para fortalecer tu postulación.",
        "step_1_title" => "1. Completa tu perfil",
        "step_1_text" => "Agrega tu nombre, teléfono, ubicación, descripción, foto de perfil y currículum. Un perfil completo ayuda a las empresas a entender tu información profesional más rápido.",
        "step_2_title" => "2. Lee los requisitos del empleo",
        "step_2_text" => "Antes de aplicar, revisa responsabilidades, habilidades, nivel de experiencia, modalidad y fecha límite.",
        "step_3_title" => "3. Prepara tu currículum",
        "step_3_text" => "Sube un currículum PDF, DOC o DOCX con información actualizada. Asegúrate de que el archivo sea claro y fácil de leer.",
        "step_4_title" => "4. Escribe un mensaje directo",
        "step_4_text" => "Tu mensaje de postulación debe ser corto, respetuoso y relacionado con la vacante. Menciona tu interés y tus habilidades más fuertes.",
        "step_5_title" => "5. Da seguimiento a tus postulaciones",
        "step_5_text" => "Después de aplicar, visita la sección Mis postulaciones para revisar si tu postulación está pendiente, revisada, aceptada o rechazada.",
        "go_my_profile" => "Ir a mi perfil",
        "create_my_profile" => "Crear mi perfil",

        "companies_label" => "Para empresas",
        "companies_title" => "Recomendaciones para contratación inclusiva",
        "companies_text" => "Las empresas también pueden usar SkillBridge para publicar oportunidades más claras y accesibles.",
        "clear_requirements" => "Requisitos claros",
        "clear_requirements_text" => "Escribe requisitos necesarios para el puesto y evita lenguaje confuso.",
        "accessible_process" => "Proceso accesible",
        "accessible_process_text" => "Ofrece comunicación respetuosa y considera necesidades de accesibilidad durante entrevistas.",
        "inclusive_culture" => "Cultura inclusiva",
        "inclusive_culture_text" => "Promueve un ambiente donde las personas puedan participar, aprender y crecer con dignidad.",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_newsletter" => "Boletín",
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
        "footer_newsletter_text" => "Recibe nuevas vacantes y consejos profesionales.",
        "footer_email_address" => "Correo electrónico",
        "footer_email_placeholder" => "Tu correo electrónico",
        "footer_subscribe" => "Suscribirse",
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

    return "recursos.php?" . http_build_query($params);
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
                    aria-label="<?php echo limpiar(t("dark_mode")); ?>"
                    title="<?php echo limpiar(t("dark_mode")); ?>"></i>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast" aria-label="<?php echo limpiar(t("high_contrast")); ?>">
                <i class="fa-solid fa-circle-half-stroke"
                    role="img"
                    aria-label="<?php echo limpiar(t("high_contrast")); ?>"
                    title="<?php echo limpiar(t("high_contrast")); ?>"></i>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage" aria-label="<?php echo limpiar(t("read_mode")); ?>">
                <i class="fa-solid fa-volume-high"
                    role="img"
                    aria-label="<?php echo limpiar(t("read_mode")); ?>"
                    title="<?php echo limpiar(t("read_mode")); ?>"></i>
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
                        <?php echo limpiar($esEmpresa ? t("hero_text_company") : t("hero_text")); ?>
                    </p>

                    <div class="hero-actions">
                        <?php if ($esEmpresaOAdmin): ?>
                            <a href="publicarvacante.php" class="button button-primary">
                                <?php echo limpiar(t("post_job")); ?>
                                <i class="fa-solid fa-arrow-right"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                            </a>

                            <a href="postulaciones-empresa.php" class="button button-secondary">
                                <?php echo limpiar(t("review_applications")); ?>
                            </a>
                        <?php else: ?>
                            <a href="empleos.php" class="button button-primary">
                                <?php echo limpiar(t("explore_jobs")); ?>
                                <i class="fa-solid fa-arrow-right"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                            </a>

                            <?php if ($usuarioLogueado): ?>
                                <a href="perfil.php" class="button button-secondary">
                                    <?php echo limpiar(t("complete_profile")); ?>
                                </a>
                            <?php else: ?>
                                <a href="registro.php" class="button button-secondary">
                                    <?php echo limpiar(t("create_account")); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="extra-hero-card">
                    <i class="fa-solid fa-book-open"
                        role="img"
                        aria-label="<?php echo limpiar(t("book_icon")); ?>"
                        title="<?php echo limpiar(t("book_icon")); ?>"></i>

                    <h3><?php echo limpiar(t("professional_growth")); ?></h3>

                    <p>
                        <?php echo limpiar(t("professional_growth_text")); ?>
                    </p>
                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("guides_label")); ?></span>

                        <h2><?php echo limpiar(t("guides_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("guides_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="extra-grid">

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-user-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("user_check_icon")); ?>"
                                title="<?php echo limpiar(t("user_check_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("profile_tips")); ?></h3>

                        <p>
                            <?php echo limpiar(t("profile_tips_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-file-lines"
                                role="img"
                                aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                title="<?php echo limpiar(t("file_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("curriculum_advice")); ?></h3>

                        <p>
                            <?php echo limpiar(t("curriculum_advice_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-comments"
                                role="img"
                                aria-label="<?php echo limpiar(t("comments_icon")); ?>"
                                title="<?php echo limpiar(t("comments_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("interview_preparation")); ?></h3>

                        <p>
                            <?php echo limpiar(t("interview_preparation_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("accessibility_card")); ?></h3>

                        <p>
                            <?php echo limpiar(t("accessibility_card_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-magnifying-glass-chart"
                                role="img"
                                aria-label="<?php echo limpiar(t("search_icon")); ?>"
                                title="<?php echo limpiar(t("search_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("job_search_strategy")); ?></h3>

                        <p>
                            <?php echo limpiar(t("job_search_strategy_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-paper-plane"
                                role="img"
                                aria-label="<?php echo limpiar(t("send_icon")); ?>"
                                title="<?php echo limpiar(t("send_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("application_message")); ?></h3>

                        <p>
                            <?php echo limpiar(t("application_message_text")); ?>
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <?php if (!$esEmpresa): ?>
            <section class="extra-section">
                <div class="container">

                    <div class="contact-wrapper">

                        <div class="auth-header">
                            <span class="section-label"><?php echo limpiar(t("checklist_label")); ?></span>

                            <h2><?php echo limpiar(t("checklist_title")); ?></h2>

                            <p class="auth-subtitle">
                                <?php echo limpiar(t("checklist_text")); ?>
                            </p>
                        </div>

                        <div class="profile-description">

                            <h3><?php echo limpiar(t("step_1_title")); ?></h3>
                            <p><?php echo limpiar(t("step_1_text")); ?></p>

                            <br>

                            <h3><?php echo limpiar(t("step_2_title")); ?></h3>
                            <p><?php echo limpiar(t("step_2_text")); ?></p>

                            <br>

                            <h3><?php echo limpiar(t("step_3_title")); ?></h3>
                            <p><?php echo limpiar(t("step_3_text")); ?></p>

                            <br>

                            <h3><?php echo limpiar(t("step_4_title")); ?></h3>
                            <p><?php echo limpiar(t("step_4_text")); ?></p>

                            <br>

                            <h3><?php echo limpiar(t("step_5_title")); ?></h3>
                            <p><?php echo limpiar(t("step_5_text")); ?></p>

                            <br>

                            <?php if ($usuarioLogueado): ?>
                                <a href="perfil.php" class="button button-primary">
                                    <?php echo limpiar(t("go_my_profile")); ?>
                                    <i class="fa-solid fa-user"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                        title="<?php echo limpiar(t("user_icon")); ?>"></i>
                                </a>
                            <?php else: ?>
                                <a href="registro.php" class="button button-primary">
                                    <?php echo limpiar(t("create_my_profile")); ?>
                                    <i class="fa-solid fa-user-plus"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("user_plus_icon")); ?>"
                                        title="<?php echo limpiar(t("user_plus_icon")); ?>"></i>
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>
            </section>
        <?php endif; ?>

        <section class="extra-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("companies_label")); ?></span>

                        <h2><?php echo limpiar(t("companies_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("companies_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="extra-grid">

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-clipboard-list"
                                role="img"
                                aria-label="<?php echo limpiar(t("clipboard_icon")); ?>"
                                title="<?php echo limpiar(t("clipboard_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("clear_requirements")); ?></h3>

                        <p>
                            <?php echo limpiar(t("clear_requirements_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-handshake-angle"
                                role="img"
                                aria-label="<?php echo limpiar(t("handshake_icon")); ?>"
                                title="<?php echo limpiar(t("handshake_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("accessible_process")); ?></h3>

                        <p>
                            <?php echo limpiar(t("accessible_process_text")); ?>
                        </p>
                    </article>

                    <article class="extra-card">
                        <div class="extra-card-icon">
                            <i class="fa-solid fa-building-user"
                                role="img"
                                aria-label="<?php echo limpiar(t("building_user_icon")); ?>"
                                title="<?php echo limpiar(t("building_user_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("inclusive_culture")); ?></h3>

                        <p>
                            <?php echo limpiar(t("inclusive_culture_text")); ?>
                        </p>
                    </article>

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
