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
    <title>Coringa: Delírio a Dois - Página do Filme</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/ini.css">
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
            if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            $resultado = "SELECT * FROM imagens WHERE id = '$id'";
            $query = $mysqli->query($resultado);

            if ($row = $query->fetch_assoc()) {
             echo '<div class="movie-header">';
             echo '<h1 class= "original-title">'. $row['nome'] . '</h1>';
             echo '<h2 style="color: white;">' . $row['descricao'] . '</h2>';
             echo '<div class="rating">';
             echo '<span>⭐ 5,7/10</span>';
             echo '<p>4,8 mil avaliações</p>';
             echo '</div>';
             echo '</div>';
             echo '<div class="movie-content">';
             echo '<div class="movie-poster">';
             echo '<img src="' . $row['foto'] . '" alt= "Poster do filme Coringa">';
            } else {
                echo 'nenhum filme encontrado';
            }
            
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
                    }
                } else {
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
</body>
</html>
