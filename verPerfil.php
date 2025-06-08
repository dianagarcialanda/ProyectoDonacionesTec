<?php
include_once("AccesoDatos.php");

if (!isset($_GET['id'])) {
    echo "<p>Usuario no especificado.</p>";
    exit;
}

$id = $_GET['id'];

$pdo = AccesoDatos::obtenerInstancia();

$sql = "SELECT nombre, correo, telefono, rol FROM usuarios WHERE idUsuario = :idUsuario";
$stmt = $pdo->prepare($sql);
$stmt->execute([':idUsuario' => $id]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo '<div style="padding: 20px;">';
    echo "<p><strong>Nombre:</strong><br>" . htmlspecialchars($usuario['nombre']) . "</p>";
    echo "<p><strong>Correo:</strong><br>" . htmlspecialchars($usuario['correo']) . "</p>";
    echo "<p><strong>Teléfono:</strong><br>" . htmlspecialchars($usuario['telefono']) . "</p>";
    echo "<p><strong>Rol:</strong><br>" . htmlspecialchars($usuario['rol']) . "</p>";
    echo '</div>';
} else {
    echo "<p>Usuario no encontrado.</p>";
}
?>

