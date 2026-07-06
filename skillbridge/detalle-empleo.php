<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Desarrollador Web Jr. | SkillBridge</title>

    <meta
        name="description"
        content="Conoce los detalles de la vacante Desarrollador Web Jr. en SkillBridge."
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
    <link rel="stylesheet" href="css/detalle-empleo.css">
</head>

<body>

    <a href="#mainContent" class="skip-link">
        Saltar al contenido principal
    </a>

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

    <!-- ACCESIBILIDAD -->

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

    <main id="mainContent">

        <!-- ENCABEZADO -->

        <section class="detail-hero-section">

            <div class="detail-hero-circle detail-hero-circle-one"></div>
            <div class="detail-hero-circle detail-hero-circle-two"></div>

            <div class="container">

                <div class="breadcrumb">
                    <a href="index.php">Inicio</a>
                    <i class="fa-solid fa-chevron-right"></i>

                    <a href="empleos.php">Buscar empleos</a>
                    <i class="fa-solid fa-chevron-right"></i>

                    <span>Desarrollador Web Jr.</span>
                </div>

                <div class="detail-hero-card">

                    <div class="detail-company-logo">
                        <i class="fa-solid fa-code"></i>
                    </div>

                    <div class="detail-hero-main">

                        <div class="detail-hero-labels">
                            <span class="job-type remote">Remoto</span>

                            <span class="detail-inclusive-label">
                                <i class="fa-solid fa-universal-access"></i>
                                Empresa inclusiva
                            </span>

                            <span class="verified-label">
                                <i class="fa-solid fa-circle-check"></i>
                                Vacante verificada
                            </span>
                        </div>

                        <h1>Desarrollador Web Jr.</h1>

                        <p class="detail-company-name">
                            Innovatech SV
                        </p>

                        <div class="detail-hero-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Remoto desde El Salvador
                            </span>

                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Tiempo completo
                            </span>

                            <span>
                                <i class="fa-regular fa-calendar"></i>
                                Publicado hoy
                            </span>
                        </div>

                    </div>

                    <div class="detail-hero-actions">
                        <button
                            class="save-job-button detail-save-button"
                            id="detailSaveButton"
                            data-job="Desarrollador Web Jr."
                            aria-label="Guardar empleo"
                        >
                            <i class="fa-regular fa-bookmark"></i>
                            <span>Guardar</span>
                        </button>

                        <button
                            class="detail-share-button"
                            id="shareJobButton"
                            aria-label="Compartir empleo"
                        >
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>Compartir</span>
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
                                <span class="detail-small-label">SOBRE LA VACANTE</span>
                                <h2>Descripción del puesto</h2>
                            </div>
                        </div>

                        <p>
                            En Innovatech SV buscamos un Desarrollador Web Jr. con interés
                            por crear experiencias digitales modernas, accesibles y funcionales.
                            Formarás parte de un equipo tecnológico que desarrolla soluciones
                            web para empresas nacionales e internacionales.
                        </p>

                        <p>
                            Esta oportunidad está pensada para personas con conocimientos de
                            HTML, CSS y JavaScript que quieran fortalecer sus habilidades,
                            aprender nuevas herramientas y participar en proyectos reales.
                        </p>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-purple">
                                <i class="fa-solid fa-list-check"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">RESPONSABILIDADES</span>
                                <h2>Lo que harás en este puesto</h2>
                            </div>
                        </div>

                        <ul class="detail-list">
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Desarrollar y actualizar páginas web responsivas.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Convertir diseños visuales en interfaces funcionales.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Aplicar buenas prácticas de accesibilidad web.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Corregir errores visuales y mejorar la experiencia de usuario.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Trabajar en equipo usando herramientas de control de versiones.
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Participar en reuniones de seguimiento y planificación.
                            </li>
                        </ul>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-green">
                                <i class="fa-solid fa-user-check"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">PERFIL IDEAL</span>
                                <h2>Requisitos para aplicar</h2>
                            </div>
                        </div>

                        <div class="requirements-grid">

                            <div class="requirement-item">
                                <div class="requirement-number">01</div>

                                <div>
                                    <h3>Conocimientos técnicos</h3>
                                    <p>
                                        HTML5, CSS3, JavaScript básico o intermedio
                                        y diseño responsive.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">02</div>

                                <div>
                                    <h3>Educación</h3>
                                    <p>
                                        Bachillerato técnico, estudios universitarios
                                        o cursos relacionados con tecnología.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">03</div>

                                <div>
                                    <h3>Experiencia</h3>
                                    <p>
                                        No es obligatorio tener experiencia laboral.
                                        Se valoran proyectos personales o académicos.
                                    </p>
                                </div>
                            </div>

                            <div class="requirement-item">
                                <div class="requirement-number">04</div>

                                <div>
                                    <h3>Habilidades personales</h3>
                                    <p>
                                        Responsabilidad, comunicación, creatividad
                                        y disposición para aprender.
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
                                <span class="detail-small-label">VALOR AGREGADO</span>
                                <h2>Será un plus si tienes</h2>
                            </div>
                        </div>

                        <div class="skills-cloud">
                            <span>Git y GitHub</span>
                            <span>Figma</span>
                            <span>React</span>
                            <span>Bootstrap</span>
                            <span>Tailwind CSS</span>
                            <span>Inglés básico</span>
                            <span>Canva</span>
                            <span>UX/UI</span>
                            <span>Trabajo remoto</span>
                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-pink">
                                <i class="fa-solid fa-gift"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">BENEFICIOS</span>
                                <h2>Lo que ofrece Innovatech SV</h2>
                            </div>
                        </div>

                        <div class="benefits-grid">

                            <div class="benefit-item">
                                <i class="fa-solid fa-house-laptop"></i>
                                <h3>Trabajo remoto</h3>
                                <p>Trabaja desde casa con horarios organizados.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <h3>Capacitaciones</h3>
                                <p>Acceso a cursos y talleres tecnológicos.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-chart-line"></i>
                                <h3>Crecimiento profesional</h3>
                                <p>Oportunidad de avanzar a nuevos roles.</p>
                            </div>

                            <div class="benefit-item">
                                <i class="fa-solid fa-heart"></i>
                                <h3>Ambiente inclusivo</h3>
                                <p>Respeto, diversidad y colaboración real.</p>
                            </div>

                        </div>
                    </article>

                    <article class="detail-content-card">
                        <div class="detail-section-heading">
                            <div class="detail-section-icon detail-icon-blue">
                                <i class="fa-solid fa-route"></i>
                            </div>

                            <div>
                                <span class="detail-small-label">PROCESO DE SELECCIÓN</span>
                                <h2>¿Qué pasará después de postularte?</h2>
                            </div>
                        </div>

                        <div class="selection-process">

                            <div class="selection-step">
                                <div class="selection-step-number">1</div>

                                <div>
                                    <h3>Revisión de perfil</h3>
                                    <p>
                                        El equipo revisará tus habilidades, CV y proyectos.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">2</div>

                                <div>
                                    <h3>Entrevista inicial</h3>
                                    <p>
                                        Conversación virtual para conocerte mejor.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">3</div>

                                <div>
                                    <h3>Prueba práctica</h3>
                                    <p>
                                        Realizarás un reto breve relacionado con desarrollo web.
                                    </p>
                                </div>
                            </div>

                            <div class="selection-line"></div>

                            <div class="selection-step">
                                <div class="selection-step-number">4</div>

                                <div>
                                    <h3>Resultado final</h3>
                                    <p>
                                        Recibirás una respuesta por correo electrónico.
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
                                <span class="detail-small-label">SOBRE LA EMPRESA</span>
                                <h2>Innovatech SV</h2>
                                <p>Soluciones tecnológicas para empresas modernas.</p>
                            </div>

                        </div>

                        <p class="company-profile-description">
                            Innovatech SV es una empresa salvadoreña dedicada al desarrollo
                            de soluciones digitales, plataformas web y herramientas de gestión
                            para pequeñas, medianas y grandes empresas. Su equipo trabaja de
                            forma colaborativa y promueve oportunidades para personas de
                            distintos perfiles y experiencias.
                        </p>

                        <div class="company-profile-stats">

                            <div>
                                <strong>2018</strong>
                                <span>Año de fundación</span>
                            </div>

                            <div>
                                <strong>50+</strong>
                                <span>Colaboradores</span>
                            </div>

                            <div>
                                <strong>24</strong>
                                <span>Vacantes publicadas</span>
                            </div>

                        </div>

                        <a href="empresas.html" class="text-link">
                            Conocer empresas aliadas
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </article>

                </section>

                <!-- COLUMNA DERECHA -->

                <aside class="detail-sidebar">

                    <div class="apply-card">

                        <div class="apply-card-top">
                            <span class="apply-card-label">SALARIO ESTIMADO</span>

                            <h2>$950 <small>/ mes</small></h2>

                            <p>
                                El salario puede variar según experiencia, habilidades
                                y resultados de la entrevista.
                            </p>
                        </div>

                        <div class="apply-card-info">

                            <div>
                                <i class="fa-solid fa-briefcase"></i>

                                <span>
                                    <strong>Tipo de empleo</strong>
                                    Tiempo completo
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-house-laptop"></i>

                                <span>
                                    <strong>Modalidad</strong>
                                    100% remoto
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-layer-group"></i>

                                <span>
                                    <strong>Nivel</strong>
                                    Junior
                                </span>
                            </div>

                            <div>
                                <i class="fa-solid fa-calendar-days"></i>

                                <span>
                                    <strong>Fecha límite</strong>
                                    30 de septiembre
                                </span>
                            </div>

                        </div>

                        <button
                            class="button button-primary detail-apply-button open-application-modal"
                        >
                            Postularme ahora
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>

                        <p class="apply-card-note">
                            <i class="fa-solid fa-shield-heart"></i>
                            Tus datos serán enviados únicamente a la empresa.
                        </p>

                    </div>

                    <div class="accessibility-company-card">

                        <div class="accessibility-company-icon">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <div>
                            <h3>Compromiso inclusivo</h3>

                            <p>
                                Esta empresa declara promover procesos de selección
                                respetuosos y accesibles.
                            </p>
                        </div>

                    </div>

                    <div class="sidebar-share-card">

                        <h3>Comparte esta oportunidad</h3>

                        <p>
                            Tal vez esta vacante sea ideal para alguien que conoces.
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
                                aria-label="Copiar enlace"
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
                        <span class="section-label">TAMBIÉN PODRÍA INTERESARTE</span>

                        <h2>Empleos similares para tu perfil</h2>

                        <p>
                            Explora otras oportunidades relacionadas con tecnología,
                            desarrollo y habilidades digitales.
                        </p>
                    </div>

                    <a href="empleos.php" class="text-link">
                        Ver todos los empleos
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="similar-jobs-grid">

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>

                            <span class="job-type in-person">Presencial</span>
                        </div>

                        <h3>Soporte Técnico IT</h3>
                        <p>Nexa Solutions</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Antiguo Cuscatlán
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $850 / mes
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            Ver oportunidad
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>

                            <span class="job-type hybrid">Híbrido</span>
                        </div>

                        <h3>Analista de Datos</h3>
                        <p>DataVision</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                San Salvador
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $1,200 / mes
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            Ver oportunidad
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>

                    <article class="similar-job-card">

                        <div class="similar-job-top">
                            <div class="company-logo company-blue">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>

                            <span class="job-type remote">Remoto</span>
                        </div>

                        <h3>Desarrollador Frontend</h3>
                        <p>Cloud Bridge Labs</p>

                        <div class="similar-job-info">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                Remoto
                            </span>

                            <span>
                                <i class="fa-solid fa-dollar-sign"></i>
                                $1,800 / mes
                            </span>
                        </div>

                        <a href="empleos.php" class="similar-job-link">
                            Ver oportunidad
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
                        NO DEJES PASAR ESTA OPORTUNIDAD
                    </span>

                    <h2>Tu próximo proyecto profesional puede empezar hoy.</h2>

                    <p>
                        Postúlate, muestra tus habilidades y da el siguiente paso
                        hacia una carrera en tecnología.
                    </p>
                </div>

                <button
                    class="button button-light open-application-modal"
                >
                    Postularme ahora
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
                aria-label="Cerrar formulario de postulación"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div id="applicationFormContainer">

                <div class="application-modal-icon">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>

                <span class="modal-label">POSTULACIÓN RÁPIDA</span>

                <h2 id="applicationModalTitle">
                    Postularme como Desarrollador Web Jr.
                </h2>

                <p class="application-modal-description">
                    Completa tus datos para enviar tu perfil a Innovatech SV.
                </p>

                <form id="applicationForm" class="application-form">

                    <div class="application-form-row">

                        <div class="application-form-group">
                            <label for="applicantName">Nombre completo</label>

                            <input
                                type="text"
                                id="applicantName"
                                placeholder="Ejemplo: Isaías Pérez"
                                required
                            >
                        </div>

                        <div class="application-form-group">
                            <label for="applicantEmail">Correo electrónico</label>

                            <input
                                type="email"
                                id="applicantEmail"
                                placeholder="correo@ejemplo.com"
                                required
                            >
                        </div>

                    </div>

                    <div class="application-form-row">

                        <div class="application-form-group">
                            <label for="applicantPhone">Teléfono</label>

                            <input
                                type="tel"
                                id="applicantPhone"
                                placeholder="0000-0000"
                                required
                            >
                        </div>

                        <div class="application-form-group">
                            <label for="applicantExperience">Experiencia</label>

                            <select id="applicantExperience" required>
                                <option value="">Selecciona una opción</option>
                                <option>Sin experiencia laboral</option>
                                <option>Menos de 1 año</option>
                                <option>1 a 2 años</option>
                                <option>Más de 2 años</option>
                            </select>
                        </div>

                    </div>

                    <div class="application-form-group">
                        <label for="applicantMessage">
                            Cuéntanos brevemente por qué te interesa esta vacante
                        </label>

                        <textarea
                            id="applicantMessage"
                            rows="4"
                            placeholder="Me interesa esta oportunidad porque..."
                            required
                        ></textarea>
                    </div>

                    <div class="application-form-group">
                        <label for="applicantCV">Adjuntar CV</label>

                        <label class="cv-upload-box" for="applicantCV">
                            <i class="fa-solid fa-file-arrow-up"></i>

                            <span id="cvFileText">
                                Selecciona un archivo PDF o DOCX
                            </span>

                            <small>Máximo 5 MB</small>
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
                            Acepto que SkillBridge comparta mis datos con Innovatech SV
                            para esta postulación.
                        </span>
                    </label>

                    <button type="submit" class="button button-primary application-submit-button">
                        Enviar postulación
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                </form>

            </div>

            <div class="application-success hidden" id="applicationSuccess">

                <div class="application-success-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <span class="modal-label">POSTULACIÓN ENVIADA</span>

                <h2>¡Excelente decisión!</h2>

                <p>
                    Tu postulación para <strong>Desarrollador Web Jr.</strong>
                    fue enviada correctamente a Innovatech SV.
                </p>

                <div class="application-success-info">
                    <i class="fa-solid fa-envelope"></i>

                    <span>
                        Recibirás actualizaciones del proceso en tu correo electrónico.
                    </span>
                </div>

                <button class="button button-primary" id="closeSuccessButton">
                    Entendido
                    <i class="fa-solid fa-check"></i>
                </button>

            </div>

        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/detalle-empleo.js"></script>
</body>
</html>