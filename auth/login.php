<?php
session_start();
require_once '../config/config.php';

// Si ya hay una sesión activa, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Verificar si el email existe y obtener los datos del usuario
        $stmt = $pdo->prepare("SELECT id, email, password, verified, nombre FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Verificar si la cuenta está verificada
            if ($usuario['verified'] == 1) {
                // Iniciar sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['nombre'] = $usuario['nombre'];
                
                // Actualizar último login
                $updateStmt = $pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?");
                $updateStmt->execute([$usuario['id']]);

                // Redirigir al dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $mensaje = "Por favor, verifica tu cuenta antes de iniciar sesión. Revisa tu correo electrónico.";
            }
        } else {
            $mensaje = "Email o contraseña incorrectos.";
        }

    } catch (PDOException $e) {
        $mensaje = "Error en el servidor. Por favor, intenta más tarde.";
        error_log("Error de login: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($mensaje)): ?>
            <div class="alert"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>