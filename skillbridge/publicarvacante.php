<?php
// Configuración de la base de datos (Ajusta con tus credenciales locales)
$host = "localhost";
$user = "root";
$password = "";
$database = "skillbridge_db"; // Cambia este nombre por el de tu BD real

$mensaje = "";
$clase_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura y limpieza de campos obligatorios
    $nombre_empresa = trim($_POST['nombre_empresa']);
    $titulo_vacante = trim($_POST['titulo_vacante']);
    $categoria      = trim($_POST['categoria']);
    $modalidad      = trim($_POST['modalidad']);
    $ubicacion      = trim($_POST['ubicacion']);
    $salario        = trim($_POST['salario']);
    $descripcion    = trim($_POST['descripcion']);
    $requisitos     = trim($_POST['requisitos']);
    $fecha_limite   = trim($_POST['fecha_limite']);

    // Validación básica en servidor de campos obligatorios
    if (empty($nombre_empresa) || empty($titulo_vacante) || empty($categoria) || empty($modalidad) || empty($ubicacion) || empty($descripcion) || empty($fecha_limite)) {
        $mensaje = "Por favor, completa todos los campos requeridos.";
        $clase_mensaje = "error";
    } else {
        // Conexión a la base de datos
        $conn = new mysqli($host, $user, $password, $database);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Preparar la consulta SQL para evitar inyecciones SQL
        $sql = "INSERT INTO vacantes (empresa, titulo, categoria, modalidad, ubicacion, salario, descripcion, requisitos, fecha_limite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $nombre_empresa, $titulo_vacante, $categoria, $modalidad, $ubicacion, $salario, $descripcion, $requisitos, $fecha_limite);

        if ($stmt->execute()) {
            $mensaje = "¡Vacante publicada con éxito!";
            $clase_mensaje = "success";
        } else {
            $mensaje = "Hubo un error al guardar la vacante: " . $conn->error;
            $clase_mensaje = "error";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vacante - SkillBridge</title>
    <link rel="stylesheet" href="publicarvacante.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">SkillBridge</div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="empleos.php">Buscar empleos</a>
            <a href="empresas.php">Empresas</a>
            <a href="#">Recursos</a>
            <a href="#">Accesibilidad</a>
        </nav>
    </header>

    <main class="container-form">
        <div class="form-card">
            <h2>Publicar Nueva Oportunidad Laboral</h2>
            <p class="subtitle">Ayúdanos a romper barreras. Completa el formulario para que cientos de talentos puedan postularse.</p>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $clase_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form action="publicar-vacante.php" method="POST" id="formVacante">
                <div class="form-group">
                    <label Improvement for="nombre_empresa">Nombre de la Empresa *</label>
                    <input type="text" name="nombre_empresa" id="nombre_empresa" placeholder="Ej. Innova Tech" required>
                </div>

                <div class="form-group">
                    <label for="titulo_vacante">Título de la Vacante *</label>
                    <input type="text" name="titulo_vacante" id="titulo_vacante" placeholder="Ej. Desarrollador Junior" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">Categoría *</label>
                        <select name="categoria" id="categoria" required>
                            <option value="">Selecciona una opción</option>
                            <option value="Tecnología">Tecnología y Sistemas</option>
                            <option value="Administración">Administración</option>
                            <option value="Atención al Cliente">Atención al Cliente</option>
                            <option value="Diseño">Diseño y Multimedia</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modalidad">Modalidad *</label>
                        <select name="modalidad" id="modalidad" required>
                            <option value="">Selecciona una opción</option>
                            <option value="Presencial">Presencial</option>
                            <option value="Remoto">Remoto</option>
                            <option value="Híbrido">Híbrido</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ubicacion">Ubicación *</label>
                        <input type="text" name="ubicacion" id="ubicacion" placeholder="Ej. San Salvador, El Salvador" required>
                    </div>

                    <div class="form-group">
                        <label for="salario">Salario (Opcional)</label>
                        <input type="text" name="salario" id="salario" placeholder="Ej. $600 - $800 o A convenir">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción de la Vacante *</label>
                    <textarea name="descripcion" id="descripcion" rows="5" placeholder="Detalla las funciones clave del puesto..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="requisitos">Requisitos y Aptitudes</label>
                    <textarea name="requisitos" id="requisitos" rows="4" placeholder="Ej. Conocimientos básicos en HTML/CSS, proactividad..."></textarea>
                </div>

                <div class="form-group">
                    <label for="fecha_limite">Fecha Límite de Postulación *</label>
                    <input type="date" name="fecha_limite" id="fecha_limite" required>
                </div>

                <button type="submit" class="btn-submit">Publicar Oferta</button>
            </form>
        </div>
    </main>

    <script src="js/publicar-vacante.js"></script>
</body>
</html>