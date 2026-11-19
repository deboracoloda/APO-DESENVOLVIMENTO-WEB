<?php
session_start();
require_once 'classes/Usuario.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: produtos.php");
    exit;
}

$msg = "";
$tipoMsg = "danger";
$usuarioObj = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'cadastrar') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = $_POST['senha'];

        if ($usuarioObj->cadastrar($nome, $email, $senha)) {
            $msg = "Cadastro realizado com sucesso! Faça login.";
            $tipoMsg = "success";
        } else {
            $msg = "Erro: Este e-mail já está cadastrado.";
        }
    } elseif ($acao === 'login') {
        $email = trim($_POST['email']);
        $senha = $_POST['senha'];
        $dados = $usuarioObj->autenticar($email, $senha);

        if ($dados) {
            $_SESSION['usuario_id'] = $dados['id'];
            $_SESSION['usuario_nome'] = $dados['nome'];
            header("Location: produtos.php");
            exit;
        } else {
            $msg = "E-mail ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acesso - Sistema de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="text-center mb-4">Mini Sistema de Gestão de Produtos</h2>
    <?php if ($msg): ?>
        <div class="alert alert-<?= $tipoMsg ?> text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">
        <!-- Formulário de Login -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Entrar no Sistema</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="login">
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulário de Cadastro -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Criar Nova Conta</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="cadastrar">
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
