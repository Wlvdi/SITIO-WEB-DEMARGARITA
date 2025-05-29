<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "trabajo"); // Cambia "tu_base_de_datos"

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica si es un POST y vienen datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT); // Encripta la contraseña
    $comuna = $_POST["comuna"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];

    $sql = "INSERT INTO datos (nombre, email, contrasena, comuna, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("ssssss", $nombre, $email, $contrasena, $comuna, $direccion, $telefono);

    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No se recibieron datos válidos.";
}
$conexion->close();
?>