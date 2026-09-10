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
$mensaje = "";
$claseMensaje = "";
$postulaciones = [];
$usuarioEmpresa = null;

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

    if (
        strpos($cvArchivo, "://") !== false ||
        strpos($cvArchivo, "../") !== false ||
        substr($cvArchivo, 0, 1) === "/"
    ) {
        return "";
    }

    if (strpos($cvArchivo, "cv/") === 0) {
        return $cvArchivo;
    }

    return "cv/" . $cvArchivo;
}

try {
    $sqlUsuario = "
        SELECT *
        FROM usuarios
        WHERE id_usuario = :id_usuario
        LIMIT 1
    ";

    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([
        ":id_usuario" => $idUsuario
    ]);

    $usuarioEmpresa = $stmtUsuario->fetch();

    if (!$usuarioEmpresa) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    exit("There was an error loading your account.");
}

$tipoUsuario = $usuarioEmpresa["tipo_usuario"] ?? "";
$_SESSION["tipo_usuario"] = $tipoUsuario;
$_SESSION["nombre"] = $usuarioEmpresa["nombre"] ?? ($_SESSION["nombre"] ?? "Company");

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif (!empty($usuarioEmpresa["idioma_preferido"])) {
    $idiomaActual = $usuarioEmpresa["idioma_preferido"];
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
    } catch (PDOException $e) {
        $_SESSION["idioma_preferido"] = $idiomaActual;
    }
}

$translations = [
    "en" => [
        "page_title" => "Company Applications | SkillBridge",
        "meta_description" => "Review candidates who applied to your company job openings on SkillBridge.",
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
        "email_icon" => "Email icon",
        "phone_icon" => "Phone icon",
        "location_icon" => "Location icon",
        "calendar_icon" => "Calendar icon",
        "file_icon" => "File icon",
        "warning_icon" => "Warning icon",
        "check_icon" => "Success icon",
        "plus_icon" => "Add icon",
        "arrow_icon" => "Arrow icon",
        "folder_icon" => "Folder icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",
        "send_icon" => "Send icon",
        "invalid_status" => "Invalid application status.",
        "update_error" => "There was an error updating the application.",
        "updated_success" => "Application status updated successfully.",
        "load_error" => "There was an error loading applications.",
        "company_dashboard" => "Company dashboard",
        "applications_received" => "Applications received",
        "hero_text" => "Review candidates who applied to your job openings and update their status.",
        "total_applications" => "Total applications",
        "application_singular" => "application",
        "application_plural" => "applications",
        "summary_text" => "Manage candidate applications from one place.",
        "post_another_job" => "Post another job",
        "all" => "All",
        "pending" => "Pending",
        "reviewed" => "Reviewed",
        "accepted" => "Accepted",
        "rejected" => "Rejected",
        "no_date" => "No date available",
        "applied_on" => "Applied on",
        "candidate_message" => "Candidate message",
        "no_message" => "No message added.",
        "accessibility_information" => "Accessibility information",
        "disability_registered" => "Disability registered:",
        "type" => "Type:",
        "yes" => "Yes",
        "no" => "No",
        "not_specified" => "Not specified",
        "profile_description" => "Profile description",
        "no_phone" => "No phone added",
        "no_location" => "No location added",
        "curriculum_attached" => "Curriculum attached",
        "profile_curriculum" => "Profile curriculum available",
        "view_curriculum" => "View curriculum",
        "file_not_found" => "File path saved, but the file was not found.",
        "no_curriculum" => "No curriculum attached",
        "view_job" => "View job",
        "update_status" => "Update status",
        "save" => "Save",
        "no_applications_title" => "No applications received yet.",
        "no_applications_text" => "When candidates apply to your job openings, their applications will appear here.",
        "footer_description" => "We connect talent, companies, and opportunities to build a more accessible future of work.",
        "footer_for_companies" => "For companies",
        "footer_platform" => "Platform",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_contact" => "Contact the team",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],
    "es" => [
        "page_title" => "Postulaciones recibidas | SkillBridge",
        "meta_description" => "Revisa los candidatos que aplicaron a las vacantes de tu empresa en SkillBridge.",
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
        "email_icon" => "Ícono de correo electrónico",
        "phone_icon" => "Ícono de teléfono",
        "location_icon" => "Ícono de ubicación",
        "calendar_icon" => "Ícono de calendario",
        "file_icon" => "Ícono de archivo",
        "warning_icon" => "Ícono de advertencia",
        "check_icon" => "Ícono de éxito",
        "plus_icon" => "Ícono de agregar",
        "arrow_icon" => "Ícono de flecha",
        "folder_icon" => "Ícono de carpeta",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",
        "send_icon" => "Ícono de enviar",
        "invalid_status" => "Estado de postulación inválido.",
        "update_error" => "Hubo un error al actualizar la postulación.",
        "updated_success" => "El estado de la postulación se actualizó correctamente.",
        "load_error" => "Hubo un error al cargar las postulaciones.",
        "company_dashboard" => "Panel de empresa",
        "applications_received" => "Postulaciones recibidas",
        "hero_text" => "Revisa los candidatos que aplicaron a tus vacantes y actualiza su estado.",
        "total_applications" => "Total de postulaciones",
        "application_singular" => "postulación",
        "application_plural" => "postulaciones",
        "summary_text" => "Administra las postulaciones de candidatos desde un solo lugar.",
        "post_another_job" => "Publicar otra vacante",
        "all" => "Todas",
        "pending" => "Pendiente",
        "reviewed" => "Revisada",
        "accepted" => "Aceptada",
        "rejected" => "Rechazada",
        "no_date" => "Sin fecha disponible",
        "applied_on" => "Aplicó el",
        "candidate_message" => "Mensaje del candidato",
        "no_message" => "No se agregó ningún mensaje.",
        "accessibility_information" => "Información de accesibilidad",
        "disability_registered" => "Discapacidad registrada:",
        "type" => "Tipo:",
        "yes" => "Sí",
        "no" => "No",
        "not_specified" => "No especificado",
        "profile_description" => "Descripción del perfil",
        "no_phone" => "Sin teléfono agregado",
        "no_location" => "Sin ubicación agregada",
        "curriculum_attached" => "Currículum adjunto",
        "profile_curriculum" => "Currículum del perfil disponible",
        "view_curriculum" => "Ver currículum",
        "file_not_found" => "La ruta del archivo está guardada, pero el archivo no se encontró.",
        "no_curriculum" => "Sin currículum adjunto",
        "view_job" => "Ver vacante",
        "update_status" => "Actualizar estado",
        "save" => "Guardar",
        "no_applications_title" => "Aún no hay postulaciones recibidas.",
        "no_applications_text" => "Cuando los candidatos apliquen a tus vacantes, sus postulaciones aparecerán aquí.",
        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.",
        "footer_for_companies" => "Para empresas",
        "footer_platform" => "Plataforma",
        "footer_about" => "Quiénes somos",
        "footer_accessibility" => "Accesibilidad",
        "footer_privacy" => "Privacidad",
        "footer_terms" => "Términos y condiciones",
        "footer_contact" => "Contactar al equipo",
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
    unset($params["updated"]);

    return "postulaciones-empresa.php?" . http_build_query($params);
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
        return t("reviewed");
    }

    if ($estado === "aceptada") {
        return t("accepted");
    }

    if ($estado === "rechazada") {
        return t("rejected");
    }

    return t("pending");
}

function estadoClase($estado) {
    if ($estado === "revisada") return "revision";
    if ($estado === "aceptada") return "aceptada";
    if ($estado === "rechazada") return "rechazada";
    return "enviada";
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

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;

if (!$esEmpresaOAdmin) {
    header("Location: perfil.php");
    exit;
}

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);
$nombreEmpresaSesion = $usuarioEmpresa["nombre"] ?? "Company";

$modoOscuro = !empty($usuarioEmpresa["modo_oscuro"]) ? 1 : 0;
$altoContraste = !empty($usuarioEmpresa["alto_contraste"]) ? 1 : 0;
$modoLectura = !empty($usuarioEmpresa["modo_lectura"]) ? 1 : 0;
$escalaTexto = isset($usuarioEmpresa["escala_texto"]) ? (float)$usuarioEmpresa["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "actualizar_estado") {
    $idPostulacion = (int)($_POST["id_postulacion"] ?? 0);
    $nuevoEstado = $_POST["estado"] ?? "";
    $estadosPermitidos = ["pendiente", "revisada", "aceptada", "rechazada"];

    if ($idPostulacion <= 0 || !in_array($nuevoEstado, $estadosPermitidos, true)) {
        $mensaje = t("invalid_status");
        $claseMensaje = "error";
    } else {
        try {
            if ($esAdmin) {
                $sqlActualizar = "
                    UPDATE postulaciones
                    SET estado = :estado
                    WHERE id_postulacion = :id_postulacion
                ";

                $stmtActualizar = $pdo->prepare($sqlActualizar);
                $stmtActualizar->execute([
                    ":estado" => $nuevoEstado,
                    ":id_postulacion" => $idPostulacion
                ]);
            } else {
                $sqlActualizar = "
                    UPDATE postulaciones
                    INNER JOIN vacantes
                        ON postulaciones.id_vacante = vacantes.id_vacante
                    INNER JOIN empresas
                        ON vacantes.id_empresa = empresas.id_empresa
                    SET postulaciones.estado = :estado
                    WHERE postulaciones.id_postulacion = :id_postulacion
                      AND empresas.nombre = :nombre_empresa
                ";

                $stmtActualizar = $pdo->prepare($sqlActualizar);
                $stmtActualizar->execute([
                    ":estado" => $nuevoEstado,
                    ":id_postulacion" => $idPostulacion,
                    ":nombre_empresa" => $nombreEmpresaSesion
                ]);
            }

            header("Location: postulaciones-empresa.php?updated=1&lang=" . urlencode($idiomaActual));
            exit;
        } catch (PDOException $e) {
            $mensaje = t("update_error");
            $claseMensaje = "error";
        }
    }
}

if (isset($_GET["updated"]) && $_GET["updated"] === "1") {
    $mensaje = t("updated_success");
    $claseMensaje = "success";
}

try {
    $condicionEmpresa = "";
    $parametros = [];

    if (!$esAdmin) {
        $condicionEmpresa = "AND empresas.nombre = :nombre_empresa";
        $parametros[":nombre_empresa"] = $nombreEmpresaSesion;
    }

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
            empresas.nombre AS empresa_nombre,
            usuarios.nombre AS candidato_nombre,
            usuarios.correo AS candidato_correo,
            usuarios.telefono AS candidato_telefono,
            usuarios.ubicacion AS candidato_ubicacion,
            usuarios.descripcion AS candidato_descripcion,
            usuarios.discapacidad,
            usuarios.tipo_discapacidad,
            usuarios.curriculum AS candidato_curriculum
        FROM postulaciones
        INNER JOIN vacantes
            ON postulaciones.id_vacante = vacantes.id_vacante
        INNER JOIN empresas
            ON vacantes.id_empresa = empresas.id_empresa
        INNER JOIN usuarios
            ON postulaciones.id_usuario = usuarios.id_usuario
        WHERE 1 = 1
        $condicionEmpresa
        ORDER BY postulaciones.fecha_postulacion DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $postulaciones = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensaje = t("load_error");
    $claseMensaje = "error";
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
$totalPostulaciones = count($postulaciones);
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
            <button class="accessibility-option" id="increaseFont" aria-label="<?php echo limpiar(t("increase_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-plus" role="img" aria-label="<?php echo limpiar(t("increase_text")); ?>" title="<?php echo limpiar(t("increase_text")); ?>"></i>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont" aria-label="<?php echo limpiar(t("decrease_text")); ?>">
                <i class="fa-solid fa-magnifying-glass-minus" role="img" aria-label="<?php echo limpiar(t("decrease_text")); ?>" title="<?php echo limpiar(t("decrease_text")); ?>"></i>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode" aria-label="<?php echo limpiar(t("dark_mode")); ?>">
                <i class="fa-solid fa-moon" role="img" aria-label="<?php echo limpiar(t("dark_mode")); ?>" title="<?php echo limpiar(t("dark_mode")); ?>"></i>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast" aria-label="<?php echo limpiar(t("high_contrast")); ?>">
                <i class="fa-solid fa-circle-half-stroke" role="img" aria-label="<?php echo limpiar(t("high_contrast")); ?>" title="<?php echo limpiar(t("high_contrast")); ?>"></i>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage" aria-label="<?php echo limpiar(t("read_mode")); ?>">
                <i class="fa-solid fa-volume-high" role="img" aria-label="<?php echo limpiar(t("read_mode")); ?>" title="<?php echo limpiar(t("read_mode")); ?>"></i>
                <span><?php echo limpiar(t("read_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading" aria-label="<?php echo limpiar(t("stop_reading")); ?>">
                <i class="fa-solid fa-volume-xmark" role="img" aria-label="<?php echo limpiar(t("stop_reading")); ?>" title="<?php echo limpiar(t("stop_reading")); ?>"></i>
                <span><?php echo limpiar(t("stop_reading")); ?></span>
            </button>
        </div>
    </aside>

    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="logo" aria-label="<?php echo limpiar(t("aria_logo")); ?>" title="<?php echo limpiar(t("aria_logo")); ?>">
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
                <i class="fa-solid fa-bars" role="img" aria-label="<?php echo limpiar(t("menu_icon")); ?>" title="<?php echo limpiar(t("menu_icon")); ?>"></i>
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

                <?php if ($esAdmin): ?>
                    <li>
                        <a href="postulaciones.php"<?php echo enlaceActivo("postulaciones.php"); ?>>
                            <?php echo limpiar(t("nav_my_applications")); ?>
                        </a>
                    </li>
                <?php endif; ?>

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
                    <i class="fa-solid fa-language" role="img" aria-label="<?php echo limpiar(t("language_icon")); ?>" title="<?php echo limpiar(t("language_icon")); ?>"></i>
                    <?php echo limpiar($etiquetaIdiomaSiguiente); ?>
                </a>
            </div>
        </nav>
    </header>

    <main class="postulaciones-page">
        <section class="applications-hero">
            <div class="container">
                <div class="titulo-seccion">
                    <span class="section-label"><?php echo limpiar(t("company_dashboard")); ?></span>
                    <h1><?php echo limpiar(t("applications_received")); ?></h1>
                    <p><?php echo limpiar(t("hero_text")); ?></p>
                </div>
            </div>
        </section>

        <section class="applications-content">
            <div class="container">
                <?php if (!empty($mensaje)): ?>
                    <div class="application-alert <?php echo limpiar($claseMensaje); ?>">
                        <?php if ($claseMensaje === "success"): ?>
                            <i class="fa-solid fa-circle-check" role="img" aria-label="<?php echo limpiar(t("check_icon")); ?>" title="<?php echo limpiar(t("check_icon")); ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-triangle-exclamation" role="img" aria-label="<?php echo limpiar(t("warning_icon")); ?>" title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                        <?php endif; ?>
                        <span><?php echo limpiar($mensaje); ?></span>
                    </div>
                <?php endif; ?>

                <div class="applications-summary">
                    <div>
                        <span class="section-label"><?php echo limpiar(t("total_applications")); ?></span>
                        <h2>
                            <?php echo $totalPostulaciones; ?>
                            <?php echo limpiar($totalPostulaciones === 1 ? t("application_singular") : t("application_plural")); ?>
                        </h2>
                        <p><?php echo limpiar(t("summary_text")); ?></p>
                    </div>

                    <a href="publicarvacante.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-primary">
                        <?php echo limpiar(t("post_another_job")); ?>
                        <i class="fa-solid fa-plus" role="img" aria-label="<?php echo limpiar(t("plus_icon")); ?>" title="<?php echo limpiar(t("plus_icon")); ?>"></i>
                    </a>
                </div>

                <div class="filtros" id="applicationFilters">
                    <button class="filtro activo" type="button" data-filter="all"><?php echo limpiar(t("all")); ?></button>
                    <button class="filtro" type="button" data-filter="pendiente"><?php echo limpiar(t("pending")); ?></button>
                    <button class="filtro" type="button" data-filter="revisada"><?php echo limpiar(t("reviewed")); ?></button>
                    <button class="filtro" type="button" data-filter="aceptada"><?php echo limpiar(t("accepted")); ?></button>
                    <button class="filtro" type="button" data-filter="rechazada"><?php echo limpiar(t("rejected")); ?></button>
                </div>

                <div class="postulaciones-grid" id="applicationsGrid">
                    <?php foreach ($postulaciones as $postulacion): ?>
                        <?php
                            $estado = $postulacion["estado"] ?? "pendiente";
                            $tituloVacante = campoIdioma($postulacion, "titulo");

                            if ($tituloVacante === "") {
                                $tituloVacante = t("nav_post_job");
                            }

                            $cvAplicacion = $postulacion["cv_archivo"] ?? "";
                            $cvPerfil = $postulacion["candidato_curriculum"] ?? "";
                            $cvArchivo = !empty($cvAplicacion) ? $cvAplicacion : $cvPerfil;
                            $rutaCV = rutaCurriculum($cvArchivo);
                            $cvDisponible = !empty($rutaCV) && file_exists($rutaCV);
                            $tieneDiscapacidad = !empty($postulacion["discapacidad"]) && $postulacion["discapacidad"] == 1 ? t("yes") : t("no");
                            $tipoDiscapacidad = !empty($postulacion["tipo_discapacidad"]) ? $postulacion["tipo_discapacidad"] : t("not_specified");
                        ?>

                        <article class="postulacion-card" data-status="<?php echo limpiar($estado); ?>">
                            <div class="card-header">
                                <div>
                                    <h3><?php echo limpiar($postulacion["candidato_nombre"]); ?></h3>
                                    <p class="card-subtitle"><?php echo limpiar($tituloVacante); ?></p>
                                </div>

                                <span class="estado <?php echo limpiar(estadoClase($estado)); ?>">
                                    <?php echo limpiar(estadoTexto($estado)); ?>
                                </span>
                            </div>

                            <p>
                                <i class="fa-solid fa-envelope" role="img" aria-label="<?php echo limpiar(t("email_icon")); ?>" title="<?php echo limpiar(t("email_icon")); ?>"></i>
                                <?php echo limpiar($postulacion["candidato_correo"]); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-phone" role="img" aria-label="<?php echo limpiar(t("phone_icon")); ?>" title="<?php echo limpiar(t("phone_icon")); ?>"></i>
                                <?php echo limpiar($postulacion["candidato_telefono"] ?: t("no_phone")); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-location-dot" role="img" aria-label="<?php echo limpiar(t("location_icon")); ?>" title="<?php echo limpiar(t("location_icon")); ?>"></i>
                                <?php echo limpiar($postulacion["candidato_ubicacion"] ?: t("no_location")); ?>
                            </p>

                            <p>
                                <i class="fa-solid fa-calendar-days" role="img" aria-label="<?php echo limpiar(t("calendar_icon")); ?>" title="<?php echo limpiar(t("calendar_icon")); ?>"></i>
                                <?php echo limpiar(t("applied_on")); ?> <?php echo limpiar(fechaTexto($postulacion["fecha_postulacion"])); ?>
                            </p>

                            <div class="application-message-preview">
                                <strong><?php echo limpiar(t("candidate_message")); ?></strong>
                                <?php echo limpiar($postulacion["mensaje"] ?: t("no_message")); ?>
                            </div>

                            <div class="application-message-preview">
                                <strong><?php echo limpiar(t("accessibility_information")); ?></strong>
                                <?php echo limpiar(t("disability_registered")); ?> <?php echo limpiar($tieneDiscapacidad); ?><br>
                                <?php echo limpiar(t("type")); ?> <?php echo limpiar($tipoDiscapacidad); ?>
                            </div>

                            <?php if (!empty($postulacion["candidato_descripcion"])): ?>
                                <div class="application-message-preview">
                                    <strong><?php echo limpiar(t("profile_description")); ?></strong>
                                    <?php echo limpiar($postulacion["candidato_descripcion"]); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($cvArchivo)): ?>
                                <div class="application-file-box">
                                    <div>
                                        <i class="fa-solid fa-file-lines" role="img" aria-label="<?php echo limpiar(t("file_icon")); ?>" title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                        <span>
                                            <?php echo limpiar(!empty($cvAplicacion) ? t("curriculum_attached") : t("profile_curriculum")); ?>
                                        </span>
                                    </div>

                                    <?php if ($cvDisponible): ?>
                                        <a href="<?php echo limpiar($rutaCV); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo limpiar(t("view_curriculum")); ?>
                                            <i class="fa-solid fa-arrow-up-right-from-square" role="img" aria-label="<?php echo limpiar(t("arrow_icon")); ?>" title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                        </a>
                                    <?php else: ?>
                                        <small><?php echo limpiar(t("file_not_found")); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="application-file-box application-file-empty">
                                    <div>
                                        <i class="fa-solid fa-file-circle-xmark" role="img" aria-label="<?php echo limpiar(t("file_icon")); ?>" title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                        <span><?php echo limpiar(t("no_curriculum")); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card-actions">
                                <a href="detalle-empleo.php?id=<?php echo (int)$postulacion["id_vacante"]; ?>&lang=<?php echo limpiar($idiomaActual); ?>"
                                    class="button button-secondary button-small">
                                    <?php echo limpiar(t("view_job")); ?>
                                    <i class="fa-solid fa-arrow-right" role="img" aria-label="<?php echo limpiar(t("arrow_icon")); ?>" title="<?php echo limpiar(t("arrow_icon")); ?>"></i>
                                </a>
                            </div>

                            <form action="postulaciones-empresa.php?lang=<?php echo limpiar($idiomaActual); ?>" method="POST" class="status-form">
                                <input type="hidden" name="accion" value="actualizar_estado">
                                <input type="hidden" name="id_postulacion" value="<?php echo (int)$postulacion["id_postulacion"]; ?>">

                                <label for="estado_<?php echo (int)$postulacion["id_postulacion"]; ?>">
                                    <?php echo limpiar(t("update_status")); ?>
                                </label>

                                <div class="status-row">
                                    <select name="estado" id="estado_<?php echo (int)$postulacion["id_postulacion"]; ?>">
                                        <option value="pendiente" <?php echo $estado === "pendiente" ? "selected" : ""; ?>><?php echo limpiar(t("pending")); ?></option>
                                        <option value="revisada" <?php echo $estado === "revisada" ? "selected" : ""; ?>><?php echo limpiar(t("reviewed")); ?></option>
                                        <option value="aceptada" <?php echo $estado === "aceptada" ? "selected" : ""; ?>><?php echo limpiar(t("accepted")); ?></option>
                                        <option value="rechazada" <?php echo $estado === "rechazada" ? "selected" : ""; ?>><?php echo limpiar(t("rejected")); ?></option>
                                    </select>

                                    <button type="submit" class="button button-primary button-small">
                                        <?php echo limpiar(t("save")); ?>
                                    </button>
                                </div>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="no-applications <?php echo $totalPostulaciones > 0 ? "hidden" : ""; ?>" id="noApplications">
                    <i class="fa-solid fa-folder-open" role="img" aria-label="<?php echo limpiar(t("folder_icon")); ?>" title="<?php echo limpiar(t("folder_icon")); ?>"></i>
                    <h3><?php echo limpiar(t("no_applications_title")); ?></h3>
                    <p><?php echo limpiar(t("no_applications_text")); ?></p>

                    <a href="publicarvacante.php?lang=<?php echo limpiar($idiomaActual); ?>" class="button button-primary">
                        <?php echo limpiar(t("nav_post_job")); ?>
                        <i class="fa-solid fa-plus" role="img" aria-label="<?php echo limpiar(t("plus_icon")); ?>" title="<?php echo limpiar(t("plus_icon")); ?>"></i>
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
