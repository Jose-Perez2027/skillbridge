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

    if (
        $texto === "atencion" ||
        $texto === "atencion al cliente" ||
        $texto === "customer service" ||
        $texto === "customer support"
    ) {
        return "customer service";
    }

    if (
        $texto === "recursos humanos" ||
        $texto === "human resources" ||
        $texto === "rrhh"
    ) {
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

    if (
        $texto === "presencial" ||
        $texto === "on-site" ||
        $texto === "onsite" ||
        $texto === "in-person" ||
        $texto === "in person"
    ) {
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

function normalizarNivel($valor) {
    $texto = normalizarTextoBusqueda($valor);

    if ($texto === "no experience" || $texto === "sin experiencia") {
        return "no experience";
    }

    if ($texto === "junior") {
        return "junior";
    }

    if ($texto === "mid-level" || $texto === "mid level" || $texto === "intermedio") {
        return "mid-level";
    }

    if ($texto === "senior") {
        return "senior";
    }

    return $texto;
}

function habilidadesLista($habilidades) {
    $habilidades = trim($habilidades ?? "");

    if ($habilidades === "") {
        return [];
    }

    if (strpos($habilidades, "|") !== false) {
        $lista = explode("|", $habilidades);
    } else {
        $lista = explode(",", $habilidades);
    }

    $resultado = [];

    foreach ($lista as $habilidad) {
        $habilidad = trim($habilidad);

        if ($habilidad !== "") {
            $resultado[] = $habilidad;
        }
    }

    return array_slice($resultado, 0, 4);
}

function inicialEmpresa($nombre) {
    $nombre = trim($nombre ?? "Company");

    if ($nombre === "") {
        return "C";
    }

    return strtoupper(substr($nombre, 0, 1));
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
            $_SESSION["nombre"] = $usuarioActual["nombre"] ?? "";
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
        "page_title" => "Find Jobs | SkillBridge",
        "meta_description" => "Find accessible job opportunities on SkillBridge.",

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
        "search_icon" => "Search icon",
        "briefcase_icon" => "Job icon",
        "location_icon" => "Location icon",
        "clock_icon" => "Clock icon",
        "user_level_icon" => "Experience level icon",
        "category_icon" => "Category icon",
        "calendar_icon" => "Calendar icon",
        "bookmark_icon" => "Save job icon",
        "arrow_icon" => "Arrow icon",
        "building_icon" => "Company icon",
        "plus_icon" => "Add icon",
        "warning_icon" => "Warning icon",
        "grid_icon" => "Grid view icon",
        "list_icon" => "List view icon",
        "filter_icon" => "Filter icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",
        "send_icon" => "Send icon",
        "chevron_icon" => "Navigation arrow icon",
        "seedling_icon" => "No experience icon",

        "error_loading_jobs" => "There was an error loading job openings.",
        "check_database" => "Please check your database connection.",

        "salary_not_specified" => "Salary not specified",
        "no_deadline" => "No deadline",
        "location_not_specified" => "Location not specified",
        "type_not_specified" => "Type not specified",
        "level_not_specified" => "Level not specified",
        "general" => "General",

        "breadcrumb_home" => "Home",
        "breadcrumb_find_jobs" => "Find jobs",

        "hero_label" => "Accessible job board",
        "hero_title" => "Find accessible job opportunities.",
        "hero_text" => "Explore active job openings published by companies connected to SkillBridge.",
        "active_openings" => "Active openings",

        "search_label" => "What job are you looking for?",
        "search_placeholder" => "Example: Developer, Designer, Sales",
        "category_label" => "Category",
        "all_categories" => "All categories",
        "search_button" => "Find jobs",

        "quick_filters" => "Quick filters:",
        "remote" => "Remote",
        "hybrid" => "Hybrid",
        "onsite" => "On-site",
        "no_experience" => "No experience",
        "accessible_jobs" => "Accessible jobs",

        "filters_title" => "Filters",
        "filters_text" => "Refine your search.",
        "clear_all" => "Clear all",
        "work_arrangement" => "Work arrangement",
        "experience_level" => "Experience level",
        "salary" => "Salary",
        "any" => "Any",
        "accessible_opportunities" => "Accessible opportunities",
        "accessible_note" => "Some openings highlight accessibility and inclusive hiring conditions.",

        "results_label" => "Results",
        "available_jobs" => "Available jobs",
        "job_found_singular" => "job opening found.",
        "job_found_plural" => "job openings found.",

        "sort_jobs" => "Sort jobs",
        "newest_first" => "Newest first",
        "highest_salary" => "Highest salary",
        "lowest_salary" => "Lowest salary",
        "title_az" => "Title A-Z",
        "grid_view" => "Grid view",
        "list_view" => "List view",

        "save_job" => "Save job",
        "job_label" => "Job",
        "inclusive" => "Accessible",
        "teamwork" => "Teamwork",
        "communication" => "Communication",
        "responsibility" => "Responsibility",
        "estimated_salary" => "Estimated salary",
        "deadline" => "Deadline",
        "view_details" => "View details",
        "apply_now" => "Apply now",
        "admin_account" => "Administrator account",

        "no_jobs_title" => "No job openings found.",
        "no_jobs_text" => "Try another keyword, category, work arrangement, or experience level.",
        "reset_search" => "Reset search",

        "company_promo_label" => "For companies",
        "company_promo_title" => "Do you want to publish an accessible job opening?",
        "company_promo_text" => "SkillBridge helps companies connect with motivated candidates and promote accessible hiring opportunities.",
        "post_job" => "Post a job",
        "go_to_profile" => "Go to profile",
        "login_as_company" => "Log in as company",

        "cat_technology" => "Technology",
        "cat_design" => "Design",
        "cat_administration" => "Administration",
        "cat_sales" => "Sales",
        "cat_customer_service" => "Customer Service",
        "cat_human_resources" => "Human Resources",

        "level_junior" => "Junior",
        "level_mid" => "Mid-level",
        "level_senior" => "Senior",

        "type_full_time" => "Full-time",
        "type_part_time" => "Part-time",
        "type_internship" => "Internship",
        "type_temporary" => "Temporary",
        "type_freelance" => "Freelance",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_newsletter" => "Newsletter",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and advice",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact" => "Contact the team",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_newsletter_text" => "Receive new job openings and professional advice.",
        "footer_email_address" => "Email address",
        "footer_email_placeholder" => "Your email address",
        "footer_subscribe" => "Subscribe",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Buscar empleos | SkillBridge",
        "meta_description" => "Encuentra oportunidades laborales accesibles en SkillBridge.",

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
        "search_icon" => "Ícono de búsqueda",
        "briefcase_icon" => "Ícono de empleo",
        "location_icon" => "Ícono de ubicación",
        "clock_icon" => "Ícono de horario",
        "user_level_icon" => "Ícono de nivel de experiencia",
        "category_icon" => "Ícono de categoría",
        "calendar_icon" => "Ícono de calendario",
        "bookmark_icon" => "Ícono de guardar empleo",
        "arrow_icon" => "Ícono de flecha",
        "building_icon" => "Ícono de empresa",
        "plus_icon" => "Ícono de agregar",
        "warning_icon" => "Ícono de advertencia",
        "grid_icon" => "Ícono de vista en cuadrícula",
        "list_icon" => "Ícono de vista en lista",
        "filter_icon" => "Ícono de filtro",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",
        "send_icon" => "Ícono de enviar",
        "chevron_icon" => "Ícono de flecha de navegación",
        "seedling_icon" => "Ícono de sin experiencia",

        "error_loading_jobs" => "Hubo un error al cargar las vacantes.",
        "check_database" => "Revisa la conexión con la base de datos.",

        "salary_not_specified" => "Salario no especificado",
        "no_deadline" => "Sin fecha límite",
        "location_not_specified" => "Ubicación no especificada",
        "type_not_specified" => "Tipo no especificado",
        "level_not_specified" => "Nivel no especificado",
        "general" => "General",

        "breadcrumb_home" => "Inicio",
        "breadcrumb_find_jobs" => "Buscar empleos",

        "hero_label" => "Bolsa de empleos accesible",
        "hero_title" => "Encuentra oportunidades laborales accesibles.",
        "hero_text" => "Explora vacantes activas publicadas por empresas conectadas con SkillBridge.",
        "active_openings" => "Vacantes activas",

        "search_label" => "¿Qué empleo estás buscando?",
        "search_placeholder" => "Ejemplo: Desarrollador, Diseñador, Ventas",
        "category_label" => "Categoría",
        "all_categories" => "Todas las categorías",
        "search_button" => "Buscar empleos",

        "quick_filters" => "Filtros rápidos:",
        "remote" => "Remoto",
        "hybrid" => "Híbrido",
        "onsite" => "Presencial",
        "no_experience" => "Sin experiencia",
        "accessible_jobs" => "Empleos accesibles",

        "filters_title" => "Filtros",
        "filters_text" => "Refina tu búsqueda.",
        "clear_all" => "Limpiar todo",
        "work_arrangement" => "Modalidad de trabajo",
        "experience_level" => "Nivel de experiencia",
        "salary" => "Salario",
        "any" => "Cualquiera",
        "accessible_opportunities" => "Oportunidades accesibles",
        "accessible_note" => "Algunas vacantes resaltan condiciones de accesibilidad y contratación inclusiva.",

        "results_label" => "Resultados",
        "available_jobs" => "Vacantes disponibles",
        "job_found_singular" => "vacante encontrada.",
        "job_found_plural" => "vacantes encontradas.",

        "sort_jobs" => "Ordenar empleos",
        "newest_first" => "Más recientes primero",
        "highest_salary" => "Salario más alto",
        "lowest_salary" => "Salario más bajo",
        "title_az" => "Título A-Z",
        "grid_view" => "Vista en cuadrícula",
        "list_view" => "Vista en lista",

        "save_job" => "Guardar empleo",
        "job_label" => "Empleo",
        "inclusive" => "Accesible",
        "teamwork" => "Trabajo en equipo",
        "communication" => "Comunicación",
        "responsibility" => "Responsabilidad",
        "estimated_salary" => "Salario estimado",
        "deadline" => "Fecha límite",
        "view_details" => "Ver detalles",
        "apply_now" => "Aplicar ahora",
        "admin_account" => "Cuenta de administrador",

        "no_jobs_title" => "No se encontraron vacantes.",
        "no_jobs_text" => "Prueba con otra palabra clave, categoría, modalidad o nivel de experiencia.",
        "reset_search" => "Restablecer búsqueda",

        "company_promo_label" => "Para empresas",
        "company_promo_title" => "¿Quieres publicar una vacante accesible?",
        "company_promo_text" => "SkillBridge ayuda a las empresas a conectar con candidatos motivados y a promover oportunidades laborales accesibles.",
        "post_job" => "Publicar vacante",
        "go_to_profile" => "Ir al perfil",
        "login_as_company" => "Iniciar sesión como empresa",

        "cat_technology" => "Tecnología",
        "cat_design" => "Diseño",
        "cat_administration" => "Administración",
        "cat_sales" => "Ventas",
        "cat_customer_service" => "Atención al cliente",
        "cat_human_resources" => "Recursos humanos",

        "level_junior" => "Junior",
        "level_mid" => "Intermedio",
        "level_senior" => "Senior",

        "type_full_time" => "Tiempo completo",
        "type_part_time" => "Medio tiempo",
        "type_internship" => "Pasantía",
        "type_temporary" => "Temporal",
        "type_freelance" => "Freelance",

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

function urlConIdioma($idioma) {
    $params = $_GET;
    $params["lang"] = $idioma;

    return "empleos.php?" . http_build_query($params);
}

function campoIdioma($vacante, $campoBase) {
    global $idiomaActual;

    $campoPreferido = $campoBase . "_" . $idiomaActual;
    $campoAlternativo = $campoBase . "_" . ($idiomaActual === "es" ? "en" : "es");

    if (!empty($vacante[$campoPreferido])) {
        return $vacante[$campoPreferido];
    }

    if (!empty($vacante[$campoAlternativo])) {
        return $vacante[$campoAlternativo];
    }

    return $vacante[$campoBase] ?? "";
}

function salarioTexto($salario) {
    if ($salario === null || $salario === "" || $salario == 0) {
        return t("salary_not_specified");
    }

    return "$" . number_format((float)$salario, 2);
}

function fechaTexto($fecha) {
    global $idiomaActual;

    if (empty($fecha)) {
        return t("no_deadline");
    }

    $timestamp = strtotime($fecha);

    if (!$timestamp) {
        return t("no_deadline");
    }

    if ($idiomaActual === "es") {
        return date("d/m/Y", $timestamp);
    }

    return date("M d, Y", $timestamp);
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

function etiquetaCategoria($categoria) {
    $categoriaNormalizada = normalizarCategoria($categoria);

    $mapa = [
        "technology" => t("cat_technology"),
        "design" => t("cat_design"),
        "administration" => t("cat_administration"),
        "sales" => t("cat_sales"),
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

    return $mapa[$modalidadNormalizada] ?? ($modalidad !== "" ? $modalidad : t("job_label"));
}

function etiquetaTipoEmpleo($tipoEmpleo) {
    $tipoNormalizado = normalizarTipoEmpleo($tipoEmpleo);

    $mapa = [
        "full-time" => t("type_full_time"),
        "part-time" => t("type_part_time"),
        "internship" => t("type_internship"),
        "temporary" => t("type_temporary"),
        "freelance" => t("type_freelance")
    ];

    return $mapa[$tipoNormalizado] ?? ($tipoEmpleo !== "" ? $tipoEmpleo : t("type_not_specified"));
}

function etiquetaNivel($nivel) {
    $nivelNormalizado = normalizarNivel($nivel);

    $mapa = [
        "no experience" => t("no_experience"),
        "junior" => t("level_junior"),
        "mid-level" => t("level_mid"),
        "senior" => t("level_senior")
    ];

    return $mapa[$nivelNormalizado] ?? ($nivel !== "" ? $nivel : t("level_not_specified"));
}

function buscarCoincidenciaUnica($vacantes, $busqueda, $categoriaFiltro, $modalidadFiltro) {
    $busquedaNormalizada = normalizarTextoBusqueda($busqueda);
    $categoriaNormalizada = normalizarCategoria($categoriaFiltro);
    $modalidadNormalizada = normalizarModalidad($modalidadFiltro);

    if ($busquedaNormalizada === "") {
        return null;
    }

    $coincidenciasExactas = [];
    $coincidenciasGenerales = [];

    foreach ($vacantes as $vacante) {
        $idVacante = (int)($vacante["id_vacante"] ?? 0);

        if ($idVacante <= 0) {
            continue;
        }

        $categoria = $vacante["categoria"] ?? "";
        $modalidad = $vacante["modalidad"] ?? "";

        $coincideCategoria =
            $categoriaNormalizada === "" ||
            normalizarCategoria($categoria) === $categoriaNormalizada;

        $coincideModalidad =
            $modalidadNormalizada === "" ||
            normalizarModalidad($modalidad) === $modalidadNormalizada;

        if (!$coincideCategoria || !$coincideModalidad) {
            continue;
        }

        $tituloActual = campoIdioma($vacante, "titulo");
        $tituloEn = $vacante["titulo_en"] ?? "";
        $tituloEs = $vacante["titulo_es"] ?? "";
        $empresa = $vacante["empresa_nombre"] ?? "";
        $ubicacion = $vacante["ubicacion"] ?? "";
        $tipoEmpleo = $vacante["tipo_empleo"] ?? "";
        $nivel = $vacante["nivel_experiencia"] ?? "";
        $habilidades = campoIdioma($vacante, "habilidades");
        $descripcion = campoIdioma($vacante, "descripcion");
        $requisitos = campoIdioma($vacante, "requisitos");

        $tituloNormalizado = normalizarTextoBusqueda($tituloActual);

        $textoGeneral = normalizarTextoBusqueda(
            $tituloActual . " " .
            $tituloEn . " " .
            $tituloEs . " " .
            $empresa . " " .
            $categoria . " " .
            $modalidad . " " .
            $ubicacion . " " .
            $tipoEmpleo . " " .
            $nivel . " " .
            $habilidades . " " .
            $descripcion . " " .
            $requisitos
        );

        if ($tituloNormalizado === $busquedaNormalizada) {
            $coincidenciasExactas[] = $vacante;
        }

        if (strlen($busquedaNormalizada) >= 3 && strpos($textoGeneral, $busquedaNormalizada) !== false) {
            $coincidenciasGenerales[] = $vacante;
        }
    }

    if (count($coincidenciasExactas) === 1) {
        return $coincidenciasExactas[0];
    }

    if (count($coincidenciasGenerales) === 1) {
        return $coincidenciasGenerales[0];
    }

    return null;
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

if ($usuarioLogueado && $esEmpresa) {
    header("Location: perfil.php");
    exit;
}

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = $usuarioActual && !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = $usuarioActual && !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = $usuarioActual && !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = $usuarioActual && isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$categorias = [
    ["value" => "Technology", "label" => t("cat_technology")],
    ["value" => "Design", "label" => t("cat_design")],
    ["value" => "Administration", "label" => t("cat_administration")],
    ["value" => "Sales", "label" => t("cat_sales")],
    ["value" => "Customer Service", "label" => t("cat_customer_service")],
    ["value" => "Human Resources", "label" => t("cat_human_resources")]
];

$modalidades = [
    ["value" => "Remote", "label" => t("remote"), "icon" => "fa-house-laptop"],
    ["value" => "Hybrid", "label" => t("hybrid"), "icon" => "fa-building-user"],
    ["value" => "On-site", "label" => t("onsite"), "icon" => "fa-location-dot"]
];

$niveles = [
    ["value" => "No experience", "label" => t("no_experience")],
    ["value" => "Junior", "label" => t("level_junior")],
    ["value" => "Mid-level", "label" => t("level_mid")],
    ["value" => "Senior", "label" => t("level_senior")]
];

$vacantes = [];
$errorCarga = "";

try {
    $sql = "
        SELECT
            vacantes.id_vacante,
            vacantes.titulo,
            vacantes.titulo_en,
            vacantes.titulo_es,
            vacantes.categoria,
            vacantes.modalidad,
            vacantes.ubicacion,
            vacantes.tipo_empleo,
            vacantes.nivel_experiencia,
            vacantes.salario,
            vacantes.descripcion,
            vacantes.descripcion_en,
            vacantes.descripcion_es,
            vacantes.responsabilidades,
            vacantes.responsabilidades_en,
            vacantes.responsabilidades_es,
            vacantes.requisitos,
            vacantes.requisitos_en,
            vacantes.requisitos_es,
            vacantes.habilidades,
            vacantes.habilidades_en,
            vacantes.habilidades_es,
            vacantes.fecha_publicacion,
            vacantes.fecha_limite,
            vacantes.estado,
            vacantes.inclusiva,
            empresas.nombre AS empresa_nombre,
            empresas.lema AS empresa_lema
        FROM vacantes
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        WHERE vacantes.estado = 'activa'
        ORDER BY vacantes.fecha_publicacion DESC
    ";

    $consulta = $pdo->query($sql);
    $vacantes = $consulta->fetchAll();
} catch (PDOException $e) {
    $errorCarga = t("error_loading_jobs");
}

$busquedaInicial = trim($_GET["search"] ?? ($_GET["q"] ?? ""));
$categoriaInicial = trim($_GET["category"] ?? ($_GET["categoria"] ?? ""));
$modalidadInicial = trim($_GET["mode"] ?? ($_GET["modalidad"] ?? ""));

if ($busquedaInicial !== "" && empty($errorCarga)) {
    $coincidenciaUnica = buscarCoincidenciaUnica(
        $vacantes,
        $busquedaInicial,
        $categoriaInicial,
        $modalidadInicial
    );

    if ($coincidenciaUnica) {
        $idCoincidencia = (int)($coincidenciaUnica["id_vacante"] ?? 0);

        if ($idCoincidencia > 0) {
            header("Location: detalle-empleo.php?id=" . $idCoincidencia . "&lang=" . urlencode($idiomaActual));
            exit;
        }
    }
}

$totalVacantes = count($vacantes);

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
    <link rel="stylesheet" href="css/empleos.css?v=20260904footerfinal">
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

    <main>

        <section class="jobs-page-hero">

            <div class="jobs-hero-shape jobs-hero-shape-one"></div>
            <div class="jobs-hero-shape jobs-hero-shape-two"></div>

            <div class="container jobs-page-hero-content">

                <div class="breadcrumb">
                    <a href="index.php"><?php echo limpiar(t("breadcrumb_home")); ?></a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                    <span><?php echo limpiar(t("breadcrumb_find_jobs")); ?></span>
                </div>

                <div class="jobs-hero-text">

                    <div>
                        <span class="section-label">
                            <?php echo limpiar(t("hero_label")); ?>
                        </span>

                        <h1><?php echo limpiar(t("hero_title")); ?></h1>

                        <p>
                            <?php echo limpiar(t("hero_text")); ?>
                        </p>
                    </div>

                    <div class="hero-jobs-stat">
                        <div class="hero-jobs-stat-icon">
                            <i class="fa-solid fa-briefcase"
                                role="img"
                                aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                        </div>

                        <div>
                            <strong><?php echo $totalVacantes; ?></strong>
                            <span><?php echo limpiar(t("active_openings")); ?></span>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <section class="jobs-search-section">
            <div class="container">

                <div class="employment-search-box">

                    <div class="employment-search-field employment-main-search">
                        <label for="jobSearchInput">
                            <?php echo limpiar(t("search_label")); ?>
                        </label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-magnifying-glass"
                                role="img"
                                aria-label="<?php echo limpiar(t("search_icon")); ?>"
                                title="<?php echo limpiar(t("search_icon")); ?>"></i>

                            <input type="text" id="jobSearchInput"
                                value="<?php echo limpiar($busquedaInicial); ?>"
                                placeholder="<?php echo limpiar(t("search_placeholder")); ?>">
                        </div>
                    </div>

                    <div class="employment-search-field">
                        <label for="categorySelect">
                            <?php echo limpiar(t("category_label")); ?>
                        </label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-layer-group"
                                role="img"
                                aria-label="<?php echo limpiar(t("category_icon")); ?>"
                                title="<?php echo limpiar(t("category_icon")); ?>"></i>

                            <select id="categorySelect">
                                <option value=""><?php echo limpiar(t("all_categories")); ?></option>

                                <?php foreach ($categorias as $categoriaItem): ?>
                                    <option value="<?php echo limpiar($categoriaItem["value"]); ?>"
                                        <?php echo normalizarCategoria($categoriaInicial) === normalizarCategoria($categoriaItem["value"]) ? "selected" : ""; ?>>
                                        <?php echo limpiar($categoriaItem["label"]); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="button button-primary employment-search-button"
                        id="searchButton">
                        <?php echo limpiar(t("search_button")); ?>
                        <i class="fa-solid fa-arrow-right"
                            role="img"
                            aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                            title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                    </button>

                </div>

                <div class="quick-filter-row">
                    <span><?php echo limpiar(t("quick_filters")); ?></span>

                    <button type="button" class="quick-filter" data-filter-type="modality" data-value="Remote">
                        <i class="fa-solid fa-house-laptop"
                            role="img"
                            aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                            title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                        <?php echo limpiar(t("remote")); ?>
                    </button>

                    <button type="button" class="quick-filter" data-filter-type="modality" data-value="Hybrid">
                        <i class="fa-solid fa-building-user"
                            role="img"
                            aria-label="<?php echo limpiar(t("building_icon")); ?>"
                            title="<?php echo limpiar(t("building_icon")); ?>"></i>
                        <?php echo limpiar(t("hybrid")); ?>
                    </button>

                    <button type="button" class="quick-filter" data-filter-type="modality" data-value="On-site">
                        <i class="fa-solid fa-location-dot"
                            role="img"
                            aria-label="<?php echo limpiar(t("location_icon")); ?>"
                            title="<?php echo limpiar(t("location_icon")); ?>"></i>
                        <?php echo limpiar(t("onsite")); ?>
                    </button>

                    <button type="button" class="quick-filter" data-filter-type="experience" data-value="No experience">
                        <i class="fa-solid fa-seedling"
                            role="img"
                            aria-label="<?php echo limpiar(t("seedling_icon")); ?>"
                            title="<?php echo limpiar(t("seedling_icon")); ?>"></i>
                        <?php echo limpiar(t("no_experience")); ?>
                    </button>

                    <button type="button" class="quick-filter" data-filter-type="inclusive" data-value="1">
                        <i class="fa-solid fa-universal-access"
                            role="img"
                            aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                            title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        <?php echo limpiar(t("accessible_jobs")); ?>
                    </button>
                </div>

            </div>
        </section>

        <section class="jobs-content-section">
            <div class="container jobs-page-layout">

                <aside class="jobs-filters">

                    <div class="filters-header">
                        <div>
                            <h2><?php echo limpiar(t("filters_title")); ?></h2>
                            <p><?php echo limpiar(t("filters_text")); ?></p>
                        </div>

                        <button type="button" class="clear-filters-button" id="clearFiltersButton">
                            <?php echo limpiar(t("clear_all")); ?>
                        </button>
                    </div>

                    <div class="filter-group">
                        <button type="button" class="filter-title">
                            <?php echo limpiar(t("work_arrangement")); ?>
                            <i class="fa-solid fa-chevron-up"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </button>

                        <div class="filter-options">
                            <?php foreach ($modalidades as $modalidadItem): ?>
                                <label class="filter-option">
                                    <input type="checkbox" class="filter-checkbox" data-filter="modality"
                                        value="<?php echo limpiar($modalidadItem["value"]); ?>">
                                    <span class="custom-checkbox"></span>
                                    <?php echo limpiar($modalidadItem["label"]); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-group">
                        <button type="button" class="filter-title">
                            <?php echo limpiar(t("experience_level")); ?>
                            <i class="fa-solid fa-chevron-up"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </button>

                        <div class="filter-options">
                            <?php foreach ($niveles as $nivelItem): ?>
                                <label class="filter-option">
                                    <input type="checkbox" class="filter-checkbox" data-filter="experience"
                                        value="<?php echo limpiar($nivelItem["value"]); ?>">
                                    <span class="custom-checkbox"></span>
                                    <?php echo limpiar($nivelItem["label"]); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-group">
                        <button type="button" class="filter-title">
                            <?php echo limpiar(t("category_label")); ?>
                            <i class="fa-solid fa-chevron-up"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </button>

                        <div class="filter-options">
                            <?php foreach ($categorias as $categoriaItem): ?>
                                <label class="filter-option">
                                    <input type="checkbox" class="filter-checkbox" data-filter="category"
                                        value="<?php echo limpiar($categoriaItem["value"]); ?>">
                                    <span class="custom-checkbox"></span>
                                    <?php echo limpiar($categoriaItem["label"]); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-group salary-filter-group">
                        <button type="button" class="filter-title">
                            <?php echo limpiar(t("salary")); ?>
                            <i class="fa-solid fa-chevron-up"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </button>

                        <div class="salary-range-box">
                            <input type="range" id="salaryRange"
                                min="0" max="3000" step="50" value="3000">

                            <div class="salary-range-values">
                                <span>$0</span>
                                <strong id="salaryValue"><?php echo limpiar(t("any")); ?></strong>
                                <span>$3000+</span>
                            </div>
                        </div>
                    </div>

                    <div class="inclusive-note">
                        <div class="inclusive-note-icon">
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </div>

                        <div>
                            <strong><?php echo limpiar(t("accessible_opportunities")); ?></strong>
                            <p>
                                <?php echo limpiar(t("accessible_note")); ?>
                            </p>
                        </div>
                    </div>

                </aside>

                <section class="jobs-results">

                    <div class="jobs-results-top">
                        <div>
                            <span class="results-small-text">
                                <?php echo limpiar(t("results_label")); ?>
                            </span>

                            <h2><?php echo limpiar(t("available_jobs")); ?></h2>

                            <p id="jobsCount">
                                <?php echo $totalVacantes; ?>
                                <?php echo limpiar($totalVacantes === 1 ? t("job_found_singular") : t("job_found_plural")); ?>
                            </p>
                        </div>

                        <div class="jobs-result-actions">
                            <select id="sortSelect" aria-label="<?php echo limpiar(t("sort_jobs")); ?>">
                                <option value="newest"><?php echo limpiar(t("newest_first")); ?></option>
                                <option value="salary-high"><?php echo limpiar(t("highest_salary")); ?></option>
                                <option value="salary-low"><?php echo limpiar(t("lowest_salary")); ?></option>
                                <option value="title"><?php echo limpiar(t("title_az")); ?></option>
                            </select>

                            <div class="view-buttons">
                                <button type="button" class="view-button active" data-view="grid"
                                    aria-label="<?php echo limpiar(t("grid_view")); ?>">
                                    <i class="fa-solid fa-table-cells-large"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("grid_icon")); ?>"
                                        title="<?php echo limpiar(t("grid_icon")); ?>"></i>
                                </button>

                                <button type="button" class="view-button" data-view="list"
                                    aria-label="<?php echo limpiar(t("list_view")); ?>">
                                    <i class="fa-solid fa-list"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("list_icon")); ?>"
                                        title="<?php echo limpiar(t("list_icon")); ?>"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="active-filters" id="activeFilters"></div>

                    <?php if (!empty($errorCarga)): ?>
                        <div class="employment-no-results">
                            <div class="employment-no-results-icon">
                                <i class="fa-solid fa-triangle-exclamation"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                    title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                            </div>

                            <h3><?php echo limpiar($errorCarga); ?></h3>
                            <p><?php echo limpiar(t("check_database")); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="employment-jobs-grid" id="jobsGrid">

                        <?php foreach ($vacantes as $index => $vacante): ?>
                            <?php
                                $idVacante = (int)($vacante["id_vacante"] ?? 0);

                                $titulo = campoIdioma($vacante, "titulo");
                                $descripcion = campoIdioma($vacante, "descripcion");
                                $requisitos = campoIdioma($vacante, "requisitos");
                                $responsabilidades = campoIdioma($vacante, "responsabilidades");
                                $habilidadesTexto = campoIdioma($vacante, "habilidades");

                                if ($titulo === "") {
                                    $titulo = t("job_label");
                                }

                                $empresa = $vacante["empresa_nombre"] ?? "Company";
                                $categoria = $vacante["categoria"] ?? "";
                                $modalidad = $vacante["modalidad"] ?? "";
                                $modalidadCss = modalidadClase($modalidad);
                                $ubicacion = $vacante["ubicacion"] ?? "";
                                $tipoEmpleo = $vacante["tipo_empleo"] ?? "";
                                $nivel = $vacante["nivel_experiencia"] ?? "";
                                $salario = $vacante["salario"] ?? 0;
                                $fechaPublicacion = $vacante["fecha_publicacion"] ?? "";
                                $fechaLimite = $vacante["fecha_limite"] ?? "";
                                $habilidades = habilidadesLista($habilidadesTexto);
                                $inclusiva = (int)($vacante["inclusiva"] ?? 0);

                                $iconClasses = ["company-blue", "company-purple", "company-pink", "company-orange", "company-green"];
                                $iconClass = $iconClasses[$index % count($iconClasses)];

                                $textoBusqueda = normalizarTextoBusqueda(
                                    $titulo . " " .
                                    ($vacante["titulo_en"] ?? "") . " " .
                                    ($vacante["titulo_es"] ?? "") . " " .
                                    $empresa . " " .
                                    $categoria . " " .
                                    $modalidad . " " .
                                    $ubicacion . " " .
                                    $tipoEmpleo . " " .
                                    $nivel . " " .
                                    $habilidadesTexto . " " .
                                    $descripcion . " " .
                                    $requisitos . " " .
                                    $responsabilidades
                                );

                                $categoriaEtiqueta = etiquetaCategoria($categoria);
                                $modalidadEtiqueta = etiquetaModalidad($modalidad);
                                $tipoEmpleoEtiqueta = etiquetaTipoEmpleo($tipoEmpleo);
                                $nivelEtiqueta = etiquetaNivel($nivel);
                            ?>

                            <article class="employment-job-card"
                                data-search="<?php echo limpiar($textoBusqueda); ?>"
                                data-title="<?php echo limpiar(normalizarTextoBusqueda($titulo)); ?>"
                                data-category="<?php echo limpiar($categoria); ?>"
                                data-modality="<?php echo limpiar($modalidad); ?>"
                                data-experience="<?php echo limpiar($nivel); ?>"
                                data-location="<?php echo limpiar(normalizarTextoBusqueda($ubicacion)); ?>"
                                data-salary="<?php echo limpiar($salario ?: 0); ?>"
                                data-posted="<?php echo limpiar(strtotime($fechaPublicacion) ?: 0); ?>"
                                data-inclusive="<?php echo $inclusiva; ?>"
                                data-url="detalle-empleo.php?id=<?php echo $idVacante; ?>&lang=<?php echo limpiar($idiomaActual); ?>">

                                <div class="employment-card-top">
                                    <div class="company-logo <?php echo limpiar($iconClass); ?>"
                                        aria-label="<?php echo limpiar($empresa); ?>">
                                        <?php echo limpiar(inicialEmpresa($empresa)); ?>
                                    </div>

                                    <button class="save-job-button" type="button"
                                        aria-label="<?php echo limpiar(t("save_job")); ?>"
                                        data-job="<?php echo limpiar($titulo); ?>">
                                        <i class="fa-regular fa-bookmark"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("bookmark_icon")); ?>"
                                            title="<?php echo limpiar(t("bookmark_icon")); ?>"></i>
                                    </button>
                                </div>

                                <div class="employment-job-labels">
                                    <span class="job-type <?php echo limpiar($modalidadCss); ?>">
                                        <?php echo limpiar($modalidadEtiqueta); ?>
                                    </span>

                                    <?php if ($inclusiva === 1): ?>
                                        <span class="inclusive-job-label">
                                            <i class="fa-solid fa-universal-access"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                                            <?php echo limpiar(t("inclusive")); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h3><?php echo limpiar($titulo); ?></h3>

                                <p class="job-company">
                                    <?php echo limpiar($empresa); ?>
                                </p>

                                <div class="employment-job-info">
                                    <span>
                                        <i class="fa-solid fa-location-dot"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                            title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                        <?php echo limpiar($ubicacion ?: t("location_not_specified")); ?>
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-clock"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("clock_icon")); ?>"
                                            title="<?php echo limpiar(t("clock_icon")); ?>"></i>
                                        <?php echo limpiar($tipoEmpleoEtiqueta); ?>
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-user-graduate"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("user_level_icon")); ?>"
                                            title="<?php echo limpiar(t("user_level_icon")); ?>"></i>
                                        <?php echo limpiar($nivelEtiqueta); ?>
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-layer-group"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("category_icon")); ?>"
                                            title="<?php echo limpiar(t("category_icon")); ?>"></i>
                                        <?php echo limpiar($categoriaEtiqueta); ?>
                                    </span>
                                </div>

                                <div class="employment-job-skills">
                                    <?php if (!empty($habilidades)): ?>
                                        <?php foreach ($habilidades as $habilidad): ?>
                                            <span><?php echo limpiar($habilidad); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span><?php echo limpiar(t("teamwork")); ?></span>
                                        <span><?php echo limpiar(t("communication")); ?></span>
                                        <span><?php echo limpiar(t("responsibility")); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="employment-salary-row">
                                    <div>
                                        <span><?php echo limpiar(t("estimated_salary")); ?></span>
                                        <strong><?php echo limpiar(salarioTexto($salario)); ?></strong>
                                    </div>

                                    <div class="job-posted-date">
                                        <span>
                                            <i class="fa-solid fa-calendar-days"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("calendar_icon")); ?>"
                                                title="<?php echo limpiar(t("calendar_icon")); ?>"></i>
                                            <?php echo limpiar(t("deadline")); ?>
                                        </span>

                                        <strong><?php echo limpiar(fechaTexto($fechaLimite)); ?></strong>
                                    </div>
                                </div>

                                <div class="employment-card-actions">
                                    <a href="detalle-empleo.php?id=<?php echo $idVacante; ?>&lang=<?php echo limpiar($idiomaActual); ?>"
                                        class="button button-secondary mini-apply-button">
                                        <?php echo limpiar(t("view_details")); ?>
                                    </a>

                                    <?php if (!$usuarioLogueado): ?>
                                        <a href="login.php?redirect=<?php echo urlencode('detalle-empleo.php?id=' . $idVacante . '&apply=1&lang=' . $idiomaActual); ?>"
                                            class="button button-primary mini-apply-button">
                                            <?php echo limpiar(t("apply_now")); ?>
                                        </a>
                                    <?php elseif ($esCandidato || $esAdmin): ?>
                                        <a href="detalle-empleo.php?id=<?php echo $idVacante; ?>&apply=1&lang=<?php echo limpiar($idiomaActual); ?>"
                                            class="button button-primary mini-apply-button">
                                            <?php echo limpiar(t("apply_now")); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <div class="employment-no-results <?php echo $totalVacantes > 0 ? "hidden" : ""; ?>" id="noResults">
                        <div class="employment-no-results-icon">
                            <i class="fa-solid fa-magnifying-glass"
                                role="img"
                                aria-label="<?php echo limpiar(t("search_icon")); ?>"
                                title="<?php echo limpiar(t("search_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("no_jobs_title")); ?></h3>
                        <p><?php echo limpiar(t("no_jobs_text")); ?></p>

                        <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-primary">
                            <?php echo limpiar(t("reset_search")); ?>
                        </a>
                    </div>

                </section>

            </div>
        </section>

        <section class="employer-promotion-section">
            <div class="container">

                <div class="employer-promotion-content">

                    <div class="employer-promotion-icon">
                        <i class="fa-solid fa-building"
                            role="img"
                            aria-label="<?php echo limpiar(t("building_icon")); ?>"
                            title="<?php echo limpiar(t("building_icon")); ?>"></i>
                    </div>

                    <div>
                        <span class="section-label">
                            <?php echo limpiar(t("company_promo_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("company_promo_title")); ?></h2>

                        <p>
                            <?php echo limpiar(t("company_promo_text")); ?>
                        </p>
                    </div>

                    <?php if ($usuarioLogueado && $esAdmin): ?>
                        <a href="publicarvacante.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-light">
                            <?php echo limpiar(t("post_job")); ?>
                            <i class="fa-solid fa-plus"
                                role="img"
                                aria-label="<?php echo limpiar(t("plus_icon")); ?>"
                                title="<?php echo limpiar(t("plus_icon")); ?>"></i>
                        </a>
                    <?php elseif ($usuarioLogueado): ?>
                        <a href="perfil.php" class="button button-light">
                            <?php echo limpiar(t("go_to_profile")); ?>
                            <i class="fa-solid fa-user"
                                role="img"
                                aria-label="<?php echo limpiar(t("user_level_icon")); ?>"
                                title="<?php echo limpiar(t("user_level_icon")); ?>"></i>
                        </a>
                    <?php else: ?>
                        <a href="login.php?redirect=<?php echo urlencode('publicarvacante.php?lang=' . $idiomaActual); ?>" class="button button-light">
                            <?php echo limpiar(t("login_as_company")); ?>
                            <i class="fa-solid fa-arrow-right-to-bracket"
                                role="img"
                                aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                        </a>
                    <?php endif; ?>

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
    <script src="java/empleos.js?v=20260904footerfinal"></script>

</body>

</html>