<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // ¡Añade esta línea!

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $config = [
        'host' => 'smtp.hostinger.com',
        'port' => 587,
        'username' => 'hello@imsolutions.studio',
        'password' => 'IveMarioTheBest1!',
        'from' => 'hello@imsolutions.studio',
        'to' => 'hello@imsolutions.studio',
        'charset' => 'UTF-8' // ¡Añadido!
    ];

    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    try {
        $mail = new PHPMailer(true);
        
        // CONFIGURACIÓN ESENCIAL
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usar constante
        $mail->CharSet = $config['charset']; // ¡Importante!
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Habilitar depuración
        
        // CONFIGURACIÓN DEL MENSAJE
        $mail->setFrom($config['from'], 'Formulario Web');
        $mail->addAddress($config['to']);
        $mail->addReplyTo($email, $name);
        $mail->Subject = "Mensaje de $name";
        $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
        $mail->isHTML(false); // Asegurar modo texto plano
        
        // INTENTO DE ENVÍO
        $mail->send();
        echo "✅ Mensaje enviado correctamente";
    } catch (Exception $e) {
        // REGISTRO DETALLADO DE ERRORES
        error_log("PHPMailer Error: " . $e->getMessage());
        error_log("SMTP Debug: " . $mail->ErrorInfo);
        
        http_response_code(500);
        echo "❌ Error: " . $e->getMessage();
        echo "<br>Detalles SMTP: " . $mail->ErrorInfo; // Mostrar detalles
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
?>