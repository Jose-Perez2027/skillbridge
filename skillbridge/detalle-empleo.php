<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Junior Web Developer | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Explore the details of the Junior Web Developer position on SkillBridge."
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
    <link rel="stylesheet" href="css/detalle-empleo.css">
</head>

<body>

    <a href="#mainContent" class="skip-link">
        Skip to main content
    </a>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <!-- ACCESIBILIDAD -->

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
                <li><a href="empleos.php" class="active">Find Jobs</a></li>
                <li><a href="empresas.php">Companies</a></li>
                <li><a href="recursos.html">Resources</a></li>
                <li><a href="accesibilidad.html">Accessibility</a></li>
            </ul>

            <div class="nav-actions">
                <a href="login.php" class="login-link">Sign In</a>

                <a href="registro.php" class="button button-primary button-small">
                    Create Account
                </a>
            </div>

        </nav>
    </header>

    <main id="mainContent">

        <!-- ENCABEZADO -->

        <section class="detail-hero-section">

            <div class="detail-hero-circle detail-hero-circle-one"></div>
            <div class="detail-hero-circle detail-hero-circle-two"></div>

            <div class="container">

                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <i class="fa-solid fa-chevron-right"></i>

                    <a href="empleos.php">Find Jobs</a>
                    <i class="fa-solid fa-chevron-right"></i>

                    <span>Junior Web Developer</span>
                </div>

                <div class="detail-hero-card">

                    <div class="detail-company-logo">
                        <i class="fa-solid fa-code"></i>
                    </div>

                    <div class="detail-hero-main">

                        <div class="detail-hero-labels">
                            <span class="job-type remote">Remote</span>

                            <span class="detail-inclusive-label">
                                <i class="fa-solid fa-universal-access"></i>
                                Inclusive company
                            </span>

                            <span class="verified-label">
                                <i class="fa-solid fa-circle-check"></i>
                                Verified job
                            </span>
                        </div>

                        <h1>Junior Web Developer</h1>

                        <p class="detail-company-name">
                            Innovatech SV
                        </p>

                        <div class="detail-hero-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Remote from El Salvador
                            </span>

                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Full-time
                            </span>

                            <span>
                                <i class="fa-regular fa-calendar"></i>
                                Posted today
                            </span>
                        </div>

                    </div>

                    <div class="detail-hero-actions">
                        <button
                            class="save-job-button detail-save-button"
                            id="detailSaveButton"
                            data-job="Desarrollador Web Jr."
                            aria-label="Save job"
                        >
                            <i class="fa-regular fa-bookmark"></i>
                        </button>

                        <button
                            class="detail-share-button"
                            id="shareJobButton"
                            aria-label="Share job"
                        >
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>Share</span>
                        </button>
                    </div>

                </div>

            </div>
        </section>

        <!-- CONTENIDO PRINCIPAL -->

        <section class="detail-content-section">
            <div class="container detail-layout">

                <!-- COLUMNA IZQUIERDA -->

                <section class="detail-main-content">

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-blue">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">ABOUT THE POSITION</span>
                                <h2>Job Description</h2>
                            </div>
                        </div>

                        <p>
                            At Innovatech SV, we are looking for a Junior Web Developer interested
                            in creating modern, accessible, and functional digital experiences.
                            You will join a technology team that develops
                            web solutions for local and international companies.
                        </p>

                        <p>
                            This opportunity is designed for people with knowledge of
                            HTML, CSS, and JavaScript who want to strengthen their skills,
                            learn new tools, and participate in real projects.
                        </p>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-purple">
                                <i class="fa-solid fa-list-check"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">RESPONSIBILITIES</span>
                                <h2>What You Will Do</h2>
                            </div>
                        </div>

                        <ul class="detail-list">
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Develop and update responsive websites.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Turn visual designs into functional interfaces.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Apply web accessibility best practices.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Fix visual issues and improve the user experience.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Collaborate using version control tools.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Participate in progress and planning meetings.
                            </li>
                        </ul>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-green">
                                <i class="fa-solid fa-user-check"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">IDEAL PROFILE</span>
                                <h2>Application Requirements</h2>
                            </div>
                        </div>

                        <div class="requirements-grid">

                            <div class="requirement-item">
                                <div class="requirement-number">01</div>

                                <div>
                                    <h3>Technical Knowledge</h3>
                                    <p>
                                        HTML5, CSS3, basic or intermediate JavaScript
                                        and responsive design.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">02</div>

                                <div>
                                    <h3>Education</h3>
                                    <p>
                                        Technical high school diploma, university studies,
                                        or technology-related courses.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">03</div>

                                <div>
                                    <h3>Experience</h3>
                                    <p>
                                        Previous work experience is not required.
                                        Personal or academic projects are valued.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">04</div>

                                <div>
                                    <h3>Personal Skills</h3>
                                    <p>
                                        Responsibility, communication, creativity,
                                        and a willingness to learn.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-orange">
                                <i class="fa-solid fa-star"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">ADDITIONAL VALUE</span>
                                <h2>It Is a Plus If You Have</h2>
                            </div>
                        </div>

                        <div class="skills-cloud">
                            <span>Git and GitHub</span>
                            <span>Figma</span>
                            <span>React</span>
                            <span>Bootstrap</span>
                            <span>Tailwind CSS</span>
                            <span>Basic English</span>
                            <span>Canva</span>
                            <span>UX/UI</span>
                            <span>Remote Work</span>
                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-pink">
                                <i class="fa-solid fa-gift"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">BENEFITS</span>
                                <h2>What Innovatech SV Offers</h2>
                            </div>
                        </div>

                        <div class="benefits-grid">

                            <div class="benefit-item">
                                <i class="fa-solid fa-house-laptop"></i>
                                <h3>Remote Work</h3>
                                <p>Work from home with organized schedules.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <h3>Training</h3>
                                <p>Access to technology courses and workshops.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-chart-line"></i>
                                <h3>Professional Growth</h3>
                                <p>Opportunity to advance into new roles.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-heart"></i>
                                <h3>Inclusive Environment</h3>
                                <p>Respect, diversity, and genuine collaboration.</p>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-blue">
                                <i class="fa-solid fa-route"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">SELECTION PROCESS</span>
                                <h2>What Happens After You Apply?</h2>
                            </div>
                        </div>

                        <div class="selection-process">

                            <div class="selection-step">
                                <div class="selection-step-number">1</div>

                                <div>
                                    <h3>Profile Review</h3>
                                    <p>
                                        The team will review your skills, résumé, and projects.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">2</div>

                                <div>
                                    <h3>Initial Interview</h3>
                                    <p>
                                        A virtual conversation to get to know you better.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">3</div>

                                <div>
                                    <h3>Practical Test</h3>
                                    <p>
                                        You will complete a short web development challenge.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">4</div>

                                <div>
                                    <h3>Final Decision</h3>
                                    <p>
                                        You will receive a response by email.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card company-profile-card">
                        <div class="company-profile-header">

                            <div class="company-profile-logo">
                                <i class="fa-solid fa-code"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">ABOUT THE COMPANY</span>
                                <h2>Innovatech SV</h2>
                                <p>Technology solutions for modern companies.</p>
                            </div>

                        </div>

                        <p class="company-profile-description">
                            Innovatech SV is a Salvadoran company dedicated to developing
                            digital solutions, web platforms, and management tools
                            for small, medium-sized, and large companies. Its team works
                            collaboratively and promotes opportunities for people with
                            different backgrounds and levels of experience.
                        </p>

                        <div class="company-profile-stats">

                            <div>
                                <strong>2018</strong>
                                <span>Year Founded</span>
                            </div>

                            <div>
                                <strong>50+</strong>
                                <span>Team Members</span>
                            </div>

                            <div>
                                <strong>24</strong>
                                <span>Jobs Posted</span>
                            </div>

                        </div>

                        <a href="empresas.php" class="text-link">
                            Explore Partner Companies
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </article>

                </section>

                <!-- COLUMNA DERECHA -->

                <aside class="detail-sidebar">

                    <div class="apply-card">

                        <div class="apply-card-top">
                            <span class="apply-card-label">ESTIMATED SALARY</span>

                            <h2>$950 <small>/ month</small></h2>

                            <p>
                                Salary may vary based on experience, skills,
                                and interview results.
                            </p>
                        </div>

                        <div class="apply-card-info">

                            <div>
                                <i class="fa-solid fa-briefcase"></i>

                                <span>
                                    <strong>Employment Type</strong>
                                    Full-time
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-house-laptop"></i>

                                <span>
                                    <strong>Work Arrangement</strong>
                                    100% remote
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-layer-group"></i>

                                <span>
                                    <strong>Level</strong>
                                    Junior
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-calendar-days"></i>

                                <span>
                                    <strong>Application Deadline</strong>
                                    September 30
                                </span>
                            </div>

                        </div>

                        <button
                            class="button button-primary detail-apply-button open-application-modal"
                        >
                            Apply Now
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>

                        <p class="apply-card-note">
                            <i class="fa-solid fa-shield-heart"></i>
                            Your information will only be sent to the company.
                        </p>

                    </div>

                    <div class="accessibility-company-card">

                        <div class="accessibility-company-icon">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <div>
                            <h3>Commitment to Inclusion</h3>

                            <p>
                                This company states that it promotes
                                respectful and accessible selection processes.
                            </p>
                        </div>

                    </div>

                    <div class="sidebar-share-card">

                        <h3>Share This Opportunity</h3>

                        <p>
                            This opportunity may be perfect for someone you know.
                        </p>

                        <div class="share-social-buttons">

                            <button class="share-social-button facebook-share">
                                <i class="fa-brands fa-facebook-f"></i>
                            </button>

                            <button class="share-social-button linkedin-share">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </button>

                            <button class="share-social-button whatsapp-share">
                                <i class="fa-brands fa-whatsapp"></i>
                            </button>

                            <button
                                class="share-social-button copy-link-button"
                                id="copyLinkButton"
                                aria-label="Copy link"
                            >
                                <i class="fa-solid fa-link"></i>
                            </button>

                        </div>

                        <small id="copyLinkMessage"></small>

                    </div>

                </aside>

            </div>
        </section>

        <!-- EMPLEOS SIMILARES -->

        <section class="similar-jobs-section">
            <div class="container">

                <div class="section-heading">
                    <div>
                        <span class="section-label">YOU MAY ALSO BE INTERESTED</span>

                        <h2>Similar Jobs for Your Profile</h2>

                        <p>
                            Explore other opportunities related to technology,
                            development, and digital skills.
                        </p>
                    </div>

                    <a href="empleos.php" class="text-link">
                        View All Jobs
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="similar-jobs-grid">

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>

                            <span class="job-type in-person">On-site</span>
                        </div>

                        <h3>IT Technical Support</h3>
                        <p>Nexa Solutions</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Antiguo Cuscatlán
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $850 / month
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            View Opportunity
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>

                            <span class="job-type hybrid">Hybrid</span>
                        </div>

                        <h3>Data Analyst</h3>
                        <p>DataVision</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                San Salvador
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $1,200 / month
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            View Opportunity
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>

                            <span class="job-type remote">Remote</span>
                        </div>

                        <h3>Frontend Developer</h3>
                        <p>Cloud Bridge Labs</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Remote
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $1,800 / month
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            View Opportunity
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>

                </div>

            </div>
        </section>

        <!-- CTA -->

        <section class="detail-cta-section">
            <div class="container detail-cta-content">

                <div>
                    <span class="section-label cta-label">
                        DON'T MISS THIS OPPORTUNITY
                    </span>

                    <h2>Your Next Professional Journey Can Start Today.</h2>

                    <p>
                        Apply, showcase your skills, and take the next step
                        toward a career in technology.
                    </p>
                </div>

                <button
                    class="button button-light open-application-modal"
                >
                    Apply Now
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

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
                <p>Receive new job openings and career tips.</p>

                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only">
                        Email Address
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

    <!-- MODAL DE POSTULACIÓN -->

    <div
        class="application-modal hidden"
        id="applicationModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="applicationModalTitle"
    >
        <div class="application-modal-overlay" id="applicationModalOverlay"></div>

        <div class="application-modal-content">

            <button
                class="application-modal-close"
                id="closeApplicationModal"
                aria-label="Close application form"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div id="applicationFormContainer">

                <div class="application-modal-icon">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>

                <span class="modal-label">QUICK APPLICATION</span>

                <h2 id="applicationModalTitle">
                    Apply for the Junior Web Developer Position
                </h2>

                <p class="application-modal-description">
                    Complete your information to send your profile to Innovatech SV.
                </p>

                <form id="applicationForm" class="application-form">

                    <div class="application-form-row">

                        <div class="application-form-group">
                            <label for="applicantName">Full Name</label>

                            <input
                                type="text"
                                id="applicantName"
                                placeholder="Example: Isaías Pérez"
                                required
                            >
                        </div>

                        <div class="application-form-group">
                            <label for="applicantEmail">Email Address</label>

                            <input
                                type="email"
                                id="applicantEmail"
                                placeholder="email@example.com"
                                required
                            >
                        </div>

                    </div>

                    <div class="application-form-row">

                        <div class="application-form-group">
                            <label for="applicantPhone">Phone Number</label>

                            <input
                                type="tel"
                                id="applicantPhone"
                                placeholder="0000-0000"
                                required
                            >
                        </div>

                        <div class="application-form-group">
                            <label for="applicantExperience">Experience</label>

                            <select id="applicantExperience" required>
                                <option value="">Select an option</option>
                                <option>No work experience</option>
                                <option>Less than 1 year</option>
                                <option>1 to 2 years</option>
                                <option>More than 2 years</option>
                            </select>
                        </div>

                    </div>

                    <div class="application-form-group">
                        <label for="applicantMessage">
                            Briefly tell us why you are interested in this position
                        </label>

                        <textarea
                            id="applicantMessage"
                            rows="4"
                            placeholder="I am interested in this opportunity because..."
                            required
                        ></textarea>
                    </div>

                    <div class="application-form-group">
                        <label for="applicantCV">Attach Résumé</label>

                        <label class="cv-upload-box" for="applicantCV">
                            <i class="fa-solid fa-file-arrow-up"></i>

                            <span id="cvFileText">
                                Select a PDF or DOCX file
                            </span>

                            <small>Maximum 5 MB</small>
                        </label>

                        <input
                            type="file"
                            id="applicantCV"
                            accept=".pdf,.doc,.docx"
                            hidden
                        >
                    </div>

                    <label class="application-checkbox">
                        <input type="checkbox" required>

                        <span class="application-custom-checkbox"></span>

                        <span>
                            I agree that SkillBridge may share my information with Innovatech SV
                            for this application.
                        </span>
                    </label>

                    <button type="submit" class="button button-primary application-submit-button">
                        Submit Application
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                </form>

            </div>

            <div class="application-success hidden" id="applicationSuccess">

                <div class="application-success-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <span class="modal-label">APPLICATION SUBMITTED</span>

                <h2>Great Decision!</h2>

                <p>
                    Your application for the <strong>Junior Web Developer</strong> position
                    was successfully sent to Innovatech SV.
                </p>

                <div class="application-success-info">
                    <i class="fa-solid fa-envelope"></i>

                    <span>
                        You will receive process updates by email.
                    </span>
                </div>

                <button class="button button-primary" id="closeSuccessButton">
                    Got It
                    <i class="fa-solid fa-check"></i>
                </button>

            </div>

        </div>
    </div>

    <script src="java/java.js"></script>
    <script src="java/detalle-empleo.js"></script>
</body>
</html>