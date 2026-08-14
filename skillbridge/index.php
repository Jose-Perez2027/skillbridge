<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SkillBridge | Jobs Without Barriers</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description"
        content="SkillBridge connects talent, companies, and inclusive job opportunities in El Salvador.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- MENSAJE PARA LECTORES DE PANTALLA -->
    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <!-- BOTÓN DE ACCESIBILIDAD -->
    <button class="accessibility-button" id="accessibilityButton"
        aria-label="Open accessibility tools">
        <i class="fa-solid fa-universal-access"></i>
    </button>

    <!-- PANEL DE ACCESIBILIDAD -->
    <aside class="accessibility-panel" id="accessibilityPanel" aria-label="Accessibility tools">
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3>Accessibility</h3>
            </div>

            <button id="closeAccessibility" aria-label="Close accessibility tools">
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
                <span>Read content aloud</span>
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

            <a href="index.php" class="logo" aria-label="Go to SkillBridge home">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="logo">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button class="mobile-menu-button" id="mobileMenuButton"
                aria-label="Open navigation menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="empleos.php">Find jobs</a></li>
                <li><a href="empresas.php">Companies</a></li>
                <li><a href="recursos.html">Resources</a></li>
                <li><a href="accesibilidad.html">Accessibility</a></li>
            </ul>

            <div class="nav-actions">
                <a href="login.php" class="login-link">Log in</a>
                <a href="registro.php" class="button button-primary button-small">
                    Create account
                </a>
            </div>

        </nav>
    </header>

    <main>

        <!-- HERO -->
        <section class="hero-section">

            <div class="hero-decoration decoration-one"></div>
            <div class="hero-decoration decoration-two"></div>
            <div class="hero-decoration decoration-three"></div>

            <div class="container hero-grid">

                <div class="hero-content">

                    <div class="hero-badge">
                        <span class="badge-dot"></span>
                        Inclusive employment platform
                    </div>

                    <h1>
                        Your talent opens doors.
                        <span>We build the bridge.</span>
                    </h1>

                    <p class="hero-description">
                        SkillBridge connects people with real job opportunities,
                        committed companies, and resources for professional growth.
                    </p>

                    <div class="hero-buttons">
                        <a href="#buscar-empleo" class="button button-primary">
                            Find a job
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <a href="empresas.php" class="button button-secondary">
                            I'm a company
                            <i class="fa-solid fa-building"></i>
                        </a>
                    </div>

                    <div class="hero-trust">
                        <div class="trust-avatars">
                            <div class="avatar avatar-one">M</div>
                            <div class="avatar avatar-two">J</div>
                            <div class="avatar avatar-three">A</div>
                            <div class="avatar avatar-four">+</div>
                        </div>

                        <p>
                            More than <strong>2,500 people</strong> have already found opportunities.
                        </p>
                    </div>

                </div>

                <div class="hero-visual">

                    <div class="hero-card main-card">
                        <div class="card-header-row">
                            <div class="company-logo company-purple">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>

                            <span class="status-pill">
                                <span></span>
                                Active opening
                            </span>
                        </div>

                        <h3>Junior Web Developer</h3>
                        <p class="company-name">TechNova El Salvador</p>

                        <div class="job-tags">
                            <span><i class="fa-solid fa-location-dot"></i> San Salvador</span>
                            <span><i class="fa-solid fa-house-laptop"></i> Hybrid</span>
                        </div>

                        <div class="match-box">
                            <div class="match-row">
                                <span>Profile match</span>
                                <strong>92%</strong>
                            </div>

                            <div class="progress-bar">
                                <div class="progress-value"></div>
                            </div>
                        </div>

                        <a href="empleos.php" class="button button-primary mini-apply-button">
                            Apply now
                        </a>
                    </div>

                    <div class="hero-card small-card small-card-top">
                        <div class="small-icon blue-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <div>
                            <strong>1,240+</strong>
                            <span>Available openings</span>
                        </div>
                    </div>

                    <div class="hero-card small-card small-card-bottom">
                        <div class="small-icon green-icon">
                            <i class="fa-solid fa-handshake"></i>
                        </div>

                        <div>
                            <strong>120+</strong>
                            <span>Partner companies</span>
                        </div>
                    </div>

                    <div class="visual-circle circle-one"></div>
                    <div class="visual-circle circle-two"></div>

                </div>

            </div>

        </section>

        <!-- BUSCADOR -->
        <section class="search-section" id="buscar-empleo">
            <div class="container">

                <div class="search-box">
                    <div class="search-header">
                        <div>
                            <span class="section-label">FIND YOUR NEXT CHALLENGE</span>
                            <h2>Find an opportunity made for you</h2>
                        </div>

                        <div class="search-icon-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>

                    <form class="job-search-form" id="jobSearchForm">

                        <div class="form-group search-input-group">
                            <label for="jobSearch">What job are you looking for?</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-briefcase"></i>
                                <input type="text" id="jobSearch"
                                    placeholder="Example: Designer, Developer, Sales">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobCategory">Category</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-layer-group"></i>

                                <select id="jobCategory">
                                    <option value="todos">All categories</option>
                                    <option value="tecnologia">Technology</option>
                                    <option value="diseno">Design</option>
                                    <option value="ventas">Sales</option>
                                    <option value="administracion">Administration</option>
                                    <option value="atencion">Customer service</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobMode">Work arrangement</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-location-dot"></i>

                                <select id="jobMode">
                                    <option value="todos">All work arrangements</option>
                                    <option value="remoto">Remote</option>
                                    <option value="hibrido">Hybrid</option>
                                    <option value="presencial">On-site</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="button button-primary search-button">
                            Find jobs
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                    </form>

                </div>

            </div>
        </section>

        <!-- ESTADÍSTICAS -->
        <section class="stats-section">
            <div class="container stats-grid">

                <article class="stat-card">
                    <div class="stat-icon stat-icon-blue">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="1240">0</strong>
                        <span>Active openings</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="120">0</strong>
                        <span>Partner companies</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <i class="fa-solid fa-user-check"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="2500">0</strong>
                        <span>People connected</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-orange">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="98">0</strong>
                        <span>Positive experience</span>
                    </div>
                </article>

            </div>
        </section>

        <!-- EMPLEOS DESTACADOS -->
        <section class="featured-jobs-section" id="empleos-destacados">
            <div class="container">

                <div class="section-heading">
                    <div>
                        <span class="section-label">FEATURED OPPORTUNITIES</span>
                        <h2>Find a job that matches your skills</h2>
                        <p>
                            Explore verified openings from companies that value
                            talent, diversity, and professional growth.
                        </p>
                    </div>

                    <a href="empleos.php" class="text-link">
                        View all jobs
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <p class="results-indicator" id="resultsIndicator">
                    Showing recommended openings for you.
                </p>

                <div class="jobs-grid" id="jobsGrid">

                    <article class="job-card"
                        data-title="junior web developer html css javascript technology remote"
                        data-category="tecnologia"
                        data-mode="remoto">

                        <div class="job-card-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-code"></i>
                            </div>

                            <button class="save-job-button" aria-label="Save job"
                                data-job="Junior Web Developer">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type remote">Remote</span>

                        <h3>Junior Web Developer</h3>
                        <p class="job-company">Innovatech SV</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> San Salvador</span>
                            <span><i class="fa-solid fa-clock"></i> Full-time</span>
                        </div>

                        <div class="job-skills">
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">Posted today</span>

                            <button class="details-button"
                                data-title="Junior Web Developer"
                                data-company="Innovatech SV"
                                data-location="San Salvador"
                                data-mode="Remote"
                                data-description="We are looking for someone with knowledge of HTML, CSS, and JavaScript to support the development of modern, responsive, and interactive websites.">
                                View details
                            </button>
                        </div>

                    </article>

                    <article class="job-card"
                        data-title="graphic designer creative design hybrid"
                        data-category="diseno"
                        data-mode="hibrido">

                        <div class="job-card-top">
                            <div class="company-logo company-pink">
                                <i class="fa-solid fa-palette"></i>
                            </div>

                            <button class="save-job-button" aria-label="Save job"
                                data-job="Graphic Designer">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type hybrid">Hybrid</span>

                        <h3>Graphic Designer</h3>
                        <p class="job-company">Creativa Studio</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> Santa Tecla</span>
                            <span><i class="fa-solid fa-clock"></i> Full-time</span>
                        </div>

                        <div class="job-skills">
                            <span>Canva</span>
                            <span>Branding</span>
                            <span>Social media</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">2 days ago</span>

                            <button class="details-button"
                                data-title="Graphic Designer"
                                data-company="Creativa Studio"
                                data-location="Santa Tecla"
                                data-mode="Hybrid"
                                data-description="A creative company is looking for a graphic designer with fresh ideas, strong visual-tool skills, and a passion for creating engaging brand content.">
                                View details
                            </button>
                        </div>

                    </article>

                    <article class="job-card"
                        data-title="sales advisor sales on-site"
                        data-category="ventas"
                        data-mode="presencial">

                        <div class="job-card-top">
                            <div class="company-logo company-orange">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>

                            <button class="save-job-button" aria-label="Save job"
                                data-job="Sales Advisor">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type in-person">On-site</span>

                        <h3>Sales Advisor</h3>
                        <p class="job-company">Comercial Centro</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> Soyapango</span>
                            <span><i class="fa-solid fa-clock"></i> Full-time</span>
                        </div>

                        <div class="job-skills">
                            <span>Sales</span>
                            <span>Communication</span>
                            <span>Customer service</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">3 days ago</span>

                            <button class="details-button"
                                data-title="Sales Advisor"
                                data-company="Comercial Centro"
                                data-location="Soyapango"
                                data-mode="On-site"
                                data-description="We are looking for people with strong communication skills, a customer-focused mindset, and enthusiasm for meeting sales goals in a respectful and inclusive workplace.">
                                View details
                            </button>
                        </div>

                    </article>

                </div>

                <div class="no-results hidden" id="noResults">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>No openings were found with those filters.</h3>
                    <p>Try another keyword, category, or work arrangement.</p>
                </div>

            </div>
        </section>

        <!-- CATEGORÍAS -->
        <section class="categories-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">EXPLORE BY FIELD</span>
                        <h2>An opportunity for every talent</h2>
                        <p>
                            Discover fields where you can apply your knowledge,
                            skills, and experience.
                        </p>
                    </div>
                </div>

                <div class="categories-grid">

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-blue">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>

                        <h3>Technology</h3>
                        <p>Development, support, UX/UI design, and more.</p>
                        <span>245 openings <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-purple">
                            <i class="fa-solid fa-pen-ruler"></i>
                        </div>

                        <h3>Design and Creativity</h3>
                        <p>Graphic design, content, and visual communication.</p>
                        <span>138 openings <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-green">
                            <i class="fa-solid fa-headset"></i>
                        </div>

                        <h3>Customer Service</h3>
                        <p>Support, call center, and customer experience.</p>
                        <span>190 openings <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-orange">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <h3>Administration</h3>
                        <p>Human resources, finance, and operations.</p>
                        <span>167 openings <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                </div>

            </div>
        </section>

        <!-- PROCESO -->
        <section class="process-section">
            <div class="container process-grid">

                <div class="process-content">
                    <span class="section-label">SIMPLE, CLEAR, AND INCLUSIVE</span>

                    <h2>Finding a job should not be complicated</h2>

                    <p>
                        We designed a simple experience so you can focus
                        on what matters: showcasing your talent and connecting with companies.
                    </p>

                    <a href="registro.php" class="button button-primary">
                        Create my free profile
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="process-steps">

                    <article class="process-step">
                        <div class="step-number">01</div>

                        <div>
                            <h3>Create your profile</h3>
                            <p>Add your skills, experience, education, and professional interests.</p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">02</div>

                        <div>
                            <h3>Discover openings</h3>
                            <p>Use smart filters to find jobs that match your profile.</p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">03</div>

                        <div>
                            <h3>Apply with confidence</h3>
                            <p>Submit your application and track it from your personal dashboard.</p>
                        </div>
                    </article>

                </div>

            </div>
        </section>

        <!-- ACCESIBILIDAD -->
        <section class="inclusion-section">
            <div class="container inclusion-grid">

                <div class="inclusion-visual">
                    <div class="inclusion-card inclusion-main-card">
                        <div class="inclusion-icon-main">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <h3>Designed for everyone</h3>
                        <p>Tools that help everyone navigate comfortably.</p>
                    </div>

                    <div class="inclusion-mini-card mini-card-left">
                        <i class="fa-solid fa-volume-high"></i>
                        <span>Text reader</span>
                    </div>

                    <div class="inclusion-mini-card mini-card-right">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                        <span>High contrast</span>
                    </div>
                </div>

                <div class="inclusion-content">
                    <span class="section-label">JOBS WITHOUT BARRIERS</span>

                    <h2>Inclusion is not optional. It is part of our design.</h2>

                    <p>
                        SkillBridge includes accessibility tools to create a
                        clear, adaptable, and comfortable experience for everyone.
                    </p>

                    <ul class="inclusion-list">
                        <li><i class="fa-solid fa-check"></i> Adjustable text size.</li>
                        <li><i class="fa-solid fa-check"></i> Dark mode and high contrast.</li>
                        <li><i class="fa-solid fa-check"></i> Voice content reading.</li>
                        <li><i class="fa-solid fa-check"></i> Simple navigation and clear buttons.</li>
                    </ul>

                    <a href="accesibilidad.html" class="text-link">
                        Explore our tools
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </section>

        <!-- TESTIMONIOS -->
        <section class="testimonials-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">STORIES THAT INSPIRE</span>
                        <h2>Talent changes lives when it finds an opportunity</h2>
                    </div>
                </div>

                <div class="testimonials-grid">

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “SkillBridge helped me organize my professional profile and find
                            an opening where my design skills were valued.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-one">M</div>

                            <div>
                                <h4>María Hernández</h4>
                                <span>Graphic Designer</span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “The platform is clear and easy to use. I was able to search for
                            remote jobs and apply from my phone without any complications.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-two">J</div>

                            <div>
                                <h4>José Martínez</h4>
                                <span>Technical Support</span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “As a company, we found qualified candidates and a
                            platform that truly promotes more inclusive hiring.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-three">A</div>

                            <div>
                                <h4>Ana López</h4>
                                <span>Human Resources</span>
                            </div>
                        </div>
                    </article>

                </div>

            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
            <div class="container cta-content">

                <div>
                    <span class="section-label cta-label">YOUR NEXT OPPORTUNITY STARTS TODAY</span>
                    <h2>Let your skills speak for you.</h2>
                    <p>
                        Create your free profile, explore opportunities, and take the next step
                        in your professional journey.
                    </p>
                </div>

                <div class="cta-buttons">
                    <a href="registro.php" class="button button-light">
                        Create account
                        <i class="fa-solid fa-user-plus"></i>
                    </a>

                    <a href="empleos.php" class="button button-outline-light">
                        Explore jobs
                    </a>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-grid">

            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">
                    <div class="logo-icon">
                        <img src="img/LOGOS.png" alt="logo">
                    </div>

                    <div class="logo-text">
                        <span>Skill</span>Bridge
                    </div>
                </a>

                <p>
                    We connect talent, companies, and opportunities to build a
                    more inclusive future of work.
                </p>

                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h4>For candidates</h4>
                <a href="empleos.php">Find jobs</a>
                <a href="registro.php">Create profile</a>
                <a href="postulaciones.html">My applications</a>
                <a href="recursos.html">Resources and advice</a>
            </div>

            <div class="footer-column">
                <h4>For companies</h4>
                <a href="empresas.php">Post a job</a>
                <a href="empresas.php">Find talent</a>
                <a href="empresas.php">Business plans</a>
                <a href="contacto.html">Contact the team</a>
            </div>

            <div class="footer-column">
                <h4>Newsletter</h4>
                <p>Receive new job openings and professional advice.</p>

                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only">Email address</label>

                    <div class="newsletter-input">
                        <input type="email" id="newsletterEmail"
                            placeholder="Your email address" required>

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

    <!-- MODAL DE DETALLE DE EMPLEO -->
    <div class="modal hidden" id="jobModal" role="dialog"
        aria-modal="true" aria-labelledby="modalJobTitle">

        <div class="modal-overlay" id="modalOverlay"></div>

        <div class="modal-content">
            <button class="modal-close" id="modalClose" aria-label="Close window">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="modal-company-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>

            <span class="modal-label">FEATURED OPENING</span>

            <h2 id="modalJobTitle">Job title</h2>
            <p class="modal-company" id="modalCompany">Company</p>

            <div class="modal-info">
                <span id="modalLocation">
                    <i class="fa-solid fa-location-dot"></i>
                    Location
                </span>

                <span id="modalMode">
                    <i class="fa-solid fa-house-laptop"></i>
                    Work arrangement
                </span>
            </div>

            <p class="modal-description" id="modalDescription"></p>

            <div class="modal-actions">
                <a href="empleos.php" class="button button-primary" id="applyButton">
                    Apply now
                    <i class="fa-solid fa-paper-plane"></i>
                </a>

                <button class="button button-secondary" id="modalSaveButton">
                    Save job
                    <i class="fa-regular fa-bookmark"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="java/java.js"></script>
</body>
</html>