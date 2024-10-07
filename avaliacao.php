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
                echo '<img src="' . $row['foto'] . '" alt="Poster do filme Coringa">';
                 if (!empty($video_url)) {
                    ?>
                <iframe width="560" height="315" 
                src="<?php echo $video_url; ?>" 
                title="YouTube video player" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
                     
            </iframe>
                <?php
                 }
                echo '</div>';
                echo '<div class="movie-details">';
                echo '<p class="synopsis">' . $row['descricao'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</main>';
                
            } else {
                echo 'Nenhum filme encontrado.';
            }
        ?>
    
    </main>

    <!-- Formulário de avaliação -->
    <form action="" method="POST">
        <p>Avalie esse filme:</p>
        <label for="avaliacao">Comentário:</label>
        <textarea name="avaliacao" required></textarea>
        <label for="nota">Nota (1 a 5):</label>
        <input type="number" name="nota" min="1" max="5" required>
        <button type="submit">Enviar</button>
    </form>

    <?php
        }
    if (isset($_POST['avaliacao']) && !empty($_POST['avaliacao'])) {
        if (!isset($_SESSION['id'])) {
            echo "Usuário não está logado. Por favor, faça login.";
            exit;
        }

        $id_user = $_SESSION['id'];
        $avali = $_POST['avaliacao'];
        $nota = $_POST['nota'];

        $stmt = $mysqli->prepare("INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isii', $id_user, $avali, $nota, $id);
        
        if ($stmt->execute()) {
            echo "Avaliação enviada com sucesso!";
        } else {
            echo "Erro ao enviar a avaliação: " . $mysqli->error;
        }
    }
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
                    }
                } else {
                    echo "ID não encontrado";
                }
                ?> 
    ?>
</body>
</html>
