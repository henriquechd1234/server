<?php 
include('conexao.php');
if(!isset($_SESSION)){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/filme.css">
    <link rel="stylesheet" href="css/ini.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" a>
</head>

<body>
<nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="img/icons8-infinito-96.png" alt="RateHub Logo"></a>
        </div>
        <form class="search-container" method="GET">
            <input type="text" placeholder="Pesquisar na Biblioteca" id="busca" name="busca">
            <button type="submit">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Magnifying_glass_icon.svg" alt="Lupa">
            </button>
        </form>
    </nav>
    <hr class="linha">
</header>
<?php 
if(isset($_GET['busca'])&& !empty($_GET['busca'])){
  $procurar = '%' . $mysqli->real_escape_string($_GET['busca']) . '%';

// Consulta para buscar o filme pelo nome
$stmt = $mysqli->prepare("SELECT id, nome,descricao,foto FROM imagens WHERE nome LIKE ?");
$stmt->bind_param('s', $procurar);
$stmt->execute();
$result = $stmt->get_result();



if( $result -> num_rows === 0 || $result == ''){
    echo"<p style='color: white;'>Nenhum resultado encontrado no momento </p>" ;
}
else{
    while ($row= $result -> fetch_assoc()){
        echo '<div class="movie">';
        echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '" style="width:200px; height:auto;">';
        echo '<h2 style="color: white";>' . $row['nome'] . '</h2>';
        echo '<a class="info" href="avaliacao.php?id='  . $row['id'] . '">Mais Informações</a>';
        echo '</div>';
}
}
}else if(isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                
                $resultado = "SELECT * FROM imagens WHERE id = '$id'";
                $query = $mysqli->query($resultado);
                
           echo '<main class="content">';
             echo '<div class = "container">';
             echo '<div class="movie-header">';
             echo '<h1 class= "original-title">'. $row['nome'] . '</h1>';
             echo '<div class="rating">';
             echo '<span>⭐ 5,7/10</span>';
             echo '<p>4,8 mil avaliações</p>';
             echo '</div>';
             echo '</div>';
             echo '<div class="movie-content">';
             echo '<div class="movie-poster">';
             echo '<img src="' . $row['foto'] . '" alt= "Poster do filme Coringa">';
             echo '</div>';
             echo '<div class="movie-info">';
             echo '<div class ="trailer">';
             echo '<iframe width="560" height="315" src="'.$row ['trailer'].'"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>';
             echo '</iframe>';
             echo '</div>';
             echo '</div>';
             echo '<div class ="movie-details">';
             echo  '<p class = "synopsis"> '. $row['descricao'] .' </p>';
             echo '</div>';
             echo '</div>';
             echo '</main>';
            } else {
                echo 'nenhum filme encontrado';
            } ?>
            
<form action="" method="POST">
    <p>Avalie esse filme:</p>
    <input type="text" name="avaliacao" id="">
    <label for="nota">Nota (1 a 5):</label>
    <input type="number" name="nota" min="1" max="5" required>
    <button type="submit">Enviar</button>
</form> <?php
            // Consulta para buscar as avaliações
            $avaliacoes = "
            SELECT c.nome, a.avaliacao, a.nota, a.data_avaliacao, i.foto 
            FROM avaliacao AS a 
            JOIN cadastro AS c ON a.cadastro_id = c.id 
            JOIN imagens AS i ON a.imagens_id = i.id 
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
if (isset($_POST['avaliacao']) && !empty($_POST['avaliacao'])) {
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

    // Correção do SQL de inserção
    $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES ('$id_user','$avali', '$nota', '$id')";
    $envio = $mysqli->query($inserir);

    if ($envio === TRUE) {
        echo "Avaliação enviada com sucesso!";
    } else {
        echo "Erro ao enviar a avaliação: " . $mysqli->error;
    }

    // Após a inserção, busque novamente as avaliações
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
   

    <style>
    .movie {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 20px;
        padding: 15px;
        background-color: #333;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        max-width: 250px;
        margin-left: 70px;
    }

    .movie img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .info{
        display: block;
    width: 100%;
    padding: 5px 0;
    background-color: #555;
    border: none;
    border-radius: 5px;
    color: #fff;
    margin-bottom: 5px;
    cursor: pointer;
    margin-top: auto;
    text-align: center;
    text-decoration: none;
    }
</style>

</body>
</html>
