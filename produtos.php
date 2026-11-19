<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'classes/Produto.php';
require_once 'classes/Fornecedor.php';

$produtoObj = new Produto();
$fornecedorObj = new Fornecedor();
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = str_replace(',', '.', $_POST['preco']);
    $fornecedor_id = $_POST['fornecedor_id'];

    if ($produtoObj->cadastrar($nome, $descricao, $preco, $fornecedor_id)) {
        $msg = "Produto cadastrado com sucesso!";
    }
}

$fornecedores = $fornecedorObj->listarTodos();
$produtos = $produtoObj->listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <div id="alertaAjax" class="alert alert-success d-none">Produto atualizado via AJAX com sucesso!</div>

    <?php if (empty($fornecedores)): ?>
        <div class="alert alert-warning">
            Cadastre pelo menos um <a href="fornecedores.php" class="alert-link">Fornecedor</a> antes de cadastrar produtos.
        </div>
    <?php endif; ?>

    <!-- Formulário de Cadastro -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Cadastrar Produto</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fornecedor</label>
                    <select name="fornecedor_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($fornecedores as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success" <?= empty($fornecedores) ? 'disabled' : '' ?>>Salvar Produto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Produtos com Edição AJAX -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Produtos Cadastrados (Edição via AJAX)</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço (R$)</th>
                        <th>Fornecedor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p): ?>
                    <tr id="prod-<?= $p['id'] ?>">
                        <td><?= $p['id'] ?></td>
                        <td><input type="text" class="form-control form-control-sm nome" value="<?= htmlspecialchars($p['nome']) ?>"></td>
                        <td><input type="text" class="form-control form-control-sm descricao" value="<?= htmlspecialchars($p['descricao']) ?>"></td>
                        <td><input type="number" step="0.01" class="form-control form-control-sm preco" value="<?= $p['preco'] ?>"></td>
                        <td>
                            <select class="form-select form-select-sm fornecedor_id">
                                <?php foreach ($fornecedores as $f): ?>
                                    <option value="<?= $f['id'] ?>" <?= $f['id'] == $p['fornecedor_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($f['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="atualizarProduto(<?= $p['id'] ?>)">Atualizar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function atualizarProduto(id) {
    const linha = document.getElementById('prod-' + id);
    const dados = new URLSearchParams();
    dados.append('tipo', 'produto');
    dados.append('id', id);
    dados.append('nome', linha.querySelector('.nome').value);
    dados.append('descricao', linha.querySelector('.descricao').value);
    dados.append('preco', linha.querySelector('.preco').value);
    dados.append('fornecedor_id', linha.querySelector('.fornecedor_id').value);

    fetch('ajax_atualizar.php', {
        method: 'POST',
        body: dados
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) {
            const alerta = document.getElementById('alertaAjax');
            alerta.classList.remove('d-none');
            setTimeout(() => alerta.classList.add('d-none'), 3000);
        } else {
            alert('Erro ao atualizar produto.');
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
