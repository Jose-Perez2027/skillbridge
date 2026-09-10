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
            $_SESSION["nombre"] = $usuarioActual["nombre"] ?? "";
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

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif ($usuarioActual && !empty($usuarioActual["idioma_preferido"]) && in_array($usuarioActual["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $usuarioActual["idioma_preferido"];
} elseif (!empty($_SESSION["idioma_preferido"]) && in_array($_SESSION["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_SESSION["idioma_preferido"];
} elseif (!empty($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idiomaActual = $_COOKIE["skillbridgeLanguage"];
} else {
    $idiomaActual = "en";
}

$_SESSION["idioma_preferido"] = $idiomaActual;
setcookie("skillbridgeLanguage", $idiomaActual, time() + 31536000, "/");

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
        "page_title" => "Companies | SkillBridge",
        "meta_description" => "Discover SkillBridge partner companies and publish accessible job opportunities.",

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
        "users_icon" => "Users icon",
        "briefcase_icon" => "Job icon",
        "building_icon" => "Company icon",
        "location_icon" => "Location icon",
        "arrow_icon" => "Arrow icon",
        "clock_icon" => "Clock icon",
        "target_icon" => "Target icon",
        "handshake_icon" => "Handshake icon",
        "warning_icon" => "Warning icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",
        "send_icon" => "Send icon",
        "chevron_icon" => "Navigation arrow icon",

        "breadcrumb_home" => "Home",
        "breadcrumb_companies" => "Companies",

        "hero_label" => "Partner companies",
        "hero_title_company" => "Find qualified talent to help your company grow.",
        "hero_title_public" => "Discover companies creating accessible job opportunities.",
        "hero_text_company" => "SkillBridge connects companies with qualified candidates and promotes accessible hiring without barriers.",
        "hero_text_public" => "Explore organizations connected to SkillBridge and learn how they support accessible employment opportunities.",
        "button_post_job" => "Post a job",
        "button_view_applications" => "View received applications",
        "button_view_jobs" => "View jobs",
        "button_create_account" => "Create account",

        "mini_diverse_title" => "Diverse talent",
        "mini_diverse_text" => "Candidates with different skills and experiences.",
        "mini_inclusion_title" => "Workplace inclusion",
        "mini_inclusion_text" => "Job openings designed to create more opportunities.",
        "mini_process_title" => "Clear process",
        "mini_process_text" => "Organized information for companies and candidates.",

        "benefits_label" => "Benefits",
        "benefits_title" => "Why choose SkillBridge?",
        "benefits_text" => "Our platform helps companies post job opportunities in a clear, professional, and accessible way.",
        "benefit_hiring_title" => "Accessible hiring",
        "benefit_hiring_text" => "Promote more accessible recruitment processes for women, men, and people with disabilities.",
        "benefit_reach_title" => "Greater reach",
        "benefit_reach_text" => "Job openings reach candidates who are interested in growing professionally and contributing value.",
        "benefit_easy_title" => "Easy posting",
        "benefit_easy_text" => "Companies can organize their job opening information quickly and clearly.",

        "network_label" => "Business network",
        "network_title" => "Partner companies",
        "network_text_singular" => "1 partner company is part of SkillBridge's job opportunity network.",
        "network_text_plural_before" => "partner companies are part of SkillBridge's job opportunity network.",
        "network_empty" => "Companies will appear here when they publish job openings.",
        "partner_company_default" => "SkillBridge partner company",
        "company_description_default" => "A company committed to accessible hiring.",
        "company_location_default" => "El Salvador",
        "company_team_default" => "Growing team",
        "vacancy_singular" => "job opening",
        "vacancy_plural" => "job openings",
        "view_job_openings" => "View job openings",
        "no_companies_title" => "No partner companies yet.",
        "no_companies_text" => "When a company publishes its first job opening, it will appear in this section automatically.",

        "process_label" => "Process",
        "process_title" => "Posting a job is easy",
        "step_one_title" => "Complete the form",
        "step_one_text" => "Add the main information about your company and the job opening.",
        "step_two_title" => "Describe the position",
        "step_two_text" => "Explain the duties, requirements, work arrangement, salary, and deadline.",
        "step_three_title" => "Receive applications",
        "step_three_text" => "Candidates can view the job opening and apply through SkillBridge.",

        "cta_label_company" => "Post your job opening",
        "cta_title_company" => "Let's build job opportunities without barriers.",
        "cta_text_company" => "Post an accessible job opening and connect with qualified talent ready to contribute value to your company.",
        "cta_label_public" => "Explore accessible employment",
        "cta_title_public" => "Know the companies that believe in talent without barriers.",
        "cta_text_public" => "SkillBridge highlights organizations that create clearer and more accessible opportunities for candidates.",
        "button_go_profile" => "Go to profile",
        "button_login_company" => "Log in as company",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_newsletter" => "Newsletter",
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
        "page_title" => "Empresas | SkillBridge",
        "meta_description" => "Descubre empresas aliadas de SkillBridge y publica oportunidades laborales accesibles.",

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
        "users_icon" => "Ícono de usuarios",
        "briefcase_icon" => "Ícono de empleo",
        "building_icon" => "Ícono de empresa",
        "location_icon" => "Ícono de ubicación",
        "arrow_icon" => "Ícono de flecha",
        "clock_icon" => "Ícono de reloj",
        "target_icon" => "Ícono de objetivo",
        "handshake_icon" => "Ícono de apoyo",
        "warning_icon" => "Ícono de advertencia",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",
        "send_icon" => "Ícono de enviar",
        "chevron_icon" => "Ícono de flecha de navegación",

        "breadcrumb_home" => "Inicio",
        "breadcrumb_companies" => "Empresas",

        "hero_label" => "Empresas aliadas",
        "hero_title_company" => "Encuentra talento calificado para ayudar a crecer tu empresa.",
        "hero_title_public" => "Descubre empresas que crean oportunidades laborales accesibles.",
        "hero_text_company" => "SkillBridge conecta empresas con candidatos calificados y promueve contrataciones accesibles sin barreras.",
        "hero_text_public" => "Explora organizaciones conectadas con SkillBridge y conoce cómo apoyan oportunidades laborales accesibles.",
        "button_post_job" => "Publicar vacante",
        "button_view_applications" => "Ver postulaciones recibidas",
        "button_view_jobs" => "Ver empleos",
        "button_create_account" => "Crear cuenta",

        "mini_diverse_title" => "Talento diverso",
        "mini_diverse_text" => "Candidatos con diferentes habilidades y experiencias.",
        "mini_inclusion_title" => "Inclusión laboral",
        "mini_inclusion_text" => "Vacantes diseñadas para crear más oportunidades.",
        "mini_process_title" => "Proceso claro",
        "mini_process_text" => "Información organizada para empresas y candidatos.",

        "benefits_label" => "Beneficios",
        "benefits_title" => "¿Por qué elegir SkillBridge?",
        "benefits_text" => "Nuestra plataforma ayuda a las empresas a publicar oportunidades laborales de forma clara, profesional y accesible.",
        "benefit_hiring_title" => "Contratación accesible",
        "benefit_hiring_text" => "Promueve procesos de reclutamiento más accesibles para mujeres, hombres y personas con discapacidad.",
        "benefit_reach_title" => "Mayor alcance",
        "benefit_reach_text" => "Las vacantes llegan a candidatos interesados en crecer profesionalmente y aportar valor.",
        "benefit_easy_title" => "Publicación sencilla",
        "benefit_easy_text" => "Las empresas pueden organizar la información de sus vacantes de forma rápida y clara.",

        "network_label" => "Red empresarial",
        "network_title" => "Empresas aliadas",
        "network_text_singular" => "1 empresa aliada forma parte de la red de oportunidades laborales de SkillBridge.",
        "network_text_plural_before" => "empresas aliadas forman parte de la red de oportunidades laborales de SkillBridge.",
        "network_empty" => "Las empresas aparecerán aquí cuando publiquen vacantes.",
        "partner_company_default" => "Empresa aliada de SkillBridge",
        "company_description_default" => "Una empresa comprometida con la contratación accesible.",
        "company_location_default" => "El Salvador",
        "company_team_default" => "Equipo en crecimiento",
        "vacancy_singular" => "vacante",
        "vacancy_plural" => "vacantes",
        "view_job_openings" => "Ver vacantes",
        "no_companies_title" => "Aún no hay empresas aliadas.",
        "no_companies_text" => "Cuando una empresa publique su primera vacante, aparecerá automáticamente en esta sección.",

        "process_label" => "Proceso",
        "process_title" => "Publicar una vacante es fácil",
        "step_one_title" => "Completa el formulario",
        "step_one_text" => "Agrega la información principal de tu empresa y de la vacante.",
        "step_two_title" => "Describe el puesto",
        "step_two_text" => "Explica funciones, requisitos, modalidad de trabajo, salario y fecha límite.",
        "step_three_title" => "Recibe postulaciones",
        "step_three_text" => "Los candidatos pueden ver la vacante y postularse por medio de SkillBridge.",

        "cta_label_company" => "Publica tu vacante",
        "cta_title_company" => "Construyamos oportunidades laborales sin barreras.",
        "cta_text_company" => "Publica una vacante accesible y conecta con talento calificado listo para aportar valor a tu empresa.",
        "cta_label_public" => "Explora empleo accesible",
        "cta_title_public" => "Conoce empresas que creen en el talento sin barreras.",
        "cta_text_public" => "SkillBridge destaca organizaciones que crean oportunidades más claras y accesibles para candidatos.",
        "button_go_profile" => "Ir al perfil",
        "button_login_company" => "Iniciar sesión como empresa",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_newsletter" => "Boletín",
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

function linkPagina($pagina, $extra = []) {
    global $idiomaActual;

    $params = array_merge(["lang" => $idiomaActual], $extra);
    return $pagina . "?" . http_build_query($params);
}

function urlConIdioma($idioma) {
    $params = $_GET;
    $params["lang"] = $idioma;

    return "empresas.php?" . http_build_query($params);
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

$empresas = [];

try {
    $sqlEmpresas = "
        SELECT 
            empresas.id_empresa,
            empresas.nombre,
            empresas.lema,
            empresas.descripcion,
            empresas.ubicacion,
            empresas.colaboradores,
            empresas.fecha_registro,
            COUNT(vacantes.id_vacante) AS total_vacantes
        FROM empresas
        LEFT JOIN vacantes 
            ON empresas.id_empresa = vacantes.id_empresa
            AND vacantes.estado = 'activa'
        WHERE empresas.estado = 1
        GROUP BY 
            empresas.id_empresa,
            empresas.nombre,
            empresas.lema,
            empresas.descripcion,
            empresas.ubicacion,
            empresas.colaboradores,
            empresas.fecha_registro
        ORDER BY empresas.fecha_registro DESC
    ";

    $consultaEmpresas = $pdo->query($sqlEmpresas);
    $empresas = $consultaEmpresas->fetchAll();
} catch (Exception $error) {
    $empresas = [];
}

$totalEmpresas = count($empresas);

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
$heroTitle = $esEmpresaOAdmin ? t("hero_title_company") : t("hero_title_public");
$heroText = $esEmpresaOAdmin ? t("hero_text_company") : t("hero_text_public");
$ctaLabel = $esEmpresaOAdmin ? t("cta_label_company") : t("cta_label_public");
$ctaTitle = $esEmpresaOAdmin ? t("cta_title_company") : t("cta_title_public");
$ctaText = $esEmpresaOAdmin ? t("cta_text_company") : t("cta_text_public");
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

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/empresas.css?v=20260904footerfinal">
</head>

<body class="<?php echo limpiar($bodyClassText); ?>">

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

            <a href="<?php echo limpiar(linkPagina("index.php")); ?>" class="logo"
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
                    <a href="<?php echo limpiar(linkPagina("index.php")); ?>"<?php echo enlaceActivo("index.php"); ?>>
                        <?php echo limpiar(t("nav_home")); ?>
                    </a>
                </li>

                <?php if (!$esEmpresa): ?>
                    <li>
                        <a href="<?php echo limpiar(linkPagina("empleos.php")); ?>"<?php echo enlaceActivo("empleos.php"); ?>>
                            <?php echo limpiar(t("nav_find_jobs")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="<?php echo limpiar(linkPagina("empresas.php")); ?>"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>

                <?php if ($usuarioLogueado && ($esCandidato || $esAdmin)): ?>
                    <li>
                        <a href="<?php echo limpiar(linkPagina("postulaciones.php")); ?>"<?php echo enlaceActivo("postulaciones.php"); ?>>
                            <?php echo limpiar(t("nav_my_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado && $esEmpresaOAdmin): ?>
                    <li>
                        <a href="<?php echo limpiar(linkPagina("publicarvacante.php")); ?>"<?php echo enlaceActivo("publicarvacante.php"); ?>>
                            <?php echo limpiar(t("nav_post_job")); ?>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo limpiar(linkPagina("postulaciones-empresa.php")); ?>"<?php echo enlaceActivo("postulaciones-empresa.php"); ?>>
                            <?php echo limpiar(t("nav_received_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado): ?>
                    <li>
                        <a href="<?php echo limpiar(linkPagina("perfil.php")); ?>"<?php echo enlaceActivo("perfil.php"); ?>>
                            <?php echo limpiar(t("nav_profile")); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if ($usuarioLogueado): ?>
                    <a href="<?php echo limpiar(linkPagina("perfil.php")); ?>" class="login-link">
                        <?php echo limpiar(t("nav_my_profile")); ?>
                    </a>

                    <a href="logout.php" class="button button-primary button-small">
                        <?php echo limpiar(t("nav_logout")); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo limpiar(linkPagina("login.php")); ?>" class="login-link">
                        <?php echo limpiar(t("nav_login")); ?>
                    </a>

                    <a href="<?php echo limpiar(linkPagina("registro.php")); ?>" class="button button-primary button-small">
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

    <main>

        <section class="companies-page-hero">
            <div class="companies-hero-shape companies-hero-shape-one"></div>
            <div class="companies-hero-shape companies-hero-shape-two"></div>

            <div class="container companies-page-hero-content">

                <div class="breadcrumb">
                    <a href="<?php echo limpiar(linkPagina("index.php")); ?>">
                        <?php echo limpiar(t("breadcrumb_home")); ?>
                    </a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                    <span><?php echo limpiar(t("breadcrumb_companies")); ?></span>
                </div>

                <div class="companies-hero-grid">

                    <div class="companies-hero-text">
                        <span class="section-label">
                            <?php echo limpiar(t("hero_label")); ?>
                        </span>

                        <h1><?php echo limpiar($heroTitle); ?></h1>

                        <p>
                            <?php echo limpiar($heroText); ?>
                        </p>

                        <div class="companies-hero-buttons">
                            <?php if ($esEmpresaOAdmin): ?>
                                <a href="<?php echo limpiar(linkPagina("publicarvacante.php")); ?>" class="button button-primary">
                                    <?php echo limpiar(t("button_post_job")); ?>
                                    <i class="fa-solid fa-arrow-right"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                        title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                </a>

                                <a href="<?php echo limpiar(linkPagina("postulaciones-empresa.php")); ?>" class="button button-secondary">
                                    <?php echo limpiar(t("button_view_applications")); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo limpiar(linkPagina("empleos.php")); ?>" class="button button-primary">
                                    <?php echo limpiar(t("button_view_jobs")); ?>
                                    <i class="fa-solid fa-arrow-right"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                        title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                </a>

                                <?php if (!$usuarioLogueado): ?>
                                    <a href="<?php echo limpiar(linkPagina("registro.php")); ?>" class="button button-secondary">
                                        <?php echo limpiar(t("button_create_account")); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="companies-hero-card">

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-users"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("users_icon")); ?>"
                                    title="<?php echo limpiar(t("users_icon")); ?>"></i>
                            </div>

                            <div>
                                <strong><?php echo limpiar(t("mini_diverse_title")); ?></strong>
                                <span><?php echo limpiar(t("mini_diverse_text")); ?></span>
                            </div>
                        </div>

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-universal-access"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                    title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                            </div>

                            <div>
                                <strong><?php echo limpiar(t("mini_inclusion_title")); ?></strong>
                                <span><?php echo limpiar(t("mini_inclusion_text")); ?></span>
                            </div>
                        </div>

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-briefcase"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                    title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                            </div>

                            <div>
                                <strong><?php echo limpiar(t("mini_process_title")); ?></strong>
                                <span><?php echo limpiar(t("mini_process_text")); ?></span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <section class="companies-benefits-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">
                            <?php echo limpiar(t("benefits_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("benefits_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("benefits_text")); ?>
                        </p>
                    </div>
                </div>

                <div class="companies-benefits-grid">

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-blue">
                            <i class="fa-solid fa-handshake-angle"
                                role="img"
                                aria-label="<?php echo limpiar(t("handshake_icon")); ?>"
                                title="<?php echo limpiar(t("handshake_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("benefit_hiring_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("benefit_hiring_text")); ?>
                        </p>
                    </article>

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-purple">
                            <i class="fa-solid fa-bullseye"
                                role="img"
                                aria-label="<?php echo limpiar(t("target_icon")); ?>"
                                title="<?php echo limpiar(t("target_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("benefit_reach_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("benefit_reach_text")); ?>
                        </p>
                    </article>

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-green">
                            <i class="fa-solid fa-clock"
                                role="img"
                                aria-label="<?php echo limpiar(t("clock_icon")); ?>"
                                title="<?php echo limpiar(t("clock_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("benefit_easy_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("benefit_easy_text")); ?>
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <section class="companies-list-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">
                            <?php echo limpiar(t("network_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("network_title")); ?></h2>

                        <p>
                            <?php if ($totalEmpresas > 0): ?>
                                <?php if ($totalEmpresas === 1): ?>
                                    <?php echo limpiar(t("network_text_singular")); ?>
                                <?php else: ?>
                                    <?php echo $totalEmpresas; ?> <?php echo limpiar(t("network_text_plural_before")); ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo limpiar(t("network_empty")); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if ($totalEmpresas > 0): ?>

                    <div class="companies-grid">

                        <?php foreach ($empresas as $empresa): ?>
                            <?php
                                $nombreEmpresa = $empresa["nombre"] ?? "Company";
                                $inicialEmpresa = strtoupper(substr(trim($nombreEmpresa), 0, 1));
                                $totalVacantes = (int) ($empresa["total_vacantes"] ?? 0);
                                $lemaEmpresa = !empty($empresa["lema"]) ? $empresa["lema"] : t("partner_company_default");
                                $descripcionEmpresa = !empty($empresa["descripcion"]) ? $empresa["descripcion"] : t("company_description_default");
                                $ubicacionEmpresa = !empty($empresa["ubicacion"]) ? $empresa["ubicacion"] : t("company_location_default");
                                $colaboradoresEmpresa = !empty($empresa["colaboradores"]) ? $empresa["colaboradores"] : t("company_team_default");

                                if ($inicialEmpresa === "") {
                                    $inicialEmpresa = "C";
                                }
                            ?>

                            <article class="company-card">
                                <div class="company-logo-letter"
                                    aria-label="<?php echo limpiar($nombreEmpresa); ?>">
                                    <?php echo limpiar($inicialEmpresa); ?>
                                </div>

                                <div class="company-info">
                                    <h3>
                                        <?php echo limpiar($nombreEmpresa); ?>
                                    </h3>

                                    <p class="company-slogan">
                                        <?php echo limpiar($lemaEmpresa); ?>
                                    </p>

                                    <p class="company-description">
                                        <?php echo limpiar($descripcionEmpresa); ?>
                                    </p>

                                    <div class="company-meta">
                                        <span>
                                            <i class="fa-solid fa-location-dot"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                                title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                            <?php echo limpiar($ubicacionEmpresa); ?>
                                        </span>

                                        <span>
                                            <i class="fa-solid fa-users"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("users_icon")); ?>"
                                                title="<?php echo limpiar(t("users_icon")); ?>"></i>
                                            <?php echo limpiar($colaboradoresEmpresa); ?>
                                        </span>
                                    </div>

                                    <div class="company-card-footer">
                                        <span class="vacancies-badge">
                                            <?php echo $totalVacantes; ?>
                                            <?php echo limpiar($totalVacantes === 1 ? t("vacancy_singular") : t("vacancy_plural")); ?>
                                        </span>

                                        <?php if (!$esEmpresa): ?>
                                            <a href="<?php echo limpiar(linkPagina("empleos.php")); ?>" class="text-link">
                                                <?php echo limpiar(t("view_job_openings")); ?>
                                                <i class="fa-solid fa-arrow-right"
                                                    role="img"
                                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="no-companies-card">
                        <div class="no-companies-icon">
                            <i class="fa-solid fa-building-circle-exclamation"
                                role="img"
                                aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("no_companies_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("no_companies_text")); ?>
                        </p>

                        <?php if ($esEmpresaOAdmin): ?>
                            <a href="<?php echo limpiar(linkPagina("publicarvacante.php")); ?>" class="button button-primary">
                                <?php echo limpiar(t("button_post_job")); ?>
                                <i class="fa-solid fa-arrow-right"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                    title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

            </div>
        </section>

        <section class="companies-process-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">
                            <?php echo limpiar(t("process_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("process_title")); ?></h2>
                    </div>
                </div>

                <div class="companies-steps-grid">

                    <article class="company-step-card">
                        <span>01</span>
                        <h3><?php echo limpiar(t("step_one_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step_one_text")); ?>
                        </p>
                    </article>

                    <article class="company-step-card">
                        <span>02</span>
                        <h3><?php echo limpiar(t("step_two_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step_two_text")); ?>
                        </p>
                    </article>

                    <article class="company-step-card">
                        <span>03</span>
                        <h3><?php echo limpiar(t("step_three_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("step_three_text")); ?>
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <section class="companies-cta-section">
            <div class="container companies-cta-content">
                <div>
                    <span class="section-label cta-label">
                        <?php echo limpiar($ctaLabel); ?>
                    </span>

                    <h2><?php echo limpiar($ctaTitle); ?></h2>

                    <p>
                        <?php echo limpiar($ctaText); ?>
                    </p>
                </div>

                <?php if ($esEmpresaOAdmin): ?>
                    <a href="<?php echo limpiar(linkPagina("publicarvacante.php")); ?>" class="button button-light">
                        <?php echo limpiar(t("button_post_job")); ?>
                        <i class="fa-solid fa-arrow-right"
                            role="img"
                            aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                            title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                    </a>
                <?php elseif ($usuarioLogueado): ?>
                    <a href="<?php echo limpiar(linkPagina("perfil.php")); ?>" class="button button-light">
                        <?php echo limpiar(t("button_go_profile")); ?>
                        <i class="fa-solid fa-arrow-right"
                            role="img"
                            aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                            title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo limpiar(linkPagina("login.php", ["redirect" => "publicarvacante.php"])); ?>" class="button button-light">
                        <?php echo limpiar(t("button_login_company")); ?>
                        <i class="fa-solid fa-arrow-right"
                            role="img"
                            aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                            title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                    </a>
                <?php endif; ?>
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
            document.cookie = "skillbridgeLanguage=" + window.SkillBridgeUserPreferences.language + "; path=/; max-age=31536000";

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
