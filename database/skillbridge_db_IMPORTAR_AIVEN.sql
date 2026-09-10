-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-09-2026 a las 20:26:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `skillbridge_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `lema` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion` varchar(120) DEFAULT NULL,
  `anio_fundacion` year(4) DEFAULT NULL,
  `colaboradores` varchar(30) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `nombre`, `lema`, `descripcion`, `ubicacion`, `anio_fundacion`, `colaboradores`, `logo`, `estado`, `fecha_registro`) VALUES
(1, 'Innovatech SV', 'Technology solutions for modern businesses.', 'Innovatech SV is a Salvadoran company dedicated to developing digital solutions, web platforms, and management tools for national and international companies.', 'San Salvador, El Salvador', '2018', '50+', NULL, 1, '2026-07-06 01:45:55'),
(2, 'TechNova Solutions', 'Technology for everyone', 'Company focused on software development, digital solutions, and inclusive work opportunities.', 'San Salvador, El Salvador', '2018', '50+', NULL, 1, '2026-07-24 15:26:53'),
(3, 'Innova Creative Studio', 'Designing accessible digital experiences', 'Creative company focused on design, branding, communication, and accessible digital content.', 'Santa Tecla, El Salvador', '2020', '25+', NULL, 1, '2026-07-24 15:26:53'),
(4, 'Global Support Center', 'Connecting people with better service', 'Customer service company that promotes accessible employment and professional growth.', 'San Salvador, El Salvador', '2016', '100+', NULL, 1, '2026-07-24 15:26:53'),
(5, 'EcoNova Solutions SV', 'Technology for a greener future', 'Partner company registered through the job posting form.', 'Santa Tecla, La Libertad, El Salvador', NULL, 'Growing team', NULL, 1, '2026-07-24 15:39:49'),
(8, 'Ana Perez', 'Technology for a greener future', 'Partner company registered through the job posting form.', 'Santa Tecla, La Libertad, El Salvador', NULL, 'Growing team', NULL, 1, '2026-08-25 00:31:11'),
(9, 'Edgar Molina', 'Technology for a greener future', 'Company account created through SkillBridge.', NULL, NULL, 'Growing team', NULL, 1, '2026-08-27 15:15:00'),
(10, 'Bryan Ruben De Paz Rivera', 'SkillBridge partner company', 'Company account created through SkillBridge.', NULL, NULL, 'Growing team', NULL, 1, '2026-08-31 14:41:22'),
(11, 'Steven Anzora', 'CodeMaster', NULL, 'Santa Tecla, La Libertad, El Salvador', NULL, NULL, NULL, 1, '2026-09-04 15:20:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

CREATE TABLE `postulaciones` (
  `id_postulacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_vacante` int(11) NOT NULL,
  `mensaje` text DEFAULT NULL,
  `cv_archivo` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','revisada','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha_postulacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id_postulacion`, `id_usuario`, `id_vacante`, `mensaje`, `cv_archivo`, `estado`, `fecha_postulacion`) VALUES
(1, 1, 9, 'sadfasdfasdfas', 'cv_1_9_1787969244.pdf', 'pendiente', '2026-08-29 02:07:24'),
(2, 1, 10, 'porq si', 'cv/cv_1_1788061820.pdf', 'pendiente', '2026-08-30 04:00:05'),
(3, 11, 2, 'Porque quiero dinero', 'cv/cv_11_2_1788187217.pdf', 'pendiente', '2026-08-31 14:40:17'),
(4, 1, 11, 'hhhhh', 'cv/cv_1_1788061820.pdf', 'pendiente', '2026-08-31 15:29:02'),
(5, 13, 11, 'Busco chamba', 'cv/cv_13_11_1788191043.pdf', 'pendiente', '2026-08-31 15:44:03'),
(6, 15, 11, 'klgkldfg', NULL, 'pendiente', '2026-09-04 15:36:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `tipo_usuario` enum('candidato','empresa','administrador') NOT NULL DEFAULT 'candidato',
  `telefono` varchar(25) DEFAULT NULL,
  `ubicacion` varchar(120) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `curriculum` varchar(255) DEFAULT NULL,
  `discapacidad` tinyint(1) DEFAULT 0,
  `tipo_discapacidad` varchar(120) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `idioma_preferido` varchar(5) DEFAULT 'en',
  `modo_oscuro` tinyint(1) DEFAULT 0,
  `alto_contraste` tinyint(1) DEFAULT 0,
  `modo_lectura` tinyint(1) DEFAULT 0,
  `escala_texto` decimal(3,2) DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `password_hash`, `tipo_usuario`, `telefono`, `ubicacion`, `descripcion`, `foto_perfil`, `curriculum`, `discapacidad`, `tipo_discapacidad`, `estado`, `fecha_registro`, `idioma_preferido`, `modo_oscuro`, `alto_contraste`, `modo_lectura`, `escala_texto`) VALUES
(1, 'Jose Perez', 'isaiasjoseperez1748@gmail.com', '$2y$10$TaVtx./eESN98eQxSroeOO7VpYTlOGoq0Vqd4bTWj67gRi9E5y9l.', 'candidato', '79673898', 'San Salvador', 'fdasfsdfafafdssaf', 'img/perfiles/perfil_1_1788061820.png', 'cv/cv_1_1788061820.pdf', 0, NULL, 1, '2026-08-19 01:35:56', 'en', 0, 0, 0, 1.00),
(6, 'Isaias Perez', 'isaias.perez2027@gmail.com', '$2y$10$GXncmX6/yLWki.NfSqmKsOdOgtxAKrf9QgVsxFOR6uZpO1uttNzlq', 'candidato', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-08-19 02:11:26', 'en', 0, 0, 0, 1.00),
(7, 'Ana Perez', 'ana.perez2027@gmail.com', '$2y$10$B2EKiU1JKufLockYBhV3L.ox1AeanEZGJXjPnxLEz8ctQnmFfSquu', 'empresa', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-08-25 00:30:03', 'en', 0, 0, 0, 1.00),
(8, 'Steven Campos', 'steven.campos2027@gmail.com', '$2y$10$di5tOww/6yi3owDu6Y7fx.Q6Sikj8o5R7XxEPkNdMOUSBijAYOCWa', 'candidato', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-08-25 14:45:29', 'es', 0, 0, 0, 1.00),
(10, 'Edgar Molina', 'edgar@gmail.com', '$2y$10$03ndaSnjbz/bWC9BYuHnmeR/vjQ07WHBKKD34hKFG2HHVIH.0Hl.2', 'empresa', NULL, NULL, NULL, 'img/perfiles/perfil_10_1788190420.png', NULL, 0, NULL, 1, '2026-08-27 14:39:03', 'en', 0, 0, 0, 1.00),
(11, 'Bryan Ruben De Paz Rivera', 'bryan@gmail.com', '$2y$10$Hy.M/d1zCrId.TCLN9KFXuFko7svtfje2TsEBnhA.PsAlH6Po/6Su', 'candidato', '7565655', 'Res Altavista', 'Quiero dinero', 'img/perfiles/perfil_11_1788187145.png', NULL, 0, NULL, 1, '2026-08-31 14:37:53', 'en', 0, 0, 0, 1.00),
(12, 'Bryan Ruben De Paz Rivera', 'bryan1@gmail.com', '$2y$10$Xgqhvjgt5cQluygpCC6Hm.QKKn9zw898zyFzLudf9ctqeyAkLcQa6', 'empresa', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-08-31 14:41:22', 'en', 0, 0, 0, 1.00),
(13, 'agua', 'agua@gmail.com', '$2y$10$2FOjy7HewF1pHXw./Xtq9e64N/hsrt2IgSmdHfMcbY4NOpjS3S0R.', 'candidato', NULL, NULL, NULL, 'img/perfiles/perfil_13_1788190936.png', NULL, 0, NULL, 1, '2026-08-31 15:41:53', 'en', 0, 0, 0, 1.00),
(14, 'Steven Anzora', 'steven@gmail.com', '$2y$10$ZbP3CQ6Te.OcLAdmHYHOseTI4aa1U7FphltmDhQqVittt0x5WtgvW', 'empresa', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-09-04 15:20:38', 'en', 0, 0, 0, 1.00),
(15, 'Bryan Ruben De Paz Rivera', 'cliente@gmail.com', '$2y$10$hGCy/M7vICA6sEqdHHF8v.GiK2iIgE1S./.C9wIlz.W7uf28IjB8y', 'candidato', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-09-04 15:35:15', 'en', 0, 0, 0, 1.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacantes`
--

CREATE TABLE `vacantes` (
  `id_vacante` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `titulo_en` varchar(150) DEFAULT NULL,
  `titulo_es` varchar(150) DEFAULT NULL,
  `categoria` varchar(80) NOT NULL,
  `modalidad` varchar(50) NOT NULL,
  `ubicacion` varchar(120) NOT NULL,
  `tipo_empleo` varchar(100) NOT NULL,
  `nivel_experiencia` varchar(80) NOT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `descripcion_en` text DEFAULT NULL,
  `descripcion_es` text DEFAULT NULL,
  `responsabilidades` text DEFAULT NULL,
  `responsabilidades_en` text DEFAULT NULL,
  `responsabilidades_es` text DEFAULT NULL,
  `requisitos` text DEFAULT NULL,
  `requisitos_en` text DEFAULT NULL,
  `requisitos_es` text DEFAULT NULL,
  `habilidades` text DEFAULT NULL,
  `habilidades_en` text DEFAULT NULL,
  `habilidades_es` text DEFAULT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_limite` date DEFAULT NULL,
  `estado` varchar(30) DEFAULT 'activa',
  `inclusiva` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vacantes`
--

INSERT INTO `vacantes` (`id_vacante`, `id_empresa`, `titulo`, `titulo_en`, `titulo_es`, `categoria`, `modalidad`, `ubicacion`, `tipo_empleo`, `nivel_experiencia`, `salario`, `descripcion`, `descripcion_en`, `descripcion_es`, `responsabilidades`, `responsabilidades_en`, `responsabilidades_es`, `requisitos`, `requisitos_en`, `requisitos_es`, `habilidades`, `habilidades_en`, `habilidades_es`, `fecha_publicacion`, `fecha_limite`, `estado`, `inclusiva`) VALUES
(1, 1, 'Desarrollador Web Jr.', 'Desarrollador Web Jr.', 'Desarrollador Web Jr.', 'Technology', 'Remote', 'Remoto desde El Salvador', 'Full-time', 'Junior', 950.00, 'Buscamos un Desarrollador Web Jr. con interés por crear experiencias digitales modernas, accesibles y funcionales. Participará en proyectos reales utilizando HTML, CSS y JavaScript.', 'Buscamos un Desarrollador Web Jr. con interés por crear experiencias digitales modernas, accesibles y funcionales. Participará en proyectos reales utilizando HTML, CSS y JavaScript.', 'Buscamos un Desarrollador Web Jr. con interés por crear experiencias digitales modernas, accesibles y funcionales. Participará en proyectos reales utilizando HTML, CSS y JavaScript.', 'Desarrollar páginas web responsivas|Convertir diseños visuales en interfaces funcionales|Aplicar buenas prácticas de accesibilidad web|Corregir errores visuales|Trabajar con Git y GitHub', 'Desarrollar páginas web responsivas|Convertir diseños visuales en interfaces funcionales|Aplicar buenas prácticas de accesibilidad web|Corregir errores visuales|Trabajar con Git y GitHub', 'Desarrollar páginas web responsivas|Convertir diseños visuales en interfaces funcionales|Aplicar buenas prácticas de accesibilidad web|Corregir errores visuales|Trabajar con Git y GitHub', 'Conocimientos de HTML, CSS y JavaScript|Estudios relacionados con tecnología|Proyectos personales o académicos|Responsabilidad y disposición para aprender', 'Conocimientos de HTML, CSS y JavaScript|Estudios relacionados con tecnología|Proyectos personales o académicos|Responsabilidad y disposición para aprender', 'Conocimientos de HTML, CSS y JavaScript|Estudios relacionados con tecnología|Proyectos personales o académicos|Responsabilidad y disposición para aprender', 'HTML|CSS|JavaScript|Git|GitHub|Figma|React', 'HTML|CSS|JavaScript|Git|GitHub|Figma|React', 'HTML|CSS|JavaScript|Git|GitHub|Figma|React', '2026-07-06 01:45:55', '2026-09-30', 'activa', 1),
(2, 1, 'Junior Web Developer', 'Junior Web Developer', 'Junior Web Developer', 'Technology', 'Remote', 'El Salvador', 'Full-time', 'Junior', 750.00, 'We are looking for a junior web developer to support the creation of accessible websites and digital platforms.', 'We are looking for a junior web developer to support the creation of accessible websites and digital platforms.', 'We are looking for a junior web developer to support the creation of accessible websites and digital platforms.', 'Create web interfaces | Fix basic errors | Work with the development team', 'Create web interfaces | Fix basic errors | Work with the development team', 'Create web interfaces | Fix basic errors | Work with the development team', 'Basic knowledge of HTML, CSS and JavaScript | Responsibility | Willingness to learn', 'Basic knowledge of HTML, CSS and JavaScript | Responsibility | Willingness to learn', 'Basic knowledge of HTML, CSS and JavaScript | Responsibility | Willingness to learn', 'HTML | CSS | JavaScript | Git', 'HTML | CSS | JavaScript | Git', 'HTML | CSS | JavaScript | Git', '2026-07-24 15:26:53', '2026-09-30', 'activa', 1),
(3, 2, 'Graphic Design Assistant', 'Graphic Design Assistant', 'Graphic Design Assistant', 'Design', 'Hybrid', 'Santa Tecla, El Salvador', 'Part-time', 'Junior', 500.00, 'Support the creative team with graphic pieces, social media content, and accessible visual materials.', 'Support the creative team with graphic pieces, social media content, and accessible visual materials.', 'Support the creative team with graphic pieces, social media content, and accessible visual materials.', 'Create basic designs | Support branding projects | Prepare social media content', 'Create basic designs | Support branding projects | Prepare social media content', 'Create basic designs | Support branding projects | Prepare social media content', 'Basic design knowledge | Creativity | Good communication', 'Basic design knowledge | Creativity | Good communication', 'Basic design knowledge | Creativity | Good communication', 'Canva | Photoshop | Illustrator | Creativity', 'Canva | Photoshop | Illustrator | Creativity', 'Canva | Photoshop | Illustrator | Creativity', '2026-07-24 15:26:53', '2026-09-25', 'activa', 1),
(4, 3, 'Customer Service Agent', 'Customer Service Agent', 'Customer Service Agent', 'Customer Service', 'On-site', 'San Salvador, El Salvador', 'Full-time', 'No experience', 600.00, 'Provide customer support through calls, messages, and digital channels in a respectful and professional way.', 'Provide customer support through calls, messages, and digital channels in a respectful and professional way.', 'Provide customer support through calls, messages, and digital channels in a respectful and professional way.', 'Answer customer questions | Register cases | Provide clear information', 'Answer customer questions | Register cases | Provide clear information', 'Answer customer questions | Register cases | Provide clear information', 'Good communication | Basic computer skills | Positive attitude', 'Good communication | Basic computer skills | Positive attitude', 'Good communication | Basic computer skills | Positive attitude', 'Communication | Empathy | Microsoft Office', 'Communication | Empathy | Microsoft Office', 'Communication | Empathy | Microsoft Office', '2026-07-24 15:26:53', '2026-10-05', 'activa', 1),
(5, 5, 'Junior Graphic Designer', 'Junior Graphic Designer', 'Junior Graphic Designer', 'Design', 'Hybrid', 'Santa Tecla, La Libertad, El Salvador', 'Full-time', 'Junior', 750.00, 'EcoNova Solutions SV is looking for a creative Junior Graphic Designer to develop visual content for digital campaigns, social media and company projects. The selected candidate will work with the marketing team to create attractive and accessible designs.', 'EcoNova Solutions SV is looking for a creative Junior Graphic Designer to develop visual content for digital campaigns, social media and company projects. The selected candidate will work with the marketing team to create attractive and accessible designs.', 'EcoNova Solutions SV is looking for a creative Junior Graphic Designer to develop visual content for digital campaigns, social media and company projects. The selected candidate will work with the marketing team to create attractive and accessible designs.', 'Create social media graphics | Design promotional materials | Edit images | Support digital campaigns | Work with the marketing team | Follow the company’s visual identity', 'Create social media graphics | Design promotional materials | Edit images | Support digital campaigns | Work with the marketing team | Follow the company’s visual identity', 'Create social media graphics | Design promotional materials | Edit images | Support digital campaigns | Work with the marketing team | Follow the company’s visual identity', 'Basic knowledge of graphic design | Creativity and attention to detail | Good communication skills | Ability to work in a team | Willingness to learn | Basic English knowledge', 'Basic knowledge of graphic design | Creativity and attention to detail | Good communication skills | Ability to work in a team | Willingness to learn | Basic English knowledge', 'Basic knowledge of graphic design | Creativity and attention to detail | Good communication skills | Ability to work in a team | Willingness to learn | Basic English knowledge', 'Canva , Adobe Photoshop, Adobe Illustrator, Figma, Social media design', 'Canva , Adobe Photoshop, Adobe Illustrator, Figma, Social media design', 'Canva , Adobe Photoshop, Adobe Illustrator, Figma, Social media design', '2026-07-24 15:39:49', '2026-07-31', 'activa', 1),
(8, 8, 'rsfdasfdasf', 'rsfdasfdasf', 'rsfdasfdasf', 'Administration', 'Remote', 'fasdfasdfasdf', 'Full-time', 'Mid-level', 3423.00, 'afdfasdfasfsdaf', 'afdfasdfasfsdaf', 'afdfasdfasfsdaf', 'asdfasdfasdfasd', 'asdfasdfasdfasd', 'asdfasdfasdfasd', 'asfasdfasdfasdfads', 'asfasdfasdfasdfads', 'asfasdfasdfasdfads', 'dasfdsfasfasdfas', 'dasfdsfasfasdfas', 'dasfdsfasfasdfas', '2026-08-25 00:31:11', '2026-08-31', 'activa', 1),
(9, 9, 'Supervisor', 'Supervisor', 'Supervisor', 'Administration', 'Remote', 'San Salvador', 'Full-time', 'Mid-level', 3423.00, 'Necesita supervisar', 'Necesita supervisar', 'Necesita supervisar', 'Estar pendiente de todo', 'Estar pendiente de todo', 'Estar pendiente de todo', 'Nivel avanzado en ingles', 'Nivel avanzado en ingles', 'Nivel avanzado en ingles', 'dasfdsfasfasdfas', 'dasfdsfasfasdfas', 'dasfdsfasfasdfas', '2026-08-27 15:15:00', '2026-08-31', 'activa', 1),
(10, 1, 'Junior Web Developer', 'Junior Web Developer', 'Junior Web Developer', 'Technology', 'Hybrid', 'San Salvador', 'Full-time', 'Junior', 650.00, 'We are looking for a junior web developer with knowledge of HTML, CSS, JavaScript and basic PHP.', 'We are looking for a junior web developer with knowledge of HTML, CSS, JavaScript and basic PHP.', 'We are looking for a junior web developer with knowledge of HTML, CSS, JavaScript and basic PHP.', 'Support website development, fix bugs, and collaborate with the technology team.', 'Support website development, fix bugs, and collaborate with the technology team.', 'Support website development, fix bugs, and collaborate with the technology team.', 'Basic knowledge of HTML, CSS, JavaScript and PHP.', 'Basic knowledge of HTML, CSS, JavaScript and PHP.', 'Basic knowledge of HTML, CSS, JavaScript and PHP.', 'HTML|CSS|JavaScript|PHP', 'HTML|CSS|JavaScript|PHP', 'HTML|CSS|JavaScript|PHP', '2026-08-29 03:30:16', '2026-09-30', 'activa', 1),
(11, 9, 'Junior Graphic Designer', 'Junior Graphic Designer', 'Junior Graphic Designer', 'Technology', 'On-site', 'Santa Tecla, La Libertad, El Salvador', 'Full-time', 'Junior', 950.00, 'fgadfasdfads', 'fgadfasdfads', 'fgadfasdfads', 'fasfdsfsadfsa', 'fasfdsfsadfsa', 'fasfdsfsadfsa', 'dafdsfasfdsf', 'dafdsfasfdsf', 'dafdsfasfdsf', 'fadsfasdfadf', 'fadsfasdfadf', 'fadsfasdfadf', '2026-08-31 14:56:43', '2026-09-29', 'activa', 1),
(12, 11, 'CodeMaster', 'CodeMaster', 'CodeMaster', 'Technology', 'On-site', 'Santa Tecla, La Libertad, El Salvador', 'Part-time', 'Mid-level', 400.00, 'In this job you will have to create websites for our company CodeMaster', 'In this job you will have to create websites for our company CodeMaster', 'En este trabajo tendrás que crear sitios web para nuestra empresa CodeMaster', 'develop them professionally', 'develop them professionally', 'desarrollarlos profesionalmente', 'HTML,CSS,JS', 'HTML,CSS,JS', 'HTML,CSS,JS', 'HTML', 'HTML', 'HTML', '2026-09-04 15:27:52', '2026-12-24', 'activa', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id_postulacion`),
  ADD KEY `fk_postulaciones_usuarios` (`id_usuario`),
  ADD KEY `fk_postulaciones_vacantes` (`id_vacante`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `vacantes`
--
ALTER TABLE `vacantes`
  ADD PRIMARY KEY (`id_vacante`),
  ADD KEY `fk_vacantes_empresas` (`id_empresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id_postulacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `vacantes`
--
ALTER TABLE `vacantes`
  MODIFY `id_vacante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `fk_postulaciones_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postulaciones_vacantes` FOREIGN KEY (`id_vacante`) REFERENCES `vacantes` (`id_vacante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vacantes`
--
ALTER TABLE `vacantes`
  ADD CONSTRAINT `fk_vacantes_empresas` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
