<?php 
    include('conexao.php');

    if(isset($_POST['nome']) || isset($_POST['email']) || isset($_POST['username']) || isset($_POST['password'])){
        $error = [];
        if(strlen($_POST['nome'])==0){
            echo("Preencha o nome");
        }else if (strlen($_POST['email'])==0){
            echo("Preencha o email");
        }else if (strlen($_POST['username'])==0){
            echo("Preencha o usuario");
        }else if (strlen($_POST['password'])==0){
            echo("Preencha a senha");
        }else if (strlen($_POST['password'] > 12)){
            $errors[] ="Senha maior que 12 caracteres!!!";
        }else{

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $user = $_POST['username'];
            $senha = $_POST['password'];

            $sql= "INSERT INTO cadastro (nome,email,senha,username) VALUES ('$nome','$email','$senha','$user')";
            $sql_query = $mysqli-> query($sql) or die ("Falha na execução". $mysqli->error);

             if ($sql_query  === TRUE) {
                header('Location: login.php');
            } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
 
        }


    }


?>





<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - RateHub</title>
    <link rel="stylesheet" href="../css/login.css">
    </style>
</head>
<body>
    <div class="top-bar">
        <img src="../img/icons8-infinito-96.png" alt="">
    </div>
    <div class="login-container">
        <h2>Cadastro</h2>
        <form action="cadastro.php" method="POST">
            <div class="input-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
          <div class="req">
                    <ul>
                        <li>Deve incluir entre <strong>8 e 12 caracteres.</strong></li>
                        <li>Inclui ao menos <strong>um letra maiúscula.</strong></li>
                        <li>Incluir ao menos <strong>um número.</strong></li>
                        <li>Não pode conter espaços.</li>
                    </ul>
                 <?php if (!empty($errors)) { 
                    foreach ($errors as $error) {
                        if (strpos($error, "Senha") !== false) {
                            echo "<div style='color: rgb(208, 51, 51);'>$error</div>"; // Exibir a mensagem de erro em vermelho
                        }
                    }
                } ?>
                </div>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>
