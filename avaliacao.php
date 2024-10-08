<?php 
// Inclusão de conexão e verificação de sessão
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

    <link rel="stylesheet" href="css/filme.css">
    <link rel="stylesheet" href="css/ini.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>

<body>
    <header>
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

    <!-- Exibição de resultados de busca -->
    <main class="container">
        <?php 
if(isset($_GET['busca']) && !empty($_GET['busca'])){
            $procurar = '%' . $mysqli->real_escape_string($_GET['busca']) . '%';
            $stmt = $mysqli->prepare("SELECT id, nome, descricao, foto FROM imagens WHERE nome LIKE ?");
            $stmt->bind_param('s', $procurar);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0){
                echo "<p style='color: white;'>Nenhum resultado encontrado no momento</p>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="movie">';
                    echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '" style="width:200px; height:auto;">';
                    echo '<h2 style="color: white;">' . $row['nome'] . '</h2>';
                    echo '<a class="info" href="avaliacao.php?id=' . $row['id'] . '">Mais Informações</a>';
                    echo '</div>';
                }
            }
        } else if(isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $mysqli->prepare("SELECT * FROM imagens WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($row = $result->fetch_assoc()){
                $video_url = $row['trailer']; 
                echo '<main class="content">';
                echo '<div class="container">';
                echo '<div class="movie-header">';
                echo '<h1 class="original-title">' . $row['nome'] . '</h1>';
                echo '<div class="rating">';
                echo '<span>⭐ 5,7/10</span>';
                echo '<p>4,8 mil avaliações</p>';
                echo '</div>';
                echo '</div>';
                echo '<div class="movie-content">';
                echo '<div class="movie-poster">';
                echo '<img src="' . $row['foto'] . '" alt="Poster do filme Coringa" style="max-width: 200px;">';
                 if (!empty($video_url)) {
                    ?>
              <div class="video-container">
    <iframe src="<?php echo $video_url; ?>" frameborder="0" allowfullscreen></iframe>
</div>
                <?php
                 }
                echo '</div>';
                echo '<div class="movie-details">';
                echo '<p class="synopsis">' . $row['descricao'] . '</p>';
                echo '<p>' . 'Tempo de duração:' . $row['tempo_de_filme'] . '</p>';
                echo '<p>' . 'Diretor:' . $row['diretor'] . '</p>';
                echo '<p>' . 'Elenco Principal:' .$row ['elenco_principal'] . '</p>';
                echo '<p>' . 'Titulo Principal:' .$row ['titulo_original'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</main>'; 
            } else {
                echo 'Nenhum filme encontrado.';
            }
        ?>

    </main>

    <!-- Formulário de avaliação -->
    <div class="review-container">
        <h2>Deixe sua avaliação:</h2>
        <form action="" method="POST">
            <label for="avaliacao">Comentário:</label>
            <textarea name="avaliacao" required></textarea>
            <label for="nota">Nota (1 a 5):</label>
            <input type="number" name="nota" min="1" max="5" required>
            <button type="submit">Enviar Avaliação</button>
        </form>

        <?php
            $stmt_avaliacao = $mysqli->prepare("SELECT cadastro.nome, avaliacao, nota, data_avaliacao FROM avaliacao INNER JOIN cadastro ON avaliacao.cadastro_id = cadastro.id WHERE imagens_id = ?");
            $stmt_avaliacao->bind_param('i', $id);
            $stmt_avaliacao->execute();
            $result_avaliacao = $stmt_avaliacao->get_result();

            if ($result_avaliacao->num_rows > 0) {
                echo '<div class="avaliacoes">';
                echo '<h3>Avaliações:</h3>';
                while ($avaliacaoRow = $result_avaliacao->fetch_assoc()) {

                    echo '<p><strong>' . $avaliacaoRow['nome'] . ':</strong> ' . $avaliacaoRow['avaliacao'] . '</p>';
                    echo '<p>Nota: ' . $avaliacaoRow['nota'] . '/5</p>';
                    echo '<p>Avaliado em: ' . $avaliacaoRow['data_avaliacao'] . '</p>';

                }
                echo '</div>';
            } else {
                echo '<p>Nenhuma avaliação disponível para este filme.</p>';
            }

        }if (isset($_POST['avaliacao']) && !empty($_POST['avaliacao'])) {
            if (!isset($_SESSION['id'])) {
                echo "Usuário não está logado. Por favor, faça login.";

            }

            $id_user = $_SESSION['id'];
            if (!is_numeric($id_user)) {
                echo "ID do usuário inválido.";

            }else{

            $avali = $_POST['avaliacao'];
            $nota = $_POST['nota'];

            // Inserindo avaliação
            $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES ('$id_user','$avali', '$nota', '$id')";
            $envio = $mysqli->query($inserir);

            if ($envio === TRUE) {
                echo "Avaliação enviada com sucesso!";
            } else {
                echo "Erro ao enviar a avaliação: " . $mysqli->error;
            }
}
}
            // Exibir avaliações após envio
        ?>
    </div>
</body>
</html>
