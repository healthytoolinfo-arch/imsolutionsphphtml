<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Mostrar errores PHP (para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // MODO DEBUG (muestra detalles de conexión)
        $mail->SMTPDebug = 2;  // Cambia a 0 en producción
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hello@imsolutions.studio';    // Tu correo Hostinger
        $mail->Password   = 'Ive+mariothebest1!';          // Contraseña real de ese correo
        $mail->SMTPSecure = 'tls';                         // CAMBIADO de 'ssl' a 'tls'
        $mail->Port       = 587;                           // CAMBIADO de 465 a 587
        $mail->CharSet    = 'UTF-8';

        // Datos del mensaje
        $mail->setFrom('hello@imsolutions.studio', 'Formulario Web');
        $mail->addAddress('hello@imsolutions.studio');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje del formulario de contacto';
        $mail->Body    = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";

        $mail->send();
        echo "✅ Mensaje enviado correctamente.";
    } catch (Exception $e) {
        echo "❌ Error al enviar: " . $mail->ErrorInfo;
    }
}
?>
