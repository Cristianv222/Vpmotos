<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener información del usuario de la sesión
$nombre = $_SESSION['nombre'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .nav-bar {
            background-color: #333;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-bar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
        }
        .nav-bar a:hover {
            background-color: #555;
            border-radius: 4px;
        }
        .logout-btn {
            background-color: #dc3545;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <div>
            <a href="dashboard.php">Inicio</a>
            <a href="#">Perfil</a>
            <a href="#">Configuración</a>
        </div>
        <form action="logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
        </div>

        <!-- Aquí puedes agregar más contenido del dashboard -->
        <div class="dashboard-content">
            <h2>Panel de Control</h2>
            <p>Este es tu panel de control. Aquí podrás gestionar todas tus actividades.</p>
        </div>
    </div>
</body>
</html>