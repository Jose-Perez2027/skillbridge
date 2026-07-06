<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buscar empleos | SkillBridge</title>

    <meta
        name="description"
        content="Explora empleos inclusivos, filtra oportunidades y encuentra una vacante ideal para tus habilidades en SkillBridge."
    >

    <link rel="icon" type="image/png" href="img/favicon.png">

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
        aria-label="Abrir herramientas de accesibilidad"
    >
        <i class="fa-solid fa-universal-access"></i>
    </button>

    <aside
        class="accessibility-panel"
        id="accessibilityPanel"
        aria-label="Herramientas de accesibilidad"
    >
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3>Accesibilidad</h3>
            </div>

            <button
                id="closeAccessibility"
                aria-label="Cerrar herramientas de accesibilidad"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="accessibility-text">
            Ajusta la experiencia del sitio según tus necesidades.
        </p>

        <div class="accessibility-options">

            <button class="accessibility-option" id="increaseFont">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
                <span>Aumentar texto</span>
            </button>

            <button class="accessibility-option" id="decreaseFont">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
                <span>Disminuir texto</span>
            </button>

            <button class="accessibility-option" id="darkMode">
                <i class="fa-solid fa-moon"></i>
                <span>Modo oscuro</span>
            </button>

            <button class="accessibility-option" id="highContrast">
                <i class="fa-solid fa-circle-half-stroke"></i>
                <span>Alto contraste</span>
            </button>

            <button class="accessibility-option" id="readPage">
                <i class="fa-solid fa-volume-high"></i>
                <span>Leer contenido</span>
            </button>

            <button class="accessibility-option" id="stopReading">
                <i class="fa-solid fa-volume-xmark"></i>
                <span>Detener lectura</span>
            </button>

        </div>
    </aside>

    <!-- HEADER -->

    <header class="header">
        <nav class="navbar container">

            <a href="index.php" class="logo" aria-label="Ir al inicio de SkillBridge">
                <div class="logo-icon">
                    <img src="img/logo.png" alt="Logo de SkillBridge">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Abrir menú de navegación"
            >
                <i class="fa-solid fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="empleos.php" class="active">Buscar empleos</a></li>
                <li><a href="empresas.html">Empresas</a></li>
                <li><a href="recursos.html">Recursos</a></li>
                <li><a href="accesibilidad.html">Accesibilidad</a></li>
            </ul>

            <div class="nav-actions">
                <a href="login.html" class="login-link">Iniciar sesión</a>

                <a href="registro.html" class="button button-primary button-small">
                    Crear cuenta
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
                    <a href="index.php">Inicio</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span>Buscar empleos</span>
                </div>

                <div class="jobs-hero-text">
                    <div>
                        <span class="section-label">OPORTUNIDADES PARA TODOS</span>

                        <h1>Encuentra un empleo que conecte con tu talento.</h1>

                        <p>
                            Explora vacantes verificadas de empresas que buscan personas
                            con habilidades, compromiso y ganas de crecer.
                        </p>
                    </div>

                    <div class="hero-jobs-stat">
                        <div class="hero-jobs-stat-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <div>
                            <strong>1,240+</strong>
                            <span>Vacantes activas</span>
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
                            ¿Qué empleo estás buscando?
                        </label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                id="employmentSearch"
                                placeholder="Ejemplo: Diseñador, Programador, Ventas..."
                            >
                        </div>
                    </div>

                    <div class="employment-search-field">
                        <label for="employmentLocation">Ubicación</label>

                        <div class="employment-input-icon">
                            <i class="fa-solid fa-location-dot"></i>

                            <select id="employmentLocation">
                                <option value="todos">Todas las ubicaciones</option>
                                <option value="san-salvador">San Salvador</option>
                                <option value="soyapango">Soyapango</option>
                                <option value="santa-tecla">Santa Tecla</option>
                                <option value="antiguo-cuscatlan">Antiguo Cuscatlán</option>
                                <option value="remoto">Remoto</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="button button-primary employment-search-button">
                        Buscar empleos
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

                <div class="quick-filter-row">
                    <span>Explora rápidamente:</span>

                    <button class="quick-filter active" data-quick-filter="todos">
                        Todos
                    </button>

                    <button class="quick-filter" data-quick-filter="tecnologia">
                        <i class="fa-solid fa-laptop-code"></i>
                        Tecnología
                    </button>

                    <button class="quick-filter" data-quick-filter="diseno">
                        <i class="fa-solid fa-pen-ruler"></i>
                        Diseño
                    </button>

                    <button class="quick-filter" data-quick-filter="ventas">
                        <i class="fa-solid fa-chart-line"></i>
                        Ventas
                    </button>

                    <button class="quick-filter" data-quick-filter="administracion">
                        <i class="fa-solid fa-briefcase"></i>
                        Administración
                    </button>

                    <button class="quick-filter" data-quick-filter="atencion">
                        <i class="fa-solid fa-headset"></i>
                        Atención al cliente
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
                            <h2>Filtros</h2>
                            <p>Personaliza tu búsqueda.</p>
                        </div>

                        <button id="clearFiltersButton" class="clear-filters-button">
                            Limpiar
                        </button>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Categoría</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="tecnologia" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Tecnología
                                <small>245</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="diseno" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Diseño y creatividad
                                <small>138</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="ventas" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Ventas
                                <small>189</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="administracion" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Administración
                                <small>167</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="atencion" class="category-filter">
                                <span class="custom-checkbox"></span>
                                Atención al cliente
                                <small>201</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Modalidad</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="remoto" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                Remoto
                                <small>310</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="hibrido" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                Híbrido
                                <small>214</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="presencial" class="mode-filter">
                                <span class="custom-checkbox"></span>
                                Presencial
                                <small>716</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-title" type="button">
                            <span>Experiencia</span>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>

                        <div class="filter-options">

                            <label class="filter-option">
                                <input type="checkbox" value="sin-experiencia" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Sin experiencia
                                <small>108</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="junior" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Nivel junior
                                <small>356</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="intermedio" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Nivel intermedio
                                <small>463</small>
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" value="senior" class="experience-filter">
                                <span class="custom-checkbox"></span>
                                Nivel senior
                                <small>313</small>
                            </label>

                        </div>
                    </div>

                    <div class="filter-group salary-filter-group">
                        <button class="filter-title" type="button">
                            <span>Salario mensual</span>
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
                                <span>Desde $300</span>
                                <strong id="salaryValue">$2,000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="inclusive-note">
                        <div class="inclusive-note-icon">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <div>
                            <strong>Empleo inclusivo</strong>
                            <p>
                                Priorizamos empresas que promueven oportunidades sin barreras.
                            </p>
                        </div>
                    </div>

                </aside>

                <!-- LISTADO -->

                <section class="jobs-results-area">

                    <div class="jobs-results-top">

                        <div>
                            <span class="results-small-text">OPORTUNIDADES DISPONIBLES</span>

                            <h2 id="jobsResultsTitle">
                                12 empleos encontrados
                            </h2>

                            <p id="jobsResultsText">
                                Explora vacantes según tus intereses profesionales.
                            </p>
                        </div>

                        <div class="jobs-result-actions">

                            <label for="sortJobs" class="sr-only">
                                Ordenar empleos
                            </label>

                            <select id="sortJobs">
                                <option value="recent">Más recientes</option>
                                <option value="salary-high">Mayor salario</option>
                                <option value="salary-low">Menor salario</option>
                                <option value="alphabetical">Orden alfabético</option>
                            </select>

                            <div class="view-buttons">
                                <button
                                    class="view-button active"
                                    id="gridViewButton"
                                    aria-label="Ver empleos en cuadrícula"
                                >
                                    <i class="fa-solid fa-table-cells-large"></i>
                                </button>

                                <button
                                    class="view-button"
                                    id="listViewButton"
                                    aria-label="Ver empleos en lista"
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
                                    aria-label="Guardar empleo"
                                    data-job="Desarrollador Web Jr."
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remoto</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Desarrollador Web Jr.</h3>
                            <p class="job-company">Innovatech SV</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remoto
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>HTML</span>
                                <span>CSS</span>
                                <span>JavaScript</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$950 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hoy
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Desarrollador Web Jr."
                                    data-company="Innovatech SV"
                                    data-location="Remoto"
                                    data-mode="Remoto"
                                    data-description="Buscamos una persona con conocimientos de HTML, CSS y JavaScript. Participarás en la creación de sitios web modernos, adaptables y funcionales para diferentes empresas."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Diseñador Gráfico"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Híbrido</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Diseñador Gráfico</h3>
                            <p class="job-company">Creativa Studio</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Santa Tecla
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Canva</span>
                                <span>Branding</span>
                                <span>Redes</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$750 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 1 día
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Diseñador Gráfico"
                                    data-company="Creativa Studio"
                                    data-location="Santa Tecla"
                                    data-mode="Híbrido"
                                    data-description="Buscamos diseñador o diseñadora gráfica con creatividad, manejo de Canva y capacidad para crear contenido visual atractivo para marcas y redes sociales."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Asesor de Ventas"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">Presencial</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Asesor de Ventas</h3>
                            <p class="job-company">Comercial Centro</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Soyapango
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Ventas</span>
                                <span>Comunicación</span>
                                <span>Servicio</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$500 + comisión</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 2 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Asesor de Ventas"
                                    data-company="Comercial Centro"
                                    data-location="Soyapango"
                                    data-mode="Presencial"
                                    data-description="Buscamos personas con facilidad para hablar, orientar clientes y cumplir metas de ventas. No necesitas experiencia previa: recibirás capacitación inicial."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Auxiliar Administrativo"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">Presencial</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Auxiliar Administrativo</h3>
                            <p class="job-company">Grupo Soluciones</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Excel</span>
                                <span>Archivos</span>
                                <span>Organización</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$650 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 2 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Auxiliar Administrativo"
                                    data-company="Grupo Soluciones"
                                    data-location="San Salvador"
                                    data-mode="Presencial"
                                    data-description="Apoyarás en el orden de documentos, elaboración de reportes, atención de llamadas y tareas administrativas básicas. Se requiere responsabilidad y buena organización."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Agente de Atención al Cliente"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Híbrido</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Agente de Atención al Cliente</h3>
                            <p class="job-company">Conecta Plus</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Turnos rotativos
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Servicio</span>
                                <span>Escucha</span>
                                <span>Inglés básico</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$600 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 3 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Agente de Atención al Cliente"
                                    data-company="Conecta Plus"
                                    data-location="San Salvador"
                                    data-mode="Híbrido"
                                    data-description="Atenderás consultas de clientes por llamadas, chat y correo electrónico. Buscamos personas pacientes, responsables y con interés por brindar una excelente experiencia."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Soporte Técnico IT"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">Presencial</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Soporte Técnico IT</h3>
                            <p class="job-company">Nexa Solutions</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Antiguo Cuscatlán
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Windows</span>
                                <span>Redes</span>
                                <span>Soporte</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$850 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 4 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Soporte Técnico IT"
                                    data-company="Nexa Solutions"
                                    data-location="Antiguo Cuscatlán"
                                    data-mode="Presencial"
                                    data-description="Brindarás apoyo a usuarios con equipos, programas, redes y sistemas operativos. Se valoran conocimientos de Windows, hardware, redes y atención al cliente."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Community Manager"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remoto</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Community Manager</h3>
                            <p class="job-company">Pulse Media</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remoto
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Medio tiempo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Instagram</span>
                                <span>Contenido</span>
                                <span>Copywriting</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$700 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 5 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Community Manager"
                                    data-company="Pulse Media"
                                    data-location="Remoto"
                                    data-mode="Remoto"
                                    data-description="Crearás contenido, publicaciones y estrategias para redes sociales. Buscamos una persona creativa, organizada y con capacidad para comunicar ideas de forma atractiva."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Recepcionista"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">Presencial</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Recepcionista</h3>
                            <p class="job-company">Clínica Bienestar</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Santa Tecla
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Atención</span>
                                <span>Agenda</span>
                                <span>Orden</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$480 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 6 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Recepcionista"
                                    data-company="Clínica Bienestar"
                                    data-location="Santa Tecla"
                                    data-mode="Presencial"
                                    data-description="Serás responsable de recibir pacientes, atender llamadas, organizar citas y apoyar al equipo administrativo. Se requiere amabilidad y buena presentación."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Analista de Datos"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type hybrid">Híbrido</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Analista de Datos</h3>
                            <p class="job-company">DataVision</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    San Salvador
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Excel</span>
                                <span>Power BI</span>
                                <span>SQL</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$1,200 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 1 semana
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Analista de Datos"
                                    data-company="DataVision"
                                    data-location="San Salvador"
                                    data-mode="Híbrido"
                                    data-description="Analizarás información, prepararás reportes y crearás paneles para apoyar decisiones empresariales. Se requiere experiencia con Excel y conocimientos básicos de Power BI o SQL."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Auxiliar de Recursos Humanos"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type in-person">Presencial</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Auxiliar de Recursos Humanos</h3>
                            <p class="job-company">Talento Integral</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Soyapango
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Entrevistas</span>
                                <span>Archivos</span>
                                <span>Excel</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$700 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 1 semana
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Auxiliar de Recursos Humanos"
                                    data-company="Talento Integral"
                                    data-location="Soyapango"
                                    data-mode="Presencial"
                                    data-description="Apoyarás en procesos de reclutamiento, organización de expedientes, entrevistas iniciales y actividades de bienestar para colaboradores."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Ejecutivo de Ventas Digitales"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remoto</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Ejecutivo de Ventas Digitales</h3>
                            <p class="job-company">Mercado Online SV</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remoto
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>Ventas</span>
                                <span>WhatsApp</span>
                                <span>CRM</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$850 + comisión</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 8 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Ejecutivo de Ventas Digitales"
                                    data-company="Mercado Online SV"
                                    data-location="Remoto"
                                    data-mode="Remoto"
                                    data-description="Atenderás clientes digitales, darás seguimiento a prospectos y apoyarás las ventas por redes sociales, WhatsApp y plataformas de comercio electrónico."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
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
                                    aria-label="Guardar empleo"
                                    data-job="Desarrollador Frontend"
                                >
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>

                            <div class="employment-job-labels">
                                <span class="job-type remote">Remoto</span>
                                <span class="inclusive-job-label">
                                    <i class="fa-solid fa-universal-access"></i>
                                    Inclusivo
                                </span>
                            </div>

                            <h3>Desarrollador Frontend</h3>
                            <p class="job-company">Cloud Bridge Labs</p>

                            <div class="employment-job-info">
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    Remoto
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock"></i>
                                    Tiempo completo
                                </span>
                            </div>

                            <div class="employment-job-skills">
                                <span>React</span>
                                <span>JavaScript</span>
                                <span>Git</span>
                            </div>

                            <div class="employment-salary-row">
                                <div>
                                    <span>Salario estimado</span>
                                    <strong>$1,800 / mes</strong>
                                </div>

                                <span class="job-posted-date">
                                    <i class="fa-regular fa-clock"></i>
                                    Hace 10 días
                                </span>
                            </div>

                            <div class="employment-card-actions">
                                <button
                                    class="details-button"
                                    data-title="Desarrollador Frontend"
                                    data-company="Cloud Bridge Labs"
                                    data-location="Remoto"
                                    data-mode="Remoto"
                                    data-description="Buscamos desarrollador frontend con experiencia en React, JavaScript, CSS moderno y Git. Participarás en proyectos web para clientes internacionales."
                                >
                                    Ver detalles
                                </button>

                                <button class="mini-apply-button">
                                    Postularme
                                </button>
                            </div>
                        </article>

                    </div>

                    <!-- SIN RESULTADOS -->

                    <div class="employment-no-results hidden" id="employmentNoResults">
                        <div class="employment-no-results-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <h3>No encontramos empleos con esos filtros.</h3>

                        <p>
                            Prueba cambiar la búsqueda, quitar filtros o ampliar el rango salarial.
                        </p>

                        <button id="emptyClearFiltersButton" class="button button-primary">
                            Limpiar filtros
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                    </div>

                    <!-- PAGINACIÓN -->

                    <nav class="jobs-pagination" id="jobsPagination" aria-label="Paginación de empleos">
                        <button id="previousPageButton" aria-label="Página anterior">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <div id="paginationNumbers"></div>

                        <button id="nextPageButton" aria-label="Página siguiente">
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
                    <span class="section-label">¿REPRESENTAS UNA EMPRESA?</span>

                    <h2>Encuentra talento que haga crecer tu equipo.</h2>

                    <p>
                        Publica vacantes, conecta con candidatos preparados y forma parte
                        de una comunidad laboral más diversa e inclusiva.
                    </p>
                </div>

                <a href="empresas.html" class="button button-primary">
                    Publicar una vacante
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
                        <img src="img/logo.png" alt="Logo de SkillBridge">
                    </div>

                    <div class="logo-text">
                        <span>Skill</span>Bridge
                    </div>
                </a>

                <p>
                    Conectamos talento, empresas y oportunidades para construir
                    un futuro laboral más inclusivo.
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
                <h4>Para candidatos</h4>
                <a href="empleos.php">Buscar empleos</a>
                <a href="registro.html">Crear perfil</a>
                <a href="postulaciones.html">Mis postulaciones</a>
                <a href="recursos.html">Recursos y consejos</a>
            </div>

            <div class="footer-column">
                <h4>Para empresas</h4>
                <a href="empresas.html">Publicar vacante</a>
                <a href="empresas.html">Buscar talento</a>
                <a href="empresas.html">Planes empresariales</a>
                <a href="contacto.html">Contactar al equipo</a>
            </div>

            <div class="footer-column">
                <h4>Newsletter</h4>
                <p>Recibe nuevas vacantes y consejos profesionales.</p>

                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only">
                        Correo electrónico
                    </label>

                    <div class="newsletter-input">
                        <input
                            type="email"
                            id="newsletterEmail"
                            placeholder="Tu correo electrónico"
                            required
                        >

                        <button type="submit" aria-label="Suscribirme">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <small id="newsletterMessage"></small>
            </div>

        </div>

        <div class="container footer-bottom">
            <p>© 2026 SkillBridge. Todos los derechos reservados.</p>

            <div>
                <a href="#">Privacidad</a>
                <a href="#">Términos y condiciones</a>
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
            <button class="modal-close" id="modalClose" aria-label="Cerrar ventana">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="modal-company-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>

            <span class="modal-label">VACANTE DESTACADA</span>

            <h2 id="modalJobTitle">Título del empleo</h2>

            <p class="modal-company" id="modalCompany">Empresa</p>

            <div class="modal-info">
                <span id="modalLocation">
                    <i class="fa-solid fa-location-dot"></i>
                    Ubicación
                </span>

                <span id="modalMode">
                    <i class="fa-solid fa-house-laptop"></i>
                    Modalidad
                </span>
            </div>

            <p class="modal-description" id="modalDescription"></p>

            <div class="modal-actions">
                <button class="button button-primary" id="applyButton">
                    Postularme ahora
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

                <button class="button button-secondary" id="modalSaveButton">
                    Guardar empleo
                    <i class="fa-regular fa-bookmark"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/empleos.js"></script>
</body>
</html>