<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$error = '';

// Buscar dados da reserva
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM reservas WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    header("Location: index.php");
    exit();
}

if ($_POST) {
    $cliente = sanitizeInput($_POST['cliente']);
    $quarto = sanitizeInput($_POST['quarto']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $estado = $_POST['estado'];

    // Validações
    if (empty($cliente) || empty($quarto) || empty($checkin) || empty($checkout)) {
        $error = "Todos os campos são obrigatórios.";
    } elseif ($checkout <= $checkin) {
        $error = "A data de check-out deve ser posterior à data de check-in.";
    } else {
        try {
            $query = "UPDATE reservas SET cliente = ?, quarto = ?, checkin = ?, checkout = ?, estado = ? 
                     WHERE id = ?";
            $stmt = $db->prepare($query);

            if ($stmt->execute([$cliente, $quarto, $checkin, $checkout, $estado, $id])) {
                header("Location: index.php?updated=1");
                exit();
            } else {
                $error = "Erro ao atualizar reserva. Tente novamente.";
            }
        } catch (PDOException $e) {
            $error = "Erro: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Editar Reserva</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Nome do Cliente *</label>
                                <input type="text" class="form-control" id="cliente" name="cliente"
                                    value="<?php echo htmlspecialchars($reserva['cliente']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quarto" class="form-label">Número do Quarto *</label>
                                <input type="number" class="form-control" id="quarto" name="quarto"
                                    value="<?php echo htmlspecialchars($reserva['quarto']); ?>" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkin" class="form-label">Data de Check-in *</label>
                                <input type="date" class="form-control" id="checkin" name="checkin"
                                    value="<?php echo $reserva['checkin']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkout" class="form-label">Data de Check-out *</label>
                                <input type="date" class="form-control" id="checkout" name="checkout"
                                    value="<?php echo $reserva['checkout']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="Ativa" <?php echo $reserva['estado'] == 'Ativa' ? 'selected' : ''; ?>>Ativa</option>
                            <option value="Cancelada" <?php echo $reserva['estado'] == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkin').addEventListener('change', function() {
        var checkoutField = document.getElementById('checkout');
        checkoutField.min = this.value;
    });
</script>

<?php include '../includes/footer.php'; ?>