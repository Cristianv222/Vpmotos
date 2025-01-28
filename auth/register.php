<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoVerificacion($email, $username, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Ajusta esto según tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_correo@gmail.com'; // Tu correo
        $mail->Password = 'tu_contraseña_de_aplicacion'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Remitente y destinatario
        $mail->setFrom('tu_correo@gmail.com', 'Nombre de tu Sistema');
        $mail->addAddress($email, $username);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta';
        
        // URL de verificación (ajusta la URL base según tu configuración)
        $verificationUrl = 'http://tudominio.com/auth/verify.php?token=' . $token;
        
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <h2>¡Bienvenido a ' . htmlspecialchars('Nombre de tu Sistema') . '!</h2>
                <p>Hola ' . htmlspecialchars($username) . ',</p>
                <p>Gracias por registrarte. Para completar tu registro, por favor verifica tu cuenta haciendo clic en el siguiente botón:</p>
                <p style="text-align: center;">
                    <a href="' . $verificationUrl . '" 
                       style="background-color: #4CAF50; color: white; padding: 12px 25px; 
                              text-decoration: none; border-radius: 3px; display: inline-block;">
                        Verificar cuenta
                    </a>
                </p>
                <p>O copia y pega el siguiente enlace en tu navegador:</p>
                <p>' . $verificationUrl . '</p>
                <p>Este enlace expirará en 24 horas.</p>
                <p>Si no creaste esta cuenta, puedes ignorar este mensaje.</p>
            </div>';

        $mail->AltBody = "Bienvenido a Nombre de tu Sistema!\n\n" .
                        "Para verificar tu cuenta, visita el siguiente enlace:\n" .
                        $verificationUrl;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo de verificación: {$mail->ErrorInfo}");
        return false;
    }
}

$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif (strlen($username) < 4) {
        $mensaje = "El nombre de usuario debe tener al menos 4 caracteres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Por favor, introduce un correo electrónico válido.";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        try {
            // Verificar si el correo ya está registrado
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $mensaje = "Este correo electrónico ya está registrado.";
            } else {
                // Generar token de verificación
                $token = bin2hex(random_bytes(32));
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, verification_token, token_expiration, verified) VALUES (?, ?, ?, ?, NOW() + INTERVAL 1 DAY, 0)");
                
                if($stmt->execute([$username, $email, $password_hash, $token])) {
                    // Intentar enviar el correo de verificación
                    if(enviarCorreoVerificacion($email, $username, $token)) {
                        $mensaje = "Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.";
                        // Redirigir al login después de 3 segundos
                        header("refresh:3;url=login.php");
                    } else {
                        $mensaje = "Registro exitoso, pero hubo un problema al enviar el correo de verificación.";
                    }
                } else {
                    $mensaje = "Error al registrar el usuario.";
                }
            }
        } catch(PDOException $e) {
            $mensaje = "Error al procesar la solicitud: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo strpos($mensaje, 'exitoso') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <h2>Registro de Usuario</h2>
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="username" required minlength="4" 
                       value="<?php echo htmlspecialchars($username ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" class="btn">Registrarse</button>
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </form>
    </div>
</body>
</html>