<?php
$conexion = new mysqli("localhost", "root", "", "trabajo");

$id = $_POST["id"] ?? 0;
$nombre = $_POST["nombre"] ?? '';
$descripcion = $_POST["descripcion"] ?? '';
$precio = $_POST["precio"] ?? 0;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
  $directorio = "Imagenes/";
  $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
  $rutaDestino = $directorio . $nombreArchivo;
  move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
  $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id_producto = ?");
  $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $rutaDestino, $id);
} else {
  $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id_producto = ?");
  $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id);
}

$stmt->execute();
echo "Producto actualizado correctamente";
