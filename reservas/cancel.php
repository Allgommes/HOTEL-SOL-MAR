<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Verificar se a reserva existe
$query = "SELECT * FROM reservas WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);

if ($stmt->rowCount() == 1) {
    // Atualizar estado para Cancelada
    $update_query = "UPDATE reservas SET estado = 'Cancelada' WHERE id = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([$id]);
}

header("Location: index.php?canceled=1");
exit();
?>