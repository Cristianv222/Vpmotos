<?php
session_start();
require_once './config/config.php'; // Incluimos el archivo de conexión

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
            
            try {
                $stmt->execute([$username, $email, $password_hash, $token]);
                $mensaje = "Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.";
                
                // Aquí se podría agregar el código para enviar el correo de verificación
                // enviarCorreoVerificacion($email, $token);
                
                // Redirigir al login después de 3 segundos
                header("refresh:3;url=login.php");
            } catch(PDOException $e) {
                $mensaje = "Error al registrar el usuario: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registro de Usuario</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert <?php echo strpos($mensaje, 'exitoso') !== false ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>