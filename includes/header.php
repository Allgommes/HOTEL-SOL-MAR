<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Sol&Mar - Sistema de Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Hotel Sol&Mar</a>
            <?php if (isset($_SESSION['funcionario_nome'])): ?>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">Olá, <?php echo $_SESSION['funcionario_nome']; ?></span>
                    <a class="nav-link" href="../logout.php">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mt-4">