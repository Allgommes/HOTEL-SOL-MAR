<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'email_config.php';

if (isLoggedIn()) {
    header("Location: reservas/index.php");
    exit();
}

$error = '';
$success = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();

    $nome = sanitizeInput($_POST['nome']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nome) || empty($email) || empty($password)) {
        $error = "Todos os campos são obrigatórios.";
    } elseif ($password !== $confirm_password) {
        $error = "As passwords não coincidem.";
    } elseif (strlen($password) < 6) {
        $error = "A password deve ter pelo menos 6 caracteres.";
    } else {
        // Verificar se email já existe
        $query = "SELECT id FROM funcionarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Este email já está registado.";
        } else {
            // Inserir novo funcionário
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO funcionarios (nome, email, senha, data_cadastro, ativo) 
                     VALUES (?, ?, ?, NOW(), 1)";
            $stmt = $db->prepare($query);

            if ($stmt->execute([$nome, $email, $hashed_password])) {
                $success = "Registo realizado com sucesso. Faça login.";
            } else {
                $error = "Erro ao registar. Tente novamente.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Registo de Funcionário</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registar</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php">Já tem conta? Faça login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>