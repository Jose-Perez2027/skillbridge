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

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

try {
    $sql = "
        SELECT *
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
        session_unset();
        session_destroy();

        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    exit("There was an error loading your profile.");
}

$idiomasPermitidos = ["en", "es"];
$idiomaActual = $usuario["idioma_preferido"] ?? ($_SESSION["idioma_preferido"] ?? "en");

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

        $usuario["idioma_preferido"] = $idiomaActual;
    } catch (PDOException $e) {
        $_SESSION["idioma_preferido"] = $idiomaActual;
    }
}

$translations = [
    "en" => [
        "page_title" => "My Profile | SkillBridge",
        "meta_description" => "Manage your SkillBridge profile, account information, applications, profile photo, and accessibility details.",

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

        "profile_updated" => "Your profile was updated successfully.",
        "company_account" => "Company account",
        "admin_account" => "Administrator account",
        "candidate_account" => "Candidate account",

        "not_added" => "Not added yet",
        "not_specified" => "Not specified",
        "yes" => "Yes",
        "no" => "No",

        "email_icon" => "Email icon",
        "phone_icon" => "Phone icon",
        "location_icon" => "Location icon",
        "edit_icon" => "Edit icon",
        "logout_icon" => "Log out icon",
        "user_icon" => "User icon",
        "company_icon" => "Company icon",
        "accessibility_icon" => "Accessibility icon",
        "file_icon" => "File icon",
        "skills_icon" => "Skills icon",
        "briefcase_icon" => "Briefcase icon",
        "check_icon" => "Check icon",
        "upload_icon" => "Upload icon",
        "language_icon" => "Language icon",

        "profile_photo_alt" => "Profile photo",
        "company_photo_alt" => "Company profile image",

        "edit_profile" => "Edit profile",
        "profile_completion" => "Profile completion",

        "company_overview" => "Company overview",
        "about_me" => "About me",
        "company_information" => "Company information",
        "accessibility_information" => "Accessibility information",
        "company_profile_image" => "Company profile image",
        "resume" => "Curriculum",
        "company_strengths" => "Company strengths",
        "skills" => "Skills",
        "company_actions" => "Company actions",
        "applications" => "Applications",
        "next_steps" => "Next steps",

        "no_profile_description_company" => "No company description added yet.",
        "no_profile_description_candidate" => "No profile description added yet.",

        "account_type" => "Account type:",
        "location" => "Location:",
        "phone" => "Phone:",
        "company_slogan" => "Company slogan:",
        "collaborators" => "Collaborators:",
        "active_jobs" => "Active job openings:",
        "applications_received" => "Applications received:",
        "disability_registered" => "Disability registered:",
        "disability_type" => "Disability type:",
        "inclusive_profile_ready" => "Inclusive profile ready for accessible job opportunities.",

        "company_image_ready" => "Your company profile image is ready.",
        "company_image_missing" => "No company profile image uploaded yet.",
        "update_company_profile" => "Update company profile",

        "resume_ready" => "Your curriculum is uploaded and ready to be shared.",
        "resume_missing" => "No curriculum uploaded yet.",
        "view_resume" => "View curriculum",
        "upload_resume" => "Upload curriculum",

        "inclusive_hiring" => "Inclusive hiring",
        "talent_management" => "Talent management",
        "recruitment" => "Recruitment",
        "job_posting" => "Job posting",
        "team_growth" => "Team growth",

        "communication" => "Communication",
        "teamwork" => "Teamwork",
        "responsibility" => "Responsibility",
        "adaptability" => "Adaptability",
        "willingness_learn" => "Willingness to learn",

        "skills_note_company" => "These strengths help your company connect with candidates in an inclusive way.",
        "skills_note_candidate" => "These skills are sample profile information. Later, they can be edited from the profile form.",

        "post_job_openings" => "Post job openings",
        "post_job_description" => "Create inclusive job opportunities and connect with candidates.",
        "active_job_singular" => "active job opening",
        "active_job_plural" => "active job openings",
        "post_a_job" => "Post a job",
        "view_received_applications" => "View received applications",

        "my_applications" => "My applications",
        "my_applications_description" => "Review the jobs you have applied for.",
        "application_singular" => "application sent",
        "application_plural" => "applications sent",
        "view_my_applications" => "View my applications",
        "find_more_jobs" => "Find more jobs",

        "profile_complete" => "Your profile is complete.",
        "keep_updated" => "Keep your information updated.",

        "step_company_photo" => "Upload a company profile image.",
        "step_candidate_photo" => "Upload your profile photo.",
        "step_company_phone" => "Add company phone number.",
        "step_candidate_phone" => "Add your phone number.",
        "step_company_location" => "Add company location.",
        "step_candidate_location" => "Add your location.",
        "step_company_description" => "Write a company description.",
        "step_candidate_description" => "Write a short profile description.",
        "step_company_slogan" => "Add a company slogan.",
        "step_company_collaborators" => "Add the number of collaborators.",
        "step_company_post_job" => "Post an inclusive job opening.",
        "step_candidate_resume" => "Upload your curriculum.",
        "step_candidate_apply" => "Apply to inclusive job opportunities.",

        "footer_description" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_newsletter" => "Newsletter",
        "footer_create_profile" => "Create profile",
        "footer_contact_team" => "Contact the team",
        "footer_about_us" => "About us",
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
        "page_title" => "Mi perfil | SkillBridge",
        "meta_description" => "Administra tu perfil de SkillBridge, información de cuenta, postulaciones, foto de perfil y detalles de accesibilidad.",

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

        "profile_updated" => "Tu perfil se actualizó correctamente.",
        "company_account" => "Cuenta de empresa",
        "admin_account" => "Cuenta de administrador",
        "candidate_account" => "Cuenta de candidato",

        "not_added" => "No agregado todavía",
        "not_specified" => "No especificado",
        "yes" => "Sí",
        "no" => "No",

        "email_icon" => "Ícono de correo electrónico",
        "phone_icon" => "Ícono de teléfono",
        "location_icon" => "Ícono de ubicación",
        "edit_icon" => "Ícono de editar",
        "logout_icon" => "Ícono de cerrar sesión",
        "user_icon" => "Ícono de usuario",
        "company_icon" => "Ícono de empresa",
        "accessibility_icon" => "Ícono de accesibilidad",
        "file_icon" => "Ícono de archivo",
        "skills_icon" => "Ícono de habilidades",
        "briefcase_icon" => "Ícono de maletín",
        "check_icon" => "Ícono de verificación",
        "upload_icon" => "Ícono de subir archivo",
        "language_icon" => "Ícono de idioma",

        "profile_photo_alt" => "Foto de perfil",
        "company_photo_alt" => "Imagen de perfil de la empresa",

        "edit_profile" => "Editar perfil",
        "profile_completion" => "Perfil completado",

        "company_overview" => "Resumen de la empresa",
        "about_me" => "Sobre mí",
        "company_information" => "Información de la empresa",
        "accessibility_information" => "Información de accesibilidad",
        "company_profile_image" => "Imagen de perfil de la empresa",
        "resume" => "Currículum",
        "company_strengths" => "Fortalezas de la empresa",
        "skills" => "Habilidades",
        "company_actions" => "Acciones de la empresa",
        "applications" => "Postulaciones",
        "next_steps" => "Próximos pasos",

        "no_profile_description_company" => "Aún no se ha agregado una descripción de la empresa.",
        "no_profile_description_candidate" => "Aún no se ha agregado una descripción del perfil.",

        "account_type" => "Tipo de cuenta:",
        "location" => "Ubicación:",
        "phone" => "Teléfono:",
        "company_slogan" => "Lema de la empresa:",
        "collaborators" => "Colaboradores:",
        "active_jobs" => "Vacantes activas:",
        "applications_received" => "Postulaciones recibidas:",
        "disability_registered" => "Discapacidad registrada:",
        "disability_type" => "Tipo de discapacidad:",
        "inclusive_profile_ready" => "Perfil inclusivo listo para oportunidades laborales accesibles.",

        "company_image_ready" => "La imagen de perfil de tu empresa está lista.",
        "company_image_missing" => "Aún no se ha subido una imagen de perfil de la empresa.",
        "update_company_profile" => "Actualizar perfil de empresa",

        "resume_ready" => "Tu currículum está subido y listo para compartirse.",
        "resume_missing" => "Aún no se ha subido un currículum.",
        "view_resume" => "Ver currículum",
        "upload_resume" => "Subir currículum",

        "inclusive_hiring" => "Contratación inclusiva",
        "talent_management" => "Gestión del talento",
        "recruitment" => "Reclutamiento",
        "job_posting" => "Publicación de vacantes",
        "team_growth" => "Crecimiento del equipo",

        "communication" => "Comunicación",
        "teamwork" => "Trabajo en equipo",
        "responsibility" => "Responsabilidad",
        "adaptability" => "Adaptabilidad",
        "willingness_learn" => "Deseo de aprender",

        "skills_note_company" => "Estas fortalezas ayudan a tu empresa a conectar con candidatos de forma inclusiva.",
        "skills_note_candidate" => "Estas habilidades son información de ejemplo del perfil. Luego se pueden editar desde el formulario del perfil.",

        "post_job_openings" => "Publicar vacantes",
        "post_job_description" => "Crea oportunidades laborales inclusivas y conecta con candidatos.",
        "active_job_singular" => "vacante activa",
        "active_job_plural" => "vacantes activas",
        "post_a_job" => "Publicar vacante",
        "view_received_applications" => "Ver postulaciones recibidas",

        "my_applications" => "Mis postulaciones",
        "my_applications_description" => "Revisa los empleos a los que has aplicado.",
        "application_singular" => "postulación enviada",
        "application_plural" => "postulaciones enviadas",
        "view_my_applications" => "Ver mis postulaciones",
        "find_more_jobs" => "Buscar más empleos",

        "profile_complete" => "Tu perfil está completo.",
        "keep_updated" => "Mantén tu información actualizada.",

        "step_company_photo" => "Sube una imagen de perfil de la empresa.",
        "step_candidate_photo" => "Sube tu foto de perfil.",
        "step_company_phone" => "Agrega el teléfono de la empresa.",
        "step_candidate_phone" => "Agrega tu teléfono.",
        "step_company_location" => "Agrega la ubicación de la empresa.",
        "step_candidate_location" => "Agrega tu ubicación.",
        "step_company_description" => "Escribe una descripción de la empresa.",
        "step_candidate_description" => "Escribe una breve descripción de tu perfil.",
        "step_company_slogan" => "Agrega un lema de la empresa.",
        "step_company_collaborators" => "Agrega el número de colaboradores.",
        "step_company_post_job" => "Publica una vacante inclusiva.",
        "step_candidate_resume" => "Sube tu currículum.",
        "step_candidate_apply" => "Postúlate a oportunidades laborales inclusivas.",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_newsletter" => "Boletín",
        "footer_create_profile" => "Crear perfil",
        "footer_contact_team" => "Contactar al equipo",
        "footer_about_us" => "Quiénes somos",
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

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$nombreUsuario = $usuario["nombre"] ?? "User";
$correoUsuario = $usuario["correo"] ?? t("not_added");
$tipoUsuario = $usuario["tipo_usuario"] ?? "candidato";
$telefonoReal = $usuario["telefono"] ?? "";
$ubicacionReal = $usuario["ubicacion"] ?? "";
$descripcionReal = $usuario["descripcion"] ?? "";
$fotoPerfil = $usuario["foto_perfil"] ?? "";
$curriculum = $usuario["curriculum"] ?? "";
$tipoDiscapacidadReal = $usuario["tipo_discapacidad"] ?? "";
$discapacidadReal = $usuario["discapacidad"] ?? 0;

$modoOscuro = !empty($usuario["modo_oscuro"]) ? 1 : 0;
$altoContraste = !empty($usuario["alto_contraste"]) ? 1 : 0;
$modoLectura = !empty($usuario["modo_lectura"]) ? 1 : 0;
$escalaTexto = isset($usuario["escala_texto"]) ? (float) $usuario["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$telefonoUsuario = !empty($telefonoReal) ? $telefonoReal : t("not_added");
$ubicacionUsuario = !empty($ubicacionReal) ? $ubicacionReal : t("not_added");
$tipoDiscapacidad = !empty($tipoDiscapacidadReal) ? $tipoDiscapacidadReal : t("not_specified");
$tieneDiscapacidad = !empty($discapacidadReal) && $discapacidadReal == 1 ? t("yes") : t("no");

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

if ($esEmpresaOAdmin) {
    $descripcionUsuario = !empty($descripcionReal) ? $descripcionReal : t("no_profile_description_company");
} else {
    $descripcionUsuario = !empty($descripcionReal) ? $descripcionReal : t("no_profile_description_candidate");
}

$fotoPerfilExiste = !empty($fotoPerfil) && file_exists($fotoPerfil);
$curriculumExiste = !empty($curriculum) && file_exists($curriculum);

$_SESSION["tipo_usuario"] = $tipoUsuario;
$_SESSION["nombre"] = $nombreUsuario;
$_SESSION["idioma_preferido"] = $idiomaActual;

if ($esEmpresa) {
    $tipoUsuarioTexto = t("company_account");
} elseif ($esAdmin) {
    $tipoUsuarioTexto = t("admin_account");
} else {
    $tipoUsuarioTexto = t("candidate_account");
}

$empresaActual = null;
$empresaLema = "";
$empresaColaboradores = "";
$totalVacantes = 0;
$totalAplicacionesRecibidas = 0;
$totalPostulaciones = 0;

if ($esEmpresaOAdmin) {
    try {
        $sqlEmpresa = "
            SELECT *
            FROM empresas
            WHERE nombre = :nombre
            LIMIT 1
        ";

        $stmtEmpresa = $pdo->prepare($sqlEmpresa);
        $stmtEmpresa->execute([
            ":nombre" => $nombreUsuario
        ]);

        $empresaActual = $stmtEmpresa->fetch();

        if ($empresaActual) {
            $idEmpresa = $empresaActual["id_empresa"];
            $empresaLema = $empresaActual["lema"] ?? "";
            $empresaColaboradores = $empresaActual["colaboradores"] ?? "";

            $stmtVacantes = $pdo->prepare("
                SELECT COUNT(*) AS total
                FROM vacantes
                WHERE id_empresa = :id_empresa
                  AND estado = 'activa'
            ");

            $stmtVacantes->execute([
                ":id_empresa" => $idEmpresa
            ]);

            $totalVacantes = (int) ($stmtVacantes->fetch()["total"] ?? 0);

            $stmtAplicaciones = $pdo->prepare("
                SELECT COUNT(*) AS total
                FROM postulaciones
                INNER JOIN vacantes
                    ON postulaciones.id_vacante = vacantes.id_vacante
                WHERE vacantes.id_empresa = :id_empresa
            ");

            $stmtAplicaciones->execute([
                ":id_empresa" => $idEmpresa
            ]);

            $totalAplicacionesRecibidas = (int) ($stmtAplicaciones->fetch()["total"] ?? 0);
        }
    } catch (PDOException $e) {
        $empresaActual = null;
    }
} else {
    try {
        $stmtPostulaciones = $pdo->prepare("
            SELECT COUNT(*) AS total
            FROM postulaciones
            WHERE id_usuario = :id_usuario
        ");

        $stmtPostulaciones->execute([
            ":id_usuario" => $idUsuario
        ]);

        $totalPostulaciones = (int) ($stmtPostulaciones->fetch()["total"] ?? 0);
    } catch (PDOException $e) {
        $totalPostulaciones = 0;
    }
}

$camposPerfil = [
    !empty($nombreUsuario),
    !empty($correoUsuario),
    !empty($tipoUsuario),
    !empty($telefonoReal),
    !empty($ubicacionReal),
    !empty($descripcionReal),
    $fotoPerfilExiste
];

if ($esEmpresaOAdmin) {
    $camposPerfil[] = !empty($empresaLema);
    $camposPerfil[] = !empty($empresaColaboradores);
} else {
    $camposPerfil[] = $curriculumExiste;
}

$totalCampos = count($camposPerfil);
$camposCompletos = 0;

foreach ($camposPerfil as $campoCompleto) {
    if ($campoCompleto) {
        $camposCompletos++;
    }
}

$porcentajePerfil = $totalCampos > 0 ? round(($camposCompletos / $totalCampos) * 100) : 0;

$inicialUsuario = strtoupper(substr(trim($nombreUsuario), 0, 1));

if ($inicialUsuario === "") {
    $inicialUsuario = "U";
}

$pasosPendientes = [];

if (!$fotoPerfilExiste) {
    $pasosPendientes[] = $esEmpresaOAdmin ? t("step_company_photo") : t("step_candidate_photo");
}

if (empty($telefonoReal)) {
    $pasosPendientes[] = $esEmpresaOAdmin ? t("step_company_phone") : t("step_candidate_phone");
}

if (empty($ubicacionReal)) {
    $pasosPendientes[] = $esEmpresaOAdmin ? t("step_company_location") : t("step_candidate_location");
}

if (empty($descripcionReal)) {
    $pasosPendientes[] = $esEmpresaOAdmin ? t("step_company_description") : t("step_candidate_description");
}

if ($esEmpresaOAdmin) {
    if (empty($empresaLema)) {
        $pasosPendientes[] = t("step_company_slogan");
    }

    if (empty($empresaColaboradores)) {
        $pasosPendientes[] = t("step_company_collaborators");
    }

    $pasosPendientes[] = t("step_company_post_job");
} else {
    if (!$curriculumExiste) {
        $pasosPendientes[] = t("step_candidate_resume");
    }

    $pasosPendientes[] = t("step_candidate_apply");
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

    <meta name="description"
        content="<?php echo limpiar(t("meta_description")); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/perfil.css?v=20260904footerfinal">
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
                    aria-label="<?php echo limpiar(t("aria_close_accessibility")); ?>"
                    title="<?php echo limpiar(t("aria_close_accessibility")); ?>"></i>
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
                    <img src="img/LOGOS.png" alt="SkillBridge logo">
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
                    aria-label="<?php echo limpiar(t("aria_open_menu")); ?>"
                    title="<?php echo limpiar(t("aria_open_menu")); ?>"></i>
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

                <a href="perfil.php?lang=<?php echo limpiar($idiomaSiguiente); ?>"
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

    <main class="perfil-page">

        <section class="perfil-banner">
            <div class="container">

                <?php if (isset($_GET["updated"]) && $_GET["updated"] === "1"): ?>
                    <div class="profile-alert success">
                        <i class="fa-solid fa-circle-check"
                            role="img"
                            aria-label="<?php echo limpiar(t("check_icon")); ?>"
                            title="<?php echo limpiar(t("check_icon")); ?>"></i>
                        <span><?php echo limpiar(t("profile_updated")); ?></span>
                    </div>
                <?php endif; ?>

                <div class="perfil-card">

                    <div class="perfil-top">

                        <div class="perfil-foto">
                            <?php if ($fotoPerfilExiste): ?>
                                <img src="<?php echo limpiar($fotoPerfil); ?>"
                                    alt="<?php echo limpiar($esEmpresaOAdmin ? t("company_photo_alt") : t("profile_photo_alt")); ?>">
                            <?php else: ?>
                                <span class="perfil-inicial">
                                    <?php echo limpiar($inicialUsuario); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="perfil-info">

                            <span class="profile-type-badge profile-type-inline">
                                <?php echo limpiar($tipoUsuarioTexto); ?>
                            </span>

                            <h1><?php echo limpiar($nombreUsuario); ?></h1>

                            <p>
                                <i class="fa-solid fa-envelope"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("email_icon")); ?>"
                                    title="<?php echo limpiar(t("email_icon")); ?>"></i>
                                <?php echo limpiar($correoUsuario); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-phone"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("phone_icon")); ?>"
                                    title="<?php echo limpiar(t("phone_icon")); ?>"></i>
                                <?php echo limpiar($telefonoUsuario); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-location-dot"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                    title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                <?php echo limpiar($ubicacionUsuario); ?>
                            </p>

                            <div class="perfil-actions">
                                <a href="editar-perfil.php" class="editar-btn">
                                    <i class="fa-solid fa-pen"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("edit_icon")); ?>"
                                        title="<?php echo limpiar(t("edit_icon")); ?>"></i>
                                    <?php echo limpiar(t("edit_profile")); ?>
                                </a>

                                <a href="logout.php" class="logout-profile-btn">
                                    <i class="fa-solid fa-arrow-right-from-bracket"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("logout_icon")); ?>"
                                        title="<?php echo limpiar(t("logout_icon")); ?>"></i>
                                    <?php echo limpiar(t("nav_logout")); ?>
                                </a>
                            </div>

                        </div>

                    </div>

                    <div class="perfil-completado">

                        <div class="progreso-header">
                            <h3><?php echo limpiar(t("profile_completion")); ?></h3>
                            <span id="porcentajeTexto"><?php echo $porcentajePerfil; ?>%</span>
                        </div>

                        <div class="barra-progreso">
                            <div class="barra-fill" id="barraFill"
                                style="width: <?php echo $porcentajePerfil; ?>%;"></div>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <section class="perfil-content">

            <div class="container">

                <div class="info-grid">

                    <article class="info-card">

                        <h2>
                            <i class="fa-solid fa-user"
                                role="img"
                                aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                title="<?php echo limpiar(t("user_icon")); ?>"></i>
                            <?php echo limpiar($esEmpresaOAdmin ? t("company_overview") : t("about_me")); ?>
                        </h2>

                        <p class="profile-description">
                            <?php echo nl2br(limpiar($descripcionUsuario)); ?>
                        </p>

                        <ul class="profile-details-list">
                            <li>
                                <strong><?php echo limpiar(t("account_type")); ?></strong>
                                <?php echo limpiar($tipoUsuarioTexto); ?>
                            </li>

                            <li>
                                <strong><?php echo limpiar(t("location")); ?></strong>
                                <?php echo limpiar($ubicacionUsuario); ?>
                            </li>

                            <li>
                                <strong><?php echo limpiar(t("phone")); ?></strong>
                                <?php echo limpiar($telefonoUsuario); ?>
                            </li>
                        </ul>

                    </article>

                    <article class="info-card">

                        <h2>
                            <i class="fa-solid <?php echo $esEmpresaOAdmin ? "fa-building-circle-check" : "fa-universal-access"; ?>"
                                role="img"
                                aria-label="<?php echo limpiar($esEmpresaOAdmin ? t("company_icon") : t("accessibility_icon")); ?>"
                                title="<?php echo limpiar($esEmpresaOAdmin ? t("company_icon") : t("accessibility_icon")); ?>"></i>
                            <?php echo limpiar($esEmpresaOAdmin ? t("company_information") : t("accessibility_information")); ?>
                        </h2>

                        <?php if ($esEmpresaOAdmin): ?>
                            <ul class="profile-details-list">
                                <li>
                                    <strong><?php echo limpiar(t("company_slogan")); ?></strong>
                                    <?php echo limpiar(!empty($empresaLema) ? $empresaLema : t("not_added")); ?>
                                </li>

                                <li>
                                    <strong><?php echo limpiar(t("collaborators")); ?></strong>
                                    <?php echo limpiar(!empty($empresaColaboradores) ? $empresaColaboradores : t("not_added")); ?>
                                </li>

                                <li>
                                    <strong><?php echo limpiar(t("active_jobs")); ?></strong>
                                    <?php echo $totalVacantes; ?>
                                </li>

                                <li>
                                    <strong><?php echo limpiar(t("applications_received")); ?></strong>
                                    <?php echo $totalAplicacionesRecibidas; ?>
                                </li>
                            </ul>
                        <?php else: ?>
                            <ul class="profile-details-list">
                                <li>
                                    <strong><?php echo limpiar(t("disability_registered")); ?></strong>
                                    <?php echo limpiar($tieneDiscapacidad); ?>
                                </li>

                                <li>
                                    <strong><?php echo limpiar(t("disability_type")); ?></strong>
                                    <?php echo limpiar($tipoDiscapacidad); ?>
                                </li>

                                <li>
                                    <?php echo limpiar(t("inclusive_profile_ready")); ?>
                                </li>
                            </ul>
                        <?php endif; ?>

                    </article>

                    <article class="info-card">

                        <?php if ($esEmpresaOAdmin): ?>

                            <h2>
                                <i class="fa-solid fa-building"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("company_icon")); ?>"
                                    title="<?php echo limpiar(t("company_icon")); ?>"></i>
                                <?php echo limpiar(t("company_profile_image")); ?>
                            </h2>

                            <div class="cv-box">
                                <?php if ($fotoPerfilExiste): ?>
                                    <p>
                                        <?php echo limpiar(t("company_image_ready")); ?>
                                    </p>
                                <?php else: ?>
                                    <p>
                                        <?php echo limpiar(t("company_image_missing")); ?>
                                    </p>
                                <?php endif; ?>

                                <a href="editar-perfil.php" class="descargar-btn">
                                    <i class="fa-solid fa-upload"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("upload_icon")); ?>"
                                        title="<?php echo limpiar(t("upload_icon")); ?>"></i>
                                    <?php echo limpiar(t("update_company_profile")); ?>
                                </a>
                            </div>

                        <?php else: ?>

                            <h2>
                                <i class="fa-solid fa-file-pdf"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                    title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                <?php echo limpiar(t("resume")); ?>
                            </h2>

                            <div class="cv-box">
                                <?php if ($curriculumExiste): ?>
                                    <p>
                                        <?php echo limpiar(t("resume_ready")); ?>
                                    </p>

                                    <a href="<?php echo limpiar($curriculum); ?>" target="_blank" class="descargar-btn">
                                        <i class="fa-solid fa-file-arrow-down"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                            title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                        <?php echo limpiar(t("view_resume")); ?>
                                    </a>
                                <?php else: ?>
                                    <p>
                                        <?php echo limpiar(t("resume_missing")); ?>
                                    </p>

                                    <a href="editar-perfil.php" class="descargar-btn">
                                        <i class="fa-solid fa-upload"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("upload_icon")); ?>"
                                            title="<?php echo limpiar(t("upload_icon")); ?>"></i>
                                        <?php echo limpiar(t("upload_resume")); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </article>

                    <article class="info-card">

                        <h2>
                            <i class="fa-solid fa-laptop-code"
                                role="img"
                                aria-label="<?php echo limpiar(t("skills_icon")); ?>"
                                title="<?php echo limpiar(t("skills_icon")); ?>"></i>
                            <?php echo limpiar($esEmpresaOAdmin ? t("company_strengths") : t("skills")); ?>
                        </h2>

                        <div class="habilidades">
                            <?php if ($esEmpresaOAdmin): ?>
                                <span><?php echo limpiar(t("inclusive_hiring")); ?></span>
                                <span><?php echo limpiar(t("talent_management")); ?></span>
                                <span><?php echo limpiar(t("recruitment")); ?></span>
                                <span><?php echo limpiar(t("job_posting")); ?></span>
                                <span><?php echo limpiar(t("team_growth")); ?></span>
                            <?php else: ?>
                                <span><?php echo limpiar(t("communication")); ?></span>
                                <span><?php echo limpiar(t("teamwork")); ?></span>
                                <span><?php echo limpiar(t("responsibility")); ?></span>
                                <span><?php echo limpiar(t("adaptability")); ?></span>
                                <span><?php echo limpiar(t("willingness_learn")); ?></span>
                            <?php endif; ?>
                        </div>

                        <p class="card-note">
                            <?php echo limpiar($esEmpresaOAdmin ? t("skills_note_company") : t("skills_note_candidate")); ?>
                        </p>

                    </article>

                    <article class="info-card">

                        <h2>
                            <i class="fa-solid fa-briefcase"
                                role="img"
                                aria-label="<?php echo limpiar(t("briefcase_icon")); ?>"
                                title="<?php echo limpiar(t("briefcase_icon")); ?>"></i>
                            <?php echo limpiar($esEmpresaOAdmin ? t("company_actions") : t("applications")); ?>
                        </h2>

                        <?php if ($esEmpresaOAdmin): ?>

                            <div class="experiencia">
                                <h4><?php echo limpiar(t("post_job_openings")); ?></h4>
                                <p><?php echo limpiar(t("post_job_description")); ?></p>
                                <small>
                                    <?php echo $totalVacantes; ?>
                                    <?php echo limpiar($totalVacantes === 1 ? t("active_job_singular") : t("active_job_plural")); ?>
                                </small>
                            </div>

                            <a href="publicarvacante.php" class="profile-link-button">
                                <?php echo limpiar(t("post_a_job")); ?>
                            </a>

                            <a href="postulaciones-empresa.php" class="profile-link-button">
                                <?php echo limpiar(t("view_received_applications")); ?>
                            </a>

                        <?php else: ?>

                            <div class="experiencia">
                                <h4><?php echo limpiar(t("my_applications")); ?></h4>
                                <p><?php echo limpiar(t("my_applications_description")); ?></p>
                                <small>
                                    <?php echo $totalPostulaciones; ?>
                                    <?php echo limpiar($totalPostulaciones === 1 ? t("application_singular") : t("application_plural")); ?>
                                </small>
                            </div>

                            <a href="postulaciones.php" class="profile-link-button">
                                <?php echo limpiar(t("view_my_applications")); ?>
                            </a>

                            <a href="empleos.php" class="profile-link-button">
                                <?php echo limpiar(t("find_more_jobs")); ?>
                            </a>

                        <?php endif; ?>

                    </article>

                    <article class="info-card">

                        <h2>
                            <i class="fa-solid fa-circle-check"
                                role="img"
                                aria-label="<?php echo limpiar(t("check_icon")); ?>"
                                title="<?php echo limpiar(t("check_icon")); ?>"></i>
                            <?php echo limpiar(t("next_steps")); ?>
                        </h2>

                        <ul class="profile-details-list">
                            <?php if (!empty($pasosPendientes)): ?>
                                <?php foreach ($pasosPendientes as $paso): ?>
                                    <li><?php echo limpiar($paso); ?></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><?php echo limpiar(t("profile_complete")); ?></li>
                                <li><?php echo limpiar(t("keep_updated")); ?></li>
                            <?php endif; ?>
                        </ul>

                    </article>

                </div>

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
    <script src="java/perfil.js?v=20260904footerfinal"></script>

</body>

</html>