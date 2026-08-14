<?php
require_once "config/conexion.php";

$empresas = [];

try {
    $sqlEmpresas = "
        SELECT 
            empresas.id_empresa,
            empresas.nombre,
            empresas.lema,
            empresas.descripcion,
            empresas.ubicacion,
            empresas.colaboradores,
            COUNT(vacantes.id_vacante) AS total_vacantes
        FROM empresas
        LEFT JOIN vacantes 
            ON empresas.id_empresa = vacantes.id_empresa
            AND vacantes.estado = 'activa'
        WHERE empresas.estado = 1
        GROUP BY empresas.id_empresa
        ORDER BY empresas.fecha_registro DESC
    ";

    $consultaEmpresas = $pdo->query($sqlEmpresas);
    $empresas = $consultaEmpresas->fetchAll();
} catch (Exception $error) {
    $empresas = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Companies | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Discover SkillBridge partner companies and post inclusive job openings to connect with qualified talent."
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/empresas.css">
</head>

<body>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <!-- BOTÓN Y PANEL DE ACCESIBILIDAD -->

    <button
        class="accessibility-button"
        id="accessibilityButton"
        aria-label="Open accessibility tools"
    >
        <i class="fa-solid fa-universal-access"></i>
    </button>

    <aside
        class="accessibility-panel"
        id="accessibilityPanel"
        aria-label="Accessibility tools"
    >
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3>Accessibility</h3>
            </div>

            <button
                id="closeAccessibility"
                aria-label="Close accessibility tools"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="accessibility-text">
            Adjust the website experience to your needs.
        </p>

        <div class="accessibility-options">

            <button class="accessibility-option" id="increaseFont">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
                <span>Increase text size</span>
            </button>

            <button class="accessibility-option" id="decreaseFont">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
                <span>Decrease text size</span>
            </button>

            <button class="accessibility-option" id="darkMode">
                <i class="fa-solid fa-moon"></i>
                <span>Dark mode</span>
            </button>

            <button class="accessibility-option" id="highContrast">
                <i class="fa-solid fa-circle-half-stroke"></i>
                <span>High contrast</span>
            </button>

            <button class="accessibility-option" id="readPage">
                <i class="fa-solid fa-volume-high"></i>
                <span>Read content</span>
            </button>

            <button class="accessibility-option" id="stopReading">
                <i class="fa-solid fa-volume-xmark"></i>
                <span>Stop reading</span>
            </button>

        </div>
    </aside>

    <!-- HEADER -->

    <header class="header">
        <nav class="navbar container">

            <a href="index.php" class="logo" aria-label="Go to the SkillBridge homepage">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="SkillBridge logo">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Open navigation menu"
            >
                <i class="fa-solid fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="empleos.php">Find Jobs</a></li>
                <li><a href="empresas.php" class="active">Companies</a></li>
                <li><a href="recursos.html">Resources</a></li>
                <li><a href="accesibilidad.html">Accessibility</a></li>
            </ul>

            <div class="nav-actions">
                <a href="login.php" class="login-link">Log In</a>

                <a href="registro.php" class="button button-primary button-small">
                    Create Account
                </a>
            </div>

        </nav>
    </header>

    <main>

        <!-- HERO EMPRESAS -->

        <section class="companies-page-hero">
            <div class="companies-hero-shape companies-hero-shape-one"></div>
            <div class="companies-hero-shape companies-hero-shape-two"></div>

            <div class="container companies-page-hero-content">

                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span>Companies</span>
                </div>

                <div class="companies-hero-grid">

                    <div class="companies-hero-text">
                        <span class="section-label">PARTNER COMPANIES</span>

                        <h1>Find qualified talent to help your company grow.</h1>

                        <p>
                            SkillBridge connects companies with qualified candidates,
                            promoting inclusive and accessible job opportunities
                            without barriers.
                        </p>

                        <div class="companies-hero-buttons">
                            <a href="publicarvacante.php" class="button button-primary">
                                Post a Job
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="empleos.php" class="button button-secondary">
                                View Jobs
                            </a>
                        </div>
                    </div>

                    <div class="companies-hero-card">

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>

                            <div>
                                <strong>Diverse talent</strong>
                                <span>Candidates with different skills and experiences.</span>
                            </div>
                        </div>

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-universal-access"></i>
                            </div>

                            <div>
                                <strong>Workplace inclusion</strong>
                                <span>Job openings designed to create more opportunities.</span>
                            </div>
                        </div>

                        <div class="companies-mini-card">
                            <div class="companies-mini-icon">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>

                            <div>
                                <strong>Clear process</strong>
                                <span>Organized information for companies and candidates.</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- BENEFICIOS -->

        <section class="companies-benefits-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">BENEFITS</span>

                        <h2>Why choose SkillBridge?</h2>

                        <p>
                            Our platform helps companies post job opportunities
                            in a clear, professional, and inclusive way.
                        </p>
                    </div>
                </div>

                <div class="companies-benefits-grid">

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-blue">
                            <i class="fa-solid fa-handshake-angle"></i>
                        </div>

                        <h3>Inclusive hiring</h3>

                        <p>
                            Promote more accessible recruitment processes for women,
                            men, and people with disabilities.
                        </p>
                    </article>

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-purple">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>

                        <h3>Greater reach</h3>

                        <p>
                            Job openings reach candidates who are interested in growing
                            professionally and contributing value.
                        </p>
                    </article>

                    <article class="company-benefit-card">
                        <div class="company-benefit-icon company-benefit-green">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <h3>Easy posting</h3>

                        <p>
                            Companies can organize their job opening information
                            quickly and clearly.
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <!-- EMPRESAS ALIADAS -->

        <section class="companies-list-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">BUSINESS NETWORK</span>

                        <h2>Partner companies</h2>

                        <p>
                            These companies are part of SkillBridge’s job opportunity
                            network.
                        </p>
                    </div>
                </div>

                <div class="companies-grid">

                    <?php if (count($empresas) > 0): ?>

                        <?php foreach ($empresas as $empresa): ?>
                            <article class="company-card">
                                <div class="company-logo-letter">
                                    <?php echo strtoupper(substr($empresa["nombre"], 0, 1)); ?>
                                </div>

                                <div class="company-info">
                                    <h3>
                                        <?php echo htmlspecialchars($empresa["nombre"]); ?>
                                    </h3>

                                    <p class="company-slogan">
                                        <?php echo htmlspecialchars($empresa["lema"] ?? "SkillBridge partner company"); ?>
                                    </p>

                                    <p class="company-description">
                                        <?php echo htmlspecialchars($empresa["descripcion"] ?? "A company committed to inclusive hiring."); ?>
                                    </p>

                                    <div class="company-meta">
                                        <span>
                                            <i class="fa-solid fa-location-dot"></i>
                                            <?php echo htmlspecialchars($empresa["ubicacion"] ?? "El Salvador"); ?>
                                        </span>

                                        <span>
                                            <i class="fa-solid fa-users"></i>
                                            <?php echo htmlspecialchars($empresa["colaboradores"] ?? "Growing team"); ?>
                                        </span>
                                    </div>

                                    <div class="company-card-footer">
                                        <span class="vacancies-badge">
                                            <?php echo $empresa["total_vacantes"]; ?>
                                            job opening<?php echo $empresa["total_vacantes"] != 1 ? "s" : ""; ?>
                                        </span>

                                        <a href="empleos.php" class="text-link">
                                            View Job Openings
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <article class="company-card">
                            <div class="company-logo-letter">
                                S
                            </div>

                            <div class="company-info">
                                <h3>SkillBridge Demo</h3>

                                <p class="company-slogan">
                                    Empresa de ejemplo
                                </p>

                                <p class="company-description">
                                    This card is displayed while real companies are being added
                                    to the database.
                                </p>

                                <div class="company-meta">
                                    <span>
                                        <i class="fa-solid fa-location-dot"></i>
                                        San Salvador, El Salvador
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-users"></i>
                                        50+
                                    </span>
                                </div>

                                <div class="company-card-footer">
                                    <span class="vacancies-badge">0 job openings</span>

                                    <a href="publicarvacante.php" class="text-link">
                                        Post a Job
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>

                    <?php endif; ?>

                </div>

            </div>
        </section>

        <!-- PROCESO -->

        <section class="companies-process-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">PROCESS</span>

                        <h2>Posting a job is easy</h2>
                    </div>
                </div>

                <div class="companies-steps-grid">

                    <article class="company-step-card">
                        <span>01</span>
                        <h3>Complete the form</h3>
                        <p>
                            Add the main information about your company and the job opening.
                        </p>
                    </article>

                    <article class="company-step-card">
                        <span>02</span>
                        <h3>Describe the position</h3>
                        <p>
                            Explain the duties, requirements, work arrangement, salary, and deadline.
                        </p>
                    </article>

                    <article class="company-step-card">
                        <span>03</span>
                        <h3>Receive applications</h3>
                        <p>
                            Candidates can view the job opening and apply through SkillBridge.
                        </p>
                    </article>

                </div>

            </div>
        </section>

        <!-- CTA -->

        <section class="companies-cta-section">
            <div class="container companies-cta-content">
                <div>
                    <span class="section-label cta-label">POST YOUR JOB OPENING</span>

                    <h2>Let’s build job opportunities without barriers.</h2>

                    <p>
                        Post an inclusive job opening and connect with qualified talent
                        ready to contribute value to your company.
                    </p>
                </div>

                <a href="publicarvacante.php" class="button button-light">
                    Post a Job
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </section>

    </main>

    <!-- FOOTER -->

    <footer class="footer">
        <div class="container footer-grid">

            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">
                    <div class="logo-icon">
                        <img src="img/LOGOS.png" alt="SkillBridge logo">
                    </div>

                    <div class="logo-text">
                        <span>Skill</span>Bridge
                    </div>
                </a>

                <p>
                    We connect talent, companies, and opportunities to build
                    a more inclusive future of work.
                </p>

                <div class="social-links">
                    <a href="#" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#" aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>

                    <a href="#" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <div class="footer-column">
                <h4>For Candidates</h4>
                <a href="empleos.php">Find Jobs</a>
                <a href="registro.php">Create Profile</a>
                <a href="postulaciones.html">My Applications</a>
                <a href="recursos.html">Resources and Tips</a>
            </div>

            <div class="footer-column">
                <h4>For Companies</h4>
                <a href="empresas.php">Post a Job</a>
                <a href="empresas.php">Find Talent</a>
                <a href="empresas.php">Business Plans</a>
                <a href="contacto.html">Contact the Team</a>
            </div>

            <div class="footer-column">
                <h4>Newsletter</h4>

                <p>Receive new job openings and professional tips.</p>

                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only">
                        Email address
                    </label>

                    <div class="newsletter-input">
                        <input
                            type="email"
                            id="newsletterEmail"
                            placeholder="Your email address"
                            required
                        >

                        <button type="submit" aria-label="Subscribe">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <small id="newsletterMessage"></small>
            </div>

        </div>

        <div class="container footer-bottom">
            <p>© 2026 SkillBridge. All rights reserved.</p>

            <div>
                <a href="#">Privacy</a>
                <a href="#">Terms and Conditions</a>
            </div>
        </div>
    </footer>

    <script src="java/java.js"></script>
</body>
</html>