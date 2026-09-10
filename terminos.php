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
            $tipoUsuario = "";
        }
    } catch (PDOException $e) {
        $usuarioActual = null;
    }
}

$idiomasPermitidos = ["en", "es"];
$idioma = "en";

if (isset($_GET["lang"]) && in_array($_GET["lang"], $idiomasPermitidos, true)) {
    $idioma = $_GET["lang"];
    $_SESSION["skillbridge_language"] = $idioma;
    setcookie("skillbridgeLanguage", $idioma, time() + (365 * 24 * 60 * 60), "/");

    if ($usuarioLogueado && $usuarioActual) {
        try {
            $stmtIdioma = $pdo->prepare("UPDATE usuarios SET idioma_preferido = :idioma WHERE id_usuario = :id_usuario LIMIT 1");
            $stmtIdioma->execute([
                ":idioma" => $idioma,
                ":id_usuario" => $idUsuario
            ]);
        } catch (PDOException $e) {
            // La página puede seguir funcionando aunque no se guarde la preferencia.
        }
    }
} elseif (isset($_SESSION["skillbridge_language"]) && in_array($_SESSION["skillbridge_language"], $idiomasPermitidos, true)) {
    $idioma = $_SESSION["skillbridge_language"];
} elseif ($usuarioActual && in_array($usuarioActual["idioma_preferido"] ?? "", $idiomasPermitidos, true)) {
    $idioma = $usuarioActual["idioma_preferido"];
    $_SESSION["skillbridge_language"] = $idioma;
    setcookie("skillbridgeLanguage", $idioma, time() + (365 * 24 * 60 * 60), "/");
} elseif (isset($_COOKIE["skillbridgeLanguage"]) && in_array($_COOKIE["skillbridgeLanguage"], $idiomasPermitidos, true)) {
    $idioma = $_COOKIE["skillbridgeLanguage"];
    $_SESSION["skillbridge_language"] = $idioma;
}

$translations = [
    "en" => [
        "page_title" => "Terms and Conditions | SkillBridge",
        "meta_description" => "Terms and conditions for using SkillBridge as a candidate, company, or administrator.",
        "skip_main" => "Skip to main content",
        "open_accessibility" => "Open accessibility tools",
        "close_accessibility" => "Close accessibility tools",
        "accessibility" => "Accessibility",
        "accessibility_text" => "Adjust the website experience to your needs.",
        "increase_text" => "Increase text size",
        "decrease_text" => "Decrease text size",
        "dark_mode" => "Dark mode",
        "high_contrast" => "High contrast",
        "read_aloud" => "Read content aloud",
        "stop_reading" => "Stop reading",
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
        "switch_language" => "Cambiar a español",
        "logo_alt" => "SkillBridge logo",
        "home_aria" => "Go to SkillBridge home",
        "menu_aria" => "Open navigation menu",
        "hero_label" => "TERMS AND CONDITIONS",
        "hero_title" => "Use SkillBridge responsibly.",
        "hero_text" => "These terms explain the basic rules for using SkillBridge as a candidate, company, or administrator.",
        "hero_card_title" => "Platform rules",
        "hero_card_text" => "SkillBridge promotes respectful, inclusive, accessible, and responsible use of the platform.",
        "rules_label" => "RULES",
        "main_title" => "Terms and Conditions",
        "last_updated" => "Last updated: 2026",
        "term_1_title" => "1. Use of the platform",
        "term_1_text" => "SkillBridge allows candidates to explore job opportunities, create profiles, upload curriculum files, and apply to job openings. Companies can create profiles, publish vacancies, and review applications.",
        "term_2_title" => "2. User accounts",
        "term_2_text" => "Users must provide truthful, respectful, and updated information. Each user is responsible for protecting their account information and keeping their password private.",
        "term_3_title" => "3. Candidate responsibilities",
        "term_3_text" => "Candidates should keep their profile information updated and apply only to opportunities that match their interests, skills, experience, or goals. The information shared in an application should be clear and honest.",
        "term_4_title" => "4. Company responsibilities",
        "term_4_text" => "Companies must publish real, clear, respectful, and inclusive job opportunities. Job postings should describe responsibilities, requirements, work arrangement, salary information when available, and application deadlines.",
        "term_5_title" => "5. Inclusive hiring",
        "term_5_text" => "SkillBridge encourages companies to use accessible language, avoid discriminatory requirements, and respect candidates with different abilities, backgrounds, and professional experiences.",
        "term_6_title" => "6. Prohibited actions",
        "term_6_text" => "Users may not publish false information, offensive content, spam, discriminatory messages, fraudulent job offers, or content that harms other users or the purpose of the platform.",
        "term_7_title" => "7. Applications and selection process",
        "term_7_text" => "When a candidate applies to a job, SkillBridge records the application and allows the company to review it. The company is responsible for managing the selection process and updating the application status when necessary.",
        "term_8_title" => "8. Uploaded files",
        "term_8_text" => "Candidates may upload curriculum files in supported formats. Users should avoid uploading harmful files, false documents, or information that does not belong to them.",
        "term_9_title" => "9. Platform availability",
        "term_9_text" => "SkillBridge may be improved, updated, or temporarily unavailable due to maintenance, technical changes, or new features. The goal is always to provide a better experience for candidates and companies.",
        "term_10_title" => "10. Privacy",
        "term_10_text" => "The use of personal information is explained in the Privacy Policy. Users should review that page to understand how information is collected and used on SkillBridge.",
        "term_11_title" => "11. Contact",
        "term_11_text" => "If users have questions about these terms, they can contact the SkillBridge team through the contact page.",
        "view_privacy" => "View Privacy Policy",
        "contact_team" => "Contact the team",
        "footer_text" => "We connect talent, companies, and opportunities to build a more inclusive future of work.",
        "footer_candidates" => "For candidates",
        "create_profile" => "Create profile",
        "resources" => "Resources and advice",
        "footer_companies" => "For companies",
        "find_talent" => "Find talent",
        "business_plans" => "Business plans",
        "platform" => "Platform",
        "about_us" => "About us",
        "privacy" => "Privacy",
        "terms" => "Terms and Conditions",
        "newsletter" => "Newsletter",
        "newsletter_text" => "Receive new job openings and professional advice.",
        "email_address" => "Email address",
        "email_placeholder" => "Your email address",
        "subscribe" => "Subscribe",
        "rights" => "© 2026 SkillBridge. All rights reserved.",
        "universal_access_icon" => "Accessibility icon",
        "close_icon" => "Close icon",
        "menu_icon" => "Menu icon",
        "increase_icon" => "Increase text icon",
        "decrease_icon" => "Decrease text icon",
        "moon_icon" => "Dark mode icon",
        "contrast_icon" => "High contrast icon",
        "read_icon" => "Read aloud icon",
        "stop_icon" => "Stop reading icon",
        "contract_icon" => "Terms document icon",
        "send_icon" => "Send icon",
        "facebook_icon" => "Facebook icon",
        "instagram_icon" => "Instagram icon",
        "linkedin_icon" => "LinkedIn icon",
        "tiktok_icon" => "TikTok icon"
    ],
    "es" => [
        "page_title" => "Términos y condiciones | SkillBridge",
        "meta_description" => "Términos y condiciones para usar SkillBridge como candidato, empresa o administrador.",
        "skip_main" => "Saltar al contenido principal",
        "open_accessibility" => "Abrir herramientas de accesibilidad",
        "close_accessibility" => "Cerrar herramientas de accesibilidad",
        "accessibility" => "Accesibilidad",
        "accessibility_text" => "Ajusta la experiencia del sitio según tus necesidades.",
        "increase_text" => "Aumentar tamaño del texto",
        "decrease_text" => "Disminuir tamaño del texto",
        "dark_mode" => "Modo oscuro",
        "high_contrast" => "Alto contraste",
        "read_aloud" => "Leer contenido en voz alta",
        "stop_reading" => "Detener lectura",
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
        "switch_language" => "Switch to English",
        "logo_alt" => "Logo de SkillBridge",
        "home_aria" => "Ir al inicio de SkillBridge",
        "menu_aria" => "Abrir menú de navegación",
        "hero_label" => "TÉRMINOS Y CONDICIONES",
        "hero_title" => "Usa SkillBridge de forma responsable.",
        "hero_text" => "Estos términos explican las reglas básicas para usar SkillBridge como candidato, empresa o administrador.",
        "hero_card_title" => "Reglas de la plataforma",
        "hero_card_text" => "SkillBridge promueve un uso respetuoso, inclusivo, accesible y responsable de la plataforma.",
        "rules_label" => "REGLAS",
        "main_title" => "Términos y condiciones",
        "last_updated" => "Última actualización: 2026",
        "term_1_title" => "1. Uso de la plataforma",
        "term_1_text" => "SkillBridge permite que los candidatos exploren oportunidades laborales, creen perfiles, suban archivos de currículum y se postulen a vacantes. Las empresas pueden crear perfiles, publicar vacantes y revisar postulaciones.",
        "term_2_title" => "2. Cuentas de usuario",
        "term_2_text" => "Los usuarios deben proporcionar información verdadera, respetuosa y actualizada. Cada usuario es responsable de proteger la información de su cuenta y mantener privada su contraseña.",
        "term_3_title" => "3. Responsabilidades de los candidatos",
        "term_3_text" => "Los candidatos deben mantener actualizada la información de su perfil y postularse solo a oportunidades que coincidan con sus intereses, habilidades, experiencia o metas. La información compartida en una postulación debe ser clara y honesta.",
        "term_4_title" => "4. Responsabilidades de las empresas",
        "term_4_text" => "Las empresas deben publicar oportunidades laborales reales, claras, respetuosas e inclusivas. Las vacantes deben describir responsabilidades, requisitos, modalidad de trabajo, información salarial cuando esté disponible y fecha límite de postulación.",
        "term_5_title" => "5. Contratación inclusiva",
        "term_5_text" => "SkillBridge anima a las empresas a usar lenguaje accesible, evitar requisitos discriminatorios y respetar a candidatos con diferentes capacidades, contextos y experiencias profesionales.",
        "term_6_title" => "6. Acciones prohibidas",
        "term_6_text" => "Los usuarios no pueden publicar información falsa, contenido ofensivo, spam, mensajes discriminatorios, ofertas laborales fraudulentas ni contenido que dañe a otros usuarios o el propósito de la plataforma.",
        "term_7_title" => "7. Postulaciones y proceso de selección",
        "term_7_text" => "Cuando un candidato se postula a un empleo, SkillBridge registra la postulación y permite que la empresa la revise. La empresa es responsable de gestionar el proceso de selección y actualizar el estado de la postulación cuando sea necesario.",
        "term_8_title" => "8. Archivos subidos",
        "term_8_text" => "Los candidatos pueden subir archivos de currículum en formatos permitidos. Los usuarios deben evitar subir archivos dañinos, documentos falsos o información que no les pertenece.",
        "term_9_title" => "9. Disponibilidad de la plataforma",
        "term_9_text" => "SkillBridge puede ser mejorado, actualizado o estar temporalmente no disponible por mantenimiento, cambios técnicos o nuevas funciones. El objetivo siempre es ofrecer una mejor experiencia para candidatos y empresas.",
        "term_10_title" => "10. Privacidad",
        "term_10_text" => "El uso de la información personal se explica en la Política de privacidad. Los usuarios deben revisar esa página para comprender cómo se recopila y utiliza la información en SkillBridge.",
        "term_11_title" => "11. Contacto",
        "term_11_text" => "Si los usuarios tienen preguntas sobre estos términos, pueden contactar al equipo de SkillBridge por medio de la página de contacto.",
        "view_privacy" => "Ver política de privacidad",
        "contact_team" => "Contactar al equipo",
        "footer_text" => "Conectamos talento, empresas y oportunidades para construir un futuro laboral más inclusivo.",
        "footer_candidates" => "Para candidatos",
        "create_profile" => "Crear perfil",
        "resources" => "Recursos y consejos",
        "footer_companies" => "Para empresas",
        "find_talent" => "Encontrar talento",
        "business_plans" => "Planes empresariales",
        "platform" => "Plataforma",
        "about_us" => "Quiénes somos",
        "privacy" => "Privacidad",
        "terms" => "Términos y condiciones",
        "newsletter" => "Boletín",
        "newsletter_text" => "Recibe nuevas vacantes y consejos profesionales.",
        "email_address" => "Correo electrónico",
        "email_placeholder" => "Tu correo electrónico",
        "subscribe" => "Suscribirse",
        "rights" => "© 2026 SkillBridge. Todos los derechos reservados.",
        "universal_access_icon" => "Ícono de accesibilidad",
        "close_icon" => "Ícono de cerrar",
        "menu_icon" => "Ícono de menú",
        "increase_icon" => "Ícono para aumentar texto",
        "decrease_icon" => "Ícono para disminuir texto",
        "moon_icon" => "Ícono de modo oscuro",
        "contrast_icon" => "Ícono de alto contraste",
        "read_icon" => "Ícono de lectura en voz alta",
        "stop_icon" => "Ícono de detener lectura",
        "contract_icon" => "Ícono de documento de términos",
        "send_icon" => "Ícono de enviar",
        "facebook_icon" => "Ícono de Facebook",
        "instagram_icon" => "Ícono de Instagram",
        "linkedin_icon" => "Ícono de LinkedIn",
        "tiktok_icon" => "Ícono de TikTok"
    ]
];

function t($clave) {
    global $translations, $idioma;
    return $translations[$idioma][$clave] ?? $translations["en"][$clave] ?? $clave;
}

function icono($clases, $clave) {
    return '<i class="' . limpiar($clases) . '" role="img" aria-label="' . limpiar(t($clave)) . '" title="' . limpiar(t($clave)) . '"></i>';
}

$esEmpresa = $usuarioLogueado && $tipoUsuario === "empresa";
$esAdmin = $usuarioLogueado && $tipoUsuario === "administrador";
$esCandidato = $usuarioLogueado && $tipoUsuario === "candidato";
$mostrarBuscarEmpleos = !$esEmpresa || $esAdmin;
$mostrarMisPostulaciones = $usuarioLogueado && ($esCandidato || $esAdmin);
$mostrarPanelEmpresa = $usuarioLogueado && ($esEmpresa || $esAdmin);
$idiomaAlterno = $idioma === "es" ? "en" : "es";
$textoBotonIdioma = strtoupper($idiomaAlterno);

$modoOscuro = (int)($usuarioActual["modo_oscuro"] ?? 0) === 1;
$altoContraste = (int)($usuarioActual["alto_contraste"] ?? 0) === 1;
$modoLectura = (int)($usuarioActual["modo_lectura"] ?? 0) === 1;
$escalaTexto = (float)($usuarioActual["escala_texto"] ?? 1.00);
if ($escalaTexto < 0.85 || $escalaTexto > 1.60) {
    $escalaTexto = 1.00;
}

$bodyClasses = [];
if ($modoOscuro) $bodyClasses[] = "dark-mode";
if ($altoContraste) $bodyClasses[] = "high-contrast";
if ($modoLectura) $bodyClasses[] = "reader-mode-enabled";
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

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/auth.css?v=20260904footerfinal">
    <link rel="stylesheet" href="css/paginas-extra.css?v=20260904footerfinal">
</head>

<body class="<?php echo limpiar(implode(" ", $bodyClasses)); ?>" style="--font-scale: <?php echo limpiar(number_format($escalaTexto, 2)); ?>;">

    <a href="#mainContent" class="skip-link">
        <?php echo limpiar(t("skip_main")); ?>
    </a>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <button class="accessibility-button" id="accessibilityButton"
        aria-label="<?php echo limpiar(t("open_accessibility")); ?>"
        title="<?php echo limpiar(t("open_accessibility")); ?>">
        <?php echo icono("fa-solid fa-universal-access", "universal_access_icon"); ?>
    </button>

    <aside class="accessibility-panel" id="accessibilityPanel" aria-label="<?php echo limpiar(t("accessibility")); ?>">
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3><?php echo limpiar(t("accessibility")); ?></h3>
            </div>

            <button id="closeAccessibility" aria-label="<?php echo limpiar(t("close_accessibility")); ?>"
                title="<?php echo limpiar(t("close_accessibility")); ?>">
                <?php echo icono("fa-solid fa-xmark", "close_icon"); ?>
            </button>
        </div>

        <p class="accessibility-text">
            <?php echo limpiar(t("accessibility_text")); ?>
        </p>

        <div class="accessibility-options">
            <button class="accessibility-option" id="increaseFont" type="button">
                <?php echo icono("fa-solid fa-magnifying-glass-plus", "increase_icon"); ?>
                <span><?php echo limpiar(t("increase_text")); ?></span>
            </button>

            <button class="accessibility-option" id="decreaseFont" type="button">
                <?php echo icono("fa-solid fa-magnifying-glass-minus", "decrease_icon"); ?>
                <span><?php echo limpiar(t("decrease_text")); ?></span>
            </button>

            <button class="accessibility-option" id="darkMode" type="button">
                <?php echo icono("fa-solid fa-moon", "moon_icon"); ?>
                <span><?php echo limpiar(t("dark_mode")); ?></span>
            </button>

            <button class="accessibility-option" id="highContrast" type="button">
                <?php echo icono("fa-solid fa-circle-half-stroke", "contrast_icon"); ?>
                <span><?php echo limpiar(t("high_contrast")); ?></span>
            </button>

            <button class="accessibility-option" id="readPage" type="button">
                <?php echo icono("fa-solid fa-volume-high", "read_icon"); ?>
                <span><?php echo limpiar(t("read_aloud")); ?></span>
            </button>

            <button class="accessibility-option" id="stopReading" type="button">
                <?php echo icono("fa-solid fa-volume-xmark", "stop_icon"); ?>
                <span><?php echo limpiar(t("stop_reading")); ?></span>
            </button>
        </div>
    </aside>

    <header class="header">
        <nav class="navbar container">

            <a href="index.php" class="logo" aria-label="<?php echo limpiar(t("home_aria")); ?>">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="<?php echo limpiar(t("logo_alt")); ?>">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button class="mobile-menu-button" id="mobileMenuButton"
                aria-label="<?php echo limpiar(t("menu_aria")); ?>"
                title="<?php echo limpiar(t("menu_aria")); ?>">
                <?php echo icono("fa-solid fa-bars", "menu_icon"); ?>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php"<?php echo enlaceActivo("index.php"); ?>><?php echo limpiar(t("home")); ?></a></li>

                <?php if ($mostrarBuscarEmpleos): ?>
                    <li><a href="empleos.php"<?php echo enlaceActivo("empleos.php"); ?>><?php echo limpiar(t("find_jobs")); ?></a></li>
                <?php endif; ?>

                <li><a href="empresas.php"<?php echo enlaceActivo("empresas.php"); ?>><?php echo limpiar(t("companies")); ?></a></li>

                <?php if ($mostrarMisPostulaciones): ?>
                    <li><a href="postulaciones.php"<?php echo enlaceActivo("postulaciones.php"); ?>><?php echo limpiar(t("my_applications")); ?></a></li>
                <?php endif; ?>

                <?php if ($mostrarPanelEmpresa): ?>
                    <li><a href="publicarvacante.php"<?php echo enlaceActivo("publicarvacante.php"); ?>><?php echo limpiar(t("post_job")); ?></a></li>
                    <li><a href="postulaciones-empresa.php"<?php echo enlaceActivo("postulaciones-empresa.php"); ?>><?php echo limpiar(t("received_applications")); ?></a></li>
                <?php endif; ?>

                <?php if ($usuarioLogueado): ?>
                    <li><a href="perfil.php"<?php echo enlaceActivo("perfil.php"); ?>><?php echo limpiar(t("profile")); ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <a href="terminos.php?lang=<?php echo limpiar($idiomaAlterno); ?>"
                    class="language-toggle"
                    aria-label="<?php echo limpiar(t("switch_language")); ?>"
                    title="<?php echo limpiar(t("switch_language")); ?>">
                    <?php echo limpiar($textoBotonIdioma); ?>
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
                    <span class="section-label"><?php echo limpiar(t("hero_label")); ?></span>

                    <h1><?php echo limpiar(t("hero_title")); ?></h1>

                    <p><?php echo limpiar(t("hero_text")); ?></p>
                </div>

                <div class="extra-hero-card">
                    <?php echo icono("fa-solid fa-file-contract", "contract_icon"); ?>

                    <h3><?php echo limpiar(t("hero_card_title")); ?></h3>

                    <p><?php echo limpiar(t("hero_card_text")); ?></p>
                </div>

            </div>
        </section>

        <section class="extra-section">
            <div class="container">

                <div class="contact-wrapper">

                    <div class="auth-header">
                        <span class="section-label"><?php echo limpiar(t("rules_label")); ?></span>

                        <h2><?php echo limpiar(t("main_title")); ?></h2>

                        <p class="auth-subtitle"><?php echo limpiar(t("last_updated")); ?></p>
                    </div>

                    <div class="profile-description">

                        <?php for ($i = 1; $i <= 11; $i++): ?>
                            <h3><?php echo limpiar(t("term_" . $i . "_title")); ?></h3>
                            <p><?php echo limpiar(t("term_" . $i . "_text")); ?></p>
                            <br>
                        <?php endfor; ?>

                        <div class="hero-actions">
                            <a href="privacidad.php" class="button button-secondary">
                                <?php echo limpiar(t("view_privacy")); ?>
                            </a>

                            <a href="contacto.php" class="button button-primary">
                                <?php echo limpiar(t("contact_team")); ?>
                                <?php echo icono("fa-solid fa-paper-plane", "send_icon"); ?>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </section>

    </main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script>
        window.SkillBridgeUserPreferences = {
            language: <?php echo json_encode($idioma); ?>,
            darkMode: <?php echo $modoOscuro ? "true" : "false"; ?>,
            highContrast: <?php echo $altoContraste ? "true" : "false"; ?>,
            readMode: <?php echo $modoLectura ? "true" : "false"; ?>,
            fontScale: <?php echo json_encode(number_format($escalaTexto, 2)); ?>
        };

        localStorage.setItem("skillbridgeLanguage", window.SkillBridgeUserPreferences.language);
        localStorage.setItem("skillbridgeDarkMode", window.SkillBridgeUserPreferences.darkMode ? "true" : "false");
        localStorage.setItem("skillbridgeHighContrast", window.SkillBridgeUserPreferences.highContrast ? "true" : "false");
        localStorage.setItem("skillbridgeReadMode", window.SkillBridgeUserPreferences.readMode ? "true" : "false");
        localStorage.setItem("skillbridgeFontScale", window.SkillBridgeUserPreferences.fontScale);
    </script>

    <script src="java/java.js?v=20260904footerfinal"></script>
</body>

</html>
