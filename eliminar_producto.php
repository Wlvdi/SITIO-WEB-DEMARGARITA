<?php
$conexion = new mysqli("localhost", "root", "", "trabajo");
$id = $_POST["id"] ?? 0;
$stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
echo "Producto eliminado correctamente";

if ($ok) {
    echo "Producto eliminado correctamente";
} else {
    echo "Error al eliminar producto";
}