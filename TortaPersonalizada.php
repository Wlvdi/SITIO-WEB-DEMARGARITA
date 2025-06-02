<?php
// Verifica si el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conexion = new mysqli("localhost", "root", "", "trabajo"); // Cambia "tu_base_de_datos"

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtener datos del formulario con validación básica
    $flavor = $_POST['flavor'] ?? '';
    $size = $_POST['size'] ?? '';
    $message = $_POST['message'] ?? '';
    $deliveryDate = $_POST['deliveryDate'] ?? '';
    $details = $_POST['details'] ?? '';

    // Verificar que los campos requeridos no estén vacíos
    if ($flavor && $size && $deliveryDate) {
        $stmt = $conexion->prepare("INSERT INTO tortapersonalizada (sabor, tamano, mensaje, fecha_entrega, detalles) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $flavor, $size, $message, $deliveryDate, $details);

        if ($stmt->execute()) {
            echo "¡Solicitud guardada con éxito!";
        } else {
            echo "Error al guardar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Faltan campos obligatorios.";
    }

    $conexion->close();

} else {
    echo "Acceso inválido.";
}
?>
