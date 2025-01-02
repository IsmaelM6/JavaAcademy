<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Verificar si el usuario está autenticado y es del tipo correcto
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header('Location: ../auth/login.html');
    exit;
}

// Obtener el nombre del usuario y convertirlo a mayúsculas
$userName = strtoupper(htmlspecialchars($_SESSION['user_name']));

// Ruta de la imagen del certificado
$certificateImage = __DIR__ . '/../../public/images/certificado.png';

// Verificar si la función imagecreatefrompng existe
if (!function_exists('imagecreatefrompng')) {
    die("Error: La función imagecreatefrompng no está disponible. Asegúrate de que la extensión GD esté habilitada en tu servidor.");
}

// Crear la imagen desde el archivo
$image = @imagecreatefrompng($certificateImage);

// Verificar si se pudo cargar la imagen
if (!$image) {
    die("Error: No se pudo cargar la imagen del certificado. Verifica la ruta y el archivo de la imagen.");
}

// Colores
$black = imagecolorallocate($image, 0, 0, 0);

// Ruta a la fuente de letra
$fontPath = __DIR__ . '/../../public/fonts/arial.ttf';
$realFontPath = realpath($fontPath);

// Verificar si la fuente existe
if (!file_exists($realFontPath)) {
    die("Error: No se pudo encontrar la fuente especificada en la ruta: " . $realFontPath);
}

// Tamaño de la fuente (modificar para ajustar el tamaño del texto)
$fontSize = 40;

// Coordenadas para la inserción del texto (modificar para ajustar la posición del texto)
$y = 600; // Coordenada Y (vertical), ajustar según sea necesario

// Obtener el ancho del texto
$boundingBox = imagettfbbox($fontSize, 0, $realFontPath, $userName);
$textWidth = $boundingBox[2] - $boundingBox[0];
$x = (imagesx($image) - $textWidth) / 2; // Centrar el texto horizontalmente

// Añadir el nombre del usuario en la imagen
imagettftext($image, $fontSize, 0, $x, $y, $black, $realFontPath, $userName);

// Guardar la imagen en un archivo temporal para mostrarla en el navegador
$tempImagePath = __DIR__ . '/../../public/images/temp_certificado.png';
imagepng($image, $tempImagePath);

// Liberar memoria
imagedestroy($image);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - Java Academy</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .certificate-container {
            text-align: center;
            margin-top: 20px;
        }
        .certificate-image {
            max-width: 100%;
            height: auto;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container .btn {
            margin: 0 40px; /* Espaciado entre botones */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="certificate-container">
            <img src="../../public/images/temp_certificado.png" alt="Certificado" class="certificate-image">
            <div class="button-container d-flex justify-content-center">
                <a href="../../public/images/temp_certificado.png" download="certificado.png" class="btn btn-primary">Descargar Certificado</a>
                <a href="index.php" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</body>
</html>
