<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

$paginaActual = basename($_SERVER["PHP_SELF"]);

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

$idiomasPermitidos = ["en", "es"];

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idiomaActual = $_GET["lang"];
} elseif (isset($_POST["idioma_preferido"]) && in_array($_POST["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_POST["idioma_preferido"];
} elseif (!empty($_SESSION["idioma_preferido"]) && in_array($_SESSION["idioma_preferido"], $idiomasPermitidos, true)) {
    $idiomaActual = $_SESSION["idioma_preferido"];
} elseif (!empty($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idiomaActual = $_COOKIE["skillbridgeLanguage"];
} else {
    $idiomaActual = "en";
}

$_SESSION["idioma_preferido"] = $idiomaActual;
setcookie("skillbridgeLanguage", $idiomaActual, time() + (365 * 24 * 60 * 60), "/");

$translations = [
    "en" => [
        "page_title" => "Recover Password | SkillBridge",
        "meta_description" => "Recover your SkillBridge account password.",

        "nav_home" => "Home",
        "nav_find_jobs" => "Find jobs",
        "nav_companies" => "Companies",
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
        "email_icon" => "Email icon",
        "lock_icon" => "Password icon",
        "shield_icon" => "Password confirmation icon",
        "eye_icon" => "Show or hide password icon",
        "warning_icon" => "Warning icon",
        "success_icon" => "Success icon",
        "key_icon" => "Key icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon",

        "error_required" => "Please complete all required fields.",
        "error_email" => "Enter a valid email address.",
        "error_password_length" => "The new password must be at least 8 characters long.",
        "error_password_match" => "Passwords do not match.",
        "error_account_not_found" => "No active account was found with that email address.",
        "error_update" => "There was an error updating your password. Please try again.",
        "success_update" => "Your password was updated successfully. You can now log in.",

        "hero_label" => "Account recovery",
        "hero_title" => "Recover your password",
        "hero_text" => "Enter your account email and create a new password to recover access.",

        "email_label" => "Email address",
        "email_placeholder" => "email@example.com",
        "new_password_label" => "New password",
        "new_password_placeholder" => "At least 8 characters",
        "confirm_password_label" => "Confirm new password",
        "confirm_password_placeholder" => "Repeat your new password",
        "toggle_password" => "Show or hide password",
        "password_error" => "The password must be at least 8 characters long.",
        "passwords_match_error" => "Passwords must match.",
        "submit_button" => "Update password",
        "remembered_password" => "Remembered your password?",
        "login_here" => "Log in here",

        "footer_description" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_for_candidates" => "For candidates",
        "footer_find_jobs" => "Find jobs",
        "footer_create_profile" => "Create profile",
        "footer_my_applications" => "My applications",
        "footer_resources" => "Resources and advice",
        "footer_for_companies" => "For companies",
        "footer_post_job" => "Post a job",
        "footer_find_talent" => "Find talent",
        "footer_business_plans" => "Business plans",
        "footer_contact" => "Contact the team",
        "footer_platform" => "Platform",
        "footer_about" => "About us",
        "footer_accessibility" => "Accessibility",
        "footer_privacy" => "Privacy",
        "footer_terms" => "Terms and Conditions",
        "footer_rights" => "© 2026 SkillBridge. All rights reserved."
    ],

    "es" => [
        "page_title" => "Recuperar contraseña | SkillBridge",
        "meta_description" => "Recupera la contraseña de tu cuenta de SkillBridge.",

        "nav_home" => "Inicio",
        "nav_find_jobs" => "Buscar empleos",
        "nav_companies" => "Empresas",
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
        "email_icon" => "Ícono de correo electrónico",
        "lock_icon" => "Ícono de contraseña",
        "shield_icon" => "Ícono de confirmación de contraseña",
        "eye_icon" => "Ícono de mostrar u ocultar contraseña",
        "warning_icon" => "Ícono de advertencia",
        "success_icon" => "Ícono de éxito",
        "key_icon" => "Ícono de llave",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok",

        "error_required" => "Completa todos los campos obligatorios.",
        "error_email" => "Ingresa un correo electrónico válido.",
        "error_password_length" => "La nueva contraseña debe tener al menos 8 caracteres.",
        "error_password_match" => "Las contraseñas no coinciden.",
        "error_account_not_found" => "No se encontró una cuenta activa con ese correo electrónico.",
        "error_update" => "Hubo un error al actualizar tu contraseña. Inténtalo nuevamente.",
        "success_update" => "Tu contraseña se actualizó correctamente. Ahora puedes iniciar sesión.",

        "hero_label" => "Recuperación de cuenta",
        "hero_title" => "Recupera tu contraseña",
        "hero_text" => "Ingresa el correo de tu cuenta y crea una nueva contraseña para recuperar el acceso.",

        "email_label" => "Correo electrónico",
        "email_placeholder" => "correo@ejemplo.com",
        "new_password_label" => "Nueva contraseña",
        "new_password_placeholder" => "Al menos 8 caracteres",
        "confirm_password_label" => "Confirmar nueva contraseña",
        "confirm_password_placeholder" => "Repite tu nueva contraseña",
        "toggle_password" => "Mostrar u ocultar contraseña",
        "password_error" => "La contraseña debe tener al menos 8 caracteres.",
        "passwords_match_error" => "Las contraseñas deben coincidir.",
        "submit_button" => "Actualizar contraseña",
        "remembered_password" => "¿Recordaste tu contraseña?",
        "login_here" => "Inicia sesión aquí",

        "footer_description" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_for_candidates" => "Para candidatos",
        "footer_find_jobs" => "Buscar empleos",
        "footer_create_profile" => "Crear perfil",
        "footer_my_applications" => "Mis postulaciones",
        "footer_resources" => "Recursos y consejos",
        "footer_for_companies" => "Para empresas",
        "footer_post_job" => "Publicar vacante",
        "footer_find_talent" => "Encontrar talento",
        "footer_business_plans" => "Planes empresariales",
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

if (isset($_SESSION["id_usuario"])) {
    header("Location: perfil.php?lang=" . urlencode($idiomaActual));
    exit;
}

$errorMessage = "";
$successMessage = "";
$correoValue = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = strtolower(trim($_POST["correo"] ?? ""));
    $nuevaPassword = $_POST["nueva_password"] ?? "";
    $confirmarPassword = $_POST["confirmar_password"] ?? "";

    $correoValue = $correo;

    if ($correo === "" || $nuevaPassword === "" || $confirmarPassword === "") {
        $errorMessage = t("error_required");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = t("error_email");
    } elseif (strlen($nuevaPassword) < 8) {
        $errorMessage = t("error_password_length");
    } elseif ($nuevaPassword !== $confirmarPassword) {
        $errorMessage = t("error_password_match");
    } else {
        try {
            $buscarUsuario = $pdo->prepare("
                SELECT id_usuario
                FROM usuarios
                WHERE correo = :correo
                  AND estado = 1
                LIMIT 1
            ");

            $buscarUsuario->execute([
                ":correo" => $correo
            ]);

            $usuario = $buscarUsuario->fetch();

            if (!$usuario) {
                $errorMessage = t("error_account_not_found");
            } else {
                $passwordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

                $actualizarPassword = $pdo->prepare("
                    UPDATE usuarios
                    SET password_hash = :password_hash,
                        idioma_preferido = :idioma_preferido
                    WHERE id_usuario = :id_usuario
                    LIMIT 1
                ");

                $actualizarPassword->execute([
                    ":password_hash" => $passwordHash,
                    ":idioma_preferido" => $idiomaActual,
                    ":id_usuario" => $usuario["id_usuario"]
                ]);

                $successMessage = t("success_update");
                $correoValue = "";
            }
        } catch (PDOException $e) {
            $errorMessage = t("error_update");
        }
    }
}

$idiomaSiguiente = $idiomaActual === "es" ? "en" : "es";
$etiquetaIdiomaSiguiente = strtoupper($idiomaSiguiente);
$loginLink = "login.php?lang=" . urlencode($idiomaActual);
$registroLink = "registro.php?lang=" . urlencode($idiomaActual);
?>
<!DOCTYPE html>
<html lang="<?php echo limpiar($idiomaActual); ?>">

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
    <link rel="stylesheet" href="css/auth.css?v=20260904footerfinal">
</head>

<body class="auth-body">

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

            <a href="index.php?lang=<?php echo limpiar($idiomaActual); ?>" class="logo"
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
                    <a href="index.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("index.php"); ?>>
                        <?php echo limpiar(t("nav_home")); ?>
                    </a>
                </li>

                <li>
                    <a href="empleos.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("empleos.php"); ?>>
                        <?php echo limpiar(t("nav_find_jobs")); ?>
                    </a>
                </li>

                <li>
                    <a href="empresas.php?lang=<?php echo limpiar($idiomaActual); ?>"<?php echo enlaceActivo("empresas.php"); ?>>
                        <?php echo limpiar(t("nav_companies")); ?>
                    </a>
                </li>
            </ul>

            <div class="nav-actions">
                <a href="<?php echo limpiar($loginLink); ?>" class="login-link">
                    <?php echo limpiar(t("nav_login")); ?>
                </a>

                <a href="<?php echo limpiar($registroLink); ?>" class="button button-primary button-small">
                    <?php echo limpiar(t("nav_create_account")); ?>
                </a>

                <a href="recuperar-password.php?lang=<?php echo limpiar($idiomaSiguiente); ?>"
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

    <main class="auth-container simple-center">

        <section class="auth-box central-box">

            <div class="auth-header">
                <span class="section-label">
                    <?php echo limpiar(t("hero_label")); ?>
                </span>

                <h1><?php echo limpiar(t("hero_title")); ?></h1>

                <p class="auth-subtitle">
                    <?php echo limpiar(t("hero_text")); ?>
                </p>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="auth-alert success">
                    <i class="fa-solid fa-circle-check"
                        role="img"
                        aria-label="<?php echo limpiar(t("success_icon")); ?>"
                        title="<?php echo limpiar(t("success_icon")); ?>"></i>
                    <span><?php echo limpiar($successMessage); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="auth-alert error">
                    <i class="fa-solid fa-triangle-exclamation"
                        role="img"
                        aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                        title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                    <span><?php echo limpiar($errorMessage); ?></span>
                </div>
            <?php endif; ?>

            <form action="recuperar-password.php?lang=<?php echo limpiar($idiomaActual); ?>" method="POST" id="recoverForm" class="auth-form">

                <input type="hidden" name="idioma_preferido" value="<?php echo limpiar($idiomaActual); ?>">

                <div class="form-group-custom">
                    <label for="recoverEmail"><?php echo limpiar(t("email_label")); ?></label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("email_icon")); ?>"
                            title="<?php echo limpiar(t("email_icon")); ?>"></i>

                        <input type="email" id="recoverEmail" name="correo"
                            placeholder="<?php echo limpiar(t("email_placeholder")); ?>"
                            value="<?php echo limpiar($correoValue); ?>"
                            autocomplete="email"
                            required>

                        <i class="fa-solid fa-circle-exclamation error-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("warning_icon")); ?>"
                            title="<?php echo limpiar(t("warning_icon")); ?>"></i>
                    </div>

                    <span class="error-text">
                        <?php echo limpiar(t("error_email")); ?>
                    </span>
                </div>

                <div class="form-group-custom">
                    <label for="newPassword"><?php echo limpiar(t("new_password_label")); ?></label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("lock_icon")); ?>"
                            title="<?php echo limpiar(t("lock_icon")); ?>"></i>

                        <input type="password" id="newPassword" name="nueva_password"
                            placeholder="<?php echo limpiar(t("new_password_placeholder")); ?>"
                            autocomplete="new-password"
                            required minlength="8">

                        <button type="button" class="toggle-password-button"
                            id="toggleRecoverPassword"
                            aria-label="<?php echo limpiar(t("toggle_password")); ?>"
                            title="<?php echo limpiar(t("toggle_password")); ?>">
                            <i class="fa-solid fa-eye"
                                role="img"
                                aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                        </button>
                    </div>

                    <span class="error-text">
                        <?php echo limpiar(t("password_error")); ?>
                    </span>
                </div>

                <div class="form-group-custom">
                    <label for="confirmPassword"><?php echo limpiar(t("confirm_password_label")); ?></label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-shield-halved input-icon"
                            role="img"
                            aria-label="<?php echo limpiar(t("shield_icon")); ?>"
                            title="<?php echo limpiar(t("shield_icon")); ?>"></i>

                        <input type="password" id="confirmPassword" name="confirmar_password"
                            placeholder="<?php echo limpiar(t("confirm_password_placeholder")); ?>"
                            autocomplete="new-password"
                            required minlength="8">

                        <button type="button" class="toggle-password-button"
                            id="toggleConfirmPassword"
                            aria-label="<?php echo limpiar(t("toggle_password")); ?>"
                            title="<?php echo limpiar(t("toggle_password")); ?>">
                            <i class="fa-solid fa-eye"
                                role="img"
                                aria-label="<?php echo limpiar(t("eye_icon")); ?>"
                                title="<?php echo limpiar(t("eye_icon")); ?>"></i>
                        </button>
                    </div>

                    <span class="error-text">
                        <?php echo limpiar(t("passwords_match_error")); ?>
                    </span>
                </div>

                <button type="submit" class="button button-primary btn-block" id="btnRecoverSubmit">
                    <span><?php echo limpiar(t("submit_button")); ?></span>
                    <i class="fa-solid fa-key"
                        role="img"
                        aria-label="<?php echo limpiar(t("key_icon")); ?>"
                        title="<?php echo limpiar(t("key_icon")); ?>"></i>
                </button>

                <p class="auth-switch-text">
                    <?php echo limpiar(t("remembered_password")); ?>
                    <a href="<?php echo limpiar($loginLink); ?>">
                        <?php echo limpiar(t("login_here")); ?>
                    </a>
                </p>

            </form>

        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: "<?php echo limpiar($idiomaActual); ?>",
            isLoggedIn: false
        };

        try {
            localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);
        } catch (error) {
            console.warn("SkillBridge language preference could not be stored locally.");
        }
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
    <script src="java/auth.js?v=20260904footerfinal"></script>
</body>

</html>
