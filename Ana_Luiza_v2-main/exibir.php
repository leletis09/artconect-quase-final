<?php
$servername = "localhost"; // Altere se necessário
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$dbname = "teste"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recupera as imagens do banco de dados
$sql = "SELECT nome_arquivo, caminho FROM imagens";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exibir Imagens</title>
    <link rel="stylesheet" href="galeria.css">
    <!-- <style>
        img {
            max-width: 200px;
            margin: 10px;
        } -->
    </style>
</head>
<body>
    <h1>Galeria de Imagens</h1>
    <div>
        <?php
        if ($result->num_rows > 0) {
            // Saída de cada imagem
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row['nome_arquivo']) . "</h3>";
                echo "<img src='" . htmlspecialchars($row['caminho']) . "' alt='" . htmlspecialchars($row['nome_arquivo']) . "' />";
                echo "</div>";
            }
        } else {
            echo "Nenhuma imagem encontrada.";
        }
        ?>
    </div>
</body>
</html>