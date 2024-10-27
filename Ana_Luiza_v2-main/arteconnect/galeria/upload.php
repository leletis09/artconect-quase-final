<?php
session_start();
include("conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['tipo_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não logado.']);
    exit;
}

// Obtém o ID e tipo do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];  // 'artista', 'empresa' ou 'comum'

// Apenas artistas e empresas podem fazer uploads
if ($tipo_usuario !== 'artista' && $tipo_usuario !== 'empresa') {
    echo json_encode(['status' => 'error', 'message' => 'Você não tem permissão para fazer upload de obras.']);
    exit;
}

// Verifica se a descrição foi enviada
if (!isset($_POST['descricao']) || empty($_POST['descricao'])) {
    echo json_encode(['status' => 'error', 'message' => 'A descrição é obrigatória.']);
    exit;
}

$descricao = $_POST['descricao'];

// Verifica se o arquivo foi enviado
if (isset($_FILES['imagem'])) {
    $imagem = $_FILES['imagem'];
    $upload_dir = 'uploads/';
    
    // Verifica se o diretório existe, caso contrário, cria-o
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $imagem_url = $upload_dir . basename($imagem['name']);
    
    if (move_uploaded_file($imagem['tmp_name'], $imagem_url)) {
        // Insere o post no banco de dados
        $sql = "INSERT INTO posts (usuario_id, imagem_url, descricao, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("isss", $usuario_id, $imagem_url, $descricao, $tipo_usuario);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Obra enviada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar obra.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer upload do arquivo.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhuma imagem enviada.']);
}

$stmt->close();
$conexao->close();
?>