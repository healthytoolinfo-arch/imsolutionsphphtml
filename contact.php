<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Configuración básica
$SMTP_CONFIG = [
    'host' => 'smtp.hostinger.com',
    'port' => 587,
    'secure' => 'tls',
    'username' => 'hello@imsolutions.studio',
    'password' => 'TuContraseñaReal', // ACTUALIZAR
    'from' => 'hello@imsolutions.studio',
    'to' => 'hello@imsolutions.studio'
];

// 2. Cargar PHPMailer con archivos corregidos
require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';  // ¡Ahora con implementación real!

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);
    
    // 3. Validación
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        exit("❌ Todos los campos son requeridos");
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // 4. Configuración SMTP con archivos corregidos
        $mail->isSMTP();
        $mail->Host = $SMTP_CONFIG['host'];
        $mail->Port = $SMTP_CONFIG['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $SMTP_CONFIG['username'];
        $mail->Password = $SMTP_CONFIG['password'];
        $mail->SMTPSecure = $SMTP_CONFIG['secure'];
        
        // 5. Configuración del mensaje
        $mail->setFrom($SMTP_CONFIG['from'], 'Formulario Web');
        $mail->addAddress($SMTP_CONFIG['to']);
        $mail->addReplyTo($email, $name);
        $mail->Subject = "Nuevo mensaje de $name";
        $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
        
        // 6. Intento de envío
        if ($mail->send()) {
            echo "✅ Mensaje enviado correctamente";
        } else {
            error_log("Error SMTP: " . $mail->ErrorInfo);
            echo "❌ Error al enviar: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        error_log("Excepción: " . $e->getMessage());
        http_response_code(500);
        echo "❌ Error crítico: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
?>