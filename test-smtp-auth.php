<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración
    $config = [
        'host' => 'smtp.hostinger.com',
        'port' => 587,
        'username' => 'hello@imsolutions.studio',
        'password' => 'TuContraseñaReal',
        'from' => 'hello@imsolutions.studio',
        'to' => 'hello@imsolutions.studio'
    ];

    // Sanitizar entradas
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = 'tls';
        
        $mail->setFrom($config['from'], 'Formulario Web');
        $mail->addAddress($config['to']);
        $mail->addReplyTo($email, $name);
        $mail->Subject = "Mensaje de $name";
        $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
        
        $mail->send();
        echo "✅ Mensaje enviado correctamente";
    } catch (Exception $e) {
        http_response_code(500);
        echo "❌ Error: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}