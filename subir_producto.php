<?php
// *** LIMPIAR BUFFER DE SALIDA PARA EVITAR CARACTERES EXTRA ***
ob_start(); // ✅ Usar ob_start() en lugar de ob_clean()

// Activar reporte de errores para debugging (comentar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Cambiar a 0 para evitar output extra
ini_set('log_errors', 1); // ✅ Habilitar log de errores

// Iniciar sesión para verificar permisos de admin
session_start();

// Verificar que el usuario sea admin
if (!isset($_SESSION['tipousuario']) || $_SESSION['tipousuario'] !== 'admin') {
    ob_clean(); // ✅ Limpiar buffer antes de enviar respuesta
    header('Content-Type: application/json');
    echo json_encode(["exito" => false, "mensaje" => "No tienes permisos para realizar esta acción"]);
    exit;
}

// Establecer header JSON al inicio
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "trabajo");
if ($conexion->connect_error) {
    ob_clean(); // ✅ Limpiar buffer
    echo json_encode(["exito" => false, "mensaje" => "Error de conexión: " . $conexion->connect_error]);
    exit;
}

// ✅ Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_clean();
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit;
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

    // Validar que la categoría sea válida
    if (!in_array($categoria, ['torta', 'coctel'])) {
        throw new Exception("Categoría no válida: " . $categoria);
    }

    // Manejo de la imagen
    if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        $error_code = $_FILES["imagen"]["error"] ?? "No file";
        throw new Exception("Error al subir la imagen. Código: " . $error_code);
    }

    $imagen = $_FILES["imagen"];
    
    // Validar tipo de archivo
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($imagen["type"], $tiposPermitidos)) {
        throw new Exception("Solo se permiten imágenes (JPG, PNG, GIF). Recibido: " . $imagen["type"]);
    }

    // Validar tamaño (máximo 5MB)
    if ($imagen["size"] > 5 * 1024 * 1024) {
        throw new Exception("La imagen es demasiado grande (máximo 5MB)");
    }

    // Crear directorio si no existe
    $directorio = "Imagenes/";
    if (!is_dir($directorio)) {
        if (!mkdir($directorio, 0777, true)) {
            throw new Exception("Error al crear directorio de imágenes");
        }
    }

    // Generar nombre único para la imagen
    $extension = pathinfo($imagen["name"], PATHINFO_EXTENSION);
    $nombreArchivo = time() . "_" . uniqid() . "." . $extension;
    $rutaDestino = $directorio . $nombreArchivo;

    // Mover archivo subido
    if (!move_uploaded_file($imagen["tmp_name"], $rutaDestino)) {
        throw new Exception("Error al guardar la imagen en: " . $rutaDestino);
    }

    // Insertar en base de datos
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, descripcion, imagen, categoria) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Error en la consulta: " . $conexion->error);
    }

    $stmt->bind_param("sisss", $nombre, $precio, $descripcion, $rutaDestino, $categoria);
    
    if ($stmt->execute()) {
        $producto_id = $conexion->insert_id;
        $stmt->close();
        $conexion->close();
        
        // ✅ Limpiar buffer antes de enviar respuesta exitosa
        ob_clean();
        
        $success = [
            "exito" => true,
            "mensaje" => "Producto agregado exitosamente",
            "id" => $producto_id,
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "imagen" => $rutaDestino,
            "categoria" => $categoria
        ];
        
        echo json_encode($success);
    } else {
        throw new Exception("Error al guardar en la base de datos: " . $stmt->error);
    }
    
} catch (Exception $e) {
    // ✅ Manejo centralizado de errores
    ob_clean(); // Limpiar buffer
    
    // Cerrar conexiones si están abiertas
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conexion)) {
        $conexion->close();
    }
    
    // Enviar respuesta de error
    echo json_encode([
        "exito" => false, 
        "mensaje" => $e->getMessage(),
        "debug" => [
            "archivo" => $e->getFile(),
            "linea" => $e->getLine()
        ]
    ]);
}

// *** LIMPIAR BUFFER Y TERMINAR LIMPIAMENTE ***
ob_end_flush();
?>