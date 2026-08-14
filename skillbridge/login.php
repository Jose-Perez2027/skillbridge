<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log In | SkillBridge</title>

    <link rel="icon" type="image/png" href="img/LOGOS.png">

    <meta
        name="description"
        content="Log in to SkillBridge to manage your applications and job opportunities."
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

    <main class="auth-container simple-center">

        <section class="auth-box central-box">

            <div class="auth-header">
                <span class="section-label">WELCOME BACK</span>

                <h1>Log in to your account</h1>

                <p class="auth-subtitle">
                    Manage your professional profile, review your applications
                    or manage your active job openings.
                </p>
            </div>

            <?php if (isset($_GET["error"])): ?>
                <div class="auth-alert error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>The email address or password is incorrect.</span>
                </div>
            <?php endif; ?>

            <form action="#" method="POST" id="loginForm" class="auth-form" novalidate>

                <div class="form-group-custom">
                    <label for="loginEmail">Email address</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"></i>

                        <input
                            type="email"
                            id="loginEmail"
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
                    <div class="label-row">
                        <label for="loginPassword">Password</label>

                        <a href="#" class="forgot-password">
                            Forgot your password?
                        </a>
                    </div>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>

                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password-button"
                            id="togglePasswordLogin"
                            aria-label="Show or hide password"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <span class="error-text">
                        The password cannot be empty.
                    </span>
                </div>

                <div class="form-group-checkbox">
                    <label class="checkbox-container">
                        <input type="checkbox" id="rememberMe" name="recordar">

                        <span class="checkmark"></span>

                        <span class="terms-label">
                            Remember me on this device.
                        </span>
                    </label>
                </div>

                <button type="submit" class="button button-primary btn-block" id="btnLoginSubmit">
                    <span>Log In</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>

                <p class="auth-switch-text">
                    Don’t have an account yet?
                    <a href="registro.php">Sign up for free</a>
                </p>

            </form>

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
    <script src="java/auth.js"></script>
</body>
</html>