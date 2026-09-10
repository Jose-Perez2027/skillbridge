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

    if ($texto === "atencion" || $texto === "atencion al cliente" || $texto === "customer service" || $texto === "customer support") {
        return "customer service";
    }

    if ($texto === "recursos humanos" || $texto === "human resources" || $texto === "rrhh") {
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

    if ($texto === "presencial" || $texto === "on-site" || $texto === "onsite" || $texto === "in-person" || $texto === "in person") {
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

function dividirTexto($texto) {
    $texto = trim($texto ?? "");

    if ($texto === "") {
        return [];
    }

    if (strpos($texto, "|") !== false) {
        $partes = explode("|", $texto);
    } else {
        $partes = preg_split("/\r\n|\n|\r|,/", $texto);
    }

    $resultado = [];

    foreach ($partes as $parte) {
        $parte = trim($parte);

        if ($parte !== "") {
            $resultado[] = $parte;
        }
    }

    return $resultado;
}

function obtenerCurriculumGuardado($pdo, $idUsuario) {
    $sql = "
        SELECT curriculum
        FROM usuarios
        WHERE id_usuario = :id_usuario
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_usuario" => $idUsuario
    ]);

    $usuario = $stmt->fetch();

    if (!$usuario) {
        return "";
    }

    return $usuario["curriculum"] ?? "";
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
        "meta_description" => "Explore the details of this accessible job opportunity on SkillBridge.",
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
        "briefcase_icon" => "Job icon",
        "location_icon" => "Location icon",
        "clock_icon" => "Clock icon",
        "calendar_icon" => "Calendar icon",
        "bookmark_icon" => "Save job icon",
        "share_icon" => "Share icon",
        "list_icon" => "List icon",
        "check_icon" => "Check icon",
        "user_check_icon" => "User check icon",
        "star_icon" => "Star icon",
        "gift_icon" => "Benefits icon",
        "handshake_icon" => "Handshake icon",
        "chart_icon" => "Growth icon",
        "team_icon" => "Team icon",
        "route_icon" => "Process icon",
        "building_icon" => "Company icon",
        "salary_icon" => "Salary icon",
        "shield_icon" => "Privacy icon",
        "warning_icon" => "Warning icon",
        "send_icon" => "Send icon",
        "link_icon" => "Link icon",
        "external_link_icon" => "External link icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",
        "whatsapp_icon" => "WhatsApp icon",
        "chevron_icon" => "Navigation arrow icon",
        "plus_icon" => "Add icon",

        "salary_not_specified" => "Salary not specified",
        "salary_per_month" => "per month",
        "no_deadline" => "No deadline",
        "recently_posted" => "Recently posted",
        "job_opening" => "Job opening",
        "company" => "Company",
        "general" => "General",
        "not_specified" => "Not specified",
        "no_description" => "No description available.",
        "company_default_slogan" => "Company connected to SkillBridge.",
        "company_default_description" => "This company is part of SkillBridge and promotes accessible job opportunities.",
        "growing_team" => "Growing team",

        "company_account_cannot_apply" => "Company accounts cannot apply to job openings.",
        "write_message" => "Please write a short application message.",
        "already_applied" => "You have already applied to this job opening.",
        "application_error" => "There was an error sending your application.",
        "application_success" => "Your application was sent successfully.",
        "upload_cv_error" => "There was an error uploading your curriculum.",
        "cv_file_type_error" => "Only PDF, DOC, or DOCX files are allowed.",
        "cv_file_size_error" => "The curriculum file must be less than 5 MB.",
        "cv_save_error" => "The curriculum file could not be saved.",
        "detail_load_error" => "There was an error loading the job details.",

        "remote" => "Remote",
        "hybrid" => "Hybrid",
        "onsite" => "On-site",
        "full_time" => "Full-time",
        "part_time" => "Part-time",
        "internship" => "Internship",
        "temporary" => "Temporary",
        "freelance" => "Freelance",
        "no_experience" => "No experience",
        "junior" => "Junior",
        "mid_level" => "Mid-level",
        "senior" => "Senior",
        "technology" => "Technology",
        "design" => "Design",
        "administration" => "Administration",
        "sales" => "Sales",
        "customer_service" => "Customer Service",
        "human_resources" => "Human Resources",

        "breadcrumb_home" => "Home",
        "breadcrumb_jobs" => "Find jobs",
        "accessible_company" => "Accessible opportunity",
        "verified_job" => "Verified job",
        "posted" => "Posted",
        "save_job" => "Save job",
        "share_job" => "Share",

        "about_position_label" => "About the position",
        "job_description" => "Job description",
        "responsibilities_label" => "Responsibilities",
        "what_you_will_do" => "What you will do",
        "responsibilities_default" => "Responsibilities will be explained during the selection process.",
        "ideal_profile_label" => "Ideal profile",
        "application_requirements" => "Application requirements",
        "requirement" => "Requirement",
        "general_profile" => "General profile",
        "general_profile_text" => "The company will review your profile and skills during the process.",
        "additional_value_label" => "Additional value",
        "skills_related" => "Skills related to this job",
        "responsibility" => "Responsibility",
        "communication" => "Communication",
        "teamwork" => "Teamwork",
        "willingness" => "Willingness to learn",
        "benefits_label" => "Benefits",
        "what_company_offers" => "What %s offers",
        "inclusive_environment" => "Accessible environment",
        "inclusive_environment_text" => "Respectful and accessible selection process.",
        "professional_opportunity" => "Professional opportunity",
        "professional_opportunity_text" => "Real work experience connected to your profile.",
        "growth" => "Growth",
        "growth_text" => "Opportunity to strengthen your professional skills.",
        "teamwork_title" => "Teamwork",
        "teamwork_text" => "Collaboration with company teams and leaders.",
        "selection_process_label" => "Selection process",
        "after_apply_title" => "What happens after you apply?",
        "profile_review" => "Profile review",
        "profile_review_text" => "The company reviews your profile and application information.",
        "initial_contact" => "Initial contact",
        "initial_contact_text" => "If your profile matches, the company may contact you.",
        "interview" => "Interview or evaluation",
        "interview_text" => "The company may request an interview or additional information.",
        "final_decision" => "Final decision",
        "final_decision_text" => "You will receive updates about the selection process.",
        "about_company_label" => "About the company",
        "year_founded" => "Year founded",
        "team_members" => "Team members",
        "location" => "Location",
        "explore_companies" => "Explore partner companies",

        "estimated_salary" => "Estimated salary",
        "salary_note" => "Salary may vary based on experience, skills, and interview results.",
        "employment_type" => "Employment type",
        "work_arrangement" => "Work arrangement",
        "level" => "Level",
        "application_deadline" => "Application deadline",
        "login_to_apply" => "Log in to apply",
        "application_sent" => "Application sent",
        "apply_now" => "Apply now",
        "company_account" => "Company account",
        "application_privacy" => "Your information will only be sent to the company.",
        "commitment_title" => "Commitment to accessibility",
        "commitment_text" => "This company states that it promotes respectful and accessible selection processes.",
        "share_title" => "Share this opportunity",
        "share_text" => "This opportunity may be perfect for someone you know.",
        "copy_link" => "Copy link",

        "similar_label" => "You may also be interested",
        "similar_title" => "Similar jobs for your profile",
        "similar_text" => "Explore other opportunities related to this category.",
        "view_all_jobs" => "View all jobs",
        "view_opportunity" => "View opportunity",
        "more_jobs" => "More jobs",
        "explore_all" => "Explore all opportunities",
        "job_board" => "Accessible job board",

        "cta_label" => "Do not miss this opportunity",
        "cta_title" => "Your next professional journey can start today.",
        "cta_text" => "Apply, show your skills, and take the next step toward your professional goals.",
        "post_another_job" => "Post another job",

        "quick_application" => "Quick application",
        "apply_for_position" => "Apply for the %s position",
        "application_modal_text" => "Complete your information to send your profile to %s.",
        "close_application_form" => "Close application form",
        "full_name" => "Full name",
        "name_placeholder" => "Example: Isaías Pérez",
        "email_address" => "Email address",
        "phone_number" => "Phone number",
        "experience" => "Experience",
        "select_option" => "Select an option",
        "no_work_experience" => "No work experience",
        "less_one_year" => "Less than 1 year",
        "one_two_years" => "1 to 2 years",
        "more_two_years" => "More than 2 years",
        "interest_message" => "Briefly tell us why you are interested in this position",
        "interest_placeholder" => "I am interested in this opportunity because...",
        "curriculum" => "Curriculum",
        "saved_curriculum_found" => "Saved curriculum found",
        "use_saved_text" => "We can use the curriculum that is already saved in your profile.",
        "use_saved" => "Use saved",
        "view_saved_curriculum" => "View saved curriculum",
        "upload_different" => "You can also upload a different file. If you upload a new one, it will be used only for this application.",
        "no_saved_curriculum" => "You do not have a saved curriculum yet. Upload one for this application.",
        "select_cv" => "Select a PDF, DOC, or DOCX file",
        "max_5mb" => "Maximum 5 MB",
        "agree_share" => "I agree that SkillBridge may share my information with %s for this application.",
        "submit_application" => "Submit application",
        "submitted_label" => "Application submitted",
        "great_decision" => "Great decision!",
        "success_text" => "Your application for the %s position was successfully sent to %s.",
        "email_updates" => "You will receive process updates by email.",
        "got_it" => "Got it",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_newsletter" => "Newsletter",
        "footer_create_profile" => "Create profile",
        "footer_resources" => "Resources and tips",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact" => "Contact the team",
        "footer_newsletter_text" => "Receive new job openings and career tips.",
        "footer_email_placeholder" => "Your email address",
        "footer_subscribe" => "Subscribe",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "meta_description" => "Explora los detalles de esta oportunidad laboral accesible en SkillBridge.",
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
        "briefcase_icon" => "Ícono de empleo",
        "location_icon" => "Ícono de ubicación",
        "clock_icon" => "Ícono de horario",
        "calendar_icon" => "Ícono de calendario",
        "bookmark_icon" => "Ícono de guardar empleo",
        "share_icon" => "Ícono de compartir",
        "list_icon" => "Ícono de lista",
        "check_icon" => "Ícono de verificación",
        "user_check_icon" => "Ícono de usuario verificado",
        "star_icon" => "Ícono de estrella",
        "gift_icon" => "Ícono de beneficios",
        "handshake_icon" => "Ícono de acuerdo",
        "chart_icon" => "Ícono de crecimiento",
        "team_icon" => "Ícono de equipo",
        "route_icon" => "Ícono de proceso",
        "building_icon" => "Ícono de empresa",
        "salary_icon" => "Ícono de salario",
        "shield_icon" => "Ícono de privacidad",
        "warning_icon" => "Ícono de advertencia",
        "send_icon" => "Ícono de enviar",
        "link_icon" => "Ícono de enlace",
        "external_link_icon" => "Ícono de enlace externo",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",
        "whatsapp_icon" => "Ícono de WhatsApp",
        "chevron_icon" => "Ícono de flecha de navegación",
        "plus_icon" => "Ícono de agregar",

        "salary_not_specified" => "Salario no especificado",
        "salary_per_month" => "al mes",
        "no_deadline" => "Sin fecha límite",
        "recently_posted" => "Publicado recientemente",
        "job_opening" => "Vacante",
        "company" => "Empresa",
        "general" => "General",
        "not_specified" => "No especificado",
        "no_description" => "No hay descripción disponible.",
        "company_default_slogan" => "Empresa conectada con SkillBridge.",
        "company_default_description" => "Esta empresa forma parte de SkillBridge y promueve oportunidades laborales accesibles.",
        "growing_team" => "Equipo en crecimiento",

        "company_account_cannot_apply" => "Las cuentas de empresa no pueden aplicar a vacantes.",
        "write_message" => "Escribe un mensaje breve de postulación.",
        "already_applied" => "Ya aplicaste a esta vacante.",
        "application_error" => "Hubo un error al enviar tu postulación.",
        "application_success" => "Tu postulación se envió correctamente.",
        "upload_cv_error" => "Hubo un error al subir tu currículum.",
        "cv_file_type_error" => "Solo se permiten archivos PDF, DOC o DOCX.",
        "cv_file_size_error" => "El archivo del currículum debe pesar menos de 5 MB.",
        "cv_save_error" => "El archivo del currículum no pudo guardarse.",
        "detail_load_error" => "Hubo un error al cargar los detalles de la vacante.",

        "remote" => "Remoto",
        "hybrid" => "Híbrido",
        "onsite" => "Presencial",
        "full_time" => "Tiempo completo",
        "part_time" => "Medio tiempo",
        "internship" => "Pasantía",
        "temporary" => "Temporal",
        "freelance" => "Freelance",
        "no_experience" => "Sin experiencia",
        "junior" => "Junior",
        "mid_level" => "Intermedio",
        "senior" => "Senior",
        "technology" => "Tecnología",
        "design" => "Diseño",
        "administration" => "Administración",
        "sales" => "Ventas",
        "customer_service" => "Atención al cliente",
        "human_resources" => "Recursos humanos",

        "breadcrumb_home" => "Inicio",
        "breadcrumb_jobs" => "Buscar empleos",
        "accessible_company" => "Oportunidad accesible",
        "verified_job" => "Vacante verificada",
        "posted" => "Publicado",
        "save_job" => "Guardar empleo",
        "share_job" => "Compartir",

        "about_position_label" => "Sobre el puesto",
        "job_description" => "Descripción del empleo",
        "responsibilities_label" => "Responsabilidades",
        "what_you_will_do" => "Lo que harás",
        "responsibilities_default" => "Las responsabilidades se explicarán durante el proceso de selección.",
        "ideal_profile_label" => "Perfil ideal",
        "application_requirements" => "Requisitos de postulación",
        "requirement" => "Requisito",
        "general_profile" => "Perfil general",
        "general_profile_text" => "La empresa revisará tu perfil y habilidades durante el proceso.",
        "additional_value_label" => "Valor adicional",
        "skills_related" => "Habilidades relacionadas con este empleo",
        "responsibility" => "Responsabilidad",
        "communication" => "Comunicación",
        "teamwork" => "Trabajo en equipo",
        "willingness" => "Deseo de aprender",
        "benefits_label" => "Beneficios",
        "what_company_offers" => "Lo que ofrece %s",
        "inclusive_environment" => "Ambiente accesible",
        "inclusive_environment_text" => "Proceso de selección respetuoso y accesible.",
        "professional_opportunity" => "Oportunidad profesional",
        "professional_opportunity_text" => "Experiencia laboral real conectada con tu perfil.",
        "growth" => "Crecimiento",
        "growth_text" => "Oportunidad para fortalecer tus habilidades profesionales.",
        "teamwork_title" => "Trabajo en equipo",
        "teamwork_text" => "Colaboración con equipos y líderes de la empresa.",
        "selection_process_label" => "Proceso de selección",
        "after_apply_title" => "¿Qué pasa después de aplicar?",
        "profile_review" => "Revisión del perfil",
        "profile_review_text" => "La empresa revisa tu perfil y la información de tu postulación.",
        "initial_contact" => "Contacto inicial",
        "initial_contact_text" => "Si tu perfil coincide, la empresa puede contactarte.",
        "interview" => "Entrevista o evaluación",
        "interview_text" => "La empresa puede solicitar una entrevista o información adicional.",
        "final_decision" => "Decisión final",
        "final_decision_text" => "Recibirás actualizaciones sobre el proceso de selección.",
        "about_company_label" => "Sobre la empresa",
        "year_founded" => "Año de fundación",
        "team_members" => "Miembros del equipo",
        "location" => "Ubicación",
        "explore_companies" => "Explorar empresas aliadas",

        "estimated_salary" => "Salario estimado",
        "salary_note" => "El salario puede variar según experiencia, habilidades y resultados de entrevista.",
        "employment_type" => "Tipo de empleo",
        "work_arrangement" => "Modalidad de trabajo",
        "level" => "Nivel",
        "application_deadline" => "Fecha límite de postulación",
        "login_to_apply" => "Iniciar sesión para aplicar",
        "application_sent" => "Postulación enviada",
        "apply_now" => "Aplicar ahora",
        "company_account" => "Cuenta de empresa",
        "application_privacy" => "Tu información solo será enviada a la empresa.",
        "commitment_title" => "Compromiso con la accesibilidad",
        "commitment_text" => "Esta empresa indica que promueve procesos de selección respetuosos y accesibles.",
        "share_title" => "Compartir esta oportunidad",
        "share_text" => "Esta oportunidad puede ser perfecta para alguien que conoces.",
        "copy_link" => "Copiar enlace",

        "similar_label" => "También te puede interesar",
        "similar_title" => "Empleos similares para tu perfil",
        "similar_text" => "Explora otras oportunidades relacionadas con esta categoría.",
        "view_all_jobs" => "Ver todos los empleos",
        "view_opportunity" => "Ver oportunidad",
        "more_jobs" => "Más empleos",
        "explore_all" => "Explorar todas las oportunidades",
        "job_board" => "Bolsa de empleos accesible",

        "cta_label" => "No pierdas esta oportunidad",
        "cta_title" => "Tu próximo camino profesional puede comenzar hoy.",
        "cta_text" => "Aplica, demuestra tus habilidades y da el siguiente paso hacia tus metas profesionales.",
        "post_another_job" => "Publicar otra vacante",

        "quick_application" => "Postulación rápida",
        "apply_for_position" => "Aplicar al puesto de %s",
        "application_modal_text" => "Completa tu información para enviar tu perfil a %s.",
        "close_application_form" => "Cerrar formulario de postulación",
        "full_name" => "Nombre completo",
        "name_placeholder" => "Ejemplo: Isaías Pérez",
        "email_address" => "Correo electrónico",
        "phone_number" => "Número de teléfono",
        "experience" => "Experiencia",
        "select_option" => "Selecciona una opción",
        "no_work_experience" => "Sin experiencia laboral",
        "less_one_year" => "Menos de 1 año",
        "one_two_years" => "1 a 2 años",
        "more_two_years" => "Más de 2 años",
        "interest_message" => "Cuenta brevemente por qué te interesa este puesto",
        "interest_placeholder" => "Me interesa esta oportunidad porque...",
        "curriculum" => "Currículum",
        "saved_curriculum_found" => "Currículum guardado encontrado",
        "use_saved_text" => "Podemos usar el currículum que ya está guardado en tu perfil.",
        "use_saved" => "Usar guardado",
        "view_saved_curriculum" => "Ver currículum guardado",
        "upload_different" => "También puedes subir un archivo diferente. Si subes uno nuevo, se usará solo para esta postulación.",
        "no_saved_curriculum" => "Aún no tienes un currículum guardado. Sube uno para esta postulación.",
        "select_cv" => "Selecciona un archivo PDF, DOC o DOCX",
        "max_5mb" => "Máximo 5 MB",
        "agree_share" => "Acepto que SkillBridge comparta mi información con %s para esta postulación.",
        "submit_application" => "Enviar postulación",
        "submitted_label" => "Postulación enviada",
        "great_decision" => "¡Excelente decisión!",
        "success_text" => "Tu postulación para el puesto de %s fue enviada correctamente a %s.",
        "email_updates" => "Recibirás actualizaciones del proceso por correo electrónico.",
        "got_it" => "Entendido",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_newsletter" => "Boletín",
        "footer_create_profile" => "Crear perfil",
        "footer_resources" => "Recursos y consejos",
        "footer_find_talent" => "Encontrar talento",
        "footer_business_plans" => "Planes empresariales",
        "footer_contact" => "Contactar al equipo",
        "footer_newsletter_text" => "Recibe nuevas vacantes y consejos profesionales.",
        "footer_email_placeholder" => "Tu correo electrónico",
        "footer_subscribe" => "Suscribirse",
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

    return "detalle-empleo.php?" . http_build_query($params);
}

function campoIdioma($registro, $campoBase) {
    global $idiomaActual;

    $campoPreferido = $campoBase . "_" . $idiomaActual;
    $campoAlternativo = $campoBase . "_" . ($idiomaActual === "es" ? "en" : "es");

    if (!empty($registro[$campoPreferido])) {
        return $registro[$campoPreferido];
    }

    if (!empty($registro[$campoAlternativo])) {
        return $registro[$campoAlternativo];
    }

    return $registro[$campoBase] ?? "";
}

function salarioTexto($salario) {
    if ($salario === null || $salario === "" || $salario == 0) {
        return t("salary_not_specified");
    }

    return "$" . number_format((float)$salario, 2) . " / " . t("salary_per_month");
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

function fechaPublicacionTexto($fecha) {
    if (empty($fecha)) {
        return t("recently_posted");
    }

    return fechaTexto($fecha);
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

function etiquetaNivel($nivel) {
    $nivelNormalizado = normalizarNivel($nivel);

    $mapa = [
        "no experience" => t("no_experience"),
        "junior" => t("junior"),
        "mid-level" => t("mid_level"),
        "senior" => t("senior")
    ];

    return $mapa[$nivelNormalizado] ?? ($nivel !== "" ? $nivel : t("not_specified"));
}

function etiquetaCategoria($categoria) {
    $categoriaNormalizada = normalizarCategoria($categoria);

    $mapa = [
        "technology" => t("technology"),
        "design" => t("design"),
        "administration" => t("administration"),
        "sales" => t("sales"),
        "customer service" => t("customer_service"),
        "human resources" => t("human_resources")
    ];

    return $mapa[$categoriaNormalizada] ?? ($categoria !== "" ? $categoria : t("general"));
}

function guardarCurriculumAplicacion($archivo, $idUsuario, $idVacante, &$mensajePostulacion, &$clasePostulacion) {
    if (!isset($archivo) || $archivo["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        $mensajePostulacion = t("upload_cv_error");
        $clasePostulacion = "error";
        return null;
    }

    $nombreOriginal = $archivo["name"];
    $archivoTemporal = $archivo["tmp_name"];
    $tamanoArchivo = $archivo["size"];

    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    $extensionesPermitidas = ["pdf", "doc", "docx"];

    if (!in_array($extension, $extensionesPermitidas, true)) {
        $mensajePostulacion = t("cv_file_type_error");
        $clasePostulacion = "error";
        return null;
    }

    if ($tamanoArchivo > 5 * 1024 * 1024) {
        $mensajePostulacion = t("cv_file_size_error");
        $clasePostulacion = "error";
        return null;
    }

    $carpetaCV = "cv/";

    if (!is_dir($carpetaCV)) {
        mkdir($carpetaCV, 0777, true);
    }

    $nombreArchivo = "cv_" . $idUsuario . "_" . $idVacante . "_" . time() . "." . $extension;
    $rutaDestino = $carpetaCV . $nombreArchivo;

    if (!move_uploaded_file($archivoTemporal, $rutaDestino)) {
        $mensajePostulacion = t("cv_save_error");
        $clasePostulacion = "error";
        return null;
    }

    return $rutaDestino;
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

$idVacante = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($idVacante <= 0) {
    header("Location: " . ($esEmpresa ? "perfil.php" : "empleos.php"));
    exit;
}

$vacante = null;
$similares = [];
$mensajePostulacion = "";
$clasePostulacion = "";
$yaAplicado = false;
$estadoAplicacionActual = "";

try {
    $sql = "
        SELECT
            vacantes.id_vacante,
            vacantes.id_empresa,
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
            empresas.lema AS empresa_lema,
            empresas.descripcion AS empresa_descripcion,
            empresas.ubicacion AS empresa_ubicacion,
            empresas.anio_fundacion,
            empresas.colaboradores,
            empresas.logo AS empresa_logo
        FROM vacantes
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        WHERE vacantes.id_vacante = :id_vacante
          AND vacantes.estado = 'activa'
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_vacante" => $idVacante
    ]);

    $vacante = $stmt->fetch();

    if (!$vacante) {
        header("Location: " . ($esEmpresa ? "perfil.php" : "empleos.php"));
        exit;
    }

    $sqlSimilares = "
        SELECT
            vacantes.id_vacante,
            vacantes.titulo,
            vacantes.titulo_en,
            vacantes.titulo_es,
            vacantes.modalidad,
            vacantes.ubicacion,
            vacantes.salario,
            empresas.nombre AS empresa_nombre
        FROM vacantes
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        WHERE vacantes.estado = 'activa'
          AND vacantes.id_vacante <> :id_vacante
          AND vacantes.categoria = :categoria
        ORDER BY vacantes.fecha_publicacion DESC
        LIMIT 3
    ";

    $stmtSimilares = $pdo->prepare($sqlSimilares);
    $stmtSimilares->execute([
        ":id_vacante" => $idVacante,
        ":categoria" => $vacante["categoria"]
    ]);

    $similares = $stmtSimilares->fetchAll();

    if ($usuarioLogueado && ($esCandidato || $esAdmin)) {
        $sqlAplicacionActual = "
            SELECT id_postulacion, estado
            FROM postulaciones
            WHERE id_usuario = :id_usuario
              AND id_vacante = :id_vacante
            LIMIT 1
        ";

        $stmtAplicacionActual = $pdo->prepare($sqlAplicacionActual);
        $stmtAplicacionActual->execute([
            ":id_usuario" => $idUsuario,
            ":id_vacante" => $idVacante
        ]);

        $aplicacionActual = $stmtAplicacionActual->fetch();

        if ($aplicacionActual) {
            $yaAplicado = true;
            $estadoAplicacionActual = $aplicacionActual["estado"] ?? "pendiente";
        }
    }
} catch (PDOException $e) {
    exit(t("detail_load_error"));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "aplicar_vacante") {
    if (!$usuarioLogueado) {
        header("Location: login.php?redirect=" . urlencode("detalle-empleo.php?id=" . $idVacante . "&apply=1&lang=" . $idiomaActual));
        exit;
    }

    if ($esEmpresa) {
        $mensajePostulacion = t("company_account_cannot_apply");
        $clasePostulacion = "error";
    } elseif (!$esCandidato && !$esAdmin) {
        $mensajePostulacion = t("company_account_cannot_apply");
        $clasePostulacion = "error";
    } else {
        $mensajeAplicacion = trim($_POST["mensaje"] ?? "");
        $usarCurriculumGuardado = ($_POST["usar_curriculum_guardado"] ?? "") === "1";
        $cvArchivoPostulacion = null;

        if ($mensajeAplicacion === "") {
            $mensajePostulacion = t("write_message");
            $clasePostulacion = "error";
        } else {
            try {
                $sqlExiste = "
                    SELECT id_postulacion
                    FROM postulaciones
                    WHERE id_usuario = :id_usuario
                      AND id_vacante = :id_vacante
                    LIMIT 1
                ";

                $stmtExiste = $pdo->prepare($sqlExiste);
                $stmtExiste->execute([
                    ":id_usuario" => $idUsuario,
                    ":id_vacante" => $idVacante
                ]);

                $postulacionExistentePost = $stmtExiste->fetch();

                if ($postulacionExistentePost) {
                    $mensajePostulacion = t("already_applied");
                    $clasePostulacion = "error";
                    $yaAplicado = true;
                } else {
                    $hayArchivoNuevo = isset($_FILES["cv_archivo"])
                        && $_FILES["cv_archivo"]["error"] !== UPLOAD_ERR_NO_FILE;

                    if ($hayArchivoNuevo) {
                        $cvArchivoPostulacion = guardarCurriculumAplicacion(
                            $_FILES["cv_archivo"],
                            $idUsuario,
                            $idVacante,
                            $mensajePostulacion,
                            $clasePostulacion
                        );
                    } elseif ($usarCurriculumGuardado) {
                        $curriculumGuardado = obtenerCurriculumGuardado($pdo, $idUsuario);

                        if (!empty($curriculumGuardado) && file_exists($curriculumGuardado)) {
                            $cvArchivoPostulacion = $curriculumGuardado;
                        }
                    }

                    if ($clasePostulacion !== "error") {
                        $sqlInsertar = "
                            INSERT INTO postulaciones (
                                id_usuario,
                                id_vacante,
                                mensaje,
                                cv_archivo,
                                estado
                            ) VALUES (
                                :id_usuario,
                                :id_vacante,
                                :mensaje,
                                :cv_archivo,
                                'pendiente'
                            )
                        ";

                        $stmtInsertar = $pdo->prepare($sqlInsertar);
                        $stmtInsertar->execute([
                            ":id_usuario" => $idUsuario,
                            ":id_vacante" => $idVacante,
                            ":mensaje" => $mensajeAplicacion,
                            ":cv_archivo" => $cvArchivoPostulacion
                        ]);

                        header("Location: detalle-empleo.php?id=" . $idVacante . "&applied=1&lang=" . urlencode($idiomaActual));
                        exit;
                    }
                }
            } catch (PDOException $e) {
                $mensajePostulacion = t("application_error");
                $clasePostulacion = "error";
            }
        }
    }
}

if (isset($_GET["applied"]) && $_GET["applied"] === "1") {
    $mensajePostulacion = t("application_success");
    $clasePostulacion = "success";
    $yaAplicado = true;
}

$titulo = campoIdioma($vacante, "titulo");
$empresa = $vacante["empresa_nombre"] ?? t("company");
$empresaLema = $vacante["empresa_lema"] ?? "";
$empresaDescripcion = $vacante["empresa_descripcion"] ?? "";
$empresaUbicacion = $vacante["empresa_ubicacion"] ?? "";
$categoria = $vacante["categoria"] ?? "";
$modalidad = $vacante["modalidad"] ?? "";
$modalidadCss = modalidadClase($modalidad);
$ubicacion = $vacante["ubicacion"] ?? "";
$tipoEmpleo = $vacante["tipo_empleo"] ?? "";
$nivelExperiencia = $vacante["nivel_experiencia"] ?? "";
$salario = $vacante["salario"] ?? null;
$descripcion = campoIdioma($vacante, "descripcion");
$responsabilidades = dividirTexto(campoIdioma($vacante, "responsabilidades"));
$requisitos = dividirTexto(campoIdioma($vacante, "requisitos"));
$habilidades = dividirTexto(campoIdioma($vacante, "habilidades"));
$fechaPublicacion = $vacante["fecha_publicacion"] ?? "";
$fechaLimite = $vacante["fecha_limite"] ?? "";
$inclusiva = (int)($vacante["inclusiva"] ?? 0);
$anioFundacion = $vacante["anio_fundacion"] ?? "2026";
$colaboradores = $vacante["colaboradores"] ?? "";

if ($titulo === "") {
    $titulo = t("job_opening");
}

if ($empresaLema === "") {
    $empresaLema = t("company_default_slogan");
}

if ($empresaDescripcion === "") {
    $empresaDescripcion = t("company_default_description");
}

if ($empresaUbicacion === "") {
    $empresaUbicacion = t("not_specified");
}

if ($descripcion === "") {
    $descripcion = t("no_description");
}

if ($colaboradores === "") {
    $colaboradores = t("growing_team");
}

$modalidadEtiqueta = etiquetaModalidad($modalidad);
$tipoEmpleoEtiqueta = etiquetaTipoEmpleo($tipoEmpleo);
$nivelExperienciaEtiqueta = etiquetaNivel($nivelExperiencia);
$categoriaEtiqueta = etiquetaCategoria($categoria);

$nombreAplicante = $usuarioActual["nombre"] ?? "";
$correoAplicante = $usuarioActual["correo"] ?? "";
$telefonoAplicante = $usuarioActual["telefono"] ?? "";
$curriculumAplicante = $usuarioActual["curriculum"] ?? "";
$curriculumAplicanteExiste = !empty($curriculumAplicante) && file_exists($curriculumAplicante);

$puedeAplicar = $usuarioLogueado && ($esCandidato || $esAdmin) && !$yaAplicado;
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
    <title><?php echo limpiar($titulo); ?> | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description" content="<?php echo limpiar(t("meta_description")); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/detalle-empleo.css?v=20260904footerfinal">
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

    <aside class="accessibility-panel" id="accessibilityPanel" aria-label="<?php echo limpiar(t("accessibility_title")); ?>">
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
                        <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>" class="active">
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

    <main id="mainContent">

        <section class="detail-hero-section">

            <div class="detail-hero-circle detail-hero-circle-one"></div>
            <div class="detail-hero-circle detail-hero-circle-two"></div>

            <div class="container">

                <div class="breadcrumb">
                    <a href="index.php"><?php echo limpiar(t("breadcrumb_home")); ?></a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>

                    <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>">
                        <?php echo limpiar(t("breadcrumb_jobs")); ?>
                    </a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>

                    <span><?php echo limpiar($titulo); ?></span>
                </div>

                <div class="detail-hero-card">

                    <div class="detail-company-logo">
                        <i class="fa-solid fa-briefcase"
                            role="img"
                            aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                            title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                    </div>

                    <div class="detail-hero-main">

                        <div class="detail-hero-labels">
                            <span class="job-type <?php echo limpiar($modalidadCss); ?>">
                                <?php echo limpiar($modalidadEtiqueta); ?>
                            </span>

                            <?php if ($inclusiva === 1): ?>
                                <span class="detail-inclusive-label">
                                    <i class="fa-solid fa-universal-access"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                        title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                                    <?php echo limpiar(t("accessible_company")); ?>
                                </span>
                            <?php endif; ?>

                            <span class="verified-label">
                                <i class="fa-solid fa-circle-check"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                    title="<?php echo limpiar(t("check_icon")); ?>"></i>
                                <?php echo limpiar(t("verified_job")); ?>
                            </span>
                        </div>

                        <h1><?php echo limpiar($titulo); ?></h1>

                        <p class="detail-company-name">
                            <?php echo limpiar($empresa); ?>
                        </p>

                        <div class="detail-hero-info">
                            <span>
                                <i class="fa-solid fa-location-dot"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                    title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                <?php echo limpiar($ubicacion ?: t("not_specified")); ?>
                            </span>

                            <span>
                                <i class="fa-solid fa-clock"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("clock_icon")); ?>"
                                    title="<?php echo limpiar(t("clock_icon")); ?>"></i>
                                <?php echo limpiar($tipoEmpleoEtiqueta); ?>
                            </span>

                            <span>
                                <i class="fa-regular fa-calendar"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("calendar_icon")); ?>"
                                    title="<?php echo limpiar(t("calendar_icon")); ?>"></i>
                                <?php echo limpiar(t("posted")); ?> <?php echo limpiar(fechaPublicacionTexto($fechaPublicacion)); ?>
                            </span>
                        </div>

                    </div>

                    <div class="detail-hero-actions">
                        <button class="save-job-button detail-save-button" id="detailSaveButton"
                            data-job="<?php echo limpiar($titulo); ?>"
                            aria-label="<?php echo limpiar(t("save_job")); ?>">
                            <i class="fa-regular fa-bookmark"
                                role="img"
                                aria-label="<?php echo limpiar(t("bookmark_icon")); ?>"
                                title="<?php echo limpiar(t("bookmark_icon")); ?>"></i>
                        </button>

                        <button class="detail-share-button" id="shareJobButton"
                            aria-label="<?php echo limpiar(t("share_job")); ?>">
                            <i class="fa-solid fa-share-nodes"
                                role="img"
                                aria-label="<?php echo limpiar(t("share_icon")); ?>"
                                title="<?php echo limpiar(t("share_icon")); ?>"></i>
                            <span><?php echo limpiar(t("share_job")); ?></span>
                        </button>
                    </div>

                </div>

            </div>
        </section>

        <section class="detail-content-section">
            <div class="container detail-layout">

                <section class="detail-main-content">

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-blue">
                                <i class="fa-solid fa-briefcase"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                    title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("about_position_label")); ?></span>
                                <h2><?php echo limpiar(t("job_description")); ?></h2>
                            </div>
                        </div>

                        <p>
                            <?php echo nl2br(limpiar($descripcion)); ?>
                        </p>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-purple">
                                <i class="fa-solid fa-list-check"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("list_icon")); ?>"
                                    title="<?php echo limpiar(t("list_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("responsibilities_label")); ?></span>
                                <h2><?php echo limpiar(t("what_you_will_do")); ?></h2>
                            </div>
                        </div>

                        <ul class="detail-list">
                            <?php if (!empty($responsabilidades)): ?>
                                <?php foreach ($responsabilidades as $responsabilidad): ?>
                                    <li>
                                        <i class="fa-solid fa-check"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                            title="<?php echo limpiar(t("check_icon")); ?>"></i>
                                        <?php echo limpiar($responsabilidad); ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>
                                    <i class="fa-solid fa-check"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                        title="<?php echo limpiar(t("check_icon")); ?>"></i>
                                    <?php echo limpiar(t("responsibilities_default")); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-green">
                                <i class="fa-solid fa-user-check"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("user_check_icon")); ?>"
                                    title="<?php echo limpiar(t("user_check_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("ideal_profile_label")); ?></span>
                                <h2><?php echo limpiar(t("application_requirements")); ?></h2>
                            </div>
                        </div>

                        <div class="requirements-grid">
                            <?php if (!empty($requisitos)): ?>
                                <?php foreach ($requisitos as $index => $requisito): ?>
                                    <div class="requirement-item">
                                        <div class="requirement-number">
                                            <?php echo str_pad($index + 1, 2, "0", STR_PAD_LEFT); ?>
                                        </div>

                                        <div>
                                            <h3><?php echo limpiar(t("requirement")); ?></h3>
                                            <p><?php echo limpiar($requisito); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="requirement-item">
                                    <div class="requirement-number">01</div>

                                    <div>
                                        <h3><?php echo limpiar(t("general_profile")); ?></h3>
                                        <p><?php echo limpiar(t("general_profile_text")); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-orange">
                                <i class="fa-solid fa-star"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("star_icon")); ?>"
                                    title="<?php echo limpiar(t("star_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("additional_value_label")); ?></span>
                                <h2><?php echo limpiar(t("skills_related")); ?></h2>
                            </div>
                        </div>

                        <div class="skills-cloud">
                            <?php if (!empty($habilidades)): ?>
                                <?php foreach ($habilidades as $habilidad): ?>
                                    <span><?php echo limpiar($habilidad); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span><?php echo limpiar(t("responsibility")); ?></span>
                                <span><?php echo limpiar(t("communication")); ?></span>
                                <span><?php echo limpiar(t("teamwork")); ?></span>
                                <span><?php echo limpiar(t("willingness")); ?></span>
                            <?php endif; ?>
                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-pink">
                                <i class="fa-solid fa-gift"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("gift_icon")); ?>"
                                    title="<?php echo limpiar(t("gift_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("benefits_label")); ?></span>
                                <h2><?php echo limpiar(sprintf(t("what_company_offers"), $empresa)); ?></h2>
                            </div>
                        </div>

                        <div class="benefits-grid">

                            <div class="benefit-item">
                                <i class="fa-solid fa-handshake-angle"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("handshake_icon")); ?>"
                                    title="<?php echo limpiar(t("handshake_icon")); ?>"></i>
                                <h3><?php echo limpiar(t("inclusive_environment")); ?></h3>
                                <p><?php echo limpiar(t("inclusive_environment_text")); ?></p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-briefcase"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                    title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                                <h3><?php echo limpiar(t("professional_opportunity")); ?></h3>
                                <p><?php echo limpiar(t("professional_opportunity_text")); ?></p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-chart-line"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("chart_icon")); ?>"
                                    title="<?php echo limpiar(t("chart_icon")); ?>"></i>
                                <h3><?php echo limpiar(t("growth")); ?></h3>
                                <p><?php echo limpiar(t("growth_text")); ?></p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-people-group"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("team_icon")); ?>"
                                    title="<?php echo limpiar(t("team_icon")); ?>"></i>
                                <h3><?php echo limpiar(t("teamwork_title")); ?></h3>
                                <p><?php echo limpiar(t("teamwork_text")); ?></p>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-blue">
                                <i class="fa-solid fa-route"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("route_icon")); ?>"
                                    title="<?php echo limpiar(t("route_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("selection_process_label")); ?></span>
                                <h2><?php echo limpiar(t("after_apply_title")); ?></h2>
                            </div>
                        </div>

                        <div class="selection-process">

                            <div class="selection-step">
                                <div class="selection-step-number">1</div>
                                <div>
                                    <h3><?php echo limpiar(t("profile_review")); ?></h3>
                                    <p><?php echo limpiar(t("profile_review_text")); ?></p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">2</div>
                                <div>
                                    <h3><?php echo limpiar(t("initial_contact")); ?></h3>
                                    <p><?php echo limpiar(t("initial_contact_text")); ?></p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">3</div>
                                <div>
                                    <h3><?php echo limpiar(t("interview")); ?></h3>
                                    <p><?php echo limpiar(t("interview_text")); ?></p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">4</div>
                                <div>
                                    <h3><?php echo limpiar(t("final_decision")); ?></h3>
                                    <p><?php echo limpiar(t("final_decision_text")); ?></p>
                                </div>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card company-profile-card">
                        <div class="company-profile-header">

                            <div class="company-profile-logo">
                                <i class="fa-solid fa-building"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("building_icon")); ?>"
                                    title="<?php echo limpiar(t("building_icon")); ?>"></i>
                            </div>

                            <div>
                                <span class="detail-small-label"><?php echo limpiar(t("about_company_label")); ?></span>
                                <h2><?php echo limpiar($empresa); ?></h2>
                                <p><?php echo limpiar($empresaLema); ?></p>
                            </div>

                        </div>

                        <p class="company-profile-description">
                            <?php echo nl2br(limpiar($empresaDescripcion)); ?>
                        </p>

                        <div class="company-profile-stats">

                            <div>
                                <strong><?php echo limpiar($anioFundacion ?: "2026"); ?></strong>
                                <span><?php echo limpiar(t("year_founded")); ?></span>
                            </div>

                            <div>
                                <strong><?php echo limpiar($colaboradores); ?></strong>
                                <span><?php echo limpiar(t("team_members")); ?></span>
                            </div>

                            <div>
                                <strong><?php echo limpiar($empresaUbicacion); ?></strong>
                                <span><?php echo limpiar(t("location")); ?></span>
                            </div>

                        </div>

                        <a href="empresas.php" class="text-link">
                            <?php echo limpiar(t("explore_companies")); ?>
                            <i class="fa-solid fa-arrow-right"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </a>
                    </article>

                </section>

                <aside class="detail-sidebar">

                    <div class="apply-card">

                        <div class="apply-card-top">
                            <span class="apply-card-label"><?php echo limpiar(t("estimated_salary")); ?></span>

                            <h2><?php echo limpiar(salarioTexto($salario)); ?></h2>

                            <p>
                                <?php echo limpiar(t("salary_note")); ?>
                            </p>
                        </div>

                        <div class="apply-card-info">

                            <div>
                                <i class="fa-solid fa-briefcase"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                    title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>

                                <span>
                                    <strong><?php echo limpiar(t("employment_type")); ?></strong>
                                    <?php echo limpiar($tipoEmpleoEtiqueta); ?>
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-house-laptop"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("work_arrangement")); ?>"
                                    title="<?php echo limpiar(t("work_arrangement")); ?>"></i>

                                <span>
                                    <strong><?php echo limpiar(t("work_arrangement")); ?></strong>
                                    <?php echo limpiar($modalidadEtiqueta); ?>
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-layer-group"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("level")); ?>"
                                    title="<?php echo limpiar(t("level")); ?>"></i>

                                <span>
                                    <strong><?php echo limpiar(t("level")); ?></strong>
                                    <?php echo limpiar($nivelExperienciaEtiqueta); ?>
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-calendar-days"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("calendar_icon")); ?>"
                                    title="<?php echo limpiar(t("calendar_icon")); ?>"></i>

                                <span>
                                    <strong><?php echo limpiar(t("application_deadline")); ?></strong>
                                    <?php echo limpiar(fechaTexto($fechaLimite)); ?>
                                </span>
                            </div>

                        </div>

                        <?php if (!$usuarioLogueado): ?>
                            <a href="login.php?redirect=<?php echo urlencode('detalle-empleo.php?id=' . $idVacante . '&apply=1&lang=' . $idiomaActual); ?>"
                                class="button button-primary detail-apply-button">
                                <?php echo limpiar(t("login_to_apply")); ?>
                                <i class="fa-solid fa-arrow-right-to-bracket"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("login_to_apply")); ?>"
                                    title="<?php echo limpiar(t("login_to_apply")); ?>"></i>
                            </a>
                        <?php elseif (($esCandidato || $esAdmin) && $yaAplicado): ?>
                            <button class="button button-secondary detail-apply-button" disabled>
                                <?php echo limpiar(t("application_sent")); ?>
                                <i class="fa-solid fa-circle-check"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                    title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            </button>
                        <?php elseif ($esCandidato || $esAdmin): ?>
                            <button class="button button-primary detail-apply-button open-application-modal">
                                <?php echo limpiar(t("apply_now")); ?>
                                <i class="fa-solid fa-paper-plane"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("send_icon")); ?>"
                                    title="<?php echo limpiar(t("send_icon")); ?>"></i>
                            </button>
                        <?php else: ?>
                            <button class="button button-secondary detail-apply-button" disabled>
                                <?php echo limpiar(t("company_account")); ?>
                            </button>
                        <?php endif; ?>

                        <p class="apply-card-note">
                            <i class="fa-solid fa-shield-heart"
                                role="img"
                                aria-label="<?php echo limpiar(t("shield_icon")); ?>"
                                title="<?php echo limpiar(t("shield_icon")); ?>"></i>
                            <?php echo limpiar(t("application_privacy")); ?>
                        </p>

                    </div>

                    <div class="accessibility-company-card">

                        <div class="accessibility-company-icon">
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </div>

                        <div>
                            <h3><?php echo limpiar(t("commitment_title")); ?></h3>
                            <p><?php echo limpiar(t("commitment_text")); ?></p>
                        </div>

                    </div>

                    <div class="sidebar-share-card">

                        <h3><?php echo limpiar(t("share_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("share_text")); ?>
                        </p>

                        <div class="share-social-buttons">

                            <button class="share-social-button facebook-share" aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("facebook_icon")); ?>"
                                    title="<?php echo limpiar(t("facebook_icon")); ?>"></i>
                            </button>

                            <button class="share-social-button linkedin-share" aria-label="LinkedIn">
                                <i class="fa-brands fa-linkedin-in"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("linkedin_icon")); ?>"
                                    title="<?php echo limpiar(t("linkedin_icon")); ?>"></i>
                            </button>

                            <button class="share-social-button whatsapp-share" aria-label="WhatsApp">
                                <i class="fa-brands fa-whatsapp"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("whatsapp_icon")); ?>"
                                    title="<?php echo limpiar(t("whatsapp_icon")); ?>"></i>
                            </button>

                            <button class="share-social-button copy-link-button"
                                id="copyLinkButton" aria-label="<?php echo limpiar(t("copy_link")); ?>">
                                <i class="fa-solid fa-link"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("link_icon")); ?>"
                                    title="<?php echo limpiar(t("link_icon")); ?>"></i>
                            </button>

                        </div>

                        <small id="copyLinkMessage"></small>

                    </div>

                </aside>

            </div>
        </section>

        <?php if (!$esEmpresa): ?>
            <section class="similar-jobs-section">
                <div class="container">

                    <div class="section-heading">
                        <div>
                            <span class="section-label"><?php echo limpiar(t("similar_label")); ?></span>

                            <h2><?php echo limpiar(t("similar_title")); ?></h2>

                            <p>
                                <?php echo limpiar(t("similar_text")); ?>
                            </p>
                        </div>

                        <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>" class="text-link">
                            <?php echo limpiar(t("view_all_jobs")); ?>
                            <i class="fa-solid fa-arrow-right"
                                role="img"
                                aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                        </a>
                    </div>

                    <div class="similar-jobs-grid">

                        <?php if (!empty($similares)): ?>
                            <?php foreach ($similares as $similar): ?>
                                <?php
                                    $similarTitulo = campoIdioma($similar, "titulo");
                                    if ($similarTitulo === "") {
                                        $similarTitulo = t("job_opening");
                                    }
                                    $similarModalidad = $similar["modalidad"] ?? "";
                                    $similarModalidadEtiqueta = etiquetaModalidad($similarModalidad);
                                ?>
                                <article class="similar-job-card">

                                    <div class="similar-job-top">
                                        <div class="company-logo company-blue">
                                            <i class="fa-solid fa-briefcase"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                                title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                                        </div>

                                        <span class="job-type <?php echo limpiar(modalidadClase($similarModalidad)); ?>">
                                            <?php echo limpiar($similarModalidadEtiqueta); ?>
                                        </span>
                                    </div>

                                    <h3><?php echo limpiar($similarTitulo); ?></h3>
                                    <p><?php echo limpiar($similar["empresa_nombre"] ?? t("company")); ?></p>

                                    <div class="similar-job-info">
                                        <span>
                                            <i class="fa-solid fa-location-dot"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                                title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                            <?php echo limpiar($similar["ubicacion"] ?? t("not_specified")); ?>
                                        </span>

                                        <span>
                                            <i class="fa-solid fa-dollar-sign"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("salary_icon")); ?>"
                                                title="<?php echo limpiar(t("salary_icon")); ?>"></i>
                                            <?php echo limpiar(salarioTexto($similar["salario"] ?? null)); ?>
                                        </span>
                                    </div>

                                    <a href="detalle-empleo.php?id=<?php echo (int)$similar["id_vacante"]; ?>&lang=<?php echo limpiar($idiomaActual); ?>" class="similar-job-link">
                                        <?php echo limpiar(t("view_opportunity")); ?>
                                        <i class="fa-solid fa-arrow-right"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                            title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                                    </a>

                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <article class="similar-job-card">
                                <div class="similar-job-top">
                                    <div class="company-logo company-blue">
                                        <i class="fa-solid fa-magnifying-glass"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("search_icon")); ?>"
                                            title="<?php echo limpiar(t("search_icon")); ?>"></i>
                                    </div>

                                    <span class="job-type remote"><?php echo limpiar(t("more_jobs")); ?></span>
                                </div>

                                <h3><?php echo limpiar(t("explore_all")); ?></h3>
                                <p>SkillBridge</p>

                                <div class="similar-job-info">
                                    <span>
                                        <i class="fa-solid fa-briefcase"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                            title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                                        <?php echo limpiar(t("job_board")); ?>
                                    </span>
                                </div>

                                <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>" class="similar-job-link">
                                    <?php echo limpiar(t("view_all_jobs")); ?>
                                    <i class="fa-solid fa-arrow-right"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                                </a>
                            </article>
                        <?php endif; ?>

                    </div>

                </div>
            </section>
        <?php endif; ?>

        <section class="detail-cta-section">
            <div class="container detail-cta-content">

                <div>
                    <span class="section-label cta-label">
                        <?php echo limpiar(t("cta_label")); ?>
                    </span>

                    <h2><?php echo limpiar(t("cta_title")); ?></h2>

                    <p>
                        <?php echo limpiar(t("cta_text")); ?>
                    </p>
                </div>

                <?php if (!$usuarioLogueado): ?>
                    <a href="login.php?redirect=<?php echo urlencode('detalle-empleo.php?id=' . $idVacante . '&apply=1&lang=' . $idiomaActual); ?>"
                        class="button button-light">
                        <?php echo limpiar(t("login_to_apply")); ?>
                        <i class="fa-solid fa-arrow-right-to-bracket"
                            role="img"
                            aria-label="<?php echo limpiar(t("login_to_apply")); ?>"
                            title="<?php echo limpiar(t("login_to_apply")); ?>"></i>
                    </a>
                <?php elseif (($esCandidato || $esAdmin) && $yaAplicado): ?>
                    <button class="button button-light" disabled>
                        <?php echo limpiar(t("application_sent")); ?>
                        <i class="fa-solid fa-circle-check"
                            role="img"
                            aria-label="<?php echo limpiar(t("check_icon")); ?>"
                            title="<?php echo limpiar(t("check_icon")); ?>"></i>
                    </button>
                <?php elseif ($esCandidato || $esAdmin): ?>
                    <button class="button button-light open-application-modal">
                        <?php echo limpiar(t("apply_now")); ?>
                        <i class="fa-solid fa-paper-plane"
                            role="img"
                            aria-label="<?php echo limpiar(t("send_icon")); ?>"
                            title="<?php echo limpiar(t("send_icon")); ?>"></i>
                    </button>
                <?php else: ?>
                    <a href="publicarvacante.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-light">
                        <?php echo limpiar(t("post_another_job")); ?>
                        <i class="fa-solid fa-plus"
                            role="img"
                            aria-label="<?php echo limpiar(t("plus_icon")); ?>"
                            title="<?php echo limpiar(t("plus_icon")); ?>"></i>
                    </a>
                <?php endif; ?>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<?php if ($puedeAplicar): ?>
        <div class="application-modal hidden" id="applicationModal"
            role="dialog" aria-modal="true" aria-labelledby="applicationModalTitle">

            <div class="application-modal-overlay" id="applicationModalOverlay"></div>

            <div class="application-modal-content">

                <button class="application-modal-close" id="closeApplicationModal"
                    aria-label="<?php echo limpiar(t("close_application_form")); ?>">
                    <i class="fa-solid fa-xmark"
                        role="img"
                        aria-label="<?php echo limpiar(t("close_icon")); ?>"
                        title="<?php echo limpiar(t("close_icon")); ?>"></i>
                </button>

                <div id="applicationFormContainer">

                    <div class="application-modal-icon">
                        <i class="fa-solid fa-paper-plane"
                            role="img"
                            aria-label="<?php echo limpiar(t("send_icon")); ?>"
                            title="<?php echo limpiar(t("send_icon")); ?>"></i>
                    </div>

                    <span class="modal-label"><?php echo limpiar(t("quick_application")); ?></span>

                    <h2 id="applicationModalTitle">
                        <?php echo limpiar(sprintf(t("apply_for_position"), $titulo)); ?>
                    </h2>

                    <p class="application-modal-description">
                        <?php echo limpiar(sprintf(t("application_modal_text"), $empresa)); ?>
                    </p>

                    <?php if (!empty($mensajePostulacion)): ?>
                        <div class="auth-alert <?php echo limpiar($clasePostulacion); ?>">
                            <?php if ($clasePostulacion === "success"): ?>
                                <i class="fa-solid fa-circle-check"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                    title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-triangle-exclamation"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                    title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                            <?php endif; ?>

                            <span><?php echo limpiar($mensajePostulacion); ?></span>
                        </div>
                    <?php endif; ?>

                    <form id="applicationForm" class="application-form" method="POST"
                        action="detalle-empleo.php?id=<?php echo $idVacante; ?>&lang=<?php echo limpiar($idiomaActual); ?>" enctype="multipart/form-data">

                        <input type="hidden" name="accion" value="aplicar_vacante">

                        <div class="application-form-row">

                            <div class="application-form-group">
                                <label for="applicantName"><?php echo limpiar(t("full_name")); ?></label>

                                <input type="text" id="applicantName" name="nombre_aplicante"
                                    value="<?php echo limpiar($nombreAplicante); ?>"
                                    placeholder="<?php echo limpiar(t("name_placeholder")); ?>" required>
                            </div>

                            <div class="application-form-group">
                                <label for="applicantEmail"><?php echo limpiar(t("email_address")); ?></label>

                                <input type="email" id="applicantEmail" name="correo_aplicante"
                                    value="<?php echo limpiar($correoAplicante); ?>"
                                    placeholder="email@example.com" required>
                            </div>

                        </div>

                        <div class="application-form-row">

                            <div class="application-form-group">
                                <label for="applicantPhone"><?php echo limpiar(t("phone_number")); ?></label>

                                <input type="tel" id="applicantPhone" name="telefono_aplicante"
                                    value="<?php echo limpiar($telefonoAplicante); ?>"
                                    placeholder="0000-0000" required>
                            </div>

                            <div class="application-form-group">
                                <label for="applicantExperience"><?php echo limpiar(t("experience")); ?></label>

                                <select id="applicantExperience" name="experiencia" required>
                                    <option value=""><?php echo limpiar(t("select_option")); ?></option>
                                    <option><?php echo limpiar(t("no_work_experience")); ?></option>
                                    <option><?php echo limpiar(t("less_one_year")); ?></option>
                                    <option><?php echo limpiar(t("one_two_years")); ?></option>
                                    <option><?php echo limpiar(t("more_two_years")); ?></option>
                                </select>
                            </div>

                        </div>

                        <div class="application-form-group">
                            <label for="applicantMessage">
                                <?php echo limpiar(t("interest_message")); ?>
                            </label>

                            <textarea id="applicantMessage" name="mensaje" rows="4"
                                placeholder="<?php echo limpiar(t("interest_placeholder")); ?>"
                                required></textarea>
                        </div>

                        <div class="application-form-group">
                            <label>
                                <?php echo limpiar(t("curriculum")); ?>
                            </label>

                            <?php if ($curriculumAplicanteExiste): ?>
                                <div class="saved-curriculum-card">

                                    <div class="saved-curriculum-left">
                                        <div class="saved-curriculum-icon">
                                            <i class="fa-solid fa-file-circle-check"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                                        </div>

                                        <div class="saved-curriculum-info">
                                            <h4><?php echo limpiar(t("saved_curriculum_found")); ?></h4>
                                            <p>
                                                <?php echo limpiar(t("use_saved_text")); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <label class="saved-curriculum-check" for="usarCurriculumGuardado">
                                        <input type="checkbox" id="usarCurriculumGuardado"
                                            name="usar_curriculum_guardado" value="1" checked>
                                        <?php echo limpiar(t("use_saved")); ?>
                                    </label>

                                </div>

                                <a href="<?php echo limpiar($curriculumAplicante); ?>" target="_blank" class="saved-curriculum-link">
                                    <?php echo limpiar(t("view_saved_curriculum")); ?>
                                    <i class="fa-solid fa-arrow-up-right-from-square"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("external_link_icon")); ?>"
                                        title="<?php echo limpiar(t("external_link_icon")); ?>"></i>
                                </a>

                                <p class="optional-cv-text">
                                    <?php echo limpiar(t("upload_different")); ?>
                                </p>
                            <?php else: ?>
                                <p class="optional-cv-text">
                                    <?php echo limpiar(t("no_saved_curriculum")); ?>
                                </p>
                            <?php endif; ?>

                            <label class="cv-upload-box curriculum-upload-clean" for="applicantCV">
                                <i class="fa-solid fa-file-arrow-up"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("curriculum")); ?>"
                                    title="<?php echo limpiar(t("curriculum")); ?>"></i>

                                <span id="cvFileText">
                                    <?php echo limpiar(t("select_cv")); ?>
                                </span>

                                <small><?php echo limpiar(t("max_5mb")); ?></small>
                            </label>

                            <input type="file" id="applicantCV" name="cv_archivo"
                                accept=".pdf,.doc,.docx" hidden>
                        </div>

                        <label class="application-checkbox">
                            <input type="checkbox" required>

                            <span class="application-custom-checkbox"></span>

                            <span>
                                <?php echo limpiar(sprintf(t("agree_share"), $empresa)); ?>
                            </span>
                        </label>

                        <button type="submit" class="button button-primary application-submit-button">
                            <?php echo limpiar(t("submit_application")); ?>
                            <i class="fa-solid fa-paper-plane"
                                role="img"
                                aria-label="<?php echo limpiar(t("send_icon")); ?>"
                                title="<?php echo limpiar(t("send_icon")); ?>"></i>
                        </button>

                    </form>

                </div>

                <div class="application-success hidden" id="applicationSuccess">

                    <div class="application-success-icon">
                        <i class="fa-solid fa-circle-check"
                            role="img"
                            aria-label="<?php echo limpiar(t("check_icon")); ?>"
                            title="<?php echo limpiar(t("check_icon")); ?>"></i>
                    </div>

                    <span class="modal-label"><?php echo limpiar(t("submitted_label")); ?></span>

                    <h2><?php echo limpiar(t("great_decision")); ?></h2>

                    <p>
                        <?php echo limpiar(sprintf(t("success_text"), $titulo, $empresa)); ?>
                    </p>

                    <div class="application-success-info">
                        <i class="fa-solid fa-envelope"
                            role="img"
                            aria-label="<?php echo limpiar(t("email_address")); ?>"
                            title="<?php echo limpiar(t("email_address")); ?>"></i>

                        <span>
                            <?php echo limpiar(t("email_updates")); ?>
                        </span>
                    </div>

                    <button class="button button-primary" id="closeSuccessButton">
                        <?php echo limpiar(t("got_it")); ?>
                        <i class="fa-solid fa-check"
                            role="img"
                            aria-label="<?php echo limpiar(t("check_icon")); ?>"
                            title="<?php echo limpiar(t("check_icon")); ?>"></i>
                    </button>

                </div>

            </div>
        </div>
    <?php endif; ?>

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
    <script src="java/detalle-empleo.js?v=20260904footerfinal"></script>
</body>

</html>
