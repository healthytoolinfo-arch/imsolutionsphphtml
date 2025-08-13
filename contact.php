<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración SMTP verificada
    $config = [
        'host' => 'smtp.hostinger.com',
        'port' => 587,
        'username' => 'hello@imsolutions.studio',
        'password' => 'IveMarioTheBest1!', // Contraseña que funciona
        'from' => 'hello@imsolutions.studio',
        'to' => 'hello@imsolutions.studio'
    ];

    // Procesar y validar datos del formulario
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    try {
        $mail = new PHPMailer(true);
        
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = 'tls'; // TLS
        
        // Configuración del mensaje
        $mail->setFrom($config['from'], 'Formulario Web');
        $mail->addAddress($config['to']);
        $mail->addReplyTo($email, $name);
        $mail->Subject = "Nuevo mensaje de $name";
        $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
        
        // Envío y confirmación
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
?>