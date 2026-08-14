<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Find Jobs | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Explore inclusive jobs, filter opportunities, and find the ideal position for your skills on SkillBridge."
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
    <link rel="stylesheet" href="css/empleos.css">
</head>

<body>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <!-- BOTÓN Y PANEL DE ACCESIBILIDAD -->

    <button
        class="accessibility-button"
        id="accessibilityButton"
        aria-label="Open Accessibility Tools"
    >
        <i class="fa-solid fa-universal-access"></i>
    </button>

    <aside
        class="accessibility-panel"
        id="accessibilityPanel"
        aria-label="Accessibility Tools"
    >
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3>Accessibility</h3>
            </div>

            <button
                id="closeAccessibility"
                aria-label="Close Accessibility Tools"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="accessibility-text">
            Adjust the site experience to your needs.
        </p>

        <div class="accessibility-options">

            <button class="accessibility-option" id="increaseFont">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
                <span>Increase Text Size</span>
            </button>

            <button class="accessibility-option" id="decreaseFont">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
                <span>Decrease Text Size</span>
            </button>

            <button class="accessibility-option" id="darkMode">
                <i class="fa-solid fa-moon"></i>
                <span>Dark Mode</span>
            </button>

            <button class="accessibility-option" id="highContrast">
                <i class="fa-solid fa-circle-half-stroke"></i>
                <span>High Contrast</span>
            </button>

            <button class="accessibility-option" id="readPage">
                <i class="fa-solid fa-volume-high"></i>
                <span>Read Content</span>
            </button>

            <button class="accessibility-option" id="stopReading">
                <i class="fa-solid fa-volume-xmark"></i>
                <span>Stop Reading</span>
            </button>

        </div>
    </aside>

    <!-- HEADER -->

    <header class="header">
        <nav class="navbar container">

            <a href="index.php" class="logo" aria-label="Go to the SkillBridge Home Page">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="SkillBridge Logo">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Open Navigation Menu"
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
                <a href="login.php" class="login-link">Log In</a>

                <a href="registro.php" class="button button-primary button-small">
                    Create Account
                </a>
            </div>

        </nav>
    </header>

    <main>

        <!-- ENCABEZADO DE PÁGINA -->

        <section class="jobs-page-hero">
            <div class="jobs-hero-shape jobs-hero-shape-one"></div>
            <div class="jobs-hero-shape jobs-hero-shape-two"></div>

            <div class="container jobs-page-hero-content">

                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span>Find Jobs</span>
                </div>

                <div class="jobs-hero-text">
                    <div>
                        <span class="section-label">OPPORTUNITIES FOR EVERYONE</span>

                        <h1>Find a job that matches your talent.</h1>

                        <p>
                            Explore verified job openings from companies looking for people
                            with skills, commitment, and a desire to grow.
                        </p>
                    </div>

                    <div class="hero-jobs-stat">
                        <div class="hero-jobs-stat-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <div>
                            <strong>1,240+</strong>
                            <span>Active Job Openings</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- BUSCADOR PRINCIPAL -->

        <section class="jobs-search-section">
            <div class="container">

                <form class="employment-search-box" id="employmentSearchForm">

                    <div class="employment-search-field employment-main-search">
                        <label for="employmentSearch">
                            What job are you looking for?
                        </label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                id="employmentSearch"
                                placeholder="Example: Designer, Developer, Sales..."
                            >
                        </div>
                    </div>

                    <div class="employment-search-field">
                        <label for="employmentLocation">Location</label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-location-dot"></i>

                            <select id="employmentLocation">
                                <option value="todos">All Locations</option>
                                <option value="san-salvador">San Salvador</option>
                                <option value="soyapango">Soyapango</option>
                                <option value="santa-tecla">Santa Tecla</option>
                                <option value="antiguo-cuscatlan">Antiguo Cuscatlán</option>
                                <option value="remoto">Remote</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="button button-primary employment-search-button">
                        Find Jobs
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

                <div class="quick-filter-row">
                    <span>Quickly Explore:</span>

                    <button class="quick-filter active" data-quick-filter="todos">
                        All
                    </button>

                    <button class="quick-filter" data-quick-filter="tecnologia">
                        <i class="fa-solid fa-laptop-code"></i>
                        Technology
                    </button>

                    <button class="quick-filter" data-quick-filter="diseno">
                        <i class="fa-solid fa-pen-ruler"></i>
                        Design
                    </button>

                    <button class="quick-filter" data-quick-filter="ventas">
                        <i class="fa-solid fa-chart-line"></i>
                        Sales
                    </button>

                    <button class="quick-filter" data-quick-filter="administracion">
                        <i class="fa-solid fa-briefcase"></i>
                        Administration
                    </button>

                    <button class="quick-filter" data-quick-filter="atencion">
                        <i class="fa-solid fa-headset"></i>
                        Customer Service
                    </button>
                </div>

            </div>
        </section>

        <!-- CONTENIDO DE EMPLEOS -->

        <section class="jobs-content-section">
            <div class="container jobs-page-layout">

                <!-- FILTROS -->

                <aside class="jobs-filters" id="jobsFilters">

                    <div class="filters-header">
                        <div>
                            <h2>Filters</h2>
                            <p>Customize your search.</p>
                        </div>

                        <button id="clearFiltersButton" class="clear-filters-button">
                            Clear
                        </button>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Category</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="tecnologia" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Technology
                                <small>245</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="diseno" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Design and Creativity
                                <small>138</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="ventas" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Sales
                                <small>189</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="administracion" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Administration
                                <small>167</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="atencion" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Customer Service
                                <small>201</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Work Arrangement</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="remoto" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                Remote
                                <small>310</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="hibrido" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                Hybrid
                                <small>214</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="presencial" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                On-site
                                <small>716</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Experience</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="sin-experiencia" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                No Experience
                                <small>108</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="junior" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Junior Level
                                <small>356</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="intermedio" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Mid Level
                                <small>463</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="senior" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Senior Level
                                <small>313</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group salary-filter-group">
                        <button class="filter-title" type="button">
                            <span>Monthly Salary</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="salary-range-box">
                            <input
                                type="range"
                                id="salaryRange"
                                min="300"
                                max="2000"
                                step="100"
                                value="2000"
                            >

                            <div class="salary-range-values">
                                <span>Starting at $300</span>
                                <strong id="salaryValue">$2,000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="inclusive-note">
                        <div class="inclusive-note-icon">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <div>
                            <strong>Inclusive Employment</strong>
                            <p>
                                We prioritize companies that promote barrier-free opportunities.
                            </p>
                        </div>
                    </div>

                </aside>

                <!-- LISTADO -->

                <section class="jobs-results-area">

                    <div class="jobs-results-top">

                        <div>
                            <span class="results-small-text">AVAILABLE OPPORTUNITIES</span>

                            <h2 id="jobsResultsTitle">
                                12 Jobs Found
                            </h2>

                            <p id="jobsResultsText">
                                Explore job openings based on your professional interests.
                            </p>
                        </div>

                        <div class="jobs-result-actions">

                            <label for="sortJobs" class="sr-only">
                                Sort Jobs
                            </label>

                            <select id="sortJobs">
                                <option value="recent">Most Recent</option>
                                <option value="salary-high">Highest Salary</option>
                                <option value="salary-low">Lowest Salary</option>
                                <option value="alphabetical">Alphabetical Order</option>
                            </select>

                            <div class="view-buttons">
                                <button
                                    class="view-button active"
                                    id="gridViewButton"
                                    aria-label="View Jobs in Grid"
                                >
                                    <i class="fa-solid fa-table-cells-large"></i>
                                </button>

                                <button
                                    class="view-button"
                                    id="listViewButton"
                                    aria-label="View Jobs in List"
                                >
                                    <i class="fa-solid fa-list"></i>
                                </button>
                            </div>

                        </div>

                    </div>

                    <div class="active-filters" id="activeFilters"></div>

                    <div class="employment-jobs-grid" id="employmentJobsGrid">

                        <!-- EMPLEO 1 -->

                        <article
                            class="employment-job-card"
                            data-title="desarrollador web junior programador html css javascript tecnologia"
                            data-category="tecnologia"
                            data-mode="remoto"
                            data-location="remoto"
                            data-experience="junior"
                            data-salary="950"
                            data-date="12"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-blue">
                                    <i class="fa-solid fa-code"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Desarrollador Web Jr."
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remote</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Junior Web Developer</h3>
                            <p class="job-company">Innovatech SV</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remote
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>HTML</span>
                                <span>CSS</span>
                                <span>JavaScript</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$950 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Today
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 2 -->

                        <article
                            class="employment-job-card"
                            data-title="diseñador grafico diseño canva branding redes sociales"
                            data-category="diseno"
                            data-mode="hibrido"
                            data-location="santa-tecla"
                            data-experience="junior"
                            data-salary="750"
                            data-date="11"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-pink">
                                    <i class="fa-solid fa-palette"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Diseñador Gráfico"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Hybrid</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Graphic Designer</h3>
                            <p class="job-company">Creativa Studio</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Santa Tecla
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Canva</span>
                                <span>Branding</span>
                                <span>Social Media</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$750 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    1 Day Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 3 -->

                        <article
                            class="employment-job-card"
                            data-title="asesor de ventas comercial servicio al cliente"
                            data-category="ventas"
                            data-mode="presencial"
                            data-location="soyapango"
                            data-experience="sin-experiencia"
                            data-salary="500"
                            data-date="10"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-orange">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Asesor de Ventas"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">On-site</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Sales Representative</h3>
                            <p class="job-company">Comercial Centro</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Soyapango
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Sales</span>
                                <span>Communication</span>
                                <span>Customer Service</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$500 + Commission</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    2 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 4 -->

                        <article
                            class="employment-job-card"
                            data-title="auxiliar administrativo administración excel documentos oficina"
                            data-category="administracion"
                            data-mode="presencial"
                            data-location="san-salvador"
                            data-experience="junior"
                            data-salary="650"
                            data-date="9"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-purple">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Auxiliar Administrativo"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">On-site</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Administrative Assistant</h3>
                            <p class="job-company">Grupo Soluciones</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Excel</span>
                                <span>Filing</span>
                                <span>Organization</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$650 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    2 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 5 -->

                        <article
                            class="employment-job-card"
                            data-title="agente de atención al cliente call center servicio"
                            data-category="atencion"
                            data-mode="hibrido"
                            data-location="san-salvador"
                            data-experience="sin-experiencia"
                            data-salary="600"
                            data-date="8"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-green">
                                    <i class="fa-solid fa-headset"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Agente de Atención al Cliente"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Hybrid</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Customer Service Agent</h3>
                            <p class="job-company">Conecta Plus</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Rotating Shifts
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Customer Service</span>
                                <span>Listening Skills</span>
                                <span>Basic English</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$600 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    3 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 6 -->

                        <article
                            class="employment-job-card"
                            data-title="soporte técnico it informática computadoras redes"
                            data-category="tecnologia"
                            data-mode="presencial"
                            data-location="antiguo-cuscatlan"
                            data-experience="intermedio"
                            data-salary="850"
                            data-date="7"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-blue">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Soporte Técnico IT"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">On-site</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>IT Support Technician</h3>
                            <p class="job-company">Nexa Solutions</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Antiguo Cuscatlán
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Windows</span>
                                <span>Networking</span>
                                <span>Support</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$850 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    4 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 7 -->

                        <article
                            class="employment-job-card"
                            data-title="community manager redes sociales contenido marketing"
                            data-category="diseno"
                            data-mode="remoto"
                            data-location="remoto"
                            data-experience="junior"
                            data-salary="700"
                            data-date="6"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-pink">
                                    <i class="fa-solid fa-hashtag"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Community Manager"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remote</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Community Manager</h3>
                            <p class="job-company">Pulse Media</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remote
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Part-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Instagram</span>
                                <span>Content</span>
                                <span>Copywriting</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$700 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    5 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 8 -->

                        <article
                            class="employment-job-card"
                            data-title="recepcionista atención clientes administración oficina"
                            data-category="atencion"
                            data-mode="presencial"
                            data-location="santa-tecla"
                            data-experience="sin-experiencia"
                            data-salary="480"
                            data-date="5"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-green">
                                    <i class="fa-solid fa-bell-concierge"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Recepcionista"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">On-site</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Receptionist</h3>
                            <p class="job-company">Clínica Bienestar</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Santa Tecla
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Customer Service</span>
                                <span>Scheduling</span>
                                <span>Organization</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$480 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    6 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 9 -->

                        <article
                            class="employment-job-card"
                            data-title="analista de datos excel power bi sql tecnologia"
                            data-category="tecnologia"
                            data-mode="hibrido"
                            data-location="san-salvador"
                            data-experience="intermedio"
                            data-salary="1200"
                            data-date="4"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-blue">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Analista de Datos"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Hybrid</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Data Analyst</h3>
                            <p class="job-company">DataVision</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Excel</span>
                                <span>Power BI</span>
                                <span>SQL</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$1,200 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    1 Week Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 10 -->

                        <article
                            class="employment-job-card"
                            data-title="auxiliar de recursos humanos administración reclutamiento"
                            data-category="administracion"
                            data-mode="presencial"
                            data-location="soyapango"
                            data-experience="junior"
                            data-salary="700"
                            data-date="3"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-purple">
                                    <i class="fa-solid fa-users"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Auxiliar de Recursos Humanos"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">On-site</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Human Resources Assistant</h3>
                            <p class="job-company">Talento Integral</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Soyapango
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Interviews</span>
                                <span>Filing</span>
                                <span>Excel</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$700 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    1 Week Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 11 -->

                        <article
                            class="employment-job-card"
                            data-title="ejecutivo de ventas digitales comercial remoto redes"
                            data-category="ventas"
                            data-mode="remoto"
                            data-location="remoto"
                            data-experience="intermedio"
                            data-salary="850"
                            data-date="2"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-orange">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Ejecutivo de Ventas Digitales"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remote</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Digital Sales Executive</h3>
                            <p class="job-company">Mercado Online SV</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remote
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Sales</span>
                                <span>WhatsApp</span>
                                <span>CRM</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$850 + Commission</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    8 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                        <!-- EMPLEO 12 -->

                        <article
                            class="employment-job-card"
                            data-title="desarrollador frontend react javascript css tecnologia"
                            data-category="tecnologia"
                            data-mode="remoto"
                            data-location="remoto"
                            data-experience="senior"
                            data-salary="1800"
                            data-date="1"
                        >
                            <div class="employment-card-top">
                                <div class="company-logo company-blue">
                                    <i class="fa-solid fa-laptop-code"></i>
                                </div>

                                <button
                                    class="save-job-button"
                                    aria-label="Save Job"
                                    data-job="Desarrollador Frontend"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remote</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusive
                                </span>
                            </div>

                            <h3>Frontend Developer</h3>
                            <p class="job-company">Cloud Bridge Labs</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remote
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Full-time
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>React</span>
                                <span>JavaScript</span>
                                <span>Git</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Estimated Salary</span>
                                    <strong>$1,800 / month</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    10 Days Ago
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <a href="detalle-empleo.php?id=1" class="details-button">

                                    View Details

                                </a>

                                <button class="mini-apply-button">
                                    Apply
                                </button>
                            </div>
                        </article>

                    </div>

                    <!-- SIN RESULTADOS -->

                    <div class="employment-no-results hidden" id="employmentNoResults">
                        <div class="employment-no-results-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <h3>No jobs were found with those filters.</h3>

                        <p>
                            Try changing your search, removing filters, or expanding the salary range.
                        </p>

                        <button id="emptyClearFiltersButton" class="button button-primary">
                            Clear Filters
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                    </div>

                    <!-- PAGINACIÓN -->

                    <nav class="jobs-pagination" id="jobsPagination" aria-label="Job Pagination">
                        <button id="previousPageButton" aria-label="Previous Page">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <div id="paginationNumbers"></div>

                        <button id="nextPageButton" aria-label="Next Page">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </nav>

                </section>

            </div>
        </section>

        <!-- BLOQUE DE EMPRESAS -->

        <section class="employer-promotion-section">
            <div class="container employer-promotion-content">

                <div class="employer-promotion-icon">
                    <i class="fa-solid fa-building"></i>
                </div>

                <div>
                    <span class="section-label">DO YOU REPRESENT A COMPANY?</span>

                    <h2>Find talent that helps your team grow.</h2>

                    <p>
                        Post job openings, connect with qualified candidates, and become part
                        of a more diverse and inclusive professional community.
                    </p>
                </div>

                <a href="empresas.php" class="button button-primary">
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
                        <img src="img/LOGOS.png" alt="SkillBridge Logo">
                    </div>

                    <div class="logo-text">
                        <span>Skill</span>Bridge
                    </div>
                </a>

                <p>
                    We connect talent, companies, and opportunities to build
                    a more inclusive professional future.
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
                <a href="contacto.php">Contact the Team</a>
            </div>

            <div class="footer-column">
                <h4>Newsletter</h4>
                <p>Receive new job openings and professional tips.</p>

                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only">
                        Email Address
                    </label>

                    <div class="newsletter-input">
                        <input
                            type="email"
                            id="newsletterEmail"
                            placeholder="Your Email Address"
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

    <!-- MODAL DE DETALLE -->

    <div
        class="modal hidden"
        id="jobModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalJobTitle"
    >
        <div class="modal-overlay" id="modalOverlay"></div>

        <div class="modal-content">
            <button class="modal-close" id="modalClose" aria-label="Close Window">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="modal-company-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>

            <span class="modal-label">FEATURED JOB</span>

            <h2 id="modalJobTitle">Job Title</h2>

            <p class="modal-company" id="modalCompany">Company</p>

            <div class="modal-info">
                <span id="modalLocation">
                    <i class="fa-solid fa-location-dot"></i>
                    Location
                </span>

                <span id="modalMode">
                    <i class="fa-solid fa-house-laptop"></i>
                    Work Arrangement
                </span>
            </div>

            <p class="modal-description" id="modalDescription"></p>

            <div class="modal-actions">
                <button class="button button-primary" id="applyButton">
                    Apply Now
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

                <button class="button button-secondary" id="modalSaveButton">
                    Save Job
                    <i class="fa-regular fa-bookmark"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="java/java.js"></script>
    <script src="java/empleos.js"></script>
</body>
</html>