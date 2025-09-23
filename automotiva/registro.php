<?php
session_start();
include 'config.php';

$mensagem_erro = '';
$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senha_confirm = $_POST['senha_confirm'];
    $data_nascimento = !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $endereco = !empty($_POST['endereco']) ? $_POST['endereco'] : null;

    if ($senha !== $senha_confirm) {
        $mensagem_erro = 'As senhas não coincidem.';
    } else {
        try {
            $stmt_check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
            $stmt_check->execute([$email]);

            if ($stmt_check->fetch()) {
                $mensagem_erro = 'Este e-mail já está cadastrado.';
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql_insert = 'INSERT INTO usuarios (nome, email, senha, data_nascimento, endereco) VALUES (?, ?, ?, ?, ?)';
                $stmt_insert = $pdo->prepare($sql_insert);
                
                // Tenta executar a inserção
                if ($stmt_insert->execute([$nome, $email, $senha_hash, $data_nascimento, $endereco])) {
                    // Se deu certo, faz o login e redireciona
                    $id_usuario = $pdo->lastInsertId();
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['nome_usuario'] = $nome;
                    
                    header("Location: index.php");
                    exit();
                } else {
                    // SE FALHAR, PEGA O ERRO ESPECÍFICO DO BANCO
                    $errorInfo = $stmt_insert->errorInfo();
                    $mensagem_erro = "Não foi possível criar a conta. Erro do banco: " . ($errorInfo[2] ?? 'Erro desconhecido');
                }
            }
        } catch (PDOException $e) {
            // Este bloco pega outros erros de banco, como conexão
            $mensagem_erro = "Erro de banco de dados: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Automotiva</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">Automotiva</a>
            <nav class="nav">
                <button class="nav-toggle" aria-label="Abrir menu">
                    <span class="hamburger"></span>
                </button>
                <ul class="nav-menu">
                    <li><a href="index.php#servicos">Serviços</a></li>
                    <li><a href="index.php#produtos">Produtos</a></li>
                    <li><a href="index.php#sobre">Sobre Nós</a></li>
                    <li><a href="index.php#contato">Contato</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="carrinho.php" class="cart-icon"><img src="https://img.icons8.com/ios-glyphs/30/ffffff/shopping-cart.png" alt="Carrinho de Compras"/></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="form-section">
            <div class="form-wrapper">
                <h1>Criar Conta</h1>

                <?php if ($mensagem_erro): ?>
                    <div class="mensagem mensagem-erro"><?php echo $mensagem_erro; ?></div>
                <?php endif; ?>
                <?php if ($mensagem_sucesso): ?>
                    <div class="mensagem mensagem-sucesso"><?php echo $mensagem_sucesso; ?></div>
                <?php endif; ?>

                <form action="registro.php" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <div class="form-group">
                        <label for="senha_confirm">Confirmar Senha</label>
                        <input type="password" id="senha_confirm" name="senha_confirm" required>
                    </div>
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento (Opcional)</label>
                        <input type="date" id="data_nascimento" name="data_nascimento">
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço (Opcional)</label>
                        <input type="text" id="endereco" name="endereco" placeholder="Ex: Rua, Nº, Bairro, Cidade - Estado">
                    </div>
                    <button type="submit" class="cta-button">Registrar</button>
                </form>
                <div class="form-link">
                    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Automotiva. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>