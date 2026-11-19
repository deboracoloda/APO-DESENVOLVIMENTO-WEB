<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'classes/Produto.php';
$produtoObj = new Produto();

// Ações de limpar cesta ou remover item
if (isset($_GET['limpar'])) {
    $_SESSION['cesta'] = [];
    header("Location: cesta.php");
    exit;
}

if (isset($_GET['remover'])) {
    $idRemover = (int)$_GET['remover'];
    if (isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = array_values(array_diff($_SESSION['cesta'], [$idRemover]));
    }
    header("Location: cesta.php");
    exit;
}

$idsCesta = $_SESSION['cesta'] ?? [];
$itensCesta = $produtoObj->buscarPorIds($idsCesta);

$quantidadeTotal = count($itensCesta);
$valorTotal = 0;
foreach ($itensCesta as $item) {
    $valorTotal += (float)$item['preco'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cesta de Compras - Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h3 class="mb-4">Minha Cesta (Carrinho)</h3>

    <div class="row g-4">
        <!-- Lista de Produtos Selecionados -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Produtos Selecionados (1 unidade de cada)</div>
                <div class="card-body table-responsive">
                    <?php if (empty($itensCesta)): ?>
                        <p class="text-muted mb-0">Sua cesta está vazia. Vá até <a href="vitrine.php">Selecionar (Vitrine)</a> para escolher produtos.</p>
                    <?php else: ?>
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Fornecedor</th>
                                    <th>Valor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itensCesta as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>
                                    <td><?= htmlspecialchars($item['fornecedor_nome']) ?></td>
                                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                    <td class="text-end">
                                        <a href="cesta.php?remover=<?= $item['id'] ?>" class="btn btn-outline-danger btn-sm">Remover</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Card de Resumo -->
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">Resumo da Cesta</div>
                <div class="card-body">
                    <p class="fs-5">Quantidade de produtos: <strong><?= $quantidadeTotal ?></strong></p>
                    <p class="fs-4 text-success">Valor Total: <strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></p>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="vitrine.php" class="btn btn-outline-primary">Adicionar Mais Produtos</a>
                        <?php if ($quantidadeTotal > 0): ?>
                            <a href="cesta.php?limpar=1" class="btn btn-danger">Limpar Cesta</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
