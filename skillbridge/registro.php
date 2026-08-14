<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Create your SkillBridge account and connect with inclusive job opportunities."
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
    <link rel="stylesheet" href="css/auth.css">
</head>

<body class="auth-body">

    <div class="sr-only" aria-live="polite" id="accessibilityMessage"></div>

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
                <li><a href="empresas.html">Companies</a></li>
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

    <main class="auth-container">

        <section class="auth-split-wrapper">

            <div class="auth-sidebar">
                <div class="sidebar-content">
                    <span class="auth-badge">
                        <i class="fa-solid fa-universal-access"></i>
                        Inclusive platform
                    </span>

                    <h1>Start your professional journey without barriers.</h1>

                    <p>
                        Create your account to explore job openings, save opportunities
                        and prepare to connect with inclusive companies.
                    </p>

                    <div class="sidebar-features">
                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Flexible profiles for candidates and companies.</span>
                        </div>

                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Access to inclusive job opportunities.</span>
                        </div>

                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Accessible, clear, and easy-to-use design.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-container">

                <div class="auth-box">

                    <div class="auth-header">
                        <span class="section-label">SIGN UP FOR FREE</span>

                        <h2>Create your account</h2>

                        <p class="auth-subtitle">
                            Join SkillBridge and start building your professional profile.
                        </p>
                    </div>

                    <form action="#" method="POST" id="registerForm" class="auth-form" novalidate>

                        <div class="form-group-custom">
                            <label for="regName">Full name or company name</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-user input-icon"></i>

                                <input
                                    type="text"
                                    id="regName"
                                    name="nombre"
                                    placeholder="Example: María Hernández"
                                    required
                                    minlength="3"
                                >

                                <i class="fa-solid fa-circle-exclamation error-icon"></i>
                            </div>

                            <span class="error-text">
                                The name must be at least 3 characters long.
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="regEmail">Email address</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-envelope input-icon"></i>

                                <input
                                    type="email"
                                    id="regEmail"
                                    name="correo"
                                    placeholder="email@example.com"
                                    required
                                >

                                <i class="fa-solid fa-circle-exclamation error-icon"></i>
                            </div>

                            <span class="error-text">
                                Enter a valid email address.
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="userType">Account type</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-users input-icon"></i>

                                <select id="userType" name="tipo_usuario" required>
                                    <option value="" disabled selected>Select an option</option>
                                    <option value="candidato">I am looking for a job</option>
                                    <option value="empresa">I am hiring</option>
                                </select>
                            </div>

                            <span class="error-text">
                                Please select an account type.
                            </span>
                        </div>

                        <div class="form-group-custom">
                            <label for="regPassword">Password</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock input-icon"></i>

                                <input
                                    type="password"
                                    id="regPassword"
                                    name="password"
                                    placeholder="At least 8 characters"
                                    required
                                    minlength="8"
                                >

                                <button
                                    type="button"
                                    class="toggle-password-button"
                                    id="togglePassword"
                                    aria-label="Show or hide password"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength-meter">
                                <div class="meter-bar"></div>
                                <span class="meter-label">Password strength</span>
                            </div>

                            <span class="error-text">
                                The password must be at least 8 characters long.
                            </span>
                        </div>

                        <div class="form-group-checkbox">
                            <label class="checkbox-container">
                                <input type="checkbox" id="termsCheckbox" required>

                                <span class="checkmark"></span>

                                <span class="terms-label">
                                    I accept the
                                    <a href="#">Terms of Service</a>
                                    and the
                                    <a href="#">Privacy Policy</a>.
                                </span>
                            </label>
                        </div>

                        <button type="submit" class="button button-primary btn-block" id="btnRegisterSubmit">
                            <span>Create Account</span>
                            <i class="fa-solid fa-user-plus"></i>
                        </button>

                        <p class="auth-switch-text">
                            Already have an account?
                            <a href="login.php">Log in here</a>
                        </p>

                    </form>

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
                <a href="empresas.html">Post a Job</a>
                <a href="empresas.html">Find Talent</a>
                <a href="empresas.html">Business Plans</a>
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
    <script src="java/auth.js"></script>
</body>
</html>