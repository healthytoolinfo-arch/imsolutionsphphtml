<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Configuración SMTP
$config = [
    'host' => 'smtp.hostinger.com',
    'port' => 587,
    'username' => 'hello@imsolutions.studio',
    'password' => 'IveMarioTheBest1!',
    'from' => 'hello@imsolutions.studio',
    'to' => 'hello@imsolutions.studio',
    'charset' => 'UTF-8'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y sanitizar datos
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?? '';

    // Validación de campos
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor complete todos los campos correctamente";
        exit;
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet = $config['charset'];
        
        // Remitente y destinatario
        $mail->setFrom($config['from'], 'Formulario Web');
        $mail->addAddress($config['to']);
        $mail->addReplyTo($email, $name);
        
        // Contenido del mensaje
        $mail->Subject = "Nuevo mensaje de $name";
        $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
        $mail->isHTML(false);
        
        // Intento de envío
        if ($mail->send()) {
            echo "success";
        } else {
            throw new Exception("El servidor SMTP aceptó la conexión pero rechazó el envío del mensaje");
        }
    } catch (Exception $e) {
        // Registro detallado de errores
        error_log("PHPMailer Error: " . $e->getMessage());
        error_log("SMTP Debug: " . $mail->ErrorInfo);
        
        http_response_code(500);
        echo "Error al enviar el mensaje. Por favor intente más tarde.";
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}