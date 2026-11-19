<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado']);
    exit;
}

require_once 'classes/Fornecedor.php';
require_once 'classes/Produto.php';

$tipo = $_POST['tipo'] ?? '';

if ($tipo === 'fornecedor') {
    $f = new Fornecedor();
    $ok = $f->atualizar($_POST['id'], $_POST['nome'], $_POST['cnpj'], $_POST['telefone']);
    echo json_encode(['sucesso' => $ok]);
} elseif ($tipo === 'produto') {
    $p = new Produto();
    $ok = $p->atualizar($_POST['id'], $_POST['nome'], $_POST['descricao'], $_POST['preco'], $_POST['fornecedor_id']);
    echo json_encode(['sucesso' => $ok]);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo inválido']);
}
