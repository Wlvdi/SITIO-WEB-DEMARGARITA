<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "trabajo");
if ($conexion->connect_error) {
    echo json_encode(["exito" => false, "mensaje" => "Error de conexión"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';
    $precio = $_POST["precio"] ?? 0;
    $categoria = $_POST["categoria"] ?? '';

    if ($precio < 5000) {
        echo json_encode(["exito" => false, "mensaje" => "El precio debe ser al menos 5000"]);
        exit;
    }

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
        $directorio = "Imagenes/";
        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = $directorio . $nombreArchivo;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            // Guardar en la base de datos (incluye categoría si tu tabla lo tiene)
            $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, descripcion, imagen, categoria) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $nombre, $precio, $descripcion, $rutaDestino, $categoria);
            $stmt->execute();
            $stmt->close();

            echo json_encode([
                "exito" => true,
                "nombre" => $nombre,
                "descripcion" => $descripcion,
                "precio" => $precio,
                "imagen" => $rutaDestino,
                "categoria" => $categoria
            ]);
            exit;
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Error al mover la imagen"]);
            exit;
        }
    } else {
        echo json_encode(["exito" => false, "mensaje" => "No se recibió imagen válida"]);
        exit;
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit;
}
?>
