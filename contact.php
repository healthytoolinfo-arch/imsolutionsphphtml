<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye las clases de PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y sanitizar datos de entrada
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validaciones básicas
    if (empty($name) || empty($email) || empty($message)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: El email no tiene un formato válido.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Hostinger (SSL)
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hello@imsolutions.studio';
        // La contraseña debería estar en una variable de entorno
        $mail->Password   = $_ENV['SMTP_PASSWORD'] ?? 'Ive+mariothebest1!';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        // Configurar remitente y destinatario
        $mail->setFrom('hello@imsolutions.studio', 'Formulario Web');
        $mail->addAddress('hello@imsolutions.studio');
        $mail->addReplyTo($email, $name);

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje del formulario de contacto - ' . $name;
        $mail->Body    = "Nuevo mensaje recibido desde el formulario de contacto:\n\n" .
                        "Nombre: $name\n" .
                        "Correo: $email\n\n" .
                        "Mensaje:\n$message\n\n" .
                        "---\n" .
                        "Enviado desde: " . $_SERVER['HTTP_HOST'] . "\n" .
                        "Fecha: " . date('Y-m-d H:i:s');

        // Enviar correo
        $mail->send();
        echo "Mensaje enviado correctamente.";
    } catch (Exception $e) {
        error_log("Error PHPMailer: " . $mail->ErrorInfo);
        echo "Error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.";
    }
} else {
    echo "Error: Método no permitido.";
}
?>
