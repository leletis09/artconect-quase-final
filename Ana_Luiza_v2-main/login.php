<?php
// Inicia a sessão
session_start();

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
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Verifica nas diferentes tabelas se o usuário existe
    $tables = ['usuarios', 'artista', 'usuario_comun', 'empresa'];
    $senha_hash = null;

    foreach ($tables as $table) {
        $sql = "SELECT senha FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Se o usuário for encontrado, pega a senha
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($senha_hash);
            $stmt->fetch();
            break; // Sai do loop se encontrar o usuário
        }
        $stmt->close();
    }

    // Verifica se a senha está correta
    if ($senha_hash !== null && password_verify($senha, $senha_hash)) {
        // Armazena informações do usuário na sessão
        $_SESSION['email'] = $email; // Armazena o email do usuário
        header("Location: index2.html"); // Redireciona para a página de sucesso (ex: dashboard)
        exit();
    } else {
        $erro = "Email ou senha incorretos.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <title>Login ArtConnect</title>
</head>
<body>
    <main class="container">
        <form action="" method="post">
            <h1>Login ArtConnect</h1>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required>
            </div>
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            <button type="submit" class="login">Entrar</button>
            <p>Não tem uma conta? <a href="opcoes.html">Cadastre-se</a></p>
        </form>
    </main>
</body>
</html>