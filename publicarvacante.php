<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION["id_usuario"];
$usuarioLogueado = true;
$paginaActual = basename($_SERVER["PHP_SELF"]);
$mensaje = "";
$claseMensaje = "";

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

function selectedOption($currentValue, $optionValue) {
    return $currentValue === $optionValue ? "selected" : "";
}

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

    if (!$usuarioActual) {
        session_unset();
        session_destroy();

        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    exit("There was an error loading your account information.");
}

$tipoUsuario = $usuarioActual["tipo_usuario"] ?? ($_SESSION["tipo_usuario"] ?? "");

if ($tipoUsuario !== "empresa" && $tipoUsuario !== "administrador") {
    header("Location: perfil.php");
    exit;
}

$_SESSION["tipo_usuario"] = $tipoUsuario;

$idiomasPermitidos = ["en", "es"];
$idiomaActual = $usuarioActual["idioma_preferido"] ?? ($_SESSION["idioma_preferido"] ?? "en");

if (!in_array($idiomaActual, $idiomasPermitidos, true)) {
    $idiomaActual = "en";
}

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
    $_SESSION["idioma_preferido"] = $idiomaActual;

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
        "page_title" => "Post a Job | SkillBridge",
        "meta_description" => "Post an inclusive job opening on SkillBridge and connect your company with qualified talent.",

        "nav_home" => "Home",
        "nav_find_jobs" => "Find jobs",
        "nav_companies" => "Companies",
        "nav_my_applications" => "My applications",
        "nav_post_job" => "Post a job",
        "nav_received_applications" => "Received applications",
        "nav_profile" => "Profile",
        "nav_my_profile" => "My profile",
        "nav_logout" => "Log out",

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
        "building_icon" => "Company icon",
        "briefcase_icon" => "Job icon",
        "info_icon" => "Information icon",
        "check_icon" => "Success icon",
        "warning_icon" => "Warning icon",
        "send_icon" => "Send icon",
        "chevron_icon" => "Navigation arrow icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "published_success" => "The job opening was published successfully.",
        "error_required" => "Please complete all required fields in both languages.",
        "error_salary" => "The salary cannot be negative.",
        "error_date" => "The application deadline cannot be in the past.",
        "error_publish" => "The job opening could not be published. Review the information and try again.",

        "breadcrumb_home" => "Home",
        "breadcrumb_companies" => "Companies",
        "breadcrumb_post_job" => "Post a job",

        "section_job_posting" => "Job posting",
        "hero_title" => "Post an inclusive job opportunity.",
        "hero_description" => "Complete the company and job opening information in English and Spanish so candidates can understand the opportunity in their preferred language.",

        "tip_title" => "Tip",
        "tip_text" => "Write clear descriptions and realistic requirements so candidates understand the opportunity.",
        "inclusive_title" => "Inclusive approach",
        "inclusive_text" => "Use respectful language and describe accessible conditions for different people.",
        "organized_title" => "Organized information",
        "organized_text" => "The information entered will be shown clearly in the job board and job detail page.",

        "details_label" => "Job opening details",
        "complete_form" => "Complete the form",
        "required_note" => "Fields marked with * are required.",

        "company_info" => "Company information",
        "company_name" => "Company name *",
        "company_name_placeholder" => "Example: Innovatech SV",
        "company_name_helper" => "This name is linked to your company account.",
        "company_slogan" => "Slogan or short description",
        "company_slogan_placeholder" => "Example: Technology with purpose",
        "company_location" => "Company location",
        "company_location_placeholder" => "Example: San Salvador, El Salvador",

        "general_info" => "General position information",
        "category" => "Category *",
        "select_category" => "Select a category",
        "work_arrangement" => "Work arrangement *",
        "select_work_arrangement" => "Select a work arrangement",
        "job_location" => "Job location *",
        "job_location_placeholder" => "Example: Remote from El Salvador",
        "employment_type" => "Employment type *",
        "select_type" => "Select a type",
        "experience_level" => "Experience level *",
        "select_level" => "Select a level",
        "monthly_salary" => "Monthly salary",
        "salary_placeholder" => "Example: 950",
        "deadline" => "Application deadline *",

        "english_version" => "English version",
        "spanish_version" => "Spanish version",

        "job_title_en" => "Job title in English *",
        "job_title_en_placeholder" => "Example: Junior Web Developer",
        "description_en" => "Job description in English *",
        "description_en_placeholder" => "Clearly describe the purpose of the position.",
        "responsibilities_en" => "Responsibilities in English",
        "responsibilities_en_placeholder" => "Example: Build web interfaces | Fix errors | Work with the team",
        "requirements_en" => "Requirements in English *",
        "requirements_en_placeholder" => "Example: Basic HTML knowledge | Responsibility | Willingness to learn",
        "skills_en" => "Skills in English",
        "skills_en_placeholder" => "Example: HTML | CSS | JavaScript | Git",

        "job_title_es" => "Job title in Spanish *",
        "job_title_es_placeholder" => "Example: Desarrollador Web Junior",
        "description_es" => "Job description in Spanish *",
        "description_es_placeholder" => "Describe claramente el objetivo del puesto.",
        "responsibilities_es" => "Responsibilities in Spanish",
        "responsibilities_es_placeholder" => "Ejemplo: Crear interfaces web | Corregir errores | Trabajar con el equipo",
        "requirements_es" => "Requirements in Spanish *",
        "requirements_es_placeholder" => "Ejemplo: Conocimientos básicos de HTML | Responsabilidad | Deseos de aprender",
        "skills_es" => "Skills in Spanish",
        "skills_es_placeholder" => "Ejemplo: HTML | CSS | JavaScript | Git",

        "post_button" => "Post a job",

        "cat_technology" => "Technology",
        "cat_design" => "Design",
        "cat_administration" => "Administration",
        "cat_sales" => "Sales",
        "cat_customer_service" => "Customer Service",
        "cat_human_resources" => "Human Resources",

        "mode_remote" => "Remote",
        "mode_hybrid" => "Hybrid",
        "mode_onsite" => "On-site",

        "type_full_time" => "Full-time",
        "type_part_time" => "Part-time",
        "type_internship" => "Internship",
        "type_temporary" => "Temporary",
        "type_freelance" => "Freelance",

        "level_no_experience" => "No experience",
        "level_junior" => "Junior",
        "level_mid" => "Mid-level",
        "level_senior" => "Senior",

        "footer_description" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_for_companies" => "For companies",
        "footer_post_job" => "Post a job",
        "footer_received_applications" => "Received applications",
        "footer_companies" => "Companies",
        "footer_contact" => "Contact the team",
        "footer_platform" => "Platform",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Publicar vacante | SkillBridge",
        "meta_description" => "Publica una vacante inclusiva en SkillBridge y conecta tu empresa con talento calificado.",

        "nav_home" => "Inicio",
        "nav_find_jobs" => "Buscar empleos",
        "nav_companies" => "Empresas",
        "nav_my_applications" => "Mis postulaciones",
        "nav_post_job" => "Publicar vacante",
        "nav_received_applications" => "Postulaciones recibidas",
        "nav_profile" => "Perfil",
        "nav_my_profile" => "Mi perfil",
        "nav_logout" => "Cerrar sesión",

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
        "building_icon" => "Ícono de empresa",
        "briefcase_icon" => "Ícono de empleo",
        "info_icon" => "Ícono de información",
        "check_icon" => "Ícono de éxito",
        "warning_icon" => "Ícono de advertencia",
        "send_icon" => "Ícono de enviar",
        "chevron_icon" => "Ícono de flecha de navegación",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "published_success" => "La vacante se publicó correctamente.",
        "error_required" => "Completa todos los campos obligatorios en ambos idiomas.",
        "error_salary" => "El salario no puede ser negativo.",
        "error_date" => "La fecha límite no puede estar en el pasado.",
        "error_publish" => "La vacante no pudo publicarse. Revisa la información e inténtalo nuevamente.",

        "breadcrumb_home" => "Inicio",
        "breadcrumb_companies" => "Empresas",
        "breadcrumb_post_job" => "Publicar vacante",

        "section_job_posting" => "Publicación de vacante",
        "hero_title" => "Publica una oportunidad laboral inclusiva.",
        "hero_description" => "Completa la información de la empresa y de la vacante en inglés y español para que los candidatos comprendan la oportunidad en su idioma preferido.",

        "tip_title" => "Consejo",
        "tip_text" => "Escribe descripciones claras y requisitos realistas para que los candidatos comprendan la oportunidad.",
        "inclusive_title" => "Enfoque inclusivo",
        "inclusive_text" => "Usa lenguaje respetuoso y describe condiciones accesibles para diferentes personas.",
        "organized_title" => "Información organizada",
        "organized_text" => "La información ingresada se mostrará claramente en la bolsa de empleos y en el detalle de la vacante.",

        "details_label" => "Detalles de la vacante",
        "complete_form" => "Completa el formulario",
        "required_note" => "Los campos marcados con * son obligatorios.",

        "company_info" => "Información de la empresa",
        "company_name" => "Nombre de la empresa *",
        "company_name_placeholder" => "Ejemplo: Innovatech SV",
        "company_name_helper" => "Este nombre está vinculado a tu cuenta de empresa.",
        "company_slogan" => "Lema o descripción corta",
        "company_slogan_placeholder" => "Ejemplo: Tecnología con propósito",
        "company_location" => "Ubicación de la empresa",
        "company_location_placeholder" => "Ejemplo: San Salvador, El Salvador",

        "general_info" => "Información general del puesto",
        "category" => "Categoría *",
        "select_category" => "Selecciona una categoría",
        "work_arrangement" => "Modalidad de trabajo *",
        "select_work_arrangement" => "Selecciona una modalidad",
        "job_location" => "Ubicación del empleo *",
        "job_location_placeholder" => "Ejemplo: Remoto desde El Salvador",
        "employment_type" => "Tipo de empleo *",
        "select_type" => "Selecciona un tipo",
        "experience_level" => "Nivel de experiencia *",
        "select_level" => "Selecciona un nivel",
        "monthly_salary" => "Salario mensual",
        "salary_placeholder" => "Ejemplo: 950",
        "deadline" => "Fecha límite de postulación *",

        "english_version" => "Versión en inglés",
        "spanish_version" => "Versión en español",

        "job_title_en" => "Título del empleo en inglés *",
        "job_title_en_placeholder" => "Ejemplo: Junior Web Developer",
        "description_en" => "Descripción del empleo en inglés *",
        "description_en_placeholder" => "Clearly describe the purpose of the position.",
        "responsibilities_en" => "Responsabilidades en inglés",
        "responsibilities_en_placeholder" => "Example: Build web interfaces | Fix errors | Work with the team",
        "requirements_en" => "Requisitos en inglés *",
        "requirements_en_placeholder" => "Example: Basic HTML knowledge | Responsibility | Willingness to learn",
        "skills_en" => "Habilidades en inglés",
        "skills_en_placeholder" => "Example: HTML | CSS | JavaScript | Git",

        "job_title_es" => "Título del empleo en español *",
        "job_title_es_placeholder" => "Ejemplo: Desarrollador Web Junior",
        "description_es" => "Descripción del empleo en español *",
        "description_es_placeholder" => "Describe claramente el objetivo del puesto.",
        "responsibilities_es" => "Responsabilidades en español",
        "responsibilities_es_placeholder" => "Ejemplo: Crear interfaces web | Corregir errores | Trabajar con el equipo",
        "requirements_es" => "Requisitos en español *",
        "requirements_es_placeholder" => "Ejemplo: Conocimientos básicos de HTML | Responsabilidad | Deseos de aprender",
        "skills_es" => "Habilidades en español",
        "skills_es_placeholder" => "Ejemplo: HTML | CSS | JavaScript | Git",

        "post_button" => "Publicar vacante",

        "cat_technology" => "Tecnología",
        "cat_design" => "Diseño",
        "cat_administration" => "Administración",
        "cat_sales" => "Ventas",
        "cat_customer_service" => "Atención al cliente",
        "cat_human_resources" => "Recursos humanos",

        "mode_remote" => "Remoto",
        "mode_hybrid" => "Híbrido",
        "mode_onsite" => "Presencial",

        "type_full_time" => "Tiempo completo",
        "type_part_time" => "Medio tiempo",
        "type_internship" => "Pasantía",
        "type_temporary" => "Temporal",
        "type_freelance" => "Freelance",

        "level_no_experience" => "Sin experiencia",
        "level_junior" => "Junior",
        "level_mid" => "Intermedio",
        "level_senior" => "Senior",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_for_companies" => "Para empresas",
        "footer_post_job" => "Publicar vacante",
        "footer_received_applications" => "Postulaciones recibidas",
        "footer_companies" => "Empresas",
        "footer_contact" => "Contactar al equipo",
        "footer_platform" => "Plataforma",
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

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = isset($usuarioActual["escala_texto"]) ? (float) $usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$nombreEmpresa = "";
$lemaEmpresa = "";
$ubicacionEmpresa = "";

$tituloEn = "";
$tituloEs = "";
$categoria = "";
$modalidad = "";
$ubicacion = "";
$tipoEmpleo = "";
$nivelExperiencia = "";
$salario = "";
$descripcionEn = "";
$descripcionEs = "";
$responsabilidadesEn = "";
$responsabilidadesEs = "";
$requisitosEn = "";
$requisitosEs = "";
$habilidadesEn = "";
$habilidadesEs = "";
$fechaLimite = "";

if (isset($_GET["published"]) && $_GET["published"] === "1") {
    $mensaje = t("published_success");
    $claseMensaje = "success";
}

if ($esEmpresa) {
    $nombreEmpresa = trim($usuarioActual["nombre"] ?? "");

    try {
        $buscarDatosEmpresa = $pdo->prepare("
            SELECT lema, ubicacion
            FROM empresas
            WHERE nombre = :nombre
            LIMIT 1
        ");

        $buscarDatosEmpresa->execute([
            ":nombre" => $nombreEmpresa
        ]);

        $datosEmpresa = $buscarDatosEmpresa->fetch();

        if ($datosEmpresa) {
            $lemaEmpresa = $datosEmpresa["lema"] ?? "";
            $ubicacionEmpresa = $datosEmpresa["ubicacion"] ?? "";
        }
    } catch (PDOException $e) {
        $lemaEmpresa = "";
        $ubicacionEmpresa = "";
    }
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
    ["value" => "Remote", "label" => t("mode_remote")],
    ["value" => "Hybrid", "label" => t("mode_hybrid")],
    ["value" => "On-site", "label" => t("mode_onsite")]
];

$tiposEmpleo = [
    ["value" => "Full-time", "label" => t("type_full_time")],
    ["value" => "Part-time", "label" => t("type_part_time")],
    ["value" => "Internship", "label" => t("type_internship")],
    ["value" => "Temporary", "label" => t("type_temporary")],
    ["value" => "Freelance", "label" => t("type_freelance")]
];

$nivelesExperiencia = [
    ["value" => "No experience", "label" => t("level_no_experience")],
    ["value" => "Junior", "label" => t("level_junior")],
    ["value" => "Mid-level", "label" => t("level_mid")],
    ["value" => "Senior", "label" => t("level_senior")]
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($esEmpresa) {
        $nombreEmpresa = trim($usuarioActual["nombre"] ?? "");
    } else {
        $nombreEmpresa = trim($_POST["nombre_empresa"] ?? "");
    }

    $lemaEmpresa = trim($_POST["lema_empresa"] ?? "");
    $ubicacionEmpresa = trim($_POST["ubicacion_empresa"] ?? "");

    $tituloEn = trim($_POST["titulo_en"] ?? "");
    $tituloEs = trim($_POST["titulo_es"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $modalidad = trim($_POST["modalidad"] ?? "");
    $ubicacion = trim($_POST["ubicacion"] ?? "");
    $tipoEmpleo = trim($_POST["tipo_empleo"] ?? "");
    $nivelExperiencia = trim($_POST["nivel_experiencia"] ?? "");
    $salario = trim($_POST["salario"] ?? "");
    $descripcionEn = trim($_POST["descripcion_en"] ?? "");
    $descripcionEs = trim($_POST["descripcion_es"] ?? "");
    $responsabilidadesEn = trim($_POST["responsabilidades_en"] ?? "");
    $responsabilidadesEs = trim($_POST["responsabilidades_es"] ?? "");
    $requisitosEn = trim($_POST["requisitos_en"] ?? "");
    $requisitosEs = trim($_POST["requisitos_es"] ?? "");
    $habilidadesEn = trim($_POST["habilidades_en"] ?? "");
    $habilidadesEs = trim($_POST["habilidades_es"] ?? "");
    $fechaLimite = trim($_POST["fecha_limite"] ?? "");

    if (
        $nombreEmpresa === "" ||
        $tituloEn === "" ||
        $tituloEs === "" ||
        $categoria === "" ||
        $modalidad === "" ||
        $ubicacion === "" ||
        $tipoEmpleo === "" ||
        $nivelExperiencia === "" ||
        $descripcionEn === "" ||
        $descripcionEs === "" ||
        $requisitosEn === "" ||
        $requisitosEs === "" ||
        $fechaLimite === ""
    ) {
        $mensaje = t("error_required");
        $claseMensaje = "error";
    } elseif ($salario !== "" && floatval($salario) < 0) {
        $mensaje = t("error_salary");
        $claseMensaje = "error";
    } elseif (strtotime($fechaLimite) < strtotime(date("Y-m-d"))) {
        $mensaje = t("error_date");
        $claseMensaje = "error";
    } else {
        try {
            $pdo->beginTransaction();

            $buscarEmpresa = $pdo->prepare("
                SELECT id_empresa
                FROM empresas
                WHERE nombre = :nombre
                LIMIT 1
            ");

            $buscarEmpresa->execute([
                ":nombre" => $nombreEmpresa
            ]);

            $empresaEncontrada = $buscarEmpresa->fetch();

            if ($empresaEncontrada) {
                $idEmpresa = $empresaEncontrada["id_empresa"];

                $actualizarEmpresa = $pdo->prepare("
                    UPDATE empresas
                    SET
                        lema = CASE
                            WHEN :lema_no_vacio <> '' THEN :lema
                            ELSE lema
                        END,
                        ubicacion = CASE
                            WHEN :ubicacion_no_vacia <> '' THEN :ubicacion
                            ELSE ubicacion
                        END
                    WHERE id_empresa = :id_empresa
                ");

                $actualizarEmpresa->execute([
                    ":lema_no_vacio" => $lemaEmpresa,
                    ":lema" => $lemaEmpresa,
                    ":ubicacion_no_vacia" => $ubicacionEmpresa,
                    ":ubicacion" => $ubicacionEmpresa,
                    ":id_empresa" => $idEmpresa
                ]);
            } else {
                $insertarEmpresa = $pdo->prepare("
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

                $insertarEmpresa->execute([
                    ":nombre" => $nombreEmpresa,
                    ":lema" => $lemaEmpresa !== "" ? $lemaEmpresa : "SkillBridge partner company",
                    ":descripcion" => "Company account created through SkillBridge.",
                    ":ubicacion" => $ubicacionEmpresa !== "" ? $ubicacionEmpresa : null,
                    ":colaboradores" => "Growing team"
                ]);

                $idEmpresa = $pdo->lastInsertId();
            }

            $salarioFinal = $salario !== "" ? floatval($salario) : null;

            $insertarVacante = $pdo->prepare("
                INSERT INTO vacantes (
                    id_empresa,
                    titulo,
                    titulo_en,
                    titulo_es,
                    categoria,
                    modalidad,
                    ubicacion,
                    tipo_empleo,
                    nivel_experiencia,
                    salario,
                    descripcion,
                    descripcion_en,
                    descripcion_es,
                    responsabilidades,
                    responsabilidades_en,
                    responsabilidades_es,
                    requisitos,
                    requisitos_en,
                    requisitos_es,
                    habilidades,
                    habilidades_en,
                    habilidades_es,
                    fecha_limite,
                    estado,
                    inclusiva
                ) VALUES (
                    :id_empresa,
                    :titulo,
                    :titulo_en,
                    :titulo_es,
                    :categoria,
                    :modalidad,
                    :ubicacion,
                    :tipo_empleo,
                    :nivel_experiencia,
                    :salario,
                    :descripcion,
                    :descripcion_en,
                    :descripcion_es,
                    :responsabilidades,
                    :responsabilidades_en,
                    :responsabilidades_es,
                    :requisitos,
                    :requisitos_en,
                    :requisitos_es,
                    :habilidades,
                    :habilidades_en,
                    :habilidades_es,
                    :fecha_limite,
                    'activa',
                    1
                )
            ");

            $insertarVacante->execute([
                ":id_empresa" => $idEmpresa,
                ":titulo" => $tituloEn,
                ":titulo_en" => $tituloEn,
                ":titulo_es" => $tituloEs,
                ":categoria" => $categoria,
                ":modalidad" => $modalidad,
                ":ubicacion" => $ubicacion,
                ":tipo_empleo" => $tipoEmpleo,
                ":nivel_experiencia" => $nivelExperiencia,
                ":salario" => $salarioFinal,
                ":descripcion" => $descripcionEn,
                ":descripcion_en" => $descripcionEn,
                ":descripcion_es" => $descripcionEs,
                ":responsabilidades" => $responsabilidadesEn !== "" ? $responsabilidadesEn : null,
                ":responsabilidades_en" => $responsabilidadesEn !== "" ? $responsabilidadesEn : null,
                ":responsabilidades_es" => $responsabilidadesEs !== "" ? $responsabilidadesEs : null,
                ":requisitos" => $requisitosEn,
                ":requisitos_en" => $requisitosEn,
                ":requisitos_es" => $requisitosEs,
                ":habilidades" => $habilidadesEn !== "" ? $habilidadesEn : null,
                ":habilidades_en" => $habilidadesEn !== "" ? $habilidadesEn : null,
                ":habilidades_es" => $habilidadesEs !== "" ? $habilidadesEs : null,
                ":fecha_limite" => $fechaLimite
            ]);

            $pdo->commit();

            header("Location: publicarvacante.php?published=1");
            exit;
        } catch (Exception $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log("SkillBridge publish job error: " . $error->getMessage());

            $mensaje = t("error_publish");
            $claseMensaje = "error";
        }
    }
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

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/publicarvacante.css?v=20260904footerfinal">
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
                <a href="perfil.php" class="login-link">
                    <?php echo limpiar(t("nav_my_profile")); ?>
                </a>

                <a href="logout.php" class="button button-primary button-small">
                    <?php echo limpiar(t("nav_logout")); ?>
                </a>

                <a href="publicarvacante.php?lang=<?php echo limpiar($idiomaSiguiente); ?>"
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

        <section class="publish-page-hero">
            <div class="container publish-page-hero-content">

                <div class="breadcrumb">
                    <a href="index.php"><?php echo limpiar(t("breadcrumb_home")); ?></a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                    <a href="empresas.php"><?php echo limpiar(t("breadcrumb_companies")); ?></a>
                    <i class="fa-solid fa-chevron-right"
                        role="img"
                        aria-label="<?php echo limpiar(t("chevron_icon")); ?>"
                        title="<?php echo limpiar(t("chevron_icon")); ?>"></i>
                    <span><?php echo limpiar(t("breadcrumb_post_job")); ?></span>
                </div>

                <div class="publish-hero-text">
                    <span class="section-label">
                        <?php echo limpiar(t("section_job_posting")); ?>
                    </span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_description")); ?>
                    </p>
                </div>

            </div>
        </section>

        <section class="publish-content-section">
            <div class="container publish-layout">

                <aside class="publish-info">

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-circle-info"
                                role="img"
                                aria-label="<?php echo limpiar(t("info_icon")); ?>"
                                title="<?php echo limpiar(t("info_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("tip_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("tip_text")); ?>
                        </p>
                    </div>

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-universal-access"
                                role="img"
                                aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("inclusive_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("inclusive_text")); ?>
                        </p>
                    </div>

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-briefcase"
                                role="img"
                                aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                        </div>

                        <h3><?php echo limpiar(t("organized_title")); ?></h3>

                        <p>
                            <?php echo limpiar(t("organized_text")); ?>
                        </p>
                    </div>

                </aside>

                <section class="publish-card">

                    <div class="publish-card-header">
                        <span class="section-label">
                            <?php echo limpiar(t("details_label")); ?>
                        </span>

                        <h2><?php echo limpiar(t("complete_form")); ?></h2>

                        <p>
                            <?php echo limpiar(t("required_note")); ?>
                        </p>
                    </div>

                    <?php if (!empty($mensaje)): ?>
                        <div class="form-alert <?php echo limpiar($claseMensaje); ?>">
                            <?php if ($claseMensaje === "success"): ?>
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

                            <span>
                                <?php echo limpiar($mensaje); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <form action="publicarvacante.php" method="POST" id="publishVacancyForm" class="publish-form">

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-building"
                                role="img"
                                aria-label="<?php echo limpiar(t("building_icon")); ?>"
                                title="<?php echo limpiar(t("building_icon")); ?>"></i>
                            <?php echo limpiar(t("company_info")); ?>
                        </div>

                        <div class="publish-form-group">
                            <label for="nombre_empresa">
                                <?php echo limpiar(t("company_name")); ?>
                            </label>

                            <input type="text" id="nombre_empresa" name="nombre_empresa"
                                placeholder="<?php echo limpiar(t("company_name_placeholder")); ?>"
                                value="<?php echo limpiar($nombreEmpresa); ?>"
                                <?php echo $esEmpresa ? "readonly" : ""; ?>
                                required>

                            <?php if ($esEmpresa): ?>
                                <small class="publish-helper-text">
                                    <?php echo limpiar(t("company_name_helper")); ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="lema_empresa">
                                    <?php echo limpiar(t("company_slogan")); ?>
                                </label>

                                <input type="text" id="lema_empresa" name="lema_empresa"
                                    placeholder="<?php echo limpiar(t("company_slogan_placeholder")); ?>"
                                    value="<?php echo limpiar($lemaEmpresa); ?>">
                            </div>

                            <div class="publish-form-group">
                                <label for="ubicacion_empresa">
                                    <?php echo limpiar(t("company_location")); ?>
                                </label>

                                <input type="text" id="ubicacion_empresa" name="ubicacion_empresa"
                                    placeholder="<?php echo limpiar(t("company_location_placeholder")); ?>"
                                    value="<?php echo limpiar($ubicacionEmpresa); ?>">
                            </div>
                        </div>

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-briefcase"
                                role="img"
                                aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                            <?php echo limpiar(t("general_info")); ?>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="categoria">
                                    <?php echo limpiar(t("category")); ?>
                                </label>

                                <select id="categoria" name="categoria" required>
                                    <option value=""><?php echo limpiar(t("select_category")); ?></option>

                                    <?php foreach ($categorias as $item): ?>
                                        <option value="<?php echo limpiar($item["value"]); ?>"
                                            <?php echo selectedOption($categoria, $item["value"]); ?>>
                                            <?php echo limpiar($item["label"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="publish-form-group">
                                <label for="modalidad">
                                    <?php echo limpiar(t("work_arrangement")); ?>
                                </label>

                                <select id="modalidad" name="modalidad" required>
                                    <option value=""><?php echo limpiar(t("select_work_arrangement")); ?></option>

                                    <?php foreach ($modalidades as $item): ?>
                                        <option value="<?php echo limpiar($item["value"]); ?>"
                                            <?php echo selectedOption($modalidad, $item["value"]); ?>>
                                            <?php echo limpiar($item["label"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="ubicacion">
                                    <?php echo limpiar(t("job_location")); ?>
                                </label>

                                <input type="text" id="ubicacion" name="ubicacion"
                                    placeholder="<?php echo limpiar(t("job_location_placeholder")); ?>"
                                    value="<?php echo limpiar($ubicacion); ?>" required>
                            </div>

                            <div class="publish-form-group">
                                <label for="tipo_empleo">
                                    <?php echo limpiar(t("employment_type")); ?>
                                </label>

                                <select id="tipo_empleo" name="tipo_empleo" required>
                                    <option value=""><?php echo limpiar(t("select_type")); ?></option>

                                    <?php foreach ($tiposEmpleo as $item): ?>
                                        <option value="<?php echo limpiar($item["value"]); ?>"
                                            <?php echo selectedOption($tipoEmpleo, $item["value"]); ?>>
                                            <?php echo limpiar($item["label"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="nivel_experiencia">
                                    <?php echo limpiar(t("experience_level")); ?>
                                </label>

                                <select id="nivel_experiencia" name="nivel_experiencia" required>
                                    <option value=""><?php echo limpiar(t("select_level")); ?></option>

                                    <?php foreach ($nivelesExperiencia as $item): ?>
                                        <option value="<?php echo limpiar($item["value"]); ?>"
                                            <?php echo selectedOption($nivelExperiencia, $item["value"]); ?>>
                                            <?php echo limpiar($item["label"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="publish-form-group">
                                <label for="salario">
                                    <?php echo limpiar(t("monthly_salary")); ?>
                                </label>

                                <input type="number" id="salario" name="salario"
                                    placeholder="<?php echo limpiar(t("salary_placeholder")); ?>"
                                    min="0" step="0.01"
                                    value="<?php echo limpiar($salario); ?>">
                            </div>
                        </div>

                        <div class="publish-form-group">
                            <label for="fecha_limite">
                                <?php echo limpiar(t("deadline")); ?>
                            </label>

                            <input type="date" id="fecha_limite" name="fecha_limite"
                                value="<?php echo limpiar($fechaLimite); ?>" required>
                        </div>

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-language"
                                role="img"
                                aria-label="<?php echo limpiar(t("language_icon")); ?>"
                                title="<?php echo limpiar(t("language_icon")); ?>"></i>
                            <?php echo limpiar(t("english_version")); ?>
                        </div>

                        <div class="publish-form-group">
                            <label for="titulo_en">
                                <?php echo limpiar(t("job_title_en")); ?>
                            </label>

                            <input type="text" id="titulo_en" name="titulo_en"
                                placeholder="<?php echo limpiar(t("job_title_en_placeholder")); ?>"
                                value="<?php echo limpiar($tituloEn); ?>" required>
                        </div>

                        <div class="publish-form-group">
                            <label for="descripcion_en">
                                <?php echo limpiar(t("description_en")); ?>
                            </label>

                            <textarea id="descripcion_en" name="descripcion_en" rows="5"
                                placeholder="<?php echo limpiar(t("description_en_placeholder")); ?>"
                                required><?php echo limpiar($descripcionEn); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="responsabilidades_en">
                                <?php echo limpiar(t("responsibilities_en")); ?>
                            </label>

                            <textarea id="responsabilidades_en" name="responsabilidades_en" rows="4"
                                placeholder="<?php echo limpiar(t("responsibilities_en_placeholder")); ?>"><?php echo limpiar($responsabilidadesEn); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="requisitos_en">
                                <?php echo limpiar(t("requirements_en")); ?>
                            </label>

                            <textarea id="requisitos_en" name="requisitos_en" rows="4"
                                placeholder="<?php echo limpiar(t("requirements_en_placeholder")); ?>"
                                required><?php echo limpiar($requisitosEn); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="habilidades_en">
                                <?php echo limpiar(t("skills_en")); ?>
                            </label>

                            <input type="text" id="habilidades_en" name="habilidades_en"
                                placeholder="<?php echo limpiar(t("skills_en_placeholder")); ?>"
                                value="<?php echo limpiar($habilidadesEn); ?>">
                        </div>

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-language"
                                role="img"
                                aria-label="<?php echo limpiar(t("language_icon")); ?>"
                                title="<?php echo limpiar(t("language_icon")); ?>"></i>
                            <?php echo limpiar(t("spanish_version")); ?>
                        </div>

                        <div class="publish-form-group">
                            <label for="titulo_es">
                                <?php echo limpiar(t("job_title_es")); ?>
                            </label>

                            <input type="text" id="titulo_es" name="titulo_es"
                                placeholder="<?php echo limpiar(t("job_title_es_placeholder")); ?>"
                                value="<?php echo limpiar($tituloEs); ?>" required>
                        </div>

                        <div class="publish-form-group">
                            <label for="descripcion_es">
                                <?php echo limpiar(t("description_es")); ?>
                            </label>

                            <textarea id="descripcion_es" name="descripcion_es" rows="5"
                                placeholder="<?php echo limpiar(t("description_es_placeholder")); ?>"
                                required><?php echo limpiar($descripcionEs); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="responsabilidades_es">
                                <?php echo limpiar(t("responsibilities_es")); ?>
                            </label>

                            <textarea id="responsabilidades_es" name="responsabilidades_es" rows="4"
                                placeholder="<?php echo limpiar(t("responsibilities_es_placeholder")); ?>"><?php echo limpiar($responsabilidadesEs); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="requisitos_es">
                                <?php echo limpiar(t("requirements_es")); ?>
                            </label>

                            <textarea id="requisitos_es" name="requisitos_es" rows="4"
                                placeholder="<?php echo limpiar(t("requirements_es_placeholder")); ?>"
                                required><?php echo limpiar($requisitosEs); ?></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="habilidades_es">
                                <?php echo limpiar(t("skills_es")); ?>
                            </label>

                            <input type="text" id="habilidades_es" name="habilidades_es"
                                placeholder="<?php echo limpiar(t("skills_es_placeholder")); ?>"
                                value="<?php echo limpiar($habilidadesEs); ?>">
                        </div>

                        <button type="submit" class="button button-primary publish-button">
                            <?php echo limpiar(t("post_button")); ?>
                            <i class="fa-solid fa-paper-plane"
                                role="img"
                                aria-label="<?php echo limpiar(t("send_icon")); ?>"
                                title="<?php echo limpiar(t("send_icon")); ?>"></i>
                        </button>

                    </form>

                </section>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            darkMode: <?php echo $modoOscuro ? "true" : "false"; ?>,
            highContrast: <?php echo $altoContraste ? "true" : "false"; ?>,
            readMode: <?php echo $modoLectura ? "true" : "false"; ?>,
            fontScale: <?php echo number_format($escalaTexto, 2); ?>
        };

        try {
            localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);
            localStorage.setItem("skillbridgeDarkMode", window.SkillBridgeUserPreferences.darkMode ? "true" : "false");
            localStorage.setItem("skillbridgeHighContrast", window.SkillBridgeUserPreferences.highContrast ? "true" : "false");
            localStorage.setItem("skillbridgeReadMode", window.SkillBridgeUserPreferences.readMode ? "true" : "false");
            localStorage.setItem("skillbridgeFontScale", String(window.SkillBridgeUserPreferences.fontScale));
        } catch (error) {
            console.warn("SkillBridge preferences could not be stored locally.");
        }
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
    <script src="java/publicarvacante.js?v=20260904footerfinal"></script>
</body>

</html>