<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once("modelo/Usuario.php");
require_once("modelo/AccesoDatos.php");

$oUsu = unserialize($_SESSION["usuario"]);
$idUsuario = $oUsu->getIdUsuario();

// Validar entrada
$descripcion = $_POST['descripcion'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0;
$estado = $_POST['estado'] ?? '';
$foto = $_FILES['foto'] ?? null;

if (empty($descripcion) || empty($cantidad) || empty($estado) || !$foto) {
    die("Todos los campos son obligatorios.");
}

// 1. Conectar a la base de datos
$oAD = new AccesoDatos();
if (!$oAD->conectar()) {
    die("Error de conexión a la base de datos.");
}
$pdo = $oAD->getPDO(); // Asegúrate de que AccesoDatos tenga un getPDO()

// 2. Insertar en donaciones
$fecha = date('Y-m-d');
$tipoDonacion = 'especie';
$estadoDonacion = 'pendiente';

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO donaciones (idUsuario, tipoDonacion, fecha, estado) VALUES (?, ?, ?, ?)");
    $stmt->execute([$idUsuario, $tipoDonacion, $fecha, $estadoDonacion]);
    $idDonacion = $pdo->lastInsertId();

    // 3. Guardar foto
    $nombreFoto = '';
    if ($foto && $foto['error'] == 0) {
        $directorio = "uploads/fotos/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $nombreFoto = "foto_" . time() . "_" . rand(1000, 9999) . "." . $extension;
        $rutaCompleta = $directorio . $nombreFoto;

        if (!move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {
            $pdo->rollBack();
            die("Error al guardar la foto.");
        }
    }

    // 4. Insertar en donacionesespecie
    $stmt2 = $pdo->prepare("INSERT INTO donacionesespecie (idDonacion, descripcion, cantidad, estado, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$idDonacion, $descripcion, $cantidad, $estado, $nombreFoto]);

    $pdo->commit();

    echo "<script>alert('Donación registrada exitosamente.'); window.location.href='donacionesUsuario.php';</script>";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error en la transacción: " . $e->getMessage());
}
?>
