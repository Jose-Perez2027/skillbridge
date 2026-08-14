<?php
require_once "config/conexion.php";

$mensaje = "";
$claseMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreEmpresa = trim($_POST["nombre_empresa"] ?? "");
    $lemaEmpresa = trim($_POST["lema_empresa"] ?? "");
    $ubicacionEmpresa = trim($_POST["ubicacion_empresa"] ?? "");

    $titulo = trim($_POST["titulo"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $modalidad = trim($_POST["modalidad"] ?? "");
    $ubicacion = trim($_POST["ubicacion"] ?? "");
    $tipoEmpleo = trim($_POST["tipo_empleo"] ?? "");
    $nivelExperiencia = trim($_POST["nivel_experiencia"] ?? "");
    $salario = trim($_POST["salario"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $responsabilidades = trim($_POST["responsabilidades"] ?? "");
    $requisitos = trim($_POST["requisitos"] ?? "");
    $habilidades = trim($_POST["habilidades"] ?? "");
    $fechaLimite = trim($_POST["fecha_limite"] ?? "");

    if (
        empty($nombreEmpresa) ||
        empty($titulo) ||
        empty($categoria) ||
        empty($modalidad) ||
        empty($ubicacion) ||
        empty($tipoEmpleo) ||
        empty($nivelExperiencia) ||
        empty($descripcion) ||
        empty($requisitos) ||
        empty($fechaLimite)
    ) {
        $mensaje = "Please complete all required fields.";
        $claseMensaje = "error";
    } else {
        try {
            $pdo->beginTransaction();

            $buscarEmpresa = $pdo->prepare("
                SELECT id_empresa 
                FROM empresas 
                WHERE nombre = ?
                LIMIT 1
            ");

            $buscarEmpresa->execute([$nombreEmpresa]);
            $empresaEncontrada = $buscarEmpresa->fetch();

            if ($empresaEncontrada) {
                $idEmpresa = $empresaEncontrada["id_empresa"];
            } else {
                $insertarEmpresa = $pdo->prepare("
                    INSERT INTO empresas (
                        nombre,
                        lema,
                        descripcion,
                        ubicacion,
                        colaboradores,
                        estado
                    ) VALUES (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        1
                    )
                ");

                $insertarEmpresa->execute([
                    $nombreEmpresa,
                    $lemaEmpresa,
                    "Partner company registered through the job posting form.",
                    $ubicacionEmpresa,
                    "Growing team"
                ]);

                $idEmpresa = $pdo->lastInsertId();
            }

            $salarioFinal = null;

            if ($salario !== "") {
                $salarioFinal = floatval($salario);
            }

            $insertarVacante = $pdo->prepare("
                INSERT INTO vacantes (
                    id_empresa,
                    titulo,
                    categoria,
                    modalidad,
                    ubicacion,
                    tipo_empleo,
                    nivel_experiencia,
                    salario,
                    descripcion,
                    responsabilidades,
                    requisitos,
                    habilidades,
                    fecha_limite,
                    estado,
                    inclusiva
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'activa',
                    1
                )
            ");

            $insertarVacante->execute([
                $idEmpresa,
                $titulo,
                $categoria,
                $modalidad,
                $ubicacion,
                $tipoEmpleo,
                $nivelExperiencia,
                $salarioFinal,
                $descripcion,
                $responsabilidades,
                $requisitos,
                $habilidades,
                $fechaLimite
            ]);

            $pdo->commit();

            $mensaje = "The job opening was published successfully.";
            $claseMensaje = "success";
        } catch (Exception $error) {
            $pdo->rollBack();

            $mensaje = "The job opening could not be published. Review the information and try again.";
            $claseMensaje = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Post a Job | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Post an inclusive job opening on SkillBridge and connect your company with qualified talent."
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
    <link rel="stylesheet" href="css/publicarvacante.css">
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

        <!-- HERO PUBLICAR VACANTE -->

        <section class="publish-page-hero">
            <div class="container publish-page-hero-content">

                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="empresas.php">Companies</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span>Post a Job</span>
                </div>

                <div class="publish-hero-text">
                    <span class="section-label">JOB POSTING</span>

                    <h1>Post an inclusive job opportunity.</h1>

                    <p>
                        Complete your company and job opening information so candidates
                        can learn about the opportunity and apply through SkillBridge.
                    </p>
                </div>

            </div>
        </section>

        <!-- FORMULARIO -->

        <section class="publish-content-section">
            <div class="container publish-layout">

                <aside class="publish-info">

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>

                        <h3>Tip</h3>

                        <p>
                            Write a clear description, realistic requirements, and a work arrangement
                            that candidates can easily understand.
                        </p>
                    </div>

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-universal-access"></i>
                        </div>

                        <h3>Inclusive approach</h3>

                        <p>
                            Avoid exclusionary language and highlight accessible conditions for different people.
                        </p>
                    </div>

                    <div class="publish-info-card">
                        <div class="publish-info-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>

                        <h3>Organized information</h3>

                        <p>
                            The information entered will be used to display the job opening clearly
                            within the job board.
                        </p>
                    </div>

                </aside>

                <section class="publish-card">

                    <div class="publish-card-header">
                        <span class="section-label">JOB OPENING DETAILS</span>

                        <h2>Complete the form</h2>

                        <p>
                            Fields marked with * are required.
                        </p>
                    </div>

                    <?php if (!empty($mensaje)): ?>
                        <div class="form-alert <?php echo htmlspecialchars($claseMensaje); ?>">
                            <?php if ($claseMensaje === "success"): ?>
                                <i class="fa-solid fa-circle-check"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            <?php endif; ?>

                            <span>
                                <?php echo htmlspecialchars($mensaje); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <form action="publicarvacante.php" method="POST" id="publishVacancyForm" class="publish-form">

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-building"></i>
                            Company Information
                        </div>

                        <div class="publish-form-group">
                            <label for="nombre_empresa">Company name *</label>

                            <input
                                type="text"
                                id="nombre_empresa"
                                name="nombre_empresa"
                                placeholder="Example: Innovatech SV"
                                required
                            >
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="lema_empresa">Slogan or short description</label>

                                <input
                                    type="text"
                                    id="lema_empresa"
                                    name="lema_empresa"
                                    placeholder="Example: Modern technology solutions"
                                >
                            </div>

                            <div class="publish-form-group">
                                <label for="ubicacion_empresa">Company location</label>

                                <input
                                    type="text"
                                    id="ubicacion_empresa"
                                    name="ubicacion_empresa"
                                    placeholder="Example: San Salvador, El Salvador"
                                >
                            </div>
                        </div>

                        <div class="publish-form-section-title">
                            <i class="fa-solid fa-briefcase"></i>
                            Position Information
                        </div>

                        <div class="publish-form-group">
                            <label for="titulo">Job title *</label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                placeholder="Example: Junior Web Developer"
                                required
                            >
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="categoria">Category *</label>

                                <select id="categoria" name="categoria" required>
                                    <option value="">Select a category</option>
                                    <option value="Tecnología">Technology</option>
                                    <option value="Diseño">Design</option>
                                    <option value="Administración">Administration</option>
                                    <option value="Ventas">Sales</option>
                                    <option value="Atención al Cliente">Customer Service</option>
                                    <option value="Recursos Humanos">Human Resources</option>
                                </select>
                            </div>

                            <div class="publish-form-group">
                                <label for="modalidad">Work arrangement *</label>

                                <select id="modalidad" name="modalidad" required>
                                    <option value="">Select a work arrangement</option>
                                    <option value="Remoto">Remote</option>
                                    <option value="Híbrido">Hybrid</option>
                                    <option value="Presencial">On-site</option>
                                </select>
                            </div>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="ubicacion">Job location *</label>

                                <input
                                    type="text"
                                    id="ubicacion"
                                    name="ubicacion"
                                    placeholder="Example: Remote from El Salvador"
                                    required
                                >
                            </div>

                            <div class="publish-form-group">
                                <label for="tipo_empleo">Employment type *</label>

                                <select id="tipo_empleo" name="tipo_empleo" required>
                                    <option value="">Select a type</option>
                                    <option value="Tiempo completo">Full-time</option>
                                    <option value="Medio tiempo">Part-time</option>
                                    <option value="Pasantía">Internship</option>
                                    <option value="Temporal">Temporary</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                            </div>
                        </div>

                        <div class="publish-form-row">
                            <div class="publish-form-group">
                                <label for="nivel_experiencia">Experience level *</label>

                                <select id="nivel_experiencia" name="nivel_experiencia" required>
                                    <option value="">Select a level</option>
                                    <option value="Sin experiencia">No experience</option>
                                    <option value="Junior">Junior</option>
                                    <option value="Intermedio">Mid-level</option>
                                    <option value="Senior">Senior</option>
                                </select>
                            </div>

                            <div class="publish-form-group">
                                <label for="salario">Monthly salary</label>

                                <input
                                    type="number"
                                    id="salario"
                                    name="salario"
                                    placeholder="Example: 950"
                                    min="0"
                                    step="0.01"
                                >
                            </div>
                        </div>

                        <div class="publish-form-group">
                            <label for="descripcion">Job description *</label>

                            <textarea
                                id="descripcion"
                                name="descripcion"
                                rows="5"
                                placeholder="Clearly describe the purpose of the position."
                                required
                            ></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="responsabilidades">Responsibilities</label>

                            <textarea
                                id="responsabilidades"
                                name="responsabilidades"
                                rows="4"
                                placeholder="Example: Build web interfaces | Fix errors | Work with the team"
                            ></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="requisitos">Requirements *</label>

                            <textarea
                                id="requisitos"
                                name="requisitos"
                                rows="4"
                                placeholder="Example: Basic HTML knowledge | Responsibility | Willingness to learn"
                                required
                            ></textarea>
                        </div>

                        <div class="publish-form-group">
                            <label for="habilidades">Skills</label>

                            <input
                                type="text"
                                id="habilidades"
                                name="habilidades"
                                placeholder="Example: HTML | CSS | JavaScript | Git"
                            >
                        </div>

                        <div class="publish-form-group">
                            <label for="fecha_limite">Application deadline *</label>

                            <input
                                type="date"
                                id="fecha_limite"
                                name="fecha_limite"
                                required
                            >
                        </div>

                        <button type="submit" class="button button-primary publish-button">
                            Post a Job
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>

                    </form>

                </section>

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
                <a href="publicarvacante.php">Post a Job</a>
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
    <script src="java/publicarvacante.js"></script>
</body>
</html>