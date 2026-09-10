<?php
$sbFooterLang = $idiomaActual ?? ($_SESSION['idioma_preferido'] ?? ($_COOKIE['skillbridgeLanguage'] ?? ($_GET['lang'] ?? 'en')));
$sbFooterLang = is_string($sbFooterLang) ? strtolower(trim($sbFooterLang)) : 'en';
$sbFooterLang = in_array($sbFooterLang, ['en', 'es'], true) ? $sbFooterLang : 'en';

$sbFooterText = [
    'en' => [
        'description' => 'We connect talent, companies, and opportunities to build a more accessible future of work.',
        'candidates' => 'For candidates',
        'find_jobs' => 'Find jobs',
        'create_profile' => 'Create profile',
        'my_applications' => 'My applications',
        'resources' => 'Resources and advice',
        'companies_title' => 'For companies',
        'post_job' => 'Post a job',
        'received_applications' => 'Received applications',
        'companies' => 'Companies',
        'contact' => 'Contact the team',
        'platform' => 'Platform',
        'about' => 'About us',
        'accessibility' => 'Accessibility',
        'privacy' => 'Privacy',
        'terms' => 'Terms and Conditions',
        'newsletter' => 'Newsletter',
        'newsletter_text' => 'Receive new job openings and professional advice.',
        'email' => 'Your email address',
        'subscribe' => 'Subscribe to newsletter',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'tiktok' => 'TikTok',
        'rights' => '© 2026 SkillBridge. All rights reserved.'
    ],
    'es' => [
        'description' => 'Conectamos talento, empresas y oportunidades para construir un futuro laboral más accesible.',
        'candidates' => 'Para candidatos',
        'find_jobs' => 'Buscar empleos',
        'create_profile' => 'Crear perfil',
        'my_applications' => 'Mis postulaciones',
        'resources' => 'Recursos y consejos',
        'companies_title' => 'Para empresas',
        'post_job' => 'Publicar vacante',
        'received_applications' => 'Postulaciones recibidas',
        'companies' => 'Empresas',
        'contact' => 'Contactar al equipo',
        'platform' => 'Plataforma',
        'about' => 'Quiénes somos',
        'accessibility' => 'Accesibilidad',
        'privacy' => 'Privacidad',
        'terms' => 'Términos y condiciones',
        'newsletter' => 'Boletín',
        'newsletter_text' => 'Recibe nuevas vacantes y consejos profesionales.',
        'email' => 'Tu correo electrónico',
        'subscribe' => 'Suscribirse al boletín',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'tiktok' => 'TikTok',
        'rights' => '© 2026 SkillBridge. Todos los derechos reservados.'
    ]
];

$sbFt = function (string $key) use ($sbFooterText, $sbFooterLang): string {
    return htmlspecialchars($sbFooterText[$sbFooterLang][$key] ?? $sbFooterText['en'][$key] ?? $key, ENT_QUOTES, 'UTF-8');
};

$sbFooterLink = function (string $path) use ($sbFooterLang): string {
    $separator = strpos($path, '?') !== false ? '&' : '?';
    return htmlspecialchars($path . $separator . 'lang=' . $sbFooterLang, ENT_QUOTES, 'UTF-8');
};
?>
<footer class="footer" role="contentinfo">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="<?php echo $sbFooterLink('index.php'); ?>" class="logo footer-logo" aria-label="SkillBridge">
                <div class="logo-icon">
                    <img src="img/LOGOS.png" alt="SkillBridge logo">
                </div>
                <div class="logo-text"><span>Skill</span>Bridge</div>
            </a>

            <p><?php echo $sbFt('description'); ?></p>

            <div class="social-links" aria-label="Social media">
                <a href="#" aria-label="<?php echo $sbFt('facebook'); ?>"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                <a href="#" aria-label="<?php echo $sbFt('instagram'); ?>"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="#" aria-label="<?php echo $sbFt('linkedin'); ?>"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></a>
                <a href="#" aria-label="<?php echo $sbFt('tiktok'); ?>"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
            </div>

            <div class="footer-newsletter">
                <h4><?php echo $sbFt('newsletter'); ?></h4>
                <p><?php echo $sbFt('newsletter_text'); ?></p>
                <form class="newsletter-form" id="newsletterForm">
                    <label for="newsletterEmail" class="sr-only"><?php echo $sbFt('email'); ?></label>
                    <div class="newsletter-input">
                        <input type="email" id="newsletterEmail" placeholder="<?php echo $sbFt('email'); ?>" required>
                        <button type="submit" aria-label="<?php echo $sbFt('subscribe'); ?>">
                            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small id="newsletterMessage" aria-live="polite"></small>
                </form>
            </div>
        </div>

        <nav class="footer-column" aria-label="<?php echo $sbFt('candidates'); ?>">
            <h4><?php echo $sbFt('candidates'); ?></h4>
            <a href="<?php echo $sbFooterLink('empleos.php'); ?>"><?php echo $sbFt('find_jobs'); ?></a>
            <a href="<?php echo $sbFooterLink('registro.php'); ?>"><?php echo $sbFt('create_profile'); ?></a>
            <a href="<?php echo $sbFooterLink('postulaciones.php'); ?>"><?php echo $sbFt('my_applications'); ?></a>
            <a href="<?php echo $sbFooterLink('recursos.php'); ?>"><?php echo $sbFt('resources'); ?></a>
        </nav>

        <nav class="footer-column" aria-label="<?php echo $sbFt('companies_title'); ?>">
            <h4><?php echo $sbFt('companies_title'); ?></h4>
            <a href="<?php echo $sbFooterLink('publicarvacante.php'); ?>"><?php echo $sbFt('post_job'); ?></a>
            <a href="<?php echo $sbFooterLink('postulaciones-empresa.php'); ?>"><?php echo $sbFt('received_applications'); ?></a>
            <a href="<?php echo $sbFooterLink('empresas.php'); ?>"><?php echo $sbFt('companies'); ?></a>
            <a href="<?php echo $sbFooterLink('contacto.php'); ?>"><?php echo $sbFt('contact'); ?></a>
        </nav>

        <nav class="footer-column" aria-label="<?php echo $sbFt('platform'); ?>">
            <h4><?php echo $sbFt('platform'); ?></h4>
            <a href="<?php echo $sbFooterLink('quienessomos.php'); ?>"><?php echo $sbFt('about'); ?></a>
            <a href="<?php echo $sbFooterLink('accesibilidad.php'); ?>"><?php echo $sbFt('accessibility'); ?></a>
            <a href="<?php echo $sbFooterLink('privacidad.php'); ?>"><?php echo $sbFt('privacy'); ?></a>
            <a href="<?php echo $sbFooterLink('terminos.php'); ?>"><?php echo $sbFt('terms'); ?></a>
        </nav>
    </div>

    <div class="container footer-bottom">
        <p><?php echo $sbFt('rights'); ?></p>
        <div>
            <a href="<?php echo $sbFooterLink('privacidad.php'); ?>"><?php echo $sbFt('privacy'); ?></a>
            <a href="<?php echo $sbFooterLink('terminos.php'); ?>"><?php echo $sbFt('terms'); ?></a>
        </div>
    </div>
</footer>
