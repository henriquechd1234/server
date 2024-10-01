<?php 

    include('conexao.php');
    if(!isset($_SESSION)){
        session_start();
       }
    
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>InfinityHUB</title>
        <link rel="stylesheet" href="../css/avaliacao.css">
        <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" a>
        
    </head>
    
    <body>
        <?php 
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
        
            $resultado = ("SELECT * FROM filmes WHERE id = '$id'");
            $query = $mysqli -> query($resultado);
        
        
            if ($row = $query -> fetch_assoc()){
                echo '<div class="movie">';
                echo '<img src="' . $row['caminho'] . '" alt="' . $row['filme'] . '" style="width:200px; height:auto;">';
                echo '<h2 style="color: white";>' . $row['filme'] . '</h2>';
                echo '</div>';
            }else{
                echo 'nenhum filme encontrado';
            }
        }else{
            echo "ID não encontrado";
        }
        
            
        ?>


    </body>
</html>