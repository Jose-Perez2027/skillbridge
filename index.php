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

function normalizarTextoBusqueda($valor) {
    $valor = trim((string)($valor ?? ""));

    $mapa = [
        "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ü" => "U", "Ñ" => "N",
        "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ü" => "u", "ñ" => "n"
    ];

    $valor = strtr($valor, $mapa);
    $valor = strtolower($valor);
    $valor = preg_replace('/\s+/', ' ', $valor);

    return trim($valor);
}

function normalizarCategoria($valor) {
    $texto = normalizarTextoBusqueda($valor);

    if ($texto === "technology" || $texto === "tech" || $texto === "tecnologia") {
        return "technology";
    }

    if ($texto === "design" || $texto === "diseno" || $texto === "diseño") {
        return "design";
    }

    if ($texto === "sales" || $texto === "ventas") {
        return "sales";
    }

    if ($texto === "administration" || $texto === "administracion") {
        return "administration";
    }

    if ($texto === "customer service" || $texto === "customer support" || $texto === "atencion" || $texto === "atencion al cliente") {
        return "customer service";
    }

    if ($texto === "human resources" || $texto === "recursos humanos" || $texto === "rrhh") {
        return "human resources";
    }

    return $texto;
}

function normalizarModalidad($valor) {
    $texto = normalizarTextoBusqueda($valor);

    if ($texto === "remote" || $texto === "remoto") {
        return "remote";
    }

    if ($texto === "hybrid" || $texto === "hibrido" || $texto === "híbrido") {
        return "hybrid";
    }

    if ($texto === "on-site" || $texto === "onsite" || $texto === "in-person" || $texto === "in person" || $texto === "presencial") {
        return "on-site";
    }

    return $texto;
}

function normalizarTipoEmpleo($valor) {
    $texto = normalizarTextoBusqueda($valor);

    if ($texto === "full-time" || $texto === "full time" || $texto === "tiempo completo") {
        return "full-time";
    }

    if ($texto === "part-time" || $texto === "part time" || $texto === "medio tiempo") {
        return "part-time";
    }

    if ($texto === "internship" || $texto === "pasantia" || $texto === "pasantía") {
        return "internship";
    }

    if ($texto === "temporary" || $texto === "temporal") {
        return "temporary";
    }

    if ($texto === "freelance") {
        return "freelance";
    }

    return $texto;
}

function modalidadClase($modalidad) {
    $modalidadNormalizada = normalizarModalidad($modalidad);

    if ($modalidadNormalizada === "remote") {
        return "remote";
    }

    if ($modalidadNormalizada === "hybrid") {
        return "hybrid";
    }

    return "in-person";
}

function categoriaSlug($categoria) {
    $categoriaNormalizada = normalizarCategoria($categoria);

    $mapa = [
        "technology" => "tecnologia",
        "design" => "diseno",
        "sales" => "ventas",
        "administration" => "administracion",
        "customer service" => "atencion",
        "human resources" => "recursos-humanos"
    ];

    return $mapa[$categoriaNormalizada] ?? "todos";
}

function modalidadSlug($modalidad) {
    $modalidadNormalizada = normalizarModalidad($modalidad);

    $mapa = [
        "remote" => "remoto",
        "hybrid" => "hibrido",
        "on-site" => "presencial"
    ];

    return $mapa[$modalidadNormalizada] ?? "todos";
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
} else {
    $idiomaActual = "en";
}

if (!in_array($idiomaActual, $idiomasPermitidos, true)) {
    $idiomaActual = "en";
}

$_SESSION["idioma_preferido"] = $idiomaActual;

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
        "page_title" => "SkillBridge | Jobs Without Barriers",
        "meta_description" => "SkillBridge connects talent, companies, and accessible job opportunities in El Salvador.",

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

        "aria_logo" => "SkillBridge home",
        "logo_alt" => "SkillBridge logo",
        "aria_open_menu" => "Open navigation menu",
        "aria_open_accessibility" => "Open accessibility tools",
        "aria_close_accessibility" => "Close accessibility tools",
        "aria_switch_language" => "Switch to Spanish",
        "skip_main" => "Skip to main content",

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
        "building_icon" => "Company icon",
        "briefcase_icon" => "Job icon",
        "location_icon" => "Location icon",
        "home_work_icon" => "Work arrangement icon",
        "search_icon" => "Search icon",
        "category_icon" => "Category icon",
        "user_check_icon" => "Application icon",
        "heart_icon" => "Experience icon",
        "bookmark_icon" => "Save job icon",
        "clock_icon" => "Clock icon",
        "handshake_icon" => "Handshake icon",
        "volume_icon" => "Text reader icon",
        "contrast_icon" => "High contrast icon",
        "check_icon" => "Check icon",
        "quote_icon" => "Quote icon",
        "paper_plane_icon" => "Send icon",
        "user_icon" => "User icon",
        "user_plus_icon" => "Create account icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "hero_title_user" => "Your talent opens doors.",
        "hero_title_user_span" => "We build the bridge.",
        "hero_text_user" => "SkillBridge connects people with real job opportunities, committed companies, and resources for professional growth.",
        "hero_find_job" => "Find a job",
        "hero_company" => "I'm a company",
        "hero_profile" => "Go to my profile",
        "hero_trust_prefix" => "More than",
        "hero_trust_strong" => "2,500 people",
        "hero_trust_suffix" => "have already found opportunities.",

        "hero_title_company" => "Manage inclusive job opportunities.",
        "hero_title_company_span" => "Your company can open doors.",
        "hero_text_company" => "Post openings, review applications, and connect with candidates through an accessible platform.",
        "company_dashboard" => "Company dashboard",
        "received_applications" => "Received applications",

        "visual_status" => "Active opening",
        "visual_job_title" => "Junior Web Developer",
        "visual_company" => "TechNova El Salvador",
        "visual_mode" => "Hybrid",
        "visual_match" => "Profile match",
        "visual_apply" => "Apply now",
        "visual_available" => "Available openings",
        "visual_partners" => "Partner companies",
        "visual_company_card_title" => "Company control center",
        "visual_company_card_text" => "Publish accessible opportunities and manage applications from one place.",

        "search_label_top" => "Find your next challenge",
        "search_title" => "Find an opportunity made for you",
        "search_job_label" => "What job are you looking for?",
        "search_placeholder" => "Example: Designer, Developer, Sales",
        "category" => "Category",
        "all_categories" => "All categories",
        "work_arrangement" => "Work arrangement",
        "all_work_arrangements" => "All work arrangements",
        "find_jobs" => "Find jobs",

        "cat_technology" => "Technology",
        "cat_design" => "Design",
        "cat_sales" => "Sales",
        "cat_administration" => "Administration",
        "cat_customer_service" => "Customer Service",
        "cat_human_resources" => "Human Resources",
        "remote" => "Remote",
        "hybrid" => "Hybrid",
        "onsite" => "On-site",
        "full_time" => "Full-time",
        "part_time" => "Part-time",
        "internship" => "Internship",
        "temporary" => "Temporary",
        "freelance" => "Freelance",
        "inclusive" => "Accessible",
        "not_specified" => "Not specified",
        "general" => "General",
        "recently_posted" => "Recently posted",
        "posted" => "Posted",

        "stat_openings" => "Active openings",
        "stat_companies" => "Partner companies",
        "stat_applications_user" => "Applications sent",
        "stat_applications_company" => "Applications received",
        "stat_experience" => "Positive experience",

        "featured_label" => "Featured opportunities",
        "featured_title" => "Find a job that matches your skills",
        "featured_text" => "Explore verified openings from companies that value talent, accessibility, and professional growth.",
        "view_all_jobs" => "View all jobs",
        "showing_latest" => "Showing latest active openings from SkillBridge.",
        "view_details" => "View details",
        "no_openings_title" => "No openings were found.",
        "no_openings_db" => "Check the database connection and try again.",
        "no_openings_normal" => "Add active job openings from the company dashboard to show them here.",
        "fallback_description" => "Explore this accessible job opportunity and apply through SkillBridge.",

        "categories_label" => "Explore by field",
        "categories_title" => "An opportunity for every talent",
        "categories_text" => "Discover fields where you can apply your knowledge, skills, and experience.",
        "technology_text" => "Development, support, UX/UI design, and more.",
        "design_title" => "Design and Creativity",
        "design_text" => "Graphic design, content, and visual communication.",
        "customer_text" => "Support, call center, and customer experience.",
        "administration_text" => "Human resources, finance, and operations.",
        "openings" => "openings",

        "process_label_user" => "Simple, clear, and accessible",
        "process_title_user" => "Finding a job should not be complicated",
        "process_text_user" => "We designed a simple experience so you can focus on what matters: showcasing your talent and connecting with companies.",
        "create_free_profile" => "Create my free profile",
        "step1_user" => "Create your profile",
        "step1_user_text" => "Add your skills, experience, education, and professional interests.",
        "step2_user" => "Discover openings",
        "step2_user_text" => "Use smart filters to find jobs that match your profile.",
        "step3_user" => "Apply with confidence",
        "step3_user_text" => "Submit your application and track it from your personal dashboard.",

        "process_label_company" => "Company hiring process",
        "process_title_company" => "Posting a job should be clear and organized",
        "process_text_company" => "Create bilingual openings, explain requirements, and manage applications from your company dashboard.",
        "step1_company" => "Complete company data",
        "step1_company_text" => "Keep your company profile, contact information, and description updated.",
        "step2_company" => "Publish bilingual openings",
        "step2_company_text" => "Add the job information in English and Spanish so more people can understand it.",
        "step3_company" => "Review applications",
        "step3_company_text" => "Check candidate information and update the status of each application.",

        "inclusion_main" => "Designed for everyone",
        "inclusion_main_text" => "Tools that help everyone navigate comfortably.",
        "text_reader" => "Text reader",
        "inclusion_label" => "Jobs without barriers",
        "inclusion_title" => "Accessibility is not optional. It is part of our design.",
        "inclusion_text" => "SkillBridge includes accessibility tools to create a clear, adaptable, and comfortable experience for everyone.",
        "inclusion_1" => "Adjustable text size.",
        "inclusion_2" => "Dark mode and high contrast.",
        "inclusion_3" => "Voice content reading.",
        "inclusion_4" => "Simple navigation and clear buttons.",
        "explore_tools" => "Explore our tools",

        "stories_label" => "Stories that inspire",
        "stories_title" => "Talent changes lives when it finds an opportunity",
        "testimonial_1" => "SkillBridge helped me organize my professional profile and find an opening where my design skills were valued.",
        "testimonial_2" => "The platform is clear and easy to use. I was able to search for remote jobs and apply from my phone without complications.",
        "testimonial_3" => "As a company, we found qualified candidates and a platform that truly promotes more accessible hiring.",
        "graphic_designer" => "Graphic Designer",
        "technical_support" => "Technical Support",
        "human_resources" => "Human Resources",

        "cta_label_user" => "Your next opportunity starts today",
        "cta_title_user" => "Let your skills speak for you.",
        "cta_text_user" => "Create your free profile, explore opportunities, and take the next step in your professional journey.",
        "explore_jobs" => "Explore jobs",
        "create_account" => "Create account",
        "my_profile" => "My profile",
        "cta_label_company" => "Manage your opportunities",
        "cta_title_company" => "Keep building jobs without barriers.",
        "cta_text_company" => "Publish accessible openings, review applications, and keep your company profile updated.",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_newsletter" => "Newsletter",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and advice",
        "footer_find_talent" => "Find talent",
        "footer_contact" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_newsletter_text" => "Receive new job openings and professional advice.",
        "footer_email_address" => "Email address",
        "footer_email_placeholder" => "Your email address",
        "footer_subscribe" => "Subscribe",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved.",

        "modal_close" => "Close window",
        "modal_label" => "Featured opening",
        "modal_job_title" => "Job title",
        "modal_company" => "Company",
        "modal_location" => "Location",
        "modal_mode" => "Work arrangement",
        "modal_apply" => "Apply now",
        "modal_save" => "Save job"
    ],

    "es" => [
        "page_title" => "SkillBridge | Empleos sin barreras",
        "meta_description" => "SkillBridge conecta talento, empresas y oportunidades laborales accesibles en El Salvador.",

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

        "aria_logo" => "Inicio de SkillBridge",
        "logo_alt" => "Logo de SkillBridge",
        "aria_open_menu" => "Abrir menú de navegación",
        "aria_open_accessibility" => "Abrir herramientas de accesibilidad",
        "aria_close_accessibility" => "Cerrar herramientas de accesibilidad",
        "aria_switch_language" => "Cambiar a inglés",
        "skip_main" => "Saltar al contenido principal",

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
        "building_icon" => "Ícono de empresa",
        "briefcase_icon" => "Ícono de empleo",
        "location_icon" => "Ícono de ubicación",
        "home_work_icon" => "Ícono de modalidad de trabajo",
        "search_icon" => "Ícono de búsqueda",
        "category_icon" => "Ícono de categoría",
        "user_check_icon" => "Ícono de postulación",
        "heart_icon" => "Ícono de experiencia",
        "bookmark_icon" => "Ícono de guardar empleo",
        "clock_icon" => "Ícono de horario",
        "handshake_icon" => "Ícono de apretón de manos",
        "volume_icon" => "Ícono de lector de texto",
        "contrast_icon" => "Ícono de alto contraste",
        "check_icon" => "Ícono de verificación",
        "quote_icon" => "Ícono de cita",
        "paper_plane_icon" => "Ícono de enviar",
        "user_icon" => "Ícono de usuario",
        "user_plus_icon" => "Ícono de crear cuenta",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "hero_title_user" => "Tu talento abre puertas.",
        "hero_title_user_span" => "Nosotros construimos el puente.",
        "hero_text_user" => "SkillBridge conecta personas con oportunidades laborales reales, empresas comprometidas y recursos para crecer profesionalmente.",
        "hero_find_job" => "Buscar empleo",
        "hero_company" => "Soy una empresa",
        "hero_profile" => "Ir a mi perfil",
        "hero_trust_prefix" => "Más de",
        "hero_trust_strong" => "2,500 personas",
        "hero_trust_suffix" => "ya encontraron oportunidades.",

        "hero_title_company" => "Gestiona oportunidades laborales inclusivas.",
        "hero_title_company_span" => "Tu empresa puede abrir puertas.",
        "hero_text_company" => "Publica vacantes, revisa postulaciones y conecta con candidatos desde una plataforma accesible.",
        "company_dashboard" => "Panel de empresa",
        "received_applications" => "Postulaciones recibidas",

        "visual_status" => "Vacante activa",
        "visual_job_title" => "Desarrollador Web Junior",
        "visual_company" => "TechNova El Salvador",
        "visual_mode" => "Híbrido",
        "visual_match" => "Coincidencia del perfil",
        "visual_apply" => "Aplicar ahora",
        "visual_available" => "Vacantes disponibles",
        "visual_partners" => "Empresas aliadas",
        "visual_company_card_title" => "Centro de control empresarial",
        "visual_company_card_text" => "Publica oportunidades accesibles y gestiona postulaciones desde un solo lugar.",

        "search_label_top" => "Encuentra tu próximo reto",
        "search_title" => "Encuentra una oportunidad hecha para ti",
        "search_job_label" => "¿Qué empleo estás buscando?",
        "search_placeholder" => "Ejemplo: Diseñador, Desarrollador, Ventas",
        "category" => "Categoría",
        "all_categories" => "Todas las categorías",
        "work_arrangement" => "Modalidad de trabajo",
        "all_work_arrangements" => "Todas las modalidades",
        "find_jobs" => "Buscar empleos",

        "cat_technology" => "Tecnología",
        "cat_design" => "Diseño",
        "cat_sales" => "Ventas",
        "cat_administration" => "Administración",
        "cat_customer_service" => "Atención al cliente",
        "cat_human_resources" => "Recursos humanos",
        "remote" => "Remoto",
        "hybrid" => "Híbrido",
        "onsite" => "Presencial",
        "full_time" => "Tiempo completo",
        "part_time" => "Medio tiempo",
        "internship" => "Pasantía",
        "temporary" => "Temporal",
        "freelance" => "Freelance",
        "inclusive" => "Accesible",
        "not_specified" => "No especificado",
        "general" => "General",
        "recently_posted" => "Publicado recientemente",
        "posted" => "Publicado",

        "stat_openings" => "Vacantes activas",
        "stat_companies" => "Empresas aliadas",
        "stat_applications_user" => "Postulaciones enviadas",
        "stat_applications_company" => "Postulaciones recibidas",
        "stat_experience" => "Experiencia positiva",

        "featured_label" => "Oportunidades destacadas",
        "featured_title" => "Encuentra un empleo que combine con tus habilidades",
        "featured_text" => "Explora vacantes verificadas de empresas que valoran el talento, la accesibilidad y el crecimiento profesional.",
        "view_all_jobs" => "Ver todos los empleos",
        "showing_latest" => "Mostrando las últimas vacantes activas de SkillBridge.",
        "view_details" => "Ver detalles",
        "no_openings_title" => "No se encontraron vacantes.",
        "no_openings_db" => "Revisa la conexión con la base de datos e inténtalo nuevamente.",
        "no_openings_normal" => "Agrega vacantes activas desde el panel de empresa para mostrarlas aquí.",
        "fallback_description" => "Explora esta oportunidad laboral accesible y aplica desde SkillBridge.",

        "categories_label" => "Explora por área",
        "categories_title" => "Una oportunidad para cada talento",
        "categories_text" => "Descubre áreas donde puedes aplicar tus conocimientos, habilidades y experiencia.",
        "technology_text" => "Desarrollo, soporte, diseño UX/UI y más.",
        "design_title" => "Diseño y creatividad",
        "design_text" => "Diseño gráfico, contenido y comunicación visual.",
        "customer_text" => "Soporte, call center y experiencia al cliente.",
        "administration_text" => "Recursos humanos, finanzas y operaciones.",
        "openings" => "vacantes",

        "process_label_user" => "Simple, claro y accesible",
        "process_title_user" => "Encontrar empleo no debería ser complicado",
        "process_text_user" => "Diseñamos una experiencia sencilla para que puedas enfocarte en lo importante: mostrar tu talento y conectar con empresas.",
        "create_free_profile" => "Crear mi perfil gratis",
        "step1_user" => "Crea tu perfil",
        "step1_user_text" => "Agrega tus habilidades, experiencia, educación e intereses profesionales.",
        "step2_user" => "Descubre vacantes",
        "step2_user_text" => "Usa filtros inteligentes para encontrar empleos que coincidan con tu perfil.",
        "step3_user" => "Aplica con confianza",
        "step3_user_text" => "Envía tu postulación y dale seguimiento desde tu panel personal.",

        "process_label_company" => "Proceso de contratación empresarial",
        "process_title_company" => "Publicar una vacante debe ser claro y ordenado",
        "process_text_company" => "Crea vacantes bilingües, explica los requisitos y gestiona postulaciones desde tu panel de empresa.",
        "step1_company" => "Completa los datos de empresa",
        "step1_company_text" => "Mantén actualizado el perfil, contacto y descripción de tu empresa.",
        "step2_company" => "Publica vacantes bilingües",
        "step2_company_text" => "Agrega la información del empleo en inglés y español para que más personas la comprendan.",
        "step3_company" => "Revisa postulaciones",
        "step3_company_text" => "Consulta la información de candidatos y actualiza el estado de cada postulación.",

        "inclusion_main" => "Diseñado para todos",
        "inclusion_main_text" => "Herramientas que ayudan a navegar cómodamente.",
        "text_reader" => "Lector de texto",
        "inclusion_label" => "Empleos sin barreras",
        "inclusion_title" => "La accesibilidad no es opcional. Es parte de nuestro diseño.",
        "inclusion_text" => "SkillBridge incluye herramientas de accesibilidad para crear una experiencia clara, adaptable y cómoda para todos.",
        "inclusion_1" => "Tamaño de texto ajustable.",
        "inclusion_2" => "Modo oscuro y alto contraste.",
        "inclusion_3" => "Lectura de contenido por voz.",
        "inclusion_4" => "Navegación simple y botones claros.",
        "explore_tools" => "Explorar herramientas",

        "stories_label" => "Historias que inspiran",
        "stories_title" => "El talento cambia vidas cuando encuentra una oportunidad",
        "testimonial_1" => "SkillBridge me ayudó a organizar mi perfil profesional y encontrar una vacante donde valoraron mis habilidades de diseño.",
        "testimonial_2" => "La plataforma es clara y fácil de usar. Pude buscar empleos remotos y aplicar desde mi teléfono sin complicaciones.",
        "testimonial_3" => "Como empresa, encontramos candidatos calificados y una plataforma que realmente promueve una contratación más accesible.",
        "graphic_designer" => "Diseñadora gráfica",
        "technical_support" => "Soporte técnico",
        "human_resources" => "Recursos humanos",

        "cta_label_user" => "Tu próxima oportunidad empieza hoy",
        "cta_title_user" => "Deja que tus habilidades hablen por ti.",
        "cta_text_user" => "Crea tu perfil gratis, explora oportunidades y da el siguiente paso en tu camino profesional.",
        "explore_jobs" => "Explorar empleos",
        "create_account" => "Crear cuenta",
        "my_profile" => "Mi perfil",
        "cta_label_company" => "Gestiona tus oportunidades",
        "cta_title_company" => "Sigue construyendo empleos sin barreras.",
        "cta_text_company" => "Publica vacantes accesibles, revisa postulaciones y mantén actualizado el perfil de tu empresa.",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_newsletter" => "Boletín",
        "footer_create_profile" => "Crear perfil",
        "footer_resources" => "Recursos y consejos",
        "footer_find_talent" => "Encontrar talento",
        "footer_contact" => "Contactar al equipo",
        "footer_about" => "Quiénes somos",
        "footer_accessibility" => "Accesibilidad",
        "footer_privacy" => "Privacidad",
        "footer_terms" => "Términos y condiciones",
        "footer_newsletter_text" => "Recibe nuevas vacantes y consejos profesionales.",
        "footer_email_address" => "Correo electrónico",
        "footer_email_placeholder" => "Tu correo electrónico",
        "footer_subscribe" => "Suscribirse",
        "footer_rights" => "© 2026 SkillBridge. Todos los derechos reservados.",

        "modal_close" => "Cerrar ventana",
        "modal_label" => "Vacante destacada",
        "modal_job_title" => "Título del empleo",
        "modal_company" => "Empresa",
        "modal_location" => "Ubicación",
        "modal_mode" => "Modalidad de trabajo",
        "modal_apply" => "Aplicar ahora",
        "modal_save" => "Guardar empleo"
    ]
];

function t($key) {
    global $translations, $idiomaActual;

    return $translations[$idiomaActual][$key]
        ?? $translations["en"][$key]
        ?? $key;
}

function icono($clases, $labelKey) {
    echo '<i class="' . limpiar($clases) . '" role="img" aria-label="' . limpiar(t($labelKey)) . '" title="' . limpiar(t($labelKey)) . '"></i>';
}

function enlaceIdioma($pagina, $parametros = []) {
    global $idiomaActual;

    $parametros["lang"] = $idiomaActual;

    return $pagina . "?" . http_build_query($parametros);
}

function urlCambiarIdioma($idioma) {
    $parametros = $_GET;
    $parametros["lang"] = $idioma;

    return "index.php?" . http_build_query($parametros);
}

function campoIdioma($fila, $campoBase) {
    global $idiomaActual;

    $campoPreferido = $campoBase . "_" . $idiomaActual;
    $campoAlternativo = $campoBase . "_" . ($idiomaActual === "es" ? "en" : "es");

    if (!empty($fila[$campoPreferido])) {
        return $fila[$campoPreferido];
    }

    if (!empty($fila[$campoAlternativo])) {
        return $fila[$campoAlternativo];
    }

    return $fila[$campoBase] ?? "";
}

function etiquetaCategoria($categoria) {
    $categoriaNormalizada = normalizarCategoria($categoria);

    $mapa = [
        "technology" => t("cat_technology"),
        "design" => t("cat_design"),
        "sales" => t("cat_sales"),
        "administration" => t("cat_administration"),
        "customer service" => t("cat_customer_service"),
        "human resources" => t("cat_human_resources")
    ];

    return $mapa[$categoriaNormalizada] ?? ($categoria !== "" ? $categoria : t("general"));
}

function etiquetaModalidad($modalidad) {
    $modalidadNormalizada = normalizarModalidad($modalidad);

    $mapa = [
        "remote" => t("remote"),
        "hybrid" => t("hybrid"),
        "on-site" => t("onsite")
    ];

    return $mapa[$modalidadNormalizada] ?? ($modalidad !== "" ? $modalidad : t("not_specified"));
}

function etiquetaTipoEmpleo($tipoEmpleo) {
    $tipoNormalizado = normalizarTipoEmpleo($tipoEmpleo);

    $mapa = [
        "full-time" => t("full_time"),
        "part-time" => t("part_time"),
        "internship" => t("internship"),
        "temporary" => t("temporary"),
        "freelance" => t("freelance")
    ];

    return $mapa[$tipoNormalizado] ?? ($tipoEmpleo !== "" ? $tipoEmpleo : t("not_specified"));
}

function fechaPublicacionTexto($fecha) {
    global $idiomaActual;

    if (empty($fecha)) {
        return t("recently_posted");
    }

    $timestamp = strtotime($fecha);

    if (!$timestamp) {
        return t("recently_posted");
    }

    if ($idiomaActual === "es") {
        return t("posted") . " " . date("d/m/Y", $timestamp);
    }

    return t("posted") . " " . date("M d, Y", $timestamp);
}

function descripcionCorta($texto, $limite = 150) {
    $texto = trim(strip_tags($texto ?? ""));

    if ($texto === "") {
        return t("fallback_description");
    }

    if (function_exists("mb_strlen") && function_exists("mb_substr")) {
        if (mb_strlen($texto, "UTF-8") <= $limite) {
            return $texto;
        }

        return mb_substr($texto, 0, $limite, "UTF-8") . "...";
    }

    if (strlen($texto) <= $limite) {
        return $texto;
    }

    return substr($texto, 0, $limite) . "...";
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;
$puedeBuscarEmpleo = !$usuarioLogueado || $esCandidato || $esAdmin;

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = $usuarioActual && !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = $usuarioActual && !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = $usuarioActual && !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = $usuarioActual && isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$totalVacantesActivas = 0;
$totalEmpresas = 0;
$totalPostulaciones = 0;
$totalPostulacionesEmpresa = 0;
$porcentajeExperiencia = 98;
$empleosDestacados = [];
$errorHomeDatos = false;

try {
    $totalVacantesActivas = (int)$pdo->query("SELECT COUNT(*) FROM vacantes WHERE estado = 'activa'")->fetchColumn();
    $totalEmpresas = (int)$pdo->query("SELECT COUNT(*) FROM empresas WHERE estado = 1")->fetchColumn();
    $totalPostulaciones = (int)$pdo->query("SELECT COUNT(*) FROM postulaciones")->fetchColumn();

    if ($esEmpresa && $usuarioActual) {
        $stmtTotalEmpresa = $pdo->prepare("
            SELECT COUNT(*)
            FROM postulaciones
            INNER JOIN vacantes
                ON postulaciones.id_vacante = vacantes.id_vacante
            INNER JOIN empresas
                ON vacantes.id_empresa = empresas.id_empresa
            WHERE empresas.nombre = :nombre_empresa
        ");

        $stmtTotalEmpresa->execute([
            ":nombre_empresa" => $usuarioActual["nombre"] ?? ""
        ]);

        $totalPostulacionesEmpresa = (int)$stmtTotalEmpresa->fetchColumn();
    }

    $sqlDestacados = "
        SELECT
            vacantes.id_vacante,
            vacantes.titulo,
            vacantes.titulo_en,
            vacantes.titulo_es,
            vacantes.categoria,
            vacantes.modalidad,
            vacantes.ubicacion,
            vacantes.tipo_empleo,
            vacantes.salario,
            vacantes.descripcion,
            vacantes.descripcion_en,
            vacantes.descripcion_es,
            vacantes.fecha_publicacion,
            empresas.nombre AS empresa_nombre
        FROM vacantes
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        WHERE vacantes.estado = 'activa'
        ORDER BY vacantes.fecha_publicacion DESC
        LIMIT 3
    ";

    $stmtDestacados = $pdo->query($sqlDestacados);
    $empleosDestacados = $stmtDestacados->fetchAll();
} catch (PDOException $e) {
    $errorHomeDatos = true;
}

$categorias = [
    ["value" => "", "label" => t("all_categories")],
    ["value" => "Technology", "label" => t("cat_technology")],
    ["value" => "Design", "label" => t("cat_design")],
    ["value" => "Sales", "label" => t("cat_sales")],
    ["value" => "Administration", "label" => t("cat_administration")],
    ["value" => "Customer Service", "label" => t("cat_customer_service")],
    ["value" => "Human Resources", "label" => t("cat_human_resources")]
];

$modalidades = [
    ["value" => "", "label" => t("all_work_arrangements")],
    ["value" => "Remote", "label" => t("remote")],
    ["value" => "Hybrid", "label" => t("hybrid")],
    ["value" => "On-site", "label" => t("onsite")]
];

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
</head>

<body class="<?php echo limpiar($bodyClassText); ?>">

    <a href="#mainContent" class="skip-link">
        <?php echo limpiar(t("skip_main")); ?>
    </a>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <button class="accessibility-button" id="accessibilityButton"
        aria-label="<?php echo limpiar(t("aria_open_accessibility")); ?>"
        title="<?php echo limpiar(t("aria_open_accessibility")); ?>">
        <?php icono("fa-solid fa-universal-access", "accessibility_icon"); ?>
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
                <?php icono("fa-solid fa-xmark", "close_icon"); ?>
            </button>
        </div>

        <p class="accessibility-text">
            <?php echo limpiar(t("accessibility_text")); ?>
        </p>

        <div class="accessibility-options">
            <button class="accessibility-option" id="increaseFont"
                aria-label="<?php echo limpiar(t("increase_text")); ?>">
                <?php icono("fa-solid fa-magnifying-glass-plus", "increase_text"); ?>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont"
                aria-label="<?php echo limpiar(t("decrease_text")); ?>">
                <?php icono("fa-solid fa-magnifying-glass-minus", "decrease_text"); ?>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode"
                aria-label="<?php echo limpiar(t("dark_mode")); ?>">
                <?php icono("fa-solid fa-moon", "dark_mode"); ?>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast"
                aria-label="<?php echo limpiar(t("high_contrast")); ?>">
                <?php icono("fa-solid fa-circle-half-stroke", "high_contrast"); ?>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage"
                aria-label="<?php echo limpiar(t("read_mode")); ?>">
                <?php icono("fa-solid fa-volume-high", "read_mode"); ?>
                <span><?php echo limpiar(t("read_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading"
                aria-label="<?php echo limpiar(t("stop_reading")); ?>">
                <?php icono("fa-solid fa-volume-xmark", "stop_reading"); ?>
                <span><?php echo limpiar(t("stop_reading")); ?></span>
            </button>
        </div>
    </aside>

    <header class="header">
        <nav class="navbar container">

            <a href="<?php echo limpiar(enlaceIdioma("index.php")); ?>" class="logo"
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
                <?php icono("fa-solid fa-bars", "menu_icon"); ?>
            </button>

            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="<?php echo limpiar(enlaceIdioma("index.php")); ?>"<?php echo enlaceActivo("index.php"); ?>>
                        <?php echo limpiar(t("nav_home")); ?>
                    </a>
                </li>

                <?php if (!$esEmpresa): ?>
                    <li>
                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>"<?php echo enlaceActivo("empleos.php"); ?>>
                            <?php echo limpiar(t("nav_find_jobs")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="<?php echo limpiar(enlaceIdioma("empresas.php")); ?>"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>

                <?php if ($usuarioLogueado && ($esCandidato || $esAdmin)): ?>
                    <li>
                        <a href="<?php echo limpiar(enlaceIdioma("postulaciones.php")); ?>"<?php echo enlaceActivo("postulaciones.php"); ?>>
                            <?php echo limpiar(t("nav_my_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado && $esEmpresaOAdmin): ?>
                    <li>
                        <a href="<?php echo limpiar(enlaceIdioma("publicarvacante.php")); ?>"<?php echo enlaceActivo("publicarvacante.php"); ?>>
                            <?php echo limpiar(t("nav_post_job")); ?>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo limpiar(enlaceIdioma("postulaciones-empresa.php")); ?>"<?php echo enlaceActivo("postulaciones-empresa.php"); ?>>
                            <?php echo limpiar(t("nav_received_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($usuarioLogueado): ?>
                    <li>
                        <a href="<?php echo limpiar(enlaceIdioma("perfil.php")); ?>"<?php echo enlaceActivo("perfil.php"); ?>>
                            <?php echo limpiar(t("nav_profile")); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if ($usuarioLogueado): ?>
                    <a href="<?php echo limpiar(enlaceIdioma("perfil.php")); ?>" class="login-link">
                        <?php echo limpiar(t("nav_my_profile")); ?>
                    </a>

                    <a href="logout.php" class="button button-primary button-small">
                        <?php echo limpiar(t("nav_logout")); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo limpiar(enlaceIdioma("login.php")); ?>" class="login-link">
                        <?php echo limpiar(t("nav_login")); ?>
                    </a>

                    <a href="<?php echo limpiar(enlaceIdioma("registro.php")); ?>" class="button button-primary button-small">
                        <?php echo limpiar(t("nav_create_account")); ?>
                    </a>
                <?php endif; ?>

                <a href="<?php echo limpiar(urlCambiarIdioma($idiomaSiguiente)); ?>"
                    class="language-toggle button button-secondary button-small"
                    aria-label="<?php echo limpiar(t("aria_switch_language")); ?>"
                    title="<?php echo limpiar(t("aria_switch_language")); ?>">
                    <?php icono("fa-solid fa-language", "language_icon"); ?>
                    <?php echo limpiar($etiquetaIdiomaSiguiente); ?>
                </a>
            </div>

        </nav>
    </header>

    <main id="mainContent">

        <section class="hero-section">

            <div class="hero-decoration decoration-one"></div>
            <div class="hero-decoration decoration-two"></div>
            <div class="hero-decoration decoration-three"></div>

            <div class="container hero-grid">

                <div class="hero-content">
                    <h1>
                        <?php echo limpiar($esEmpresa ? t("hero_title_company") : t("hero_title_user")); ?>
                        <span><?php echo limpiar($esEmpresa ? t("hero_title_company_span") : t("hero_title_user_span")); ?></span>
                    </h1>

                    <p class="hero-description">
                        <?php echo limpiar($esEmpresa ? t("hero_text_company") : t("hero_text_user")); ?>
                    </p>

                    <div class="hero-buttons">
                        <?php if ($esEmpresa): ?>
                            <a href="<?php echo limpiar(enlaceIdioma("publicarvacante.php")); ?>" class="button button-primary">
                                <?php echo limpiar(t("nav_post_job")); ?>
                                <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                            </a>

                            <a href="<?php echo limpiar(enlaceIdioma("postulaciones-empresa.php")); ?>" class="button button-secondary">
                                <?php echo limpiar(t("received_applications")); ?>
                                <?php icono("fa-solid fa-building", "building_icon"); ?>
                            </a>
                        <?php else: ?>
                            <a href="#buscar-empleo" class="button button-primary">
                                <?php echo limpiar(t("hero_find_job")); ?>
                                <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                            </a>

                            <a href="<?php echo limpiar(enlaceIdioma("empresas.php")); ?>" class="button button-secondary">
                                <?php echo limpiar(t("hero_company")); ?>
                                <?php icono("fa-solid fa-building", "building_icon"); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!$esEmpresa): ?>
                        <div class="hero-trust">
                            <div class="trust-avatars" aria-label="SkillBridge community examples">
                                <div class="avatar avatar-one">M</div>
                                <div class="avatar avatar-two">J</div>
                                <div class="avatar avatar-three">A</div>
                                <div class="avatar avatar-four">+</div>
                            </div>

                            <p>
                                <?php echo limpiar(t("hero_trust_prefix")); ?>
                                <strong><?php echo limpiar(t("hero_trust_strong")); ?></strong>
                                <?php echo limpiar(t("hero_trust_suffix")); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="hero-visual">

                    <div class="hero-card main-card">
                        <?php if ($esEmpresa): ?>
                            <div class="card-header-row">
                                <div class="company-logo company-purple">
                                    <?php icono("fa-solid fa-building", "building_icon"); ?>
                                </div>

                                <span class="status-pill">
                                    <span></span>
                                    <?php echo limpiar(t("company_dashboard")); ?>
                                </span>
                            </div>

                            <h3><?php echo limpiar(t("visual_company_card_title")); ?></h3>
                            <p class="company-name"><?php echo limpiar(t("visual_company_card_text")); ?></p>

                            <div class="job-tags">
                                <span><?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?> <?php echo limpiar($totalVacantesActivas . " " . t("stat_openings")); ?></span>
                                <span><?php icono("fa-solid fa-user-check", "user_check_icon"); ?> <?php echo limpiar($totalPostulacionesEmpresa . " " . t("received_applications")); ?></span>
                            </div>

                            <a href="<?php echo limpiar(enlaceIdioma("publicarvacante.php")); ?>" class="button button-primary mini-apply-button">
                                <?php echo limpiar(t("nav_post_job")); ?>
                            </a>
                        <?php else: ?>
                            <div class="card-header-row">
                                <div class="company-logo company-purple">
                                    <?php icono("fa-solid fa-laptop-code", "briefcase_icon"); ?>
                                </div>

                                <span class="status-pill">
                                    <span></span>
                                    <?php echo limpiar(t("visual_status")); ?>
                                </span>
                            </div>

                            <h3><?php echo limpiar(t("visual_job_title")); ?></h3>
                            <p class="company-name"><?php echo limpiar(t("visual_company")); ?></p>

                            <div class="job-tags">
                                <span><?php icono("fa-solid fa-location-dot", "location_icon"); ?> San Salvador</span>
                                <span><?php icono("fa-solid fa-house-laptop", "home_work_icon"); ?> <?php echo limpiar(t("visual_mode")); ?></span>
                            </div>

                            <div class="match-box">
                                <div class="match-row">
                                    <span><?php echo limpiar(t("visual_match")); ?></span>
                                    <strong>92%</strong>
                                </div>

                                <div class="progress-bar">
                                    <div class="progress-value"></div>
                                </div>
                            </div>

                            <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>" class="button button-primary mini-apply-button">
                                <?php echo limpiar(t("visual_apply")); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="hero-card small-card small-card-top">
                        <div class="small-icon blue-icon">
                            <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                        </div>

                        <div>
                            <strong><?php echo (int)$totalVacantesActivas; ?></strong>
                            <span><?php echo limpiar(t("visual_available")); ?></span>
                        </div>
                    </div>

                    <div class="hero-card small-card small-card-bottom">
                        <div class="small-icon green-icon">
                            <?php icono("fa-solid fa-handshake", "handshake_icon"); ?>
                        </div>

                        <div>
                            <strong><?php echo (int)$totalEmpresas; ?></strong>
                            <span><?php echo limpiar(t("visual_partners")); ?></span>
                        </div>
                    </div>

                    <div class="visual-circle circle-one"></div>
                    <div class="visual-circle circle-two"></div>

                </div>

            </div>

        </section>

        <?php if ($puedeBuscarEmpleo): ?>
            <section class="search-section" id="buscar-empleo">
                <div class="container">

                    <div class="search-box">
                        <div class="search-header">
                            <div>
                                <span class="section-label"><?php echo limpiar(t("search_label_top")); ?></span>
                                <h2><?php echo limpiar(t("search_title")); ?></h2>
                            </div>

                            <div class="search-icon-box">
                                <?php icono("fa-solid fa-magnifying-glass", "search_icon"); ?>
                            </div>
                        </div>

                        <form class="job-search-form" id="jobSearchForm" action="empleos.php" method="GET">
                            <input type="hidden" name="lang" value="<?php echo limpiar($idiomaActual); ?>">

                            <div class="form-group search-input-group">
                                <label for="jobSearch"><?php echo limpiar(t("search_job_label")); ?></label>

                                <div class="input-with-icon">
                                    <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                                    <input type="text" id="jobSearch" name="search"
                                        placeholder="<?php echo limpiar(t("search_placeholder")); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jobCategory"><?php echo limpiar(t("category")); ?></label>

                                <div class="input-with-icon">
                                    <?php icono("fa-solid fa-layer-group", "category_icon"); ?>

                                    <select id="jobCategory" name="category">
                                        <?php foreach ($categorias as $categoriaItem): ?>
                                            <option value="<?php echo limpiar($categoriaItem["value"]); ?>">
                                                <?php echo limpiar($categoriaItem["label"]); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jobMode"><?php echo limpiar(t("work_arrangement")); ?></label>

                                <div class="input-with-icon">
                                    <?php icono("fa-solid fa-location-dot", "location_icon"); ?>

                                    <select id="jobMode" name="mode">
                                        <?php foreach ($modalidades as $modalidadItem): ?>
                                            <option value="<?php echo limpiar($modalidadItem["value"]); ?>">
                                                <?php echo limpiar($modalidadItem["label"]); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="button button-primary search-button">
                                <?php echo limpiar(t("find_jobs")); ?>
                                <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                            </button>
                        </form>

                    </div>

                </div>
            </section>
        <?php endif; ?>

        <section class="stats-section">
            <div class="container stats-grid">

                <article class="stat-card">
                    <div class="stat-icon stat-icon-blue">
                        <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                    </div>

                    <div>
                        <strong class="counter" data-target="<?php echo (int)$totalVacantesActivas; ?>">0</strong>
                        <span><?php echo limpiar(t("stat_openings")); ?></span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <?php icono("fa-solid fa-building", "building_icon"); ?>
                    </div>

                    <div>
                        <strong class="counter" data-target="<?php echo (int)$totalEmpresas; ?>">0</strong>
                        <span><?php echo limpiar(t("stat_companies")); ?></span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <?php icono("fa-solid fa-user-check", "user_check_icon"); ?>
                    </div>

                    <div>
                        <strong class="counter" data-target="<?php echo (int)($esEmpresa ? $totalPostulacionesEmpresa : $totalPostulaciones); ?>">0</strong>
                        <span><?php echo limpiar($esEmpresa ? t("stat_applications_company") : t("stat_applications_user")); ?></span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-orange">
                        <?php icono("fa-solid fa-heart", "heart_icon"); ?>
                    </div>

                    <div>
                        <strong class="counter" data-target="<?php echo (int)$porcentajeExperiencia; ?>">0</strong>
                        <span><?php echo limpiar(t("stat_experience")); ?></span>
                    </div>
                </article>

            </div>
        </section>

        <?php if ($puedeBuscarEmpleo): ?>
            <section class="featured-jobs-section" id="empleos-destacados">
                <div class="container">

                    <div class="section-heading">
                        <div>
                            <span class="section-label"><?php echo limpiar(t("featured_label")); ?></span>
                            <h2><?php echo limpiar(t("featured_title")); ?></h2>
                            <p><?php echo limpiar(t("featured_text")); ?></p>
                        </div>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>" class="text-link">
                            <?php echo limpiar(t("view_all_jobs")); ?>
                            <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                        </a>
                    </div>

                    <p class="results-indicator" id="resultsIndicator">
                        <?php echo limpiar(t("showing_latest")); ?>
                    </p>

                    <div class="jobs-grid" id="jobsGrid">

                        <?php if (!empty($empleosDestacados)): ?>
                            <?php foreach ($empleosDestacados as $empleo): ?>
                                <?php
                                    $tituloEmpleo = campoIdioma($empleo, "titulo");
                                    $empresaEmpleo = $empleo["empresa_nombre"] ?? "Company";
                                    $ubicacionEmpleo = $empleo["ubicacion"] ?? t("not_specified");
                                    $modalidadEmpleo = $empleo["modalidad"] ?? "";
                                    $categoriaEmpleo = $empleo["categoria"] ?? "";
                                    $tipoEmpleoValor = $empleo["tipo_empleo"] ?? "";
                                    $descripcionEmpleo = descripcionCorta(campoIdioma($empleo, "descripcion"));
                                    $urlDetalle = enlaceIdioma("detalle-empleo.php", ["id" => (int)$empleo["id_vacante"]]);
                                    $modalidadEtiqueta = etiquetaModalidad($modalidadEmpleo);
                                    $categoriaEtiqueta = etiquetaCategoria($categoriaEmpleo);
                                    $tipoEmpleoEtiqueta = etiquetaTipoEmpleo($tipoEmpleoValor);
                                    $dataTitle = normalizarTextoBusqueda($tituloEmpleo . " " . $empresaEmpleo . " " . $categoriaEmpleo . " " . $modalidadEmpleo . " " . $ubicacionEmpleo);
                                ?>

                                <article class="job-card"
                                    data-title="<?php echo limpiar($dataTitle); ?>"
                                    data-category="<?php echo limpiar(categoriaSlug($categoriaEmpleo)); ?>"
                                    data-mode="<?php echo limpiar(modalidadSlug($modalidadEmpleo)); ?>">

                                    <div class="job-card-top">
                                        <div class="company-logo company-blue">
                                            <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                                        </div>

                                        <button class="save-job-button" aria-label="<?php echo limpiar(t("bookmark_icon")); ?>"
                                            data-job="<?php echo limpiar($tituloEmpleo); ?>">
                                            <?php icono("fa-regular fa-bookmark", "bookmark_icon"); ?>
                                        </button>
                                    </div>

                                    <span class="job-type <?php echo limpiar(modalidadClase($modalidadEmpleo)); ?>">
                                        <?php echo limpiar($modalidadEtiqueta); ?>
                                    </span>

                                    <h3><?php echo limpiar($tituloEmpleo !== "" ? $tituloEmpleo : t("visual_job_title")); ?></h3>
                                    <p class="job-company"><?php echo limpiar($empresaEmpleo); ?></p>

                                    <div class="job-details">
                                        <span><?php icono("fa-solid fa-location-dot", "location_icon"); ?> <?php echo limpiar($ubicacionEmpleo); ?></span>
                                        <span><?php icono("fa-solid fa-clock", "clock_icon"); ?> <?php echo limpiar($tipoEmpleoEtiqueta); ?></span>
                                    </div>

                                    <div class="job-skills">
                                        <span><?php echo limpiar($categoriaEtiqueta); ?></span>
                                        <span><?php echo limpiar($modalidadEtiqueta); ?></span>
                                        <span><?php echo limpiar(t("inclusive")); ?></span>
                                    </div>

                                    <div class="job-card-footer">
                                        <span class="job-date">
                                            <?php echo limpiar(fechaPublicacionTexto($empleo["fecha_publicacion"] ?? "")); ?>
                                        </span>

                                        <a href="<?php echo limpiar($urlDetalle); ?>" class="details-button"
                                            data-title="<?php echo limpiar($tituloEmpleo); ?>"
                                            data-company="<?php echo limpiar($empresaEmpleo); ?>"
                                            data-location="<?php echo limpiar($ubicacionEmpleo); ?>"
                                            data-mode="<?php echo limpiar($modalidadEtiqueta); ?>"
                                            data-description="<?php echo limpiar($descripcionEmpleo); ?>"
                                            data-url="<?php echo limpiar($urlDetalle); ?>">
                                            <?php echo limpiar(t("view_details")); ?>
                                        </a>
                                    </div>

                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <div class="no-results <?php echo empty($empleosDestacados) ? "" : "hidden"; ?>" id="noResults">
                        <?php icono("fa-solid fa-magnifying-glass", "search_icon"); ?>
                        <h3><?php echo limpiar(t("no_openings_title")); ?></h3>
                        <p>
                            <?php echo limpiar($errorHomeDatos ? t("no_openings_db") : t("no_openings_normal")); ?>
                        </p>
                    </div>

                </div>
            </section>

            <section class="categories-section">
                <div class="container">

                    <div class="section-heading centered-heading">
                        <div>
                            <span class="section-label"><?php echo limpiar(t("categories_label")); ?></span>
                            <h2><?php echo limpiar(t("categories_title")); ?></h2>
                            <p><?php echo limpiar(t("categories_text")); ?></p>
                        </div>
                    </div>

                    <div class="categories-grid">

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php", ["category" => "Technology"])); ?>" class="category-card">
                            <div class="category-icon category-blue">
                                <?php icono("fa-solid fa-laptop-code", "briefcase_icon"); ?>
                            </div>

                            <h3><?php echo limpiar(t("cat_technology")); ?></h3>
                            <p><?php echo limpiar(t("technology_text")); ?></p>
                            <span>245 <?php echo limpiar(t("openings")); ?> <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?></span>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php", ["category" => "Design"])); ?>" class="category-card">
                            <div class="category-icon category-purple">
                                <?php icono("fa-solid fa-pen-ruler", "category_icon"); ?>
                            </div>

                            <h3><?php echo limpiar(t("design_title")); ?></h3>
                            <p><?php echo limpiar(t("design_text")); ?></p>
                            <span>138 <?php echo limpiar(t("openings")); ?> <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?></span>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php", ["category" => "Customer Service"])); ?>" class="category-card">
                            <div class="category-icon category-green">
                                <?php icono("fa-solid fa-headset", "user_check_icon"); ?>
                            </div>

                            <h3><?php echo limpiar(t("cat_customer_service")); ?></h3>
                            <p><?php echo limpiar(t("customer_text")); ?></p>
                            <span>190 <?php echo limpiar(t("openings")); ?> <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?></span>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php", ["category" => "Administration"])); ?>" class="category-card">
                            <div class="category-icon category-orange">
                                <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                            </div>

                            <h3><?php echo limpiar(t("cat_administration")); ?></h3>
                            <p><?php echo limpiar(t("administration_text")); ?></p>
                            <span>167 <?php echo limpiar(t("openings")); ?> <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?></span>
                        </a>

                    </div>

                </div>
            </section>
        <?php endif; ?>

        <section class="process-section">
            <div class="container process-grid">

                <div class="process-content">
                    <span class="section-label">
                        <?php echo limpiar($esEmpresa ? t("process_label_company") : t("process_label_user")); ?>
                    </span>

                    <h2><?php echo limpiar($esEmpresa ? t("process_title_company") : t("process_title_user")); ?></h2>

                    <p>
                        <?php echo limpiar($esEmpresa ? t("process_text_company") : t("process_text_user")); ?>
                    </p>

                    <?php if ($esEmpresa): ?>
                        <a href="<?php echo limpiar(enlaceIdioma("publicarvacante.php")); ?>" class="button button-primary">
                            <?php echo limpiar(t("nav_post_job")); ?>
                            <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                        </a>
                    <?php elseif ($usuarioLogueado): ?>
                        <a href="<?php echo limpiar(enlaceIdioma("perfil.php")); ?>" class="button button-primary">
                            <?php echo limpiar(t("hero_profile")); ?>
                            <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo limpiar(enlaceIdioma("registro.php")); ?>" class="button button-primary">
                            <?php echo limpiar(t("create_free_profile")); ?>
                            <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="process-steps">

                    <article class="process-step">
                        <div class="step-number">01</div>

                        <div>
                            <h3><?php echo limpiar($esEmpresa ? t("step1_company") : t("step1_user")); ?></h3>
                            <p><?php echo limpiar($esEmpresa ? t("step1_company_text") : t("step1_user_text")); ?></p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">02</div>

                        <div>
                            <h3><?php echo limpiar($esEmpresa ? t("step2_company") : t("step2_user")); ?></h3>
                            <p><?php echo limpiar($esEmpresa ? t("step2_company_text") : t("step2_user_text")); ?></p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">03</div>

                        <div>
                            <h3><?php echo limpiar($esEmpresa ? t("step3_company") : t("step3_user")); ?></h3>
                            <p><?php echo limpiar($esEmpresa ? t("step3_company_text") : t("step3_user_text")); ?></p>
                        </div>
                    </article>

                </div>

            </div>
        </section>

        <section class="inclusion-section">
            <div class="container inclusion-grid">

                <div class="inclusion-visual">
                    <div class="inclusion-card inclusion-main-card">
                        <div class="inclusion-icon-main">
                            <?php icono("fa-solid fa-universal-access", "accessibility_icon"); ?>
                        </div>

                        <h3><?php echo limpiar(t("inclusion_main")); ?></h3>
                        <p><?php echo limpiar(t("inclusion_main_text")); ?></p>
                    </div>

                    <div class="inclusion-mini-card mini-card-left">
                        <?php icono("fa-solid fa-volume-high", "volume_icon"); ?>
                        <span><?php echo limpiar(t("text_reader")); ?></span>
                    </div>

                    <div class="inclusion-mini-card mini-card-right">
                        <?php icono("fa-solid fa-circle-half-stroke", "contrast_icon"); ?>
                        <span><?php echo limpiar(t("high_contrast")); ?></span>
                    </div>
                </div>

                <div class="inclusion-content">
                    <span class="section-label"><?php echo limpiar(t("inclusion_label")); ?></span>

                    <h2><?php echo limpiar(t("inclusion_title")); ?></h2>

                    <p><?php echo limpiar(t("inclusion_text")); ?></p>

                    <ul class="inclusion-list">
                        <li><?php icono("fa-solid fa-check", "check_icon"); ?> <?php echo limpiar(t("inclusion_1")); ?></li>
                        <li><?php icono("fa-solid fa-check", "check_icon"); ?> <?php echo limpiar(t("inclusion_2")); ?></li>
                        <li><?php icono("fa-solid fa-check", "check_icon"); ?> <?php echo limpiar(t("inclusion_3")); ?></li>
                        <li><?php icono("fa-solid fa-check", "check_icon"); ?> <?php echo limpiar(t("inclusion_4")); ?></li>
                    </ul>

                    <button type="button" class="text-link" id="openAccessibilityFromSection">
                        <?php echo limpiar(t("explore_tools")); ?>
                        <?php icono("fa-solid fa-arrow-right", "arrow_icon"); ?>
                    </button>
                </div>

            </div>
        </section>

        <section class="testimonials-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("stories_label")); ?></span>
                        <h2><?php echo limpiar(t("stories_title")); ?></h2>
                    </div>
                </div>

                <div class="testimonials-grid">

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <?php icono("fa-solid fa-quote-left", "quote_icon"); ?>
                        </div>

                        <p>“<?php echo limpiar(t("testimonial_1")); ?>”</p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-one">M</div>

                            <div>
                                <h4>María Hernández</h4>
                                <span><?php echo limpiar(t("graphic_designer")); ?></span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <?php icono("fa-solid fa-quote-left", "quote_icon"); ?>
                        </div>

                        <p>“<?php echo limpiar(t("testimonial_2")); ?>”</p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-two">J</div>

                            <div>
                                <h4>José Martínez</h4>
                                <span><?php echo limpiar(t("technical_support")); ?></span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <?php icono("fa-solid fa-quote-left", "quote_icon"); ?>
                        </div>

                        <p>“<?php echo limpiar(t("testimonial_3")); ?>”</p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-three">A</div>

                            <div>
                                <h4>Ana López</h4>
                                <span><?php echo limpiar(t("human_resources")); ?></span>
                            </div>
                        </div>
                    </article>

                </div>

            </div>
        </section>

        <section class="cta-section">
            <div class="container cta-content">
                <div>
                    <span class="section-label cta-label">
                        <?php echo limpiar($esEmpresa ? t("cta_label_company") : t("cta_label_user")); ?>
                    </span>
                    <h2><?php echo limpiar($esEmpresa ? t("cta_title_company") : t("cta_title_user")); ?></h2>
                    <p><?php echo limpiar($esEmpresa ? t("cta_text_company") : t("cta_text_user")); ?></p>
                </div>

                <div class="cta-buttons">
                    <?php if ($esEmpresa): ?>
                        <a href="<?php echo limpiar(enlaceIdioma("publicarvacante.php")); ?>" class="button button-light">
                            <?php echo limpiar(t("nav_post_job")); ?>
                            <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("perfil.php")); ?>" class="button button-outline-light">
                            <?php echo limpiar(t("my_profile")); ?>
                        </a>
                    <?php elseif ($usuarioLogueado): ?>
                        <a href="<?php echo limpiar(enlaceIdioma("perfil.php")); ?>" class="button button-light">
                            <?php echo limpiar(t("my_profile")); ?>
                            <?php icono("fa-solid fa-user", "user_icon"); ?>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>" class="button button-outline-light">
                            <?php echo limpiar(t("explore_jobs")); ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo limpiar(enlaceIdioma("registro.php")); ?>" class="button button-light">
                            <?php echo limpiar(t("create_account")); ?>
                            <?php icono("fa-solid fa-user-plus", "user_plus_icon"); ?>
                        </a>

                        <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>" class="button button-outline-light">
                            <?php echo limpiar(t("explore_jobs")); ?>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<?php if ($puedeBuscarEmpleo): ?>
        <div class="modal hidden" id="jobModal" role="dialog"
            aria-modal="true" aria-labelledby="modalJobTitle">

            <div class="modal-overlay" id="modalOverlay"></div>

            <div class="modal-content">
                <button class="modal-close" id="modalClose"
                    aria-label="<?php echo limpiar(t("modal_close")); ?>"
                    title="<?php echo limpiar(t("modal_close")); ?>">
                    <?php icono("fa-solid fa-xmark", "close_icon"); ?>
                </button>

                <div class="modal-company-icon">
                    <?php icono("fa-solid fa-briefcase", "briefcase_icon"); ?>
                </div>

                <span class="modal-label"><?php echo limpiar(t("modal_label")); ?></span>

                <h2 id="modalJobTitle"><?php echo limpiar(t("modal_job_title")); ?></h2>
                <p class="modal-company" id="modalCompany"><?php echo limpiar(t("modal_company")); ?></p>

                <div class="modal-info">
                    <span id="modalLocation">
                        <?php icono("fa-solid fa-location-dot", "location_icon"); ?>
                        <?php echo limpiar(t("modal_location")); ?>
                    </span>

                    <span id="modalMode">
                        <?php icono("fa-solid fa-house-laptop", "home_work_icon"); ?>
                        <?php echo limpiar(t("modal_mode")); ?>
                    </span>
                </div>

                <p class="modal-description" id="modalDescription"></p>

                <div class="modal-actions">
                    <a href="<?php echo limpiar(enlaceIdioma("empleos.php")); ?>" class="button button-primary" id="applyButton">
                        <?php echo limpiar(t("modal_apply")); ?>
                        <?php icono("fa-solid fa-paper-plane", "paper_plane_icon"); ?>
                    </a>

                    <button class="button button-secondary" id="modalSaveButton">
                        <?php echo limpiar(t("modal_save")); ?>
                        <?php icono("fa-regular fa-bookmark", "bookmark_icon"); ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            isLoggedIn: <?php echo $usuarioLogueado ? "true" : "false"; ?>,
            userType: "<?php echo limpiar($tipoUsuario); ?>",
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

    <script>
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

</body>

</html>
