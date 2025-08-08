<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye las clases de PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Hostinger (SSL)
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hello@imsolutions.studio'; // Tu email completo
        $mail->Password   = 'Ive+mariothebest1!';        // Sustituir aquí
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Configurar remitente y destinatario
        $mail->setFrom('hello@imsolutions.studio', 'Formulario Web');
        $mail->addAddress('hello@imsolutions.studio'); // O cualquier destinatario

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje del formulario de contacto';
        $mail->Body    = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";

        // Enviar correo
        $mail->send();
        echo "Mensaje enviado correctamente.";
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>