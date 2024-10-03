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
    <title>Coringa: Delírio a Dois - Página do Filme</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../design/css/ini.css">
</head>

<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="ini.php"><img src="../design/img/icons8-infinito-96.png" alt="RateHub Logo"></a>
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

<main class="content">
    <div class="container">
        <div class="movie-header">
            <h1>Coringa: Delírio a Dois</h1>
            <p class="original-title">Título original: Joker: Folie à Deux</p>
            <p>2024 · 16 · 2h 18min</p>
            <div class="rating">
                <span>⭐ 5,7/10</span>
                <p>4,8 mil avaliações</p>
            </div>
        </div>

        <div class="movie-content">
            <div class="movie-poster">
                <img src="https://www.reservacultural.com.br/niteroi/wp-content/uploads/2024/09/Coringa_-Del%C3%ADrio-a-Dois.png" alt="Poster do filme Coringa">
            </div>
        </div>

        <div class="movie-details">
            <p class="synopsis">
                O comediante fracassado Arthur Fleck conhece o amor de sua vida, Harley Quinn, enquanto está encarcerado no Arkham State Hospital. Os dois embarcam em uma desventura romântica condenada.
            </p>
            <p><div class="p2">Direção:</div> Todd Phillips</p>
            <p><div class="p2">Roteiristas:</div> Scott Silver, Todd Phillips, Bob Kane</p>
            <p><div class="p2">Elenco:</div> Joaquin Phoenix, Lady Gaga, Brendan Gleeson</p>
        </div>

        <!-- Avaliações -->
        <div class="movie-reviews">
            <?php 
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];

                // Consulta para buscar as informações do filme
                $resultado = "SELECT * FROM imagens WHERE id = '$id'";
                $query = $mysqli->query($resultado);

                if ($row = $query->fetch_assoc()) {
                    echo '<div class="movie">';
                    echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '" style="width:200px; height:auto;">';
                    echo '<h2 style="color: white;">' . $row['descricao'] . '</h2>';
                    echo '</div>';
                } else {
                    echo 'Nenhum filme encontrado';
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
        </div>

        <!-- Formulário de avaliação -->
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
                exit; 
            }

            $id_user = $_SESSION['id'];
            if (!is_numeric($id_user)) {
                echo "ID do usuário inválido.";
                exit;
            }

            $avali = $_POST['avaliacao'];
            $nota = $_POST['nota'];

            // Inserir avaliação
            $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES ('$id_user','$avali', '$nota', '$id')";
            $envio = $mysqli->query($inserir);

            if ($envio === TRUE) {
                echo "Avaliação enviada com sucesso!";
            } else {
                echo "Erro ao enviar a avaliação: " . $mysqli->error;
            }

            // Recarregar avaliações após a inserção
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
    </div>
</main>

<footer class="footer">
    <div class="container">
        <button class="add-to-list">Avaliações</button>
    </div>
</footer>

<script src="scripts.js"></script>
</body>
</html>
