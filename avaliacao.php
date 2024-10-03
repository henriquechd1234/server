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
    <div class="top-bar">
        <a href="index.php"><img src="../img/icons8-infinito-96.png" alt=""></a>
    </div>
        <?php 
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
        
            $resultado = ("SELECT * FROM imagens WHERE id = '$id'");
            $query = $mysqli -> query($resultado);
        
        
            if ($row = $query -> fetch_assoc()){
                echo '<div class="movie">';
                echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '" style="width:200px; height:auto;">';
                echo '<h2 style="color: white";>' . $row['descricao'] . '</h2>';
                echo '</div>';
            }else{
                echo 'nenhum filme encontrado';
            }
        }else{
            echo "ID não encontrado";
        }
        ?>

        <form action="" method="POST">
            <p>Avalie esse filme:</p>
            <input type="text" name="avaliacao" id="">
            <label for="nota">Nota (1 a 5):</label>
            <input type="number" name="nota" min="1" max="5" required>
            <button type="submit">Enviar</button>

        </form>
        <?php 
            if(isset($_POST['avaliacao']) && !empty($_POST['avaliacao'])) {
                if (!isset($_SESSION['id'])) {
                 echo "Usuário não está logado. Por favor, faça login.";
                        exit; // Interrompe a execução do script
                        }
                  $id_user = $_SESSION['id'];
                    if (!is_numeric($id_user)) {
                    echo "ID do usuário inválido.";
                    exit; // Interrompe a execução do script
                        }

                
                $avali = $_POST['avaliacao'];
                $nota = $_POST['nota'];


                $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES  ('$id_user','$avali', '$nota','$id'";
                $envio = $mysqli -> query($inserir);
        
            if ($envio === TRUE) {
                echo "Avaliação enviada com sucesso!";
            } else {
                echo "Erro ao enviar a avaliação: " . $mysqli->error;
            }
          $avaliacoes = "
            SELECT c.nome, a.avaliacao, a.nota, a.data_avaliacao, i.foto 
            FROM avaliacao AS a 
            JOIN cadastro AS c ON a.cadastro_id = c.id 
            JOIN imagens AS i ON a.imagens_id = i.id  -- Junte a tabela de imagens
            WHERE i.id = '$id'"; 

            $queryAvaliacao = $mysqli->query($avaliacoes);


            if ($queryAvaliacao->num_rows > 0) {
                echo '<div class="avaliacoes">';
                while ($avaliacaoRow = $queryAvaliacao->fetch_assoc()) {
                    echo '<div class="avaliacao">';
                    echo '<p><strong>' . $avaliacaoRow['nome'] . ':</strong> ' . $avaliacaoRow['avaliacao'] . '</p>';
                    echo '<p>Nota: ' . $avaliacaoRow['nota'] . '/5</p>';
                    echo '<p>Avaliado em: ' . $avaliacaoRow['data_avaliacao'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo 'Nenhuma avaliação disponível para este filme.';
            }
            }
            ?>
                 
    </body>
</html>
