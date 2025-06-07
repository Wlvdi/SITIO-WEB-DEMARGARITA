<?php
// Crear este archivo como "debug_productos.php" para probar la conexión y datos
session_start();

echo "<h1>Depuración de Productos</h1>";

// 1. Verificar conexión a la base de datos
echo "<h2>1. Probando conexión a la base de datos:</h2>";
try {
    $conexion = new mysqli("localhost", "root", "", "trabajo");
    
    if ($conexion->connect_error) {
        echo "❌ Error de conexión: " . $conexion->connect_error . "<br>";
    } else {
        echo "✅ Conexión exitosa a la base de datos 'trabajo'<br>";
    }
} catch (Exception $e) {
    echo "❌ Excepción en conexión: " . $e->getMessage() . "<br>";
}

// 2. Verificar si existe la tabla productos
echo "<h2>2. Verificando tabla 'productos':</h2>";
$checkTable = $conexion->query("SHOW TABLES LIKE 'productos'");
if ($checkTable->num_rows == 0) {
    echo "❌ La tabla 'productos' NO existe<br>";
    echo "Tablas disponibles:<br>";
    $tables = $conexion->query("SHOW TABLES");
    while ($table = $tables->fetch_array()) {
        echo "- " . $table[0] . "<br>";
    }
} else {
    echo "✅ La tabla 'productos' existe<br>";
    
    // 3. Verificar estructura de la tabla
    echo "<h3>Estructura de la tabla:</h3>";
    $estructura = $conexion->query("DESCRIBE productos");
    while ($campo = $estructura->fetch_assoc()) {
        echo "- " . $campo['Field'] . " (" . $campo['Type'] . ")<br>";
    }
}

// 4. Contar productos por categoría
echo "<h2>3. Contando productos:</h2>";
$totalProductos = $conexion->query("SELECT COUNT(*) as total FROM productos");
$total = $totalProductos->fetch_assoc();
echo "Total de productos: " . $total['total'] . "<br>";

$tortasCount = $conexion->query("SELECT COUNT(*) as total FROM productos WHERE categoria = 'torta'");
$tortas = $tortasCount->fetch_assoc();
echo "Productos de torta: " . $tortas['total'] . "<br>";

$coctelCount = $conexion->query("SELECT COUNT(*) as total FROM productos WHERE categoria = 'coctel'");
$coctel = $coctelCount->fetch_assoc();
echo "Productos de coctel: " . $coctel['total'] . "<br>";

// 5. Mostrar algunos productos de ejemplo
echo "<h2>4. Productos de torta (primeros 5):</h2>";
$productos = $conexion->query("SELECT * FROM productos WHERE categoria = 'torta' LIMIT 5");
if ($productos->num_rows > 0) {
    while ($producto = $productos->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<strong>ID:</strong> " . $producto['id_producto'] . "<br>";
        echo "<strong>Nombre:</strong> " . htmlspecialchars($producto['nombre']) . "<br>";
        echo "<strong>Descripción:</strong> " . htmlspecialchars($producto['descripcion']) . "<br>";
        echo "<strong>Precio:</strong> $" . number_format($producto['precio'], 0, ',', '.') . "<br>";
        echo "<strong>Imagen:</strong> " . htmlspecialchars($producto['imagen']) . "<br>";
        echo "<strong>Categoría:</strong> " . htmlspecialchars($producto['categoria']) . "<br>";
        echo "</div>";
    }
} else {
    echo "❌ No se encontraron productos de torta<br>";
}

// 6. Probar la función cargarProductos
echo "<h2>5. Probando función cargarProductos:</h2>";

function cargarProductos($categoria) {
    $productos = [];
    try {
        $conexion = new mysqli("localhost", "root", "", "trabajo");
        
        if ($conexion->connect_error) {
            echo "Error de conexión en función: " . $conexion->connect_error . "<br>";
            return $productos;
        }
        
        $checkTable = $conexion->query("SHOW TABLES LIKE 'productos'");
        if ($checkTable->num_rows == 0) {
            echo "La tabla 'productos' no existe en función<br>";
            $conexion->close();
            return $productos;
        }
        
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE categoria = ? ORDER BY precio DESC");
        
        if ($stmt === false) {
            echo "Error al preparar la consulta en función: " . $conexion->error . "<br>";
            $conexion->close();
            return $productos;
        }
        
        $stmt->bind_param("s", $categoria);
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta en función: " . $stmt->error . "<br>";
            $stmt->close();
            $conexion->close();
            return $productos;
        }
        
        $resultado = $stmt->get_result();
        
        while ($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }
        
        $stmt->close();
        $conexion->close();
        
    } catch (Exception $e) {
        echo "Error en función cargarProductos: " . $e->getMessage() . "<br>";
    }
    
    return $productos;
}

$productosTortas = cargarProductos('torta');
echo "Productos cargados con función: " . count($productosTortas) . "<br>";

if (!empty($productosTortas)) {
    echo "<h3>Primer producto cargado:</h3>";
    $primer = $productosTortas[0];
    echo "Nombre: " . htmlspecialchars($primer['nombre']) . "<br>";
    echo "Precio: $" . number_format($primer['precio'], 0, ',', '.') . "<br>";
} else {
    echo "❌ La función no devolvió productos<br>";
}

$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, h3 { color: #333; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <p><a href="Web.php">Volver a la página principal</a></p>
</body>
</html>