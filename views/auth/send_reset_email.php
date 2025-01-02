<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        echo '<script>alert("Solo se permiten correos de Gmail."); window.history.back();</script>';
        exit;
    }

    $query = "SELECT * FROM alumnos WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if ($result) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $update = "UPDATE alumnos SET reset_token = ?, reset_expiry = ? WHERE email = ?";
        $stmt = $pdo->prepare($update);
        $stmt->execute([$token, $expiry, $email]);

        $mail = new PHPMailer(true);

        try {
    

    // Agregar imagen incrustada
    $mail->addEmbeddedImage('../../public/images/equipo.png', 'equipo', 'equipo.png');
    $mail->addEmbeddedImage('../../public/images/logov2.png', 'java', 'logov2.png');

     $mail->isSMTP();
     $mail->Host = 'smtp.gmail.com';
     $mail->SMTPAuth = true;
     $mail->Username = 'noreply.javaacademy@gmail.com';
     $mail->Password = 'xrwn lnlu kshj zdqz';
     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
     $mail->Port = 587;
     $mail->setFrom('noreply.javaacademy@gmail.com', 'Recuperacion de Contrasena');
     $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Restablecimiento de Contraseña';
    $mail->Body    = '<html>
                        <body>
                            <p>Estimado usuario,</p>
                            <p>Recibimos una solicitud para restablecer la contraseña de su cuenta.</p>
                            <p>Para restablecer su contraseña, haga clic en el siguiente enlace:</p>
                            <p><a href="http://localhost/JavaAcademyCertificado2/views/auth/reset_password.php?token=' . $token . '">Restablecer Contraseña</a></p>
                            <p>Este enlace caducará en 15 minutos.</p>
                            <p>Si no solicitó este cambio, puede ignorar este correo electrónico.</p>
                            <p>Saludos cordiales,</p>
                            <p>El equipo de desarrollo Code Crafters...de JavaAcademy</p>
                            <p><img src="cid:equipo" alt="Logo de JavaAcademy" style="max-width: 300px;"></p>
			    <p><img src="cid:java" alt="Logo de JavaAcademy" style="max-width: 300px;"></p>
                        </body>
                    </html>';

    $mail->send();


            echo '<script>alert("Ingrese a su cuenta de Gmail para el cambio de contraseña."); window.location.href = "login.html";</script>';
        } catch (Exception $e) {
            echo '<script>alert("No se pudo enviar el correo. Error: ' . $mail->ErrorInfo . '"); window.history.back();</script>';
        }
    } else {
        echo '<script>alert("Correo no encontrado."); window.history.back();</script>';
    }
}
?>
