<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/conexion.php";

$paginaActual = basename($_SERVER["PHP_SELF"]);
$usuarioLogueado = isset($_SESSION["id_usuario"]);
$idUsuario = $_SESSION["id_usuario"] ?? null;
$tipoUsuario = $_SESSION["tipo_usuario"] ?? "";
$usuarioActual = null;

function limpiar($valor) {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

function enlaceActivo($pagina) {
    global $paginaActual;
    return $paginaActual === $pagina ? ' class="active"' : '';
}

if ($usuarioLogueado) {
    try {
        $stmtUsuario = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id_usuario AND estado = 1 LIMIT 1");
        $stmtUsuario->execute([":id_usuario" => $idUsuario]);
        $usuarioActual = $stmtUsuario->fetch();

        if ($usuarioActual) {
            $tipoUsuario = $usuarioActual["tipo_usuario"] ?? $tipoUsuario;
            $_SESSION["tipo_usuario"] = $tipoUsuario;
            $_SESSION["nombre"] = $usuarioActual["nombre"] ?? ($_SESSION["nombre"] ?? "");
            $_SESSION["correo"] = $usuarioActual["correo"] ?? ($_SESSION["correo"] ?? "");
        } else {
            session_unset();
            session_destroy();
            $usuarioLogueado = false;
            $idUsuario = null;
            $tipoUsuario = "";
        }
    } catch (PDOException $e) {
        $usuarioActual = null;
    }
}

$idiomasPermitidos = ["en", "es"];
$idioma = $_SESSION["idioma_preferido"]
    ?? ($_COOKIE["skillbridgeLanguage"] ?? ($usuarioActual["idioma_preferido"] ?? "en"));

if (!in_array($idioma, $idiomasPermitidos, true)) {
    $idioma = "en";
}

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idioma = $_GET["lang"];
    $_SESSION["idioma_preferido"] = $idioma;
    setcookie("skillbridgeLanguage", $idioma, time() + (365 * 24 * 60 * 60), "/");

    if ($usuarioLogueado && $idUsuario) {
        try {
            $stmtIdioma = $pdo->prepare("UPDATE usuarios SET idioma_preferido = :idioma WHERE id_usuario = :id_usuario LIMIT 1");
            $stmtIdioma->execute([
                ":idioma" => $idioma,
                ":id_usuario" => $idUsuario
            ]);
        } catch (PDOException $e) {
            // El idioma queda guardado en sesión y cookie aunque falle la actualización en BD.
        }
    }
}

$translations = [
    "en" => [
        "page_title" => "Privacy Policy | SkillBridge",
        "meta_description" => "Privacy policy for SkillBridge, an inclusive employment platform.",
        "skip" => "Skip to main content",
        "open_accessibility" => "Open accessibility tools",
        "close_accessibility" => "Close accessibility tools",
        "accessibility" => "Accessibility",
        "accessibility_tools" => "Accessibility tools",
        "accessibility_text" => "Adjust the website experience to your needs.",
        "increase_text" => "Increase text size",
        "decrease_text" => "Decrease text size",
        "dark_mode" => "Dark mode",
        "high_contrast" => "High contrast",
        "read_aloud" => "Read content aloud",
        "stop_reading" => "Stop reading",
        "switch_language" => "Switch to Spanish",
        "logo_home" => "Go to SkillBridge home",
        "open_menu" => "Open navigation menu",
        "home" => "Home",
        "find_jobs" => "Find jobs",
        "companies" => "Companies",
        "my_applications" => "My applications",
        "post_job" => "Post a job",
        "received_applications" => "Received applications",
        "profile" => "Profile",
        "my_profile" => "My Profile",
        "logout" => "Log Out",
        "login" => "Log In",
        "create_account" => "Create Account",
        "privacy_policy" => "Privacy Policy",
        "hero_title" => "Your information matters to us.",
        "hero_text" => "This page explains how SkillBridge protects and uses the information shared by candidates and companies on the platform.",
        "data_protection" => "Data protection",
        "data_protection_text" => "SkillBridge is designed to handle personal and professional information responsibly.",
        "information" => "Information",
        "last_updated" => "Last updated: 2026",
        "h1" => "1. Information we collect",
        "p1" => "SkillBridge may collect information such as full name, email address, password, account type, phone number, location, profile description, accessibility information, profile photo, curriculum, applications, and job posting details.",
        "h2" => "2. How we use information",
        "p2" => "We use this information to create accounts, display professional profiles, connect candidates with companies, publish job opportunities, manage applications, and improve the platform experience.",
        "h3" => "3. Curriculum and application information",
        "p3" => "When a candidate applies to a job opening, SkillBridge may share the application message, contact information, profile information, and curriculum with the company that published the job opening.",
        "h4" => "4. Accessibility information",
        "p4" => "Accessibility or disability information is used only to support inclusive job opportunities and help companies understand possible accessibility needs. This information should be handled with respect and confidentiality.",
        "h5" => "5. Company information",
        "p5" => "Companies may publish information such as company name, description, location, job openings, requirements, salary estimates, work arrangement, and application deadlines.",
        "h6" => "6. Data security",
        "p6" => "Passwords are stored using secure hashing methods. Users should also protect their account information, avoid sharing passwords, and log out when using shared devices.",
        "h7" => "7. User responsibility",
        "p7" => "Users are responsible for keeping their profile information accurate and for sharing only information they want companies to review during the application process.",
        "h8" => "8. Contact",
        "p8" => "If you have questions about privacy or the use of your information, you can contact the SkillBridge team through the contact page.",
        "contact_team" => "Contact the team",
        "footer_text" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_candidates" => "For candidates",
        "create_profile" => "Create profile",
        "resources" => "Resources and advice",
        "footer_companies" => "For companies",
        "find_talent" => "Find talent",
        "company_dashboard" => "Company dashboard",
        "platform" => "Platform",
        "about_us" => "About us",
        "privacy" => "Privacy",
        "terms" => "Terms and Conditions",
        "contact" => "Contact",
        "rights" => "© 2026 SkillBridge. All rights reserved.",
        "universal_access_icon" => "Accessibility icon",
        "close_icon" => "Close icon",
        "increase_icon" => "Increase text icon",
        "decrease_icon" => "Decrease text icon",
        "moon_icon" => "Dark mode icon",
        "contrast_icon" => "High contrast icon",
        "volume_icon" => "Read aloud icon",
        "stop_volume_icon" => "Stop reading icon",
        "menu_icon" => "Menu icon",
        "shield_icon" => "Data protection icon",
        "paper_plane_icon" => "Send icon"
    ],
    "es" => [
        "page_title" => "Política de privacidad | SkillBridge",
        "meta_description" => "Política de privacidad de SkillBridge, una plataforma de empleo inclusivo.",
        "skip" => "Saltar al contenido principal",
        "open_accessibility" => "Abrir herramientas de accesibilidad",
        "close_accessibility" => "Cerrar herramientas de accesibilidad",
        "accessibility" => "Accesibilidad",
        "accessibility_tools" => "Herramientas de accesibilidad",
        "accessibility_text" => "Ajusta la experiencia del sitio según tus necesidades.",
        "increase_text" => "Aumentar tamaño del texto",
        "decrease_text" => "Disminuir tamaño del texto",
        "dark_mode" => "Modo oscuro",
        "high_contrast" => "Alto contraste",
        "read_aloud" => "Leer contenido en voz alta",
        "stop_reading" => "Detener lectura",
        "switch_language" => "Cambiar a inglés",
        "logo_home" => "Ir al inicio de SkillBridge",
        "open_menu" => "Abrir menú de navegación",
        "home" => "Inicio",
        "find_jobs" => "Buscar empleos",
        "companies" => "Empresas",
        "my_applications" => "Mis postulaciones",
        "post_job" => "Publicar vacante",
        "received_applications" => "Postulaciones recibidas",
        "profile" => "Perfil",
        "my_profile" => "Mi perfil",
        "logout" => "Cerrar sesión",
        "login" => "Iniciar sesión",
        "create_account" => "Crear cuenta",
        "privacy_policy" => "Política de privacidad",
        "hero_title" => "Tu información es importante para nosotros.",
        "hero_text" => "Esta página explica cómo SkillBridge protege y utiliza la información compartida por candidatos y empresas dentro de la plataforma.",
        "data_protection" => "Protección de datos",
        "data_protection_text" => "SkillBridge está diseñado para manejar la información personal y profesional de forma responsable.",
        "information" => "Información",
        "last_updated" => "Última actualización: 2026",
        "h1" => "1. Información que recopilamos",
        "p1" => "SkillBridge puede recopilar información como nombre completo, correo electrónico, contraseña, tipo de cuenta, teléfono, ubicación, descripción del perfil, información de accesibilidad, foto de perfil, currículum, postulaciones y detalles de vacantes publicadas.",
        "h2" => "2. Cómo usamos la información",
        "p2" => "Usamos esta información para crear cuentas, mostrar perfiles profesionales, conectar candidatos con empresas, publicar oportunidades laborales, gestionar postulaciones y mejorar la experiencia de la plataforma.",
        "h3" => "3. Información del currículum y postulaciones",
        "p3" => "Cuando un candidato se postula a una vacante, SkillBridge puede compartir el mensaje de postulación, información de contacto, información del perfil y currículum con la empresa que publicó la vacante.",
        "h4" => "4. Información de accesibilidad",
        "p4" => "La información de accesibilidad o discapacidad se usa únicamente para apoyar oportunidades laborales inclusivas y ayudar a las empresas a comprender posibles necesidades de accesibilidad. Esta información debe manejarse con respeto y confidencialidad.",
        "h5" => "5. Información de empresas",
        "p5" => "Las empresas pueden publicar información como nombre de la empresa, descripción, ubicación, vacantes, requisitos, estimaciones salariales, modalidad de trabajo y fechas límite de postulación.",
        "h6" => "6. Seguridad de los datos",
        "p6" => "Las contraseñas se almacenan usando métodos seguros de cifrado. Los usuarios también deben proteger su cuenta, evitar compartir contraseñas y cerrar sesión cuando usen dispositivos compartidos.",
        "h7" => "7. Responsabilidad del usuario",
        "p7" => "Los usuarios son responsables de mantener actualizada y correcta la información de su perfil, y de compartir únicamente la información que desean que las empresas revisen durante el proceso de postulación.",
        "h8" => "8. Contacto",
        "p8" => "Si tienes preguntas sobre privacidad o sobre el uso de tu información, puedes contactar al equipo de SkillBridge por medio de la página de contacto.",
        "contact_team" => "Contactar al equipo",
        "footer_text" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_candidates" => "Para candidatos",
        "create_profile" => "Crear perfil",
        "resources" => "Recursos y consejos",
        "footer_companies" => "Para empresas",
        "find_talent" => "Buscar talento",
        "company_dashboard" => "Panel de empresa",
        "platform" => "Plataforma",
        "about_us" => "Quiénes somos",
        "privacy" => "Privacidad",
        "terms" => "Términos y condiciones",
        "contact" => "Contacto",
        "rights" => "© 2026 SkillBridge. Todos los derechos reservados.",
        "universal_access_icon" => "Ícono de accesibilidad",
        "close_icon" => "Ícono de cerrar",
        "increase_icon" => "Ícono de aumentar texto",
        "decrease_icon" => "Ícono de disminuir texto",
        "moon_icon" => "Ícono de modo oscuro",
        "contrast_icon" => "Ícono de alto contraste",
        "volume_icon" => "Ícono de lectura en voz alta",
        "stop_volume_icon" => "Ícono de detener lectura",
        "menu_icon" => "Ícono de menú",
        "shield_icon" => "Ícono de protección de datos",
        "paper_plane_icon" => "Ícono de enviar"
    ]
];

function t($key) {
    global $translations, $idioma;
    return $translations[$idioma][$key] ?? $translations["en"][$key] ?? $key;
}

function icono($clases, $etiqueta) {
    return '<i class="' . limpiar($clases) . '" role="img" aria-label="' . limpiar($etiqueta) . '" title="' . limpiar($etiqueta) . '"></i>';
}

$esEmpresa = $usuarioLogueado && $tipoUsuario === "empresa";
$esAdmin = $usuarioLogueado && $tipoUsuario === "administrador";
$esCandidato = $usuarioLogueado && $tipoUsuario === "candidato";
$esEmpresaOAdmin = $usuarioLogueado && ($esEmpresa || $esAdmin);
$esCandidatoOAdmin = $usuarioLogueado && ($esCandidato || $esAdmin);

$modoOscuro = !empty($usuarioActual["modo_oscuro"]);
$altoContraste = !empty($usuarioActual["alto_contraste"]);
$modoLectura = !empty($usuarioActual["modo_lectura"]);
$escalaTexto = isset($usuarioActual["escala_texto"]) ? (float)$usuarioActual["escala_texto"] : 1.0;

if ($escalaTexto < 0.85 || $escalaTexto > 1.40) {
    $escalaTexto = 1.0;
}

$bodyClasses = [];
if ($modoOscuro) $bodyClasses[] = "dark-mode";
if ($altoContraste) $bodyClasses[] = "high-contrast";
if ($modoLectura) $bodyClasses[] = "reader-mode-enabled";

$proximoIdioma = $idioma === "es" ? "en" : "es";
$etiquetaIdioma = strtoupper($proximoIdioma);
$urlIdioma = "privacidad.php?lang=" . urlencode($proximoIdioma);

$versionStyle = file_exists("css/style.css") ? filemtime("css/style.css") : time();
$versionAuth = file_exists("css/auth.css") ? filemtime("css/auth.css") : time();
$versionExtra = file_exists("css/paginas-extra.css") ? filemtime("css/paginas-extra.css") : time();
$versionJava = file_exists("java/java.js") ? filemtime("java/java.js") : time();
?>
<!DOCTYPE html>
<html lang="<?php echo limpiar($idioma); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo limpiar(t("page_title")); ?></title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description" content="<?php echo limpiar(t("meta_description")); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=<?php echo (int)$versionStyle; ?>">
    <link rel="stylesheet" href="css/auth.css?v=<?php echo (int)$versionAuth; ?>">
    <link rel="stylesheet" href="css/paginas-extra.css?v=<?php echo (int)$versionExtra; ?>">
</head>

<body class="<?php echo limpiar(implode(" ", $bodyClasses)); ?>" style="--skillbridge-font-scale: <?php echo limpiar(number_format($escalaTexto, 2, '.', '')); ?>;">

    <script>
        window.SkillBridgeUserPreferences = {
            language: <?php echo json_encode($idioma); ?>,
            darkMode: <?php echo $modoOscuro ? "true" : "false"; ?>,
            highContrast: <?php echo $altoContraste ? "true" : "false"; ?>,
            readMode: <?php echo $modoLectura ? "true" : "false"; ?>,
            fontScale: <?php echo json_encode(number_format($escalaTexto, 2, '.', '')); ?>
        };

        localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);
        localStorage.setItem("skillbridgeDarkMode", window.SkillBridgeUserPreferences.darkMode ? "true" : "false");
        localStorage.setItem("skillbridgeHighContrast", window.SkillBridgeUserPreferences.highContrast ? "true" : "false");
        localStorage.setItem("skillbridgeReadMode", window.SkillBridgeUserPreferences.readMode ? "true" : "false");
        localStorage.setItem("skillbridgeFontScale", String(window.SkillBridgeUserPreferences.fontScale));
    </script>

    <a href="#mainContent" class="skip-link">
        <?php echo limpiar(t("skip")); ?>
    </a>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <button class="accessibility-button" id="accessibilityButton" aria-label="<?php echo limpiar(t("open_accessibility")); ?>" title="<?php echo limpiar(t("open_accessibility")); ?>">
        <?php echo icono("fa-solid fa-universal-access", t("universal_access_icon")); ?>
    </button>

    <aside class="accessibility-panel" id="accessibilityPanel" aria-label="<?php echo limpiar(t("accessibility_tools")); ?>">
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3><?php echo limpiar(t("accessibility")); ?></h3>
            </div>

            <button id="closeAccessibility" aria-label="<?php echo limpiar(t("close_accessibility")); ?>" title="<?php echo limpiar(t("close_accessibility")); ?>">
                <?php echo icono("fa-solid fa-xmark", t("close_icon")); ?>
            </button>
        </div>

        <p class="accessibility-text">
            <?php echo limpiar(t("accessibility_text")); ?>
        </p>

        <div class="accessibility-options">
            <button class="accessibility-option" id="increaseFont" type="button">
                <?php echo icono("fa-solid fa-magnifying-glass-plus", t("increase_icon")); ?>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont" type="button">
                <?php echo icono("fa-solid fa-magnifying-glass-minus", t("decrease_icon")); ?>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode" type="button">
                <?php echo icono("fa-solid fa-moon", t("moon_icon")); ?>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast" type="button">
                <?php echo icono("fa-solid fa-circle-half-stroke", t("contrast_icon")); ?>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage" type="button">
                <?php echo icono("fa-solid fa-volume-high", t("volume_icon")); ?>
                <span><?php echo limpiar(t("read_aloud")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading" type="button">
                <?php echo icono("fa-solid fa-volume-xmark", t("stop_volume_icon")); ?>
                <span><?php echo limpiar(t("stop_reading")); ?></span>
            </button>
        </div>
    </aside>

    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="logo" aria-label="<?php echo limpiar(t("logo_home")); ?>">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="SkillBridge logo">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button class="mobile-menu-button" id="mobileMenuButton" aria-label="<?php echo limpiar(t("open_menu")); ?>" title="<?php echo limpiar(t("open_menu")); ?>">
                <?php echo icono("fa-solid fa-bars", t("menu_icon")); ?>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php"<?php echo enlaceActivo("index.php"); ?>><?php echo limpiar(t("home")); ?></a></li>

                <?php if (!$esEmpresa): ?>
                    <li><a href="empleos.php"<?php echo enlaceActivo("empleos.php"); ?>><?php echo limpiar(t("find_jobs")); ?></a></li>
                <?php endif; ?>

                <li><a href="empresas.php"<?php echo enlaceActivo("empresas.php"); ?>><?php echo limpiar(t("companies")); ?></a></li>

                <?php if ($esCandidatoOAdmin): ?>
                    <li><a href="postulaciones.php"<?php echo enlaceActivo("postulaciones.php"); ?>><?php echo limpiar(t("my_applications")); ?></a></li>
                <?php endif; ?>

                <?php if ($esEmpresaOAdmin): ?>
                    <li><a href="publicarvacante.php"<?php echo enlaceActivo("publicarvacante.php"); ?>><?php echo limpiar(t("post_job")); ?></a></li>
                    <li><a href="postulaciones-empresa.php"<?php echo enlaceActivo("postulaciones-empresa.php"); ?>><?php echo limpiar(t("received_applications")); ?></a></li>
                <?php endif; ?>

                <?php if ($usuarioLogueado): ?>
                    <li><a href="perfil.php"<?php echo enlaceActivo("perfil.php"); ?>><?php echo limpiar(t("profile")); ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <a href="<?php echo limpiar($urlIdioma); ?>" class="language-toggle" aria-label="<?php echo limpiar(t("switch_language")); ?>" title="<?php echo limpiar(t("switch_language")); ?>">
                    <?php echo limpiar($etiquetaIdioma); ?>
                </a>

                <?php if ($usuarioLogueado): ?>
                    <a href="perfil.php" class="login-link"><?php echo limpiar(t("my_profile")); ?></a>
                    <a href="logout.php" class="button button-primary button-small"><?php echo limpiar(t("logout")); ?></a>
                <?php else: ?>
                    <a href="login.php" class="login-link"><?php echo limpiar(t("login")); ?></a>
                    <a href="registro.php" class="button button-primary button-small"><?php echo limpiar(t("create_account")); ?></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="extra-page-main" id="mainContent">
        <section class="extra-hero">
            <div class="container extra-hero-content">
                <div>
                    <span class="section-label"><?php echo limpiar(t("privacy_policy")); ?></span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p>
                        <?php echo limpiar(t("hero_text")); ?>
                    </p>
                </div>

                <div class="extra-hero-card">
                    <?php echo icono("fa-solid fa-shield-halved", t("shield_icon")); ?>

                    <h3><?php echo limpiar(t("data_protection")); ?></h3>

                    <p>
                        <?php echo limpiar(t("data_protection_text")); ?>
                    </p>
                </div>
            </div>
        </section>

        <section class="extra-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="auth-header">
                        <span class="section-label"><?php echo limpiar(t("information")); ?></span>

                        <h2><?php echo limpiar(t("privacy_policy")); ?></h2>

                        <p class="auth-subtitle">
                            <?php echo limpiar(t("last_updated")); ?>
                        </p>
                    </div>

                    <div class="profile-description">
                        <h3><?php echo limpiar(t("h1")); ?></h3>
                        <p><?php echo limpiar(t("p1")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h2")); ?></h3>
                        <p><?php echo limpiar(t("p2")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h3")); ?></h3>
                        <p><?php echo limpiar(t("p3")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h4")); ?></h3>
                        <p><?php echo limpiar(t("p4")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h5")); ?></h3>
                        <p><?php echo limpiar(t("p5")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h6")); ?></h3>
                        <p><?php echo limpiar(t("p6")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h7")); ?></h3>
                        <p><?php echo limpiar(t("p7")); ?></p>

                        <br>

                        <h3><?php echo limpiar(t("h8")); ?></h3>
                        <p><?php echo limpiar(t("p8")); ?></p>

                        <br>

                        <a href="contacto.php" class="button button-primary">
                            <?php echo limpiar(t("contact_team")); ?>
                            <?php echo icono("fa-solid fa-paper-plane", t("paper_plane_icon")); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script src="java/java.js?v=<?php echo (int)$versionJava; ?>"></script>
</body>

</html>
