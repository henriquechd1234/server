<?php 

    include('conexao.php');

    if ( $_SERVER['REQUEST_METHOD']=='POST'){
        $filme = $_POST['filme'];
        $descricao = $_POST['descricao'];

        $diretorio = 'img/';
        $imagem = $diretorio . basename($_FILES['imagem']['name']);

        $sql_code = "INSERT INTO imagens (filme, descricao, caminho) VALUE ('$filme', '$descricao', '$imagem')";

        $sql_query = $mysqli -> query($sql_code);

        if($sql_query == TRUE){
            echo("Imagen enviada com sucesso");
        }else{
            echo('Falha ao enviar imagem'. $mysqli ->error);
        }
           
       
    }


?>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form  method="POST" enctype="multipart/form-data">
    <label for="filme">Nome do Filme:</label>
    <input type="text" name="filme" required>
    
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" required></textarea>
    
    <label for="imagem">Selecione a Imagem:</label>
    <input type="file" name="imagem" accept="image/*" required>
    
    <button type="submit">Enviar</button>
</form>

</body>
</html>

