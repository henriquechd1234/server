<?php
    include('conexao.php');


    if (isset($_POST['username']) || isset($_POST['password'])){
        if(strlen($_POST['username'])==0){
            echo('Preencha o username');
        }else if (strlen($_POST['password'])==0){
            echo('Prencha a Senha');
        }else{
            $user = $_POST['username'];
            $senha = $_POST['password'];

            $sql = "SELECT * FROM cadastro WHERE username = '$user' and  senha ='$senha' ";
            $sql_query = $mysqli -> query($sql);

            $resultado = $sql_query -> num_rows;
            if($resultado == 1){
                $an = $sql_query -> fetch_assoc();
                if(!isset($_SESSION)){
                    session_start();
                }
                $_SESSION['id']= $an ['id'];
                $_SESSION['username']= $an ['id'];

                header("Location: ini.php");
            }
        }
    }

?>






<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RateHub</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="top-bar"> 
        <a href="ini.php"><img src="../img/icons8-infinito-96.png" alt="logoINFINTYHUB"></a>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>

</body>
</html>
