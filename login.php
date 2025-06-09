<?php
session_start();

// Conexión
$conexion = new mysqli("localhost", "root", "", "trabajo");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica que se mandó el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Verifica que no estén vacíos
    if (empty($email) || empty($contrasena)) {
        die("Faltan datos.");
    }

    // Preparar y ejecutar consulta
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, contrasena, tipousuario FROM datos WHERE email = ?");
    
    if (!$stmt) {
        die("Error en la preparación: " . $conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica si se encontró el usuario
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $hash, $tipousuario);
        $stmt->fetch();

        if (password_verify($contrasena, $hash)) {
            $_SESSION['id_usuario'] = $id;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['tipousuario'] = $tipousuario; // <- GUARDAMOS EL ROL
            header("Location: Web.html");
            if ($_SESSION['tipousuario'] === 'admin') {
                header("Location: Web.php");
            } 
            else 
            {
                header("Location: Web.php"); // Asegúrate de que este sea un archivo PHP si usas sesiones
            }
            exit;
        } else {
            echo "❌ Contraseña incorrecta aaaa.";
        }
    } else {
        echo "❌ Usuario no encontrado.";
    }

    $stmt->close();
}

$conexion->close();
?>
