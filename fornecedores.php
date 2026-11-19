<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'classes/Fornecedor.php';
$fornecedorObj = new Fornecedor();
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cnpj = trim($_POST['cnpj']);
    $telefone = trim($_POST['telefone']);

    if ($fornecedorObj->cadastrar($nome, $cnpj, $telefone)) {
        $msg = "Fornecedor cadastrado com sucesso!";
    }
}

$fornecedores = $fornecedorObj->listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores - Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <div id="alertaAjax" class="alert alert-success d-none">Fornecedor atualizado via AJAX com sucesso!</div>

    <!-- Formulário de Cadastro -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Cadastrar Fornecedor</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nome / Razão Social</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Salvar Fornecedor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Fornecedores com Edição AJAX -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Fornecedores Cadastrados (Edição via AJAX)</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornecedores as $f): ?>
                    <tr id="linha-<?= $f['id'] ?>">
                        <td><?= $f['id'] ?></td>
                        <td><input type="text" class="form-control form-control-sm nome" value="<?= htmlspecialchars($f['nome']) ?>"></td>
                        <td><input type="text" class="form-control form-control-sm cnpj" value="<?= htmlspecialchars($f['cnpj']) ?>"></td>
                        <td><input type="text" class="form-control form-control-sm telefone" value="<?= htmlspecialchars($f['telefone']) ?>"></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="atualizarFornecedor(<?= $f['id'] ?>)">Atualizar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function atualizarFornecedor(id) {
    const linha = document.getElementById('linha-' + id);
    const dados = new URLSearchParams();
    dados.append('tipo', 'fornecedor');
    dados.append('id', id);
    dados.append('nome', linha.querySelector('.nome').value);
    dados.append('cnpj', linha.querySelector('.cnpj').value);
    dados.append('telefone', linha.querySelector('.telefone').value);

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
            alert('Erro ao atualizar fornecedor.');
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
