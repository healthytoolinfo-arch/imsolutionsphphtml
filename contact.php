<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Configuración de seguridad
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Solo permitir método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("❌ Método no permitido");
}

// Cargar PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';

// Configuración SMTP
$smtpConfig = [
    'host' => 'smtp.hostinger.com',
    'port' => 587,
    'secure' => 'tls',
    'username' => 'hello@imsolutions.studio',
    'password' => 'Ive+mariothebest1!', // ACTUALIZAR ESTA CONTRASEÑA
    'from' => 'hello@imsolutions.studio',
    'from_name' => 'Formulario Web',
    'to' => 'hello@imsolutions.studio'
];

// Sanitizar entradas
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? '';
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?? '';

// Validaciones
$errors = [];
if (empty($name)) $errors[] = "Nombre requerido";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido";
if (empty($message)) $errors[] = "Mensaje requerido";

if (!empty($errors)) {
    http_response_code(400);
    exit("❌ " . implode("\n", $errors));
}

try {
    $mail = new PHPMailer(true);
    
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = $smtpConfig['host'];
    $mail->Port = $smtpConfig['port'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtpConfig['username'];
    $mail->Password = $smtpConfig['password'];
    $mail->SMTPSecure = $smtpConfig['secure'];
    $mail->CharSet = 'UTF-8';
    
    // Habilitar depuración completa
    $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL; // Nivel máximo de depuración
    
    // Configurar remitente y destinatario
    $mail->setFrom($smtpConfig['from'], $smtpConfig['from_name']);
    $mail->addAddress($smtpConfig['to']);
    $mail->addReplyTo($email, $name);
    
    // Contenido del mensaje
    $mail->isHTML(false);
    $mail->Subject = "Nuevo mensaje de $name";
    $mail->Body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
    
    // Intento de envío
    $mail->send();
    
    // Si llegamos aquí, el envío fue exitoso
    echo "✅ Mensaje enviado correctamente";
} catch (Exception $e) {
    // Registrar error detallado
    $errorMsg = "Error PHPMailer: " . $e->getMessage();
    
    if (isset($mail) && !empty($mail->ErrorInfo)) {
        $errorMsg .= "\nDetalles SMTP: " . $mail->ErrorInfo;
    }
    
    error_log("[".date('Y-m-d H:i:s')."] $errorMsg");
    http_response_code(500);
    exit("❌ Error en el servidor: " . $e->getMessage());
}
?>