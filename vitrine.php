<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'classes/Produto.php';
$produtoObj = new Produto();
$produtos = $produtoObj->listarTodos();
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selecionados = $_POST['produtos'] ?? [];

    // Validação no servidor (exigência da APO)
    if (empty($selecionados)) {
        $erro = "Selecione pelo menos um produto para adicionar à cesta!";
    } else {
        if (!isset($_SESSION['cesta'])) {
            $_SESSION['cesta'] = [];
        }
        // Considera 1 unidade única de cada produto selecionado
        foreach ($selecionados as $idProd) {
            if (!in_array($idProd, $_SESSION['cesta'])) {
                $_SESSION['cesta'][] = (int)$idProd;
            }
        }
        header("Location: cesta.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Selecionar Produtos - Vitrine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h3 class="mb-3">Selecione os Produtos para a Cesta</h3>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validarSelecao()">
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">Selecionar</th>
                            <th>Produto</th>
                            <th>Descrição</th>
                            <th>Fornecedor</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input check-produto" type="checkbox" name="produtos[]" value="<?= $p['id'] ?>">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['descricao']) ?></td>
                            <td><?= htmlspecialchars($p['fornecedor_nome']) ?></td>
                            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($produtos)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhum produto cadastrado ainda.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Adicionar Selecionados à Cesta</button>
            </div>
        </div>
    </form>
</div>

<script>
// Validação no navegador (JavaScript)
function validarSelecao() {
    const marcados = document.querySelectorAll('.check-produto:checked');
    if (marcados.length === 0) {
        alert('Por favor, marque pelo menos um produto antes de adicionar à cesta.');
        return false;
    }
    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
