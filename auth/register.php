<?php
session_start();
require_once '../config/config.php'; // Esto ahora debería funcionar porque $pdo está definido en config.php

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
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, password_reset_token, token_expiration) VALUES (?, ?, ?, ?, NOW() + INTERVAL 1 DAY)");
                
                if($stmt->execute([$username, $email, $password_hash, $token])) {
                    $mensaje = "Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.";
                    // Aquí se podría agregar el código para enviar el correo de verificación
                    // enviarCorreoVerificacion($email, $token);
                    
                    // Redirigir al login después de 3 segundos
                    header("refresh:3;url=login.php");
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