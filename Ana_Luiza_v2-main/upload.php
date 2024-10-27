<?php
// Conexão com o banco de dados
$servername = "localhost"; // Altere se necessário
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$dbname = "teste"; // Altere para o nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/"; // Pasta onde as imagens serão salvas
    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verifica o tipo de arquivo
    if ($imageFileType != "jpg" && $imageFileType != "png") {
        echo "Desculpe, somente arquivos JPG e PNG são permitidos.";
        exit;
    }

    // Move o arquivo para a pasta de uploads
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        // Salva informações no banco de dados
        $sql = "INSERT INTO imagens (nome_arquivo, caminho) VALUES ('" . basename($_FILES["imagem"]["name"]) . "', '$target_file')";
        
        if ($conn->query($sql) === TRUE) {
            echo "A imagem foi carregada e registrada com sucesso.";
        } else {
            echo "Erro ao registrar a imagem: " . $conn->error;
        }
    } else {
        echo "Desculpe, ocorreu um erro ao fazer o upload da sua imagem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Upload de Imagem</title>
</head>
<body>
    <h1>Upload de Imagem</h1>
    <form action="" method="post" enctype="multipart/form-data">
        Selecione a imagem para upload:
        <input type="file" name="imagem" required>
        <input type="submit" value="Upload Imagem">
    </form>
</body>
</html