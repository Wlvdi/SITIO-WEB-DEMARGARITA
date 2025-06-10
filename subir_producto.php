<?php
// *** VERSIÓN COMPLETAMENTE LIMPIA - SIN OUTPUT EXTRA ***

// Configuración inicial - sin output
ini_set('display_errors', 0);
error_reporting(0);

// Iniciar buffer limpio
ob_start();

// Iniciar sesión
session_start();

// Función para enviar respuesta JSON limpia
function enviarRespuesta($data) {
    // Limpiar cualquier output previo
    if (ob_get_level()) {
        ob_clean();
    }
    
    // Headers limpios
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    
    // Enviar JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
    // Terminar script limpiamente
    exit;
}

// Verificar permisos de admin
if (!isset($_SESSION['tipousuario']) || $_SESSION['tipousuario'] !== 'admin') {
    enviarRespuesta([
        "exito" => false, 
        "mensaje" => "No tienes permisos para realizar esta acción"
    ]);
}

// Verificar método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    enviarRespuesta([
        "exito" => false, 
        "mensaje" => "Método no permitido"
    ]);
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "trabajo");
if ($conexion->connect_error) {
    enviarRespuesta([
        "exito" => false, 
        "mensaje" => "Error de conexión a la base de datos"
    ]);
}

try {
    // Obtener y validar datos
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $precio = intval($_POST["precio"] ?? 0);
    $categoria = trim($_POST["categoria"] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($descripcion) || empty($categoria)) {
        throw new Exception("Todos los campos son obligatorios");
    }

    if ($precio < 1000) {
        throw new Exception("El precio debe ser al menos $1.000");
    }

    // Validar categoría
    if (!in_array($categoria, ['torta', 'coctel'])) {
        throw new Exception("Categoría no válida");
    }

    // Validar imagen
    if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Error al subir la imagen");
    }

    $imagen = $_FILES["imagen"];
    
    // Validar tipo de archivo
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($imagen["type"], $tiposPermitidos)) {
        throw new Exception("Solo se permiten imágenes JPG, PNG o GIF");
    }

    // Validar tamaño (máximo 5MB)
    if ($imagen["size"] > 5 * 1024 * 1024) {
        throw new Exception("La imagen es demasiado grande (máximo 5MB)");
    }

    // Crear directorio si no existe
    $directorio = "Imagenes/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }

    // Generar nombre único
    $extension = pathinfo($imagen["name"], PATHINFO_EXTENSION);
    $nombreArchivo = time() . "_" . uniqid() . "." . $extension;
    $rutaDestino = $directorio . $nombreArchivo;

    // Mover archivo
    if (!move_uploaded_file($imagen["tmp_name"], $rutaDestino)) {
        throw new Exception("Error al guardar la imagen");
    }

    // Insertar en base de datos
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, descripcion, imagen, categoria) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Error en la consulta SQL");
    }

    $stmt->bind_param("sisss", $nombre, $precio, $descripcion, $rutaDestino, $categoria);
    
    if ($stmt->execute()) {
        $producto_id = $conexion->insert_id;
        $stmt->close();
        $conexion->close();
        
        // Respuesta exitosa
        enviarRespuesta([
            "exito" => true,
            "mensaje" => "Producto agregado exitosamente",
            "id" => $producto_id,
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "imagen" => $rutaDestino,
            "categoria" => $categoria
        ]);
    } else {
        throw new Exception("Error al guardar en la base de datos");
    }
    
} catch (Exception $e) {
    // Cerrar conexiones
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
    
    // Respuesta de error
    enviarRespuesta([
        "exito" => false, 
        "mensaje" => $e->getMessage()
    ]);
}
?>