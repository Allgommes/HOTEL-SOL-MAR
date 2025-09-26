<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$error = '';
$success = '';

// Debug: Verificar se a sessão está correta
echo "<!-- Debug: funcionario_id = " . $_SESSION['funcionario_id'] . " -->";

if ($_POST) {
    $cliente = sanitizeInput($_POST['cliente']);
    $quarto = sanitizeInput($_POST['quarto']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Validações
    if (empty($cliente) || empty($quarto) || empty($checkin) || empty($checkout)) {
        $error = "Todos os campos são obrigatórios.";
    } elseif ($checkout <= $checkin) {
        $error = "A data de check-out deve ser posterior à data de check-in.";
    } else {
        try {
            // Verificar se o funcionário_id existe na tabela funcionarios
            $check_funcionario = "SELECT id FROM funcionarios WHERE id = ?";
            $stmt_check = $db->prepare($check_funcionario);
            $stmt_check->execute([$_SESSION['funcionario_id']]);

            if ($stmt_check->rowCount() == 0) {
                $error = "Erro: Funcionário não encontrado. Faça login novamente.";
            } else {
                $query = "INSERT INTO reservas (cliente, quarto, checkin, checkout, estado, funcionario_id) 
                         VALUES (?, ?, ?, ?, 'Ativa', ?)";
                $stmt = $db->prepare($query);

                if ($stmt->execute([$cliente, $quarto, $checkin, $checkout, $_SESSION['funcionario_id']])) {
                    header("Location: index.php?success=1");
                    exit();
                } else {
                    $error = "Erro ao criar reserva. Tente novamente.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erro: " . $e->getMessage();

            // Debug adicional
            error_log("Erro ao criar reserva: " . $e->getMessage());
            error_log("funcionario_id tentado: " . $_SESSION['funcionario_id']);
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Nova Reserva</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Debug info -->
                <div class="alert alert-info">
                    <small>Funcionário ID: <?php echo $_SESSION['funcionario_id']; ?> |
                        Nome: <?php echo $_SESSION['funcionario_nome']; ?></small>
                </div>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Nome do Cliente *</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quarto" class="form-label">Número do Quarto *</label>
                                <input type="number" class="form-control" id="quarto" name="quarto" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkin" class="form-label">Data de Check-in *</label>
                                <input type="date" class="form-control" id="checkin" name="checkin" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checkout" class="form-label">Data de Check-out *</label>
                                <input type="date" class="form-control" id="checkout" name="checkout" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkin').addEventListener('change', function() {
        var checkinDate = new Date(this.value);
        checkinDate.setDate(checkinDate.getDate() + 1);

        var checkoutField = document.getElementById('checkout');
        checkoutField.min = this.value;

        if (checkoutField.value && new Date(checkoutField.value) <= new Date(this.value)) {
            checkoutField.value = '';
        }
    });
</script>

<?php include '../includes/footer.php'; ?>