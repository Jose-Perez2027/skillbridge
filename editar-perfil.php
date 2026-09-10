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
$errorMessage = "";

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function enlaceActivo($pagina) {
    global $paginaActual;

    if ($pagina === "perfil.php" && $paginaActual === "editar-perfil.php") {
        return ' class="active"';
    }

    return $paginaActual === $pagina ? ' class="active"' : '';
}

function crearCarpetaSiNoExiste($carpeta) {
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
}

function subirFotoPerfil($archivo, $idUsuario, &$errorMessage, $idiomaActual) {
    if (!isset($archivo) || $archivo["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        $errorMessage = $idiomaActual === "es"
            ? "Hubo un error al subir la foto de perfil."
            : "There was an error uploading your profile photo.";
        return null;
    }

    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $extensionesPermitidas = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($extension, $extensionesPermitidas, true)) {
        $errorMessage = $idiomaActual === "es"
            ? "La foto de perfil debe ser JPG, JPEG, PNG o WEBP."
            : "Profile photo must be JPG, JPEG, PNG, or WEBP.";
        return null;
    }

    if ($archivo["size"] > 2 * 1024 * 1024) {
        $errorMessage = $idiomaActual === "es"
            ? "La foto de perfil debe pesar menos de 2 MB."
            : "Profile photo must be less than 2 MB.";
        return null;
    }

    $infoImagen = @getimagesize($archivo["tmp_name"]);

    if ($infoImagen === false) {
        $errorMessage = $idiomaActual === "es"
            ? "La foto seleccionada no es una imagen válida."
            : "The selected profile photo is not a valid image.";
        return null;
    }

    $carpeta = "img/perfiles/";
    crearCarpetaSiNoExiste($carpeta);

    $nombreArchivo = "perfil_" . $idUsuario . "_" . time() . "." . $extension;
    $rutaDestino = $carpeta . $nombreArchivo;

    if (!move_uploaded_file($archivo["tmp_name"], $rutaDestino)) {
        $errorMessage = $idiomaActual === "es"
            ? "La foto de perfil no pudo guardarse."
            : "The profile photo could not be saved.";
        return null;
    }

    return $rutaDestino;
}

function subirCurriculum($archivo, $idUsuario, &$errorMessage, $idiomaActual) {
    if (!isset($archivo) || $archivo["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        $errorMessage = $idiomaActual === "es"
            ? "Hubo un error al subir tu currículum."
            : "There was an error uploading your curriculum.";
        return null;
    }

    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $extensionesPermitidas = ["pdf", "doc", "docx"];

    if (!in_array($extension, $extensionesPermitidas, true)) {
        $errorMessage = $idiomaActual === "es"
            ? "El currículum debe ser PDF, DOC o DOCX."
            : "Curriculum must be PDF, DOC, or DOCX.";
        return null;
    }

    if ($archivo["size"] > 5 * 1024 * 1024) {
        $errorMessage = $idiomaActual === "es"
            ? "El currículum debe pesar menos de 5 MB."
            : "Curriculum must be less than 5 MB.";
        return null;
    }

    $carpeta = "cv/";
    crearCarpetaSiNoExiste($carpeta);

    $nombreArchivo = "cv_" . $idUsuario . "_" . time() . "." . $extension;
    $rutaDestino = $carpeta . $nombreArchivo;

    if (!move_uploaded_file($archivo["tmp_name"], $rutaDestino)) {
        $errorMessage = $idiomaActual === "es"
            ? "El currículum no pudo guardarse."
            : "The curriculum could not be saved.";
        return null;
    }

    return $rutaDestino;
}

try {
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1";
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
        "page_title" => "Edit Profile | SkillBridge",
        "meta_description" => "Edit your SkillBridge profile information, contact details, profile photo, and accessibility preferences.",

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

        "language_icon" => "Language icon",
        "accessibility_icon" => "Accessibility icon",
        "close_icon" => "Close icon",
        "menu_icon" => "Menu icon",
        "camera_icon" => "Camera icon",
        "file_icon" => "File icon",
        "user_icon" => "User icon",
        "phone_icon" => "Phone icon",
        "location_icon" => "Location icon",
        "quote_icon" => "Quote icon",
        "users_icon" => "Users icon",
        "save_icon" => "Save icon",
        "warning_icon" => "Warning icon",

        "company_account" => "Company account",
        "admin_account" => "Administrator account",
        "candidate_account" => "Candidate account",

        "profile_settings" => "Profile settings",
        "edit_company_profile" => "Edit your company profile",
        "edit_candidate_profile" => "Edit your profile",
        "company_intro" => "Complete your company information, profile image, and contact details so candidates can learn more about your organization.",
        "candidate_intro" => "Complete your information, profile photo, curriculum, and accessibility details so companies can know more about you.",

        "company_image" => "Company image",
        "profile_photo" => "Profile photo",
        "upload_new_photo" => "Upload new photo",
        "photo_requirements" => "JPG, PNG or WEBP. Max 2 MB.",
        "curriculum" => "Curriculum",
        "view_current_curriculum" => "View current curriculum",
        "no_curriculum" => "No curriculum uploaded yet.",
        "upload_curriculum" => "Upload curriculum",
        "curriculum_requirements" => "PDF, DOC or DOCX. Max 5 MB.",

        "name_company" => "Company name",
        "name_candidate" => "Full name",
        "phone_number" => "Phone number",
        "phone_placeholder" => "Example: +503 7000-0000",
        "location" => "Location",
        "location_placeholder" => "Example: Soyapango, San Salvador",
        "company_slogan" => "Company slogan",
        "company_slogan_placeholder" => "Example: Technology with purpose",
        "collaborators" => "Collaborators",
        "collaborators_placeholder" => "Example: 50+ employees",
        "company_description" => "Company description",
        "profile_description" => "Profile description",
        "company_description_placeholder" => "Write a short description about your company, values, work environment, or hiring goals.",
        "profile_description_placeholder" => "Write a short description about your skills, goals, experience, or work interests.",
        "register_disability" => "I want to register disability or accessibility information.",
        "disability_need" => "Disability or accessibility need",
        "disability_placeholder" => "Example: visual, hearing, mobility, cognitive, other",
        "disability_help" => "This information helps companies understand accessibility needs.",
        "cancel" => "Cancel",
        "save_changes" => "Save changes",

        "error_name_empty" => "The name cannot be empty.",
        "error_name_short" => "The name must be at least 3 characters long.",
        "error_name_long" => "The name is too long.",
        "error_phone_long" => "The phone number is too long.",
        "error_location_long" => "The location is too long.",
        "error_description_long" => "The description is too long.",
        "error_disability_long" => "The accessibility need is too long.",
        "error_slogan_long" => "The company slogan is too long.",
        "error_collaborators_long" => "The collaborators field is too long.",
        "error_update" => "There was an error updating your profile. Please try again.",

        "footer_rights" => "© 2026 SkillBridge. All rights reserved.",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions"
    ],

    "es" => [
        "page_title" => "Editar perfil | SkillBridge",
        "meta_description" => "Edita tu información de perfil en SkillBridge, datos de contacto, foto de perfil y preferencias de accesibilidad.",

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

        "language_icon" => "Ícono de idioma",
        "accessibility_icon" => "Ícono de accesibilidad",
        "close_icon" => "Ícono de cerrar",
        "menu_icon" => "Ícono de menú",
        "camera_icon" => "Ícono de cámara",
        "file_icon" => "Ícono de archivo",
        "user_icon" => "Ícono de usuario",
        "phone_icon" => "Ícono de teléfono",
        "location_icon" => "Ícono de ubicación",
        "quote_icon" => "Ícono de cita",
        "users_icon" => "Ícono de usuarios",
        "save_icon" => "Ícono de guardar",
        "warning_icon" => "Ícono de advertencia",

        "company_account" => "Cuenta de empresa",
        "admin_account" => "Cuenta de administrador",
        "candidate_account" => "Cuenta de candidato",

        "profile_settings" => "Ajustes del perfil",
        "edit_company_profile" => "Edita el perfil de tu empresa",
        "edit_candidate_profile" => "Edita tu perfil",
        "company_intro" => "Completa la información de tu empresa, imagen de perfil y datos de contacto para que los candidatos conozcan mejor tu organización.",
        "candidate_intro" => "Completa tu información, foto de perfil, currículum y detalles de accesibilidad para que las empresas conozcan más sobre ti.",

        "company_image" => "Imagen de la empresa",
        "profile_photo" => "Foto de perfil",
        "upload_new_photo" => "Subir nueva foto",
        "photo_requirements" => "JPG, PNG o WEBP. Máximo 2 MB.",
        "curriculum" => "Currículum",
        "view_current_curriculum" => "Ver currículum actual",
        "no_curriculum" => "Aún no se ha subido un currículum.",
        "upload_curriculum" => "Subir currículum",
        "curriculum_requirements" => "PDF, DOC o DOCX. Máximo 5 MB.",

        "name_company" => "Nombre de la empresa",
        "name_candidate" => "Nombre completo",
        "phone_number" => "Número de teléfono",
        "phone_placeholder" => "Ejemplo: +503 7000-0000",
        "location" => "Ubicación",
        "location_placeholder" => "Ejemplo: Soyapango, San Salvador",
        "company_slogan" => "Lema de la empresa",
        "company_slogan_placeholder" => "Ejemplo: Tecnología con propósito",
        "collaborators" => "Colaboradores",
        "collaborators_placeholder" => "Ejemplo: 50+ empleados",
        "company_description" => "Descripción de la empresa",
        "profile_description" => "Descripción del perfil",
        "company_description_placeholder" => "Escribe una breve descripción sobre tu empresa, valores, ambiente laboral u objetivos de contratación.",
        "profile_description_placeholder" => "Escribe una breve descripción sobre tus habilidades, metas, experiencia o intereses laborales.",
        "register_disability" => "Quiero registrar información de discapacidad o accesibilidad.",
        "disability_need" => "Discapacidad o necesidad de accesibilidad",
        "disability_placeholder" => "Ejemplo: visual, auditiva, movilidad, cognitiva u otra",
        "disability_help" => "Esta información ayuda a las empresas a comprender necesidades de accesibilidad.",
        "cancel" => "Cancelar",
        "save_changes" => "Guardar cambios",

        "error_name_empty" => "El nombre no puede estar vacío.",
        "error_name_short" => "El nombre debe tener al menos 3 caracteres.",
        "error_name_long" => "El nombre es demasiado largo.",
        "error_phone_long" => "El número de teléfono es demasiado largo.",
        "error_location_long" => "La ubicación es demasiado larga.",
        "error_description_long" => "La descripción es demasiado larga.",
        "error_disability_long" => "La necesidad de accesibilidad es demasiado larga.",
        "error_slogan_long" => "El lema de la empresa es demasiado largo.",
        "error_collaborators_long" => "El campo de colaboradores es demasiado largo.",
        "error_update" => "Hubo un error al actualizar tu perfil. Inténtalo nuevamente.",

        "footer_rights" => "© 2026 SkillBridge. Todos los derechos reservados.",
        "footer_privacy" => "Privacidad",
        "footer_terms" => "Términos y condiciones"
    ]
];

function t($key) {
    global $translations, $idiomaActual;

    return $translations[$idiomaActual][$key]
        ?? $translations["en"][$key]
        ?? $key;
}

$nombre = $usuario["nombre"] ?? "";
$tipoUsuario = $usuario["tipo_usuario"] ?? "candidato";
$telefono = $usuario["telefono"] ?? "";
$ubicacion = $usuario["ubicacion"] ?? "";
$descripcion = $usuario["descripcion"] ?? "";
$fotoPerfil = $usuario["foto_perfil"] ?? "";
$curriculum = $usuario["curriculum"] ?? "";
$discapacidad = $usuario["discapacidad"] ?? 0;
$tipoDiscapacidad = $usuario["tipo_discapacidad"] ?? "";

$modoOscuro = !empty($usuario["modo_oscuro"]) ? 1 : 0;
$altoContraste = !empty($usuario["alto_contraste"]) ? 1 : 0;
$modoLectura = !empty($usuario["modo_lectura"]) ? 1 : 0;
$escalaTexto = isset($usuario["escala_texto"]) ? (float) $usuario["escala_texto"] : 1.00;

if ($escalaTexto < 0.85 || $escalaTexto > 1.25) {
    $escalaTexto = 1.00;
}

$esEmpresa = $tipoUsuario === "empresa";
$esAdmin = $tipoUsuario === "administrador";
$esCandidato = $tipoUsuario === "candidato";
$esEmpresaOAdmin = $esEmpresa || $esAdmin;
$puedeSubirCurriculum = $esCandidato;

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);

$empresaActual = null;
$empresaLema = "";
$empresaColaboradores = "";

if ($esEmpresa) {
    try {
        $sqlEmpresa = "
            SELECT *
            FROM empresas
            WHERE nombre = :nombre
            LIMIT 1
        ";

        $stmtEmpresa = $pdo->prepare($sqlEmpresa);
        $stmtEmpresa->execute([
            ":nombre" => $nombre
        ]);

        $empresaActual = $stmtEmpresa->fetch();

        if ($empresaActual) {
            $empresaLema = $empresaActual["lema"] ?? "";
            $empresaColaboradores = $empresaActual["colaboradores"] ?? "";
        }
    } catch (PDOException $e) {
        $empresaActual = null;
    }
}

if ($esEmpresa) {
    $tipoUsuarioTexto = t("company_account");
} elseif ($esAdmin) {
    $tipoUsuarioTexto = t("admin_account");
} else {
    $tipoUsuarioTexto = t("candidate_account");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreNuevo = trim($_POST["nombre"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $ubicacion = trim($_POST["ubicacion"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    $empresaLema = $esEmpresa ? trim($_POST["empresa_lema"] ?? "") : "";
    $empresaColaboradores = $esEmpresa ? trim($_POST["empresa_colaboradores"] ?? "") : "";

    if ($esCandidato) {
        $discapacidad = isset($_POST["discapacidad"]) ? 1 : 0;
        $tipoDiscapacidad = trim($_POST["tipo_discapacidad"] ?? "");

        if ($discapacidad === 0) {
            $tipoDiscapacidad = "";
        }
    } else {
        $discapacidad = 0;
        $tipoDiscapacidad = "";
    }

    if ($nombreNuevo === "") {
        $errorMessage = t("error_name_empty");
    } elseif (strlen($nombreNuevo) < 3) {
        $errorMessage = t("error_name_short");
    } elseif (strlen($nombreNuevo) > 120) {
        $errorMessage = t("error_name_long");
    } elseif (strlen($telefono) > 25) {
        $errorMessage = t("error_phone_long");
    } elseif (strlen($ubicacion) > 120) {
        $errorMessage = t("error_location_long");
    } elseif (strlen($descripcion) > 1200) {
        $errorMessage = t("error_description_long");
    } elseif (strlen($tipoDiscapacidad) > 120) {
        $errorMessage = t("error_disability_long");
    } elseif (strlen($empresaLema) > 160) {
        $errorMessage = t("error_slogan_long");
    } elseif (strlen($empresaColaboradores) > 80) {
        $errorMessage = t("error_collaborators_long");
    }

    $nuevaFotoPerfil = null;
    $nuevoCurriculum = null;

    if ($errorMessage === "") {
        $nuevaFotoPerfil = subirFotoPerfil($_FILES["foto_perfil"] ?? null, $idUsuario, $errorMessage, $idiomaActual);
    }

    if ($errorMessage === "" && $puedeSubirCurriculum) {
        $nuevoCurriculum = subirCurriculum($_FILES["curriculum"] ?? null, $idUsuario, $errorMessage, $idiomaActual);
    }

    if ($errorMessage === "") {
        try {
            $pdo->beginTransaction();

            if ($nuevaFotoPerfil !== null) {
                $fotoPerfil = $nuevaFotoPerfil;
            }

            if ($nuevoCurriculum !== null && $puedeSubirCurriculum) {
                $curriculum = $nuevoCurriculum;
            }

            if (!$puedeSubirCurriculum) {
                $curriculum = "";
            }

            $sqlActualizarUsuario = "
                UPDATE usuarios
                SET nombre = :nombre,
                    telefono = :telefono,
                    ubicacion = :ubicacion,
                    descripcion = :descripcion,
                    foto_perfil = :foto_perfil,
                    curriculum = :curriculum,
                    discapacidad = :discapacidad,
                    tipo_discapacidad = :tipo_discapacidad,
                    idioma_preferido = :idioma_preferido
                WHERE id_usuario = :id_usuario
            ";

            $stmtActualizarUsuario = $pdo->prepare($sqlActualizarUsuario);
            $stmtActualizarUsuario->execute([
                ":nombre" => $nombreNuevo,
                ":telefono" => $telefono !== "" ? $telefono : null,
                ":ubicacion" => $ubicacion !== "" ? $ubicacion : null,
                ":descripcion" => $descripcion !== "" ? $descripcion : null,
                ":foto_perfil" => $fotoPerfil !== "" ? $fotoPerfil : null,
                ":curriculum" => $puedeSubirCurriculum && $curriculum !== "" ? $curriculum : null,
                ":discapacidad" => $discapacidad,
                ":tipo_discapacidad" => $tipoDiscapacidad !== "" ? $tipoDiscapacidad : null,
                ":idioma_preferido" => $idiomaActual,
                ":id_usuario" => $idUsuario
            ]);

            if ($esEmpresa) {
                $idEmpresaActual = $empresaActual["id_empresa"] ?? null;

                if ($idEmpresaActual) {
                    $sqlActualizarEmpresa = "
                        UPDATE empresas
                        SET nombre = :nombre,
                            lema = :lema,
                            descripcion = :descripcion,
                            ubicacion = :ubicacion,
                            colaboradores = :colaboradores,
                            estado = 1
                        WHERE id_empresa = :id_empresa
                    ";

                    $stmtActualizarEmpresa = $pdo->prepare($sqlActualizarEmpresa);
                    $stmtActualizarEmpresa->execute([
                        ":nombre" => $nombreNuevo,
                        ":lema" => $empresaLema !== "" ? $empresaLema : "SkillBridge partner company",
                        ":descripcion" => $descripcion !== "" ? $descripcion : "Company account created through SkillBridge.",
                        ":ubicacion" => $ubicacion !== "" ? $ubicacion : null,
                        ":colaboradores" => $empresaColaboradores !== "" ? $empresaColaboradores : "Growing team",
                        ":id_empresa" => $idEmpresaActual
                    ]);
                } else {
                    $sqlCrearEmpresa = "
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
                    ";

                    $stmtCrearEmpresa = $pdo->prepare($sqlCrearEmpresa);
                    $stmtCrearEmpresa->execute([
                        ":nombre" => $nombreNuevo,
                        ":lema" => $empresaLema !== "" ? $empresaLema : "SkillBridge partner company",
                        ":descripcion" => $descripcion !== "" ? $descripcion : "Company account created through SkillBridge.",
                        ":ubicacion" => $ubicacion !== "" ? $ubicacion : null,
                        ":colaboradores" => $empresaColaboradores !== "" ? $empresaColaboradores : "Growing team"
                    ]);
                }
            }

            $pdo->commit();

            $_SESSION["nombre"] = $nombreNuevo;
            $_SESSION["tipo_usuario"] = $tipoUsuario;
            $_SESSION["idioma_preferido"] = $idiomaActual;

            header("Location: perfil.php?updated=1");
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errorMessage = t("error_update");
        }
    }

    $nombre = $nombreNuevo;
}

$inicialUsuario = strtoupper(substr(trim($nombre), 0, 1));

if ($inicialUsuario === "") {
    $inicialUsuario = "U";
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
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/perfil.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/auth.css?v=20260904footerfinal">
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

            <div class="nav-actions perfil-nav-actions">
                <a href="perfil.php" class="button button-secondary button-small">
                    <?php echo limpiar(t("nav_my_profile")); ?>
                </a>

                <a href="logout.php" class="button button-primary button-small">
                    <?php echo limpiar(t("nav_logout")); ?>
                </a>

                <a href="editar-perfil.php?lang=<?php echo limpiar($idiomaSiguiente); ?>"
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

        <section class="perfil-banner edit-profile-banner">
            <div class="container">

                <div class="edit-profile-wrapper">

                    <div class="edit-profile-header">
                        <span class="section-label">
                            <?php echo limpiar(t("profile_settings")); ?>
                        </span>

                        <h1>
                            <?php echo limpiar($esEmpresa ? t("edit_company_profile") : t("edit_candidate_profile")); ?>
                        </h1>

                        <p>
                            <?php echo limpiar($esEmpresa ? t("company_intro") : t("candidate_intro")); ?>
                        </p>

                        <span class="profile-type-badge">
                            <?php echo limpiar($tipoUsuarioTexto); ?>
                        </span>
                    </div>

                    <?php if (!empty($errorMessage)): ?>
                        <div class="auth-alert error">
                            <i class="fa-solid fa-triangle-exclamation"
                                role="img"
                                aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                                title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                            <span><?php echo limpiar($errorMessage); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="editar-perfil.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">

                        <div class="edit-media-grid">

                            <div class="edit-photo-card">
                                <span class="edit-card-label">
                                    <?php echo limpiar($esEmpresa ? t("company_image") : t("profile_photo")); ?>
                                </span>

                                <div class="edit-photo-preview">
                                    <?php if (!empty($fotoPerfil) && file_exists($fotoPerfil)): ?>
                                        <img src="<?php echo limpiar($fotoPerfil); ?>"
                                            alt="<?php echo limpiar($esEmpresa ? t("company_image") : t("profile_photo")); ?>">
                                    <?php else: ?>
                                        <span><?php echo limpiar($inicialUsuario); ?></span>
                                    <?php endif; ?>
                                </div>

                                <label for="foto_perfil" class="file-upload-label">
                                    <i class="fa-solid fa-camera"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("camera_icon")); ?>"
                                        title="<?php echo limpiar(t("camera_icon")); ?>"></i>
                                    <?php echo limpiar(t("upload_new_photo")); ?>
                                </label>

                                <input type="file" id="foto_perfil" name="foto_perfil"
                                    accept=".jpg,.jpeg,.png,.webp" hidden>

                                <small><?php echo limpiar(t("photo_requirements")); ?></small>
                            </div>

                            <?php if ($puedeSubirCurriculum): ?>
                                <div class="edit-photo-card">
                                    <span class="edit-card-label">
                                        <?php echo limpiar(t("curriculum")); ?>
                                    </span>

                                    <div class="resume-preview-icon">
                                        <i class="fa-solid fa-file-lines"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                            title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                    </div>

                                    <?php if (!empty($curriculum)): ?>
                                        <a href="<?php echo limpiar($curriculum); ?>" target="_blank" class="resume-current-link">
                                            <?php echo limpiar(t("view_current_curriculum")); ?>
                                        </a>
                                    <?php else: ?>
                                        <p class="resume-empty-text">
                                            <?php echo limpiar(t("no_curriculum")); ?>
                                        </p>
                                    <?php endif; ?>

                                    <label for="curriculum" class="file-upload-label">
                                        <i class="fa-solid fa-file-arrow-up"
                                            role="img"
                                            aria-label="<?php echo limpiar(t("file_icon")); ?>"
                                            title="<?php echo limpiar(t("file_icon")); ?>"></i>
                                        <?php echo limpiar(t("upload_curriculum")); ?>
                                    </label>

                                    <input type="file" id="curriculum" name="curriculum"
                                        accept=".pdf,.doc,.docx" hidden>

                                    <small><?php echo limpiar(t("curriculum_requirements")); ?></small>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group-custom">
                            <label for="nombre">
                                <?php echo limpiar($esEmpresa ? t("name_company") : t("name_candidate")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-user input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("user_icon")); ?>"
                                    title="<?php echo limpiar(t("user_icon")); ?>"></i>

                                <input type="text" id="nombre" name="nombre"
                                    value="<?php echo limpiar($nombre); ?>"
                                    maxlength="120"
                                    required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="telefono">
                                <?php echo limpiar(t("phone_number")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-phone input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("phone_icon")); ?>"
                                    title="<?php echo limpiar(t("phone_icon")); ?>"></i>

                                <input type="text" id="telefono" name="telefono"
                                    placeholder="<?php echo limpiar(t("phone_placeholder")); ?>"
                                    maxlength="25"
                                    value="<?php echo limpiar($telefono); ?>">
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label for="ubicacion">
                                <?php echo limpiar(t("location")); ?>
                            </label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-location-dot input-icon"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("location_icon")); ?>"
                                    title="<?php echo limpiar(t("location_icon")); ?>"></i>

                                <input type="text" id="ubicacion" name="ubicacion"
                                    placeholder="<?php echo limpiar(t("location_placeholder")); ?>"
                                    maxlength="120"
                                    value="<?php echo limpiar($ubicacion); ?>">
                            </div>
                        </div>

                        <?php if ($esEmpresa): ?>

                            <div class="form-group-custom">
                                <label for="empresa_lema">
                                    <?php echo limpiar(t("company_slogan")); ?>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fa-solid fa-quote-left input-icon"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("quote_icon")); ?>"
                                        title="<?php echo limpiar(t("quote_icon")); ?>"></i>

                                    <input type="text" id="empresa_lema" name="empresa_lema"
                                        placeholder="<?php echo limpiar(t("company_slogan_placeholder")); ?>"
                                        maxlength="160"
                                        value="<?php echo limpiar($empresaLema); ?>">
                                </div>
                            </div>

                            <div class="form-group-custom">
                                <label for="empresa_colaboradores">
                                    <?php echo limpiar(t("collaborators")); ?>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fa-solid fa-users input-icon"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("users_icon")); ?>"
                                        title="<?php echo limpiar(t("users_icon")); ?>"></i>

                                    <input type="text" id="empresa_colaboradores" name="empresa_colaboradores"
                                        placeholder="<?php echo limpiar(t("collaborators_placeholder")); ?>"
                                        maxlength="80"
                                        value="<?php echo limpiar($empresaColaboradores); ?>">
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="form-group-custom">
                            <label for="descripcion">
                                <?php echo limpiar($esEmpresa ? t("company_description") : t("profile_description")); ?>
                            </label>

                            <textarea id="descripcion" name="descripcion" rows="5"
                                maxlength="1200"
                                placeholder="<?php echo limpiar($esEmpresa ? t("company_description_placeholder") : t("profile_description_placeholder")); ?>"><?php echo limpiar($descripcion); ?></textarea>
                        </div>

                        <?php if ($esCandidato): ?>

                            <div class="edit-profile-checkbox-box">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="discapacidad" name="discapacidad"
                                        <?php echo $discapacidad == 1 ? "checked" : ""; ?>>

                                    <span class="checkmark"></span>

                                    <span class="terms-label">
                                        <?php echo limpiar(t("register_disability")); ?>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group-custom" id="tipoDiscapacidadGroup">
                                <label for="tipo_discapacidad">
                                    <?php echo limpiar(t("disability_need")); ?>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fa-solid fa-universal-access input-icon"
                                        role="img"
                                        aria-label="<?php echo limpiar(t("accessibility_icon")); ?>"
                                        title="<?php echo limpiar(t("accessibility_icon")); ?>"></i>

                                    <input type="text" id="tipo_discapacidad" name="tipo_discapacidad"
                                        placeholder="<?php echo limpiar(t("disability_placeholder")); ?>"
                                        maxlength="120"
                                        value="<?php echo limpiar($tipoDiscapacidad); ?>">
                                </div>

                                <span class="helper-text">
                                    <?php echo limpiar(t("disability_help")); ?>
                                </span>
                            </div>

                        <?php endif; ?>

                        <div class="edit-profile-actions">
                            <a href="perfil.php" class="button button-secondary">
                                <?php echo limpiar(t("cancel")); ?>
                            </a>

                            <button type="submit" class="button button-primary">
                                <i class="fa-solid fa-floppy-disk"
                                    role="img"
                                    aria-label="<?php echo limpiar(t("save_icon")); ?>"
                                    title="<?php echo limpiar(t("save_icon")); ?>"></i>
                                <?php echo limpiar(t("save_changes")); ?>
                            </button>
                        </div>

                    </form>

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

    <script>
        const discapacidadCheckbox = document.getElementById("discapacidad");
        const tipoDiscapacidadGroup = document.getElementById("tipoDiscapacidadGroup");
        const tipoDiscapacidadInput = document.getElementById("tipo_discapacidad");

        function toggleDisabilityField() {
            if (!discapacidadCheckbox || !tipoDiscapacidadGroup || !tipoDiscapacidadInput) {
                return;
            }

            if (discapacidadCheckbox.checked) {
                tipoDiscapacidadGroup.style.display = "block";
            } else {
                tipoDiscapacidadGroup.style.display = "none";
                tipoDiscapacidadInput.value = "";
            }
        }

        if (discapacidadCheckbox) {
            discapacidadCheckbox.addEventListener("change", toggleDisabilityField);
            toggleDisabilityField();
        }

        const fotoInput = document.getElementById("foto_perfil");
        const curriculumInput = document.getElementById("curriculum");

        if (fotoInput) {
            fotoInput.addEventListener("change", () => {
                const label = document.querySelector("label[for='foto_perfil']");

                if (label && fotoInput.files.length > 0) {
                    label.innerHTML = `
                        <i class="fa-solid fa-circle-check" role="img" aria-label="<?php echo limpiar(t("save_icon")); ?>" title="<?php echo limpiar(t("save_icon")); ?>"></i>
                        ${fotoInput.files[0].name}
                    `;
                }
            });
        }

        if (curriculumInput) {
            curriculumInput.addEventListener("change", () => {
                const label = document.querySelector("label[for='curriculum']");

                if (label && curriculumInput.files.length > 0) {
                    label.innerHTML = `
                        <i class="fa-solid fa-circle-check" role="img" aria-label="<?php echo limpiar(t("save_icon")); ?>" title="<?php echo limpiar(t("save_icon")); ?>"></i>
                        ${curriculumInput.files[0].name}
                    `;
                }
            });
        }
    </script>

</body>

</html>