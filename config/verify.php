<?php
session_start();
require_once './config/config.php'; // Asegúrate de que este archivo contenga tu conexión a la base de datos

// Verificar si se recibió un token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die('Token no proporcionado.');
}

$token = $_GET['token'];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar el usuario con el token proporcionado
    $stmt = $pdo->prepare("SELECT id, email, verified FROM usuarios WHERE verification_token = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if ($usuario['verified'] == 1) {
            $mensaje = "Esta cuenta ya ha sido verificada anteriormente.";
            $tipo = "info";
        } else {
            // Actualizar el estado de verificación del usuario
            $updateStmt = $pdo->prepare("UPDATE usuarios SET verified = 1, verification_token = NULL WHERE id = ?");
            if ($updateStmt->execute([$usuario['id']])) {
                $mensaje = "¡Tu cuenta ha sido verificada exitosamente! Ahora puedes iniciar sesión.";
                $tipo = "success";
            } else {
                $mensaje = "Hubo un error al verificar tu cuenta. Por favor, intenta nuevamente.";
                $tipo = "error";
            }
        }
    } else {
        $mensaje = "Token de verificación inválido o expirado.";
        $tipo = "error";
    }

} catch (PDOException $e) {
    $mensaje = "Error en el servidor. Por favor, intenta más tarde.";
    $tipo = "error";
    error_log("Error de verificación: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que este archivo exista -->
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verificación de Cuenta</h1>
        <div class="message <?php echo $tipo; ?>">
            <?php echo $mensaje; ?>
        </div>
        <a href="../auth/login.php" class="btn">Ir al Login</a>
    </div>
</body>
</html>