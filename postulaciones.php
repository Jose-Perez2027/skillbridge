<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$usuarioLogueado = true;
$paginaActual = basename($_SERVER["PHP_SELF"]);
$errorCarga = "";
$postulaciones = [];
$usuarioActual = null;

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

function rutaCurriculum($cvArchivo) {
    $cvArchivo = trim($cvArchivo ?? "");

    if ($cvArchivo === "") {
        return "";
    }

    $cvArchivo = str_replace("\\", "/", $cvArchivo);

    if (strpos($cvArchivo, "../") !== false) {
        return "";
    }

    if (strpos($cvArchivo, "cv/") === 0) {
        return $cvArchivo;
    }

    return "cv/" . $cvArchivo;
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

$tipoUsuario = $usuarioActual["tipo_usuario"] ?? "candidato";
$_SESSION["tipo_usuario"] = $tipoUsuario;
$_SESSION["nombre"] = $usuarioActual["nombre"] ?? "";

if ($tipoUsuario === "empresa") {
    header("Location: perfil.php");
    exit;
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif (!empty($usuarioActual["idioma_preferido"])) {
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

if (isset($_GET["lang"])) {
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
        "page_title" => "My Applications | SkillBridge",
        "meta_description" => "Review your job applications, application status, and saved opportunities on SkillBridge.",

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
        "warning_icon" => "Warning icon",
        "building_icon" => "Company icon",
        "location_icon" => "Location icon",
        "work_mode_icon" => "Work arrangement icon",
        "file_icon" => "File icon",
        "file_missing_icon" => "Missing file icon",
        "folder_icon" => "Folder icon",
        "arrow_icon" => "Arrow icon",
        "send_icon" => "Send icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "error_loading_applications" => "There was an error loading your applications.",
        "check_database" => "Please check your database connection.",
        "no_date" => "No date available",

        "status_pending" => "Pending",
        "status_reviewed" => "Reviewed",
        "status_accepted" => "Accepted",
        "status_rejected" => "Rejected",

        "hero_label" => "Candidate dashboard",
        "hero_title" => "My applications",
        "hero_greeting" => "Hi",
        "hero_text" => "Check the status of your real job applications.",

        "filter_all" => "All",
        "filter_pending" => "Pending",
        "filter_reviewed" => "Reviewed",
        "filter_accepted" => "Accepted",
        "filter_rejected" => "Rejected",

        "job_opening" => "Job opening",
        "company" => "Company",
        "location_not_specified" => "Location not specified",
        "work_mode_not_specified" => "Work arrangement not specified",
        "applied_on" => "Applied on",
        "message" => "Message:",
        "curriculum_attached" => "Curriculum attached",
        "view_curriculum" => "View curriculum",
        "file_not_found" => "File path saved, but the file was not found.",
        "no_curriculum_attached" => "No curriculum attached",
        "view_job" => "View job",

        "no_applications_title" => "No applications found.",
        "no_applications_text" => "Apply to a job opening to see it here.",
        "find_jobs" => "Find jobs",

        "remote" => "Remote",
        "hybrid" => "Hybrid",
        "onsite" => "On-site",

        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_newsletter" => "Newsletter",
        "footer_create_profile" => "Create profile",
        "footer_find_talent" => "Find talent",
        "footer_companies" => "Companies",
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
        "page_title" => "Mis postulaciones | SkillBridge",
        "meta_description" => "Revisa tus postulaciones laborales, estado de solicitud y oportunidades guardadas en SkillBridge.",

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
        "warning_icon" => "Ícono de advertencia",
        "building_icon" => "Ícono de empresa",
        "location_icon" => "Ícono de ubicación",
        "work_mode_icon" => "Ícono de modalidad de trabajo",
        "file_icon" => "Ícono de archivo",
        "file_missing_icon" => "Ícono de archivo faltante",
        "folder_icon" => "Ícono de carpeta",
        "arrow_icon" => "Ícono de flecha",
        "send_icon" => "Ícono de enviar",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "error_loading_applications" => "Hubo un error al cargar tus postulaciones.",
        "check_database" => "Revisa la conexión con la base de datos.",
        "no_date" => "Sin fecha disponible",

        "status_pending" => "Pendiente",
        "status_reviewed" => "Revisada",
        "status_accepted" => "Aceptada",
        "status_rejected" => "Rechazada",

        "hero_label" => "Panel del candidato",
        "hero_title" => "Mis postulaciones",
        "hero_greeting" => "Hola",
        "hero_text" => "Revisa el estado de tus postulaciones laborales reales.",

        "filter_all" => "Todas",
        "filter_pending" => "Pendientes",
        "filter_reviewed" => "Revisadas",
        "filter_accepted" => "Aceptadas",
        "filter_rejected" => "Rechazadas",

        "job_opening" => "Vacante",
        "company" => "Empresa",
        "location_not_specified" => "Ubicación no especificada",
        "work_mode_not_specified" => "Modalidad no especificada",
        "applied_on" => "Aplicaste el",
        "message" => "Mensaje:",
        "curriculum_attached" => "Currículum adjunto",
        "view_curriculum" => "Ver currículum",
        "file_not_found" => "La ruta del archivo está guardada, pero el archivo no se encontró.",
        "no_curriculum_attached" => "No hay currículum adjunto",
        "view_job" => "Ver vacante",

        "no_applications_title" => "No se encontraron postulaciones.",
        "no_applications_text" => "Postúlate a una vacante para verla aquí.",
        "find_jobs" => "Buscar empleos",

        "remote" => "Remoto",
        "hybrid" => "Híbrido",
        "onsite" => "Presencial",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_candidates" => "Para candidatos",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_newsletter" => "Boletín",
        "footer_create_profile" => "Crear perfil",
        "footer_find_talent" => "Encontrar talento",
        "footer_companies" => "Empresas",
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

    return "postulaciones.php?" . http_build_query($params);
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

function fechaTexto($fecha) {
    global $idiomaActual;

    if (empty($fecha)) {
        return t("no_date");
    }

    $timestamp = strtotime($fecha);

    if (!$timestamp) {
        return t("no_date");
    }

    if ($idiomaActual === "es") {
        return date("d/m/Y", $timestamp);
    }

    return date("M d, Y", $timestamp);
}

function estadoTexto($estado) {
    $estado = trim($estado ?? "pendiente");

    if ($estado === "revisada") {
        return t("status_reviewed");
    }

    if ($estado === "aceptada") {
        return t("status_accepted");
    }

    if ($estado === "rechazada") {
        return t("status_rejected");
    }

    return t("status_pending");
}

function estadoClase($estado) {
    $estado = trim($estado ?? "pendiente");

    if ($estado === "revisada") {
        return "revision";
    }

    if ($estado === "aceptada") {
        return "aceptada";
    }

    if ($estado === "rechazada") {
        return "rechazada";
    }

    return "enviada";
}

function etiquetaModalidad($modalidad) {
    $normalizada = normalizarModalidad($modalidad);

    if ($normalizada === "remote") {
        return t("remote");
    }

    if ($normalizada === "hybrid") {
        return t("hybrid");
    }

    if ($normalizada === "on-site") {
        return t("onsite");
    }

    return $modalidad !== "" ? $modalidad : t("work_mode_not_specified");
}

$nombreUsuario = $usuarioActual["nombre"] ?? "Candidate";
$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$modoOscuro = !empty($usuarioActual["modo_oscuro"]) ? 1 : 0;
$altoContraste = !empty($usuarioActual["alto_contraste"]) ? 1 : 0;
$modoLectura = !empty($usuarioActual["modo_lectura"]) ? 1 : 0;
$escalaTexto = isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

try {
    $sql = "
        SELECT
            postulaciones.id_postulacion,
            postulaciones.id_usuario,
            postulaciones.id_vacante,
            postulaciones.mensaje,
            postulaciones.cv_archivo,
            postulaciones.estado,
            postulaciones.fecha_postulacion,
            vacantes.titulo,
            vacantes.titulo_en,
            vacantes.titulo_es,
            vacantes.modalidad,
            vacantes.ubicacion,
            empresas.nombre AS empresa_nombre
        FROM postulaciones
        INNER JOIN vacantes
            ON postulaciones.id_vacante = vacantes.id_vacante
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        WHERE postulaciones.id_usuario = :id_usuario
        ORDER BY postulaciones.fecha_postulacion DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_usuario" => $idUsuario
    ]);

    $postulaciones = $stmt->fetchAll();
} catch (PDOException $e) {
    $errorCarga = t("error_loading_applications");
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

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/postulaciones.css?v=20260904footerfinal">
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

                <li>
                    <a href="empleos.php"<?php echo enlaceActivo("empleos.php"); ?>>
                        <?php echo limpiar(t("nav_find_jobs")); ?>
                    </a>
                </li>

                <li>
                    <a href="empresas.php"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>

                <li>
                    <a href="postulaciones.php"<?php echo enlaceActivo("postulaciones.php"); ?>>
                        <?php echo limpiar(t("nav_my_applications")); ?>
                    </a>
                </li>

                <?php if ($esAdmin): ?>
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

                <li>
                    <a href="perfil.php"<?php echo enlaceActivo("perfil.php"); ?>>
                        <?php echo limpiar(t("nav_profile")); ?>
                    </a>
                </li>
            </ul>

            <div class="nav-actions">
                <a href="perfil.php" class="login-link">
                    <?php echo limpiar(t("nav_my_profile")); ?>
                </a>

                <a href="logout.php" class="button button-primary button-small">
                    <?php echo limpiar(t("nav_logout")); ?>
                </a>

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

    <main class="postulaciones-page">

        <section class="applications-hero">
            <div class="container">

                <div class="titulo-seccion">
                    <span class="section-label"><?php echo limpiar(t("hero_label")); ?></span>
                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_greeting")); ?>, <?php echo limpiar($nombreUsuario); ?>.
                        <?php echo limpiar(t("hero_text")); ?>
                    </p>
                </div>

            </div>
        </section>

        <section class="applications-content">
            <div class="container">

                <div class="filtros" id="applicationFilters">
                    <button class="filtro activo" type="button" data-filter="all">
                        <?php echo limpiar(t("filter_all")); ?>
                    </button>

                    <button class="filtro" type="button" data-filter="pendiente">
                        <?php echo limpiar(t("filter_pending")); ?>
                    </button>

                    <button class="filtro" type="button" data-filter="revisada">
                        <?php echo limpiar(t("filter_reviewed")); ?>
                    </button>

                    <button class="filtro" type="button" data-filter="aceptada">
                        <?php echo limpiar(t("filter_accepted")); ?>
                    </button>

                    <button class="filtro" type="button" data-filter="rechazada">
                        <?php echo limpiar(t("filter_rejected")); ?>
                    </button>
                </div>

                <?php if (!empty($errorCarga)): ?>
                    <div class="no-applications">
                        <i class="fa-solid fa-triangle-exclamation"
                            role="img"
                            aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                            title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                        <h3><?php echo limpiar($errorCarga); ?></h3>
                        <p><?php echo limpiar(t("check_database")); ?></p>
                    </div>
                <?php endif; ?>

                <div class="postulaciones-grid" id="applicationsGrid">

                    <?php foreach ($postulaciones as $postulacion): ?>
                        <?php
                            $estado = $postulacion["estado"] ?? "pendiente";
                            $cvArchivo = $postulacion["cv_archivo"] ?? "";
                            $rutaCV = rutaCurriculum($cvArchivo);
                            $cvDisponible = !empty($rutaCV) && file_exists($rutaCV);
                            $tituloVacante = campoIdioma($postulacion, "titulo");
                            $modalidadTexto = etiquetaModalidad($postulacion["modalidad"] ?? "");
                            $ubicacionTexto = $postulacion["ubicacion"] ?? "";

                            if ($tituloVacante === "") {
                                $tituloVacante = t("job_opening");
                            }
                        ?>

                        <article class="postulacion-card" data-status="<?php echo limpiar($estado); ?>">

                            <div class="card-header">
                                <h3><?php echo limpiar($tituloVacante); ?></h3>

                                <span class="estado <?php echo limpiar(estadoClase($estado)); ?>">
                                    <?php echo limpiar(estadoTexto($estado)); ?>
                                </span>
                            </div>

                            <p>
                                <i class="fa-solid fa-building"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("building_icon")); ?>"
                                    title="<?php echo limpiar(t("building_icon")); ?>"></i>
                                <?php echo limpiar($postulacion["empresa_nombre"] ?? t("company")); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-location-dot"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                    title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                <?php echo limpiar($ubicacionTexto !== "" ? $ubicacionTexto : t("location_not_specified")); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-house-laptop"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("work_mode_icon")); ?>"
                                    title="<?php echo limpiar(t("work_mode_icon")); ?>"></i>
                                <?php echo limpiar($modalidadTexto); ?>
                            </p>

                            <small>
                                <?php echo limpiar(t("applied_on")); ?> <?php echo limpiar(fechaTexto($postulacion["fecha_postulacion"] ?? "")); ?>
                            </small>

                            <?php if (!empty($postulacion["mensaje"])): ?>
                                <p class="application-message-preview">
                                    <strong><?php echo limpiar(t("message")); ?></strong>
                                    <?php echo limpiar($postulacion["mensaje"]); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($cvArchivo)): ?>
                                <div class="application-file-box">
                                    <div>
                                        <i class="fa-solid fa-file-lines"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                            title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                        <span><?php echo limpiar(t("curriculum_attached")); ?></span>
                                    </div>

                                    <?php if ($cvDisponible): ?>
                                        <a href="<?php echo limpiar($rutaCV); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo limpiar(t("view_curriculum")); ?>
                                            <i class="fa-solid fa-arrow-up-right-from-square"
                                                role="img"
                                                aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                                title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                        </a>
                                    <?php else: ?>
                                        <small><?php echo limpiar(t("file_not_found")); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="application-file-box application-file-empty">
                                    <div>
                                        <i class="fa-solid fa-file-circle-xmark"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("file_missing_icon")); ?>"
                                            title="<?php echo limpiar(t("file_missing_icon")); ?>"></i>
                                        <span><?php echo limpiar(t("no_curriculum_attached")); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="company-card-footer">
                                <a href="detalle-empleo.php?id=<?php echo (int)$postulacion["id_vacante"]; ?>&lang=<?php echo limpiar($idiomaActual); ?>" class="text-link">
                                    <?php echo limpiar(t("view_job")); ?>
                                    <i class="fa-solid fa-arrow-right"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                                        title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                </a>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

                <div class="no-applications <?php echo count($postulaciones) > 0 ? "hidden" : ""; ?>" id="noApplications">
                    <i class="fa-solid fa-folder-open"
                        role="img"
                        aria-label="<?php echo limpiar(t("folder_icon")); ?>"
                        title="<?php echo limpiar(t("folder_icon")); ?>"></i>
                    <h3><?php echo limpiar(t("no_applications_title")); ?></h3>
                    <p><?php echo limpiar(t("no_applications_text")); ?></p>

                    <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-primary">
                        <?php echo limpiar(t("find_jobs")); ?>
                        <i class="fa-solid fa-arrow-right"
                            role="img"
                            aria-label="<?php echo limpiar(t("arrow_icon")); ?>"
                            title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                    </a>
                </div>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            isLoggedIn: true,
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
    <script src="java/postulaciones.js?v=20260904footerfinal"></script>

</body>

</html>
