<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SkillBridge | Empleo sin barreras</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta name="description"
        content="SkillBridge conecta talento, empresas y oportunidades laborales inclusivas en El Salvador.">

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
        aria-label="Abrir herramientas de accesibilidad">
        <i class="fa-solid fa-universal-access"></i>
    </button>

    <!-- PANEL DE ACCESIBILIDAD -->
    <aside class="accessibility-panel" id="accessibilityPanel" aria-label="Herramientas de accesibilidad">
        <div class="accessibility-header">
            <div>
                <span class="panel-label">SKILLBRIDGE</span>
                <h3>Accesibilidad</h3>
            </div>

            <button id="closeAccessibility" aria-label="Cerrar herramientas de accesibilidad">
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
                    <img src="img/LOGOS.png" alt="logo">
                </div>

                <div class="logo-text">
                    <span>Skill</span>Bridge
                </div>
            </a>

            <button class="mobile-menu-button" id="mobileMenuButton"
                aria-label="Abrir menú de navegación">
                <i class="fa-solid fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php" class="active">Inicio</a></li>
                <li><a href="empleos.php">Buscar empleos</a></li>
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

        <!-- HERO -->
        <section class="hero-section">

            <div class="hero-decoration decoration-one"></div>
            <div class="hero-decoration decoration-two"></div>
            <div class="hero-decoration decoration-three"></div>

            <div class="container hero-grid">

                <div class="hero-content">

                    <div class="hero-badge">
                        <span class="badge-dot"></span>
                        Plataforma de empleo inclusivo
                    </div>

                    <h1>
                        Tu talento abre puertas.
                        <span>Nosotros construimos el puente.</span>
                    </h1>

                    <p class="hero-description">
                        SkillBridge conecta a personas con oportunidades laborales reales,
                        empresas comprometidas y recursos para crecer profesionalmente.
                    </p>

                    <div class="hero-buttons">
                        <a href="#buscar-empleo" class="button button-primary">
                            Buscar empleo
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <a href="empresas.html" class="button button-secondary">
                            Soy una empresa
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
                            Más de <strong>2,500 personas</strong> ya han encontrado oportunidades.
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
                                Vacante activa
                            </span>
                        </div>

                        <h3>Desarrollador Web Jr.</h3>
                        <p class="company-name">TechNova El Salvador</p>

                        <div class="job-tags">
                            <span><i class="fa-solid fa-location-dot"></i> San Salvador</span>
                            <span><i class="fa-solid fa-house-laptop"></i> Híbrido</span>
                        </div>

                        <div class="match-box">
                            <div class="match-row">
                                <span>Compatibilidad con tu perfil</span>
                                <strong>92%</strong>
                            </div>

                            <div class="progress-bar">
                                <div class="progress-value"></div>
                            </div>
                        </div>

                        <button class="mini-apply-button">Postularme ahora</button>
                    </div>

                    <div class="hero-card small-card small-card-top">
                        <div class="small-icon blue-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <div>
                            <strong>1,240+</strong>
                            <span>Vacantes disponibles</span>
                        </div>
                    </div>

                    <div class="hero-card small-card small-card-bottom">
                        <div class="small-icon green-icon">
                            <i class="fa-solid fa-handshake"></i>
                        </div>

                        <div>
                            <strong>120+</strong>
                            <span>Empresas aliadas</span>
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
                            <span class="section-label">ENCUENTRA TU PRÓXIMO RETO</span>
                            <h2>Busca una oportunidad hecha para ti</h2>
                        </div>

                        <div class="search-icon-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>

                    <form class="job-search-form" id="jobSearchForm">

                        <div class="form-group search-input-group">
                            <label for="jobSearch">¿Qué empleo buscas?</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-briefcase"></i>
                                <input type="text" id="jobSearch"
                                    placeholder="Ejemplo: Diseñador, Programador, Ventas">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobCategory">Categoría</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-layer-group"></i>

                                <select id="jobCategory">
                                    <option value="todos">Todas las categorías</option>
                                    <option value="tecnologia">Tecnología</option>
                                    <option value="diseno">Diseño</option>
                                    <option value="ventas">Ventas</option>
                                    <option value="administracion">Administración</option>
                                    <option value="atencion">Atención al cliente</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobMode">Modalidad</label>

                            <div class="input-with-icon">
                                <i class="fa-solid fa-location-dot"></i>

                                <select id="jobMode">
                                    <option value="todos">Todas las modalidades</option>
                                    <option value="remoto">Remoto</option>
                                    <option value="hibrido">Híbrido</option>
                                    <option value="presencial">Presencial</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="button button-primary search-button">
                            Buscar empleos
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
                        <span>Vacantes activas</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="120">0</strong>
                        <span>Empresas aliadas</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <i class="fa-solid fa-user-check"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="2500">0</strong>
                        <span>Personas conectadas</span>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-icon stat-icon-orange">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <div>
                        <strong class="counter" data-target="98">0</strong>
                        <span>Experiencia positiva</span>
                    </div>
                </article>

            </div>
        </section>

        <!-- EMPLEOS DESTACADOS -->
        <section class="featured-jobs-section" id="empleos-destacados">
            <div class="container">

                <div class="section-heading">
                    <div>
                        <span class="section-label">OPORTUNIDADES DESTACADAS</span>
                        <h2>Encuentra un trabajo que conecte con tus habilidades</h2>
                        <p>
                            Explora vacantes verificadas de empresas que valoran el talento,
                            la diversidad y el crecimiento profesional.
                        </p>
                    </div>

                    <a href="empleos.php" class="text-link">
                        Ver todos los empleos
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <p class="results-indicator" id="resultsIndicator">
                    Mostrando vacantes recomendadas para ti.
                </p>

                <div class="jobs-grid" id="jobsGrid">

                    <article class="job-card"
                        data-title="desarrollador web junior html css javascript tecnologia remoto"
                        data-category="tecnologia"
                        data-mode="remoto">

                        <div class="job-card-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-code"></i>
                            </div>

                            <button class="save-job-button" aria-label="Guardar empleo"
                                data-job="Desarrollador Web Jr.">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type remote">Remoto</span>

                        <h3>Desarrollador Web Jr.</h3>
                        <p class="job-company">Innovatech SV</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> San Salvador</span>
                            <span><i class="fa-solid fa-clock"></i> Tiempo completo</span>
                        </div>

                        <div class="job-skills">
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">Publicado hoy</span>

                            <button class="details-button"
                                data-title="Desarrollador Web Jr."
                                data-company="Innovatech SV"
                                data-location="San Salvador"
                                data-mode="Remoto"
                                data-description="Buscamos una persona con conocimientos de HTML, CSS y JavaScript para apoyar en el desarrollo de sitios web modernos, responsivos e interactivos.">
                                Ver detalle
                            </button>
                        </div>

                    </article>

                    <article class="job-card"
                        data-title="diseñador grafico creativo diseno hibrido"
                        data-category="diseno"
                        data-mode="hibrido">

                        <div class="job-card-top">
                            <div class="company-logo company-pink">
                                <i class="fa-solid fa-palette"></i>
                            </div>

                            <button class="save-job-button" aria-label="Guardar empleo"
                                data-job="Diseñador Gráfico">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type hybrid">Híbrido</span>

                        <h3>Diseñador Gráfico</h3>
                        <p class="job-company">Creativa Studio</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> Santa Tecla</span>
                            <span><i class="fa-solid fa-clock"></i> Tiempo completo</span>
                        </div>

                        <div class="job-skills">
                            <span>Canva</span>
                            <span>Branding</span>
                            <span>Redes</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">Hace 2 días</span>

                            <button class="details-button"
                                data-title="Diseñador Gráfico"
                                data-company="Creativa Studio"
                                data-location="Santa Tecla"
                                data-mode="Híbrido"
                                data-description="Empresa creativa busca diseñador gráfico con ideas frescas, dominio de herramientas visuales y pasión por crear contenido atractivo para marcas.">
                                Ver detalle
                            </button>
                        </div>

                    </article>

                    <article class="job-card"
                        data-title="asesor de ventas ventas presencial"
                        data-category="ventas"
                        data-mode="presencial">

                        <div class="job-card-top">
                            <div class="company-logo company-orange">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>

                            <button class="save-job-button" aria-label="Guardar empleo"
                                data-job="Asesor de Ventas">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <span class="job-type in-person">Presencial</span>

                        <h3>Asesor de Ventas</h3>
                        <p class="job-company">Comercial Centro</p>

                        <div class="job-details">
                            <span><i class="fa-solid fa-location-dot"></i> Soyapango</span>
                            <span><i class="fa-solid fa-clock"></i> Tiempo completo</span>
                        </div>

                        <div class="job-skills">
                            <span>Ventas</span>
                            <span>Comunicación</span>
                            <span>Servicio</span>
                        </div>

                        <div class="job-card-footer">
                            <span class="job-date">Hace 3 días</span>

                            <button class="details-button"
                                data-title="Asesor de Ventas"
                                data-company="Comercial Centro"
                                data-location="Soyapango"
                                data-mode="Presencial"
                                data-description="Buscamos personas con buena comunicación, orientación al cliente y entusiasmo por cumplir metas comerciales en un ambiente de trabajo respetuoso e inclusivo.">
                                Ver detalle
                            </button>
                        </div>

                    </article>

                </div>

                <div class="no-results hidden" id="noResults">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>No encontramos vacantes con esos filtros.</h3>
                    <p>Prueba con otra palabra, categoría o modalidad.</p>
                </div>

            </div>
        </section>

        <!-- CATEGORÍAS -->
        <section class="categories-section">
            <div class="container">

                <div class="section-heading centered-heading">
                    <div>
                        <span class="section-label">EXPLORA POR ÁREA</span>
                        <h2>Una oportunidad para cada talento</h2>
                        <p>
                            Descubre sectores donde puedes aplicar tus conocimientos,
                            habilidades y experiencia.
                        </p>
                    </div>
                </div>

                <div class="categories-grid">

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-blue">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>

                        <h3>Tecnología</h3>
                        <p>Desarrollo, soporte, diseño UX/UI y más.</p>
                        <span>245 vacantes <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-purple">
                            <i class="fa-solid fa-pen-ruler"></i>
                        </div>

                        <h3>Diseño y Creatividad</h3>
                        <p>Diseño gráfico, contenido y comunicación visual.</p>
                        <span>138 vacantes <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-green">
                            <i class="fa-solid fa-headset"></i>
                        </div>

                        <h3>Atención al Cliente</h3>
                        <p>Soporte, call center y experiencia del cliente.</p>
                        <span>190 vacantes <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    <a href="empleos.php" class="category-card">
                        <div class="category-icon category-orange">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <h3>Administración</h3>
                        <p>Recursos humanos, finanzas y operaciones.</p>
                        <span>167 vacantes <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                </div>

            </div>
        </section>

        <!-- PROCESO -->
        <section class="process-section">
            <div class="container process-grid">

                <div class="process-content">
                    <span class="section-label">SIMPLE, CLARO E INCLUSIVO</span>

                    <h2>Buscar empleo no debería ser complicado</h2>

                    <p>
                        Diseñamos una experiencia sencilla para que puedas enfocarte
                        en lo importante: mostrar tu talento y conectar con empresas.
                    </p>

                    <a href="registro.html" class="button button-primary">
                        Crear mi perfil gratis
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="process-steps">

                    <article class="process-step">
                        <div class="step-number">01</div>

                        <div>
                            <h3>Crea tu perfil</h3>
                            <p>Agrega tus habilidades, experiencia, estudios e intereses profesionales.</p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">02</div>

                        <div>
                            <h3>Descubre vacantes</h3>
                            <p>Usa filtros inteligentes para encontrar empleos según tu perfil.</p>
                        </div>
                    </article>

                    <article class="process-step">
                        <div class="step-number">03</div>

                        <div>
                            <h3>Postúlate con confianza</h3>
                            <p>Envía tu solicitud y da seguimiento desde tu panel personal.</p>
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

                        <h3>Diseñado para todos</h3>
                        <p>Herramientas que ayudan a cada persona a navegar con comodidad.</p>
                    </div>

                    <div class="inclusion-mini-card mini-card-left">
                        <i class="fa-solid fa-volume-high"></i>
                        <span>Lector de texto</span>
                    </div>

                    <div class="inclusion-mini-card mini-card-right">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                        <span>Alto contraste</span>
                    </div>
                </div>

                <div class="inclusion-content">
                    <span class="section-label">EMPLEO SIN BARRERAS</span>

                    <h2>La inclusión no es una opción. Es parte de nuestro diseño.</h2>

                    <p>
                        SkillBridge incorpora herramientas de accesibilidad para crear una
                        experiencia clara, adaptable y cómoda para todas las personas.
                    </p>

                    <ul class="inclusion-list">
                        <li><i class="fa-solid fa-check"></i> Tamaño de texto ajustable.</li>
                        <li><i class="fa-solid fa-check"></i> Modo oscuro y alto contraste.</li>
                        <li><i class="fa-solid fa-check"></i> Lectura de contenido por voz.</li>
                        <li><i class="fa-solid fa-check"></i> Navegación sencilla y botones claros.</li>
                    </ul>

                    <a href="accesibilidad.html" class="text-link">
                        Conocer nuestras herramientas
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
                        <span class="section-label">HISTORIAS QUE INSPIRAN</span>
                        <h2>El talento cambia vidas cuando encuentra una oportunidad</h2>
                    </div>
                </div>

                <div class="testimonials-grid">

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “SkillBridge me ayudó a organizar mi perfil profesional y encontrar
                            una vacante donde valoraron mis conocimientos de diseño.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-one">M</div>

                            <div>
                                <h4>María Hernández</h4>
                                <span>Diseñadora gráfica</span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “La plataforma es clara y fácil de usar. Pude buscar empleos remotos
                            y postularme sin complicaciones desde mi teléfono.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-two">J</div>

                            <div>
                                <h4>José Martínez</h4>
                                <span>Soporte técnico</span>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>

                        <p>
                            “Como empresa, encontramos candidatos preparados y una plataforma
                            que realmente promueve una contratación más inclusiva.”
                        </p>

                        <div class="testimonial-user">
                            <div class="user-photo photo-three">A</div>

                            <div>
                                <h4>Ana López</h4>
                                <span>Recursos Humanos</span>
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
                    <span class="section-label cta-label">TU PRÓXIMA OPORTUNIDAD EMPIEZA HOY</span>
                    <h2>Haz que tus habilidades hablen por ti.</h2>
                    <p>
                        Crea tu perfil gratuito, explora oportunidades y da el siguiente paso
                        en tu camino profesional.
                    </p>
                </div>

                <div class="cta-buttons">
                    <a href="registro.html" class="button button-light">
                        Crear cuenta
                        <i class="fa-solid fa-user-plus"></i>
                    </a>

                    <a href="empleos.php" class="button button-outline-light">
                        Explorar empleos
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
                    Conectamos talento, empresas y oportunidades para construir
                    un futuro laboral más inclusivo.
                </p>

                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
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
                    <label for="newsletterEmail" class="sr-only">Correo electrónico</label>

                    <div class="newsletter-input">
                        <input type="email" id="newsletterEmail"
                            placeholder="Tu correo electrónico" required>

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

    <!-- MODAL DE DETALLE DE EMPLEO -->
    <div class="modal hidden" id="jobModal" role="dialog"
        aria-modal="true" aria-labelledby="modalJobTitle">

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

    <script src="java/java.js"></script>
</body>
</html>