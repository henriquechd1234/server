<?php 
// Inclusão de conexão e verificação de sessão
include('conexao.php');
if(!isset($_SESSION)){
    session_start();
}

// Lógica de inserção da avaliação
if (isset($_POST['avaliacao']) && !empty($_POST['avaliacao'])) {
    if (!isset($_SESSION['id'])) {
        echo "Usuário não está logado. Por favor, faça login.";
    } else {
        $id_user = $_SESSION['id'];
        $avali = $_POST['avaliacao'];
        $nota = $_POST['nota'];
        $id = $_GET['id'];

        // Inserindo avaliação
        $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES ('$id_user','$avali', '$nota', '$id')";
        $envio = $mysqli->query($inserir);

        // Redirecionar após o envio para evitar reenvio duplicado
        header("Location: avaliacao.php?id=$id");
        exit();
    }
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
                  $total = "SELECT COUNT(*) AS total_avaliacoes, AVG(nota) AS media_nota
                    FROM avaliacao
                    WHERE imagens_id = ? ";

                $stmt_total = $mysqli->prepare($total);
                $stmt_total->bind_param('i', $id); // Passando o ID corretamente
                $stmt_total->execute();
                $total_query = $stmt_total->get_result();
                $ava = $total_query->fetch_assoc();

                $total_ava = $ava['total_avaliacoes'];   //aqui e a parte que pega as avaliações e as  notas  e isso ai pae e nois,vapo demaissssssssssssssssssssssssssssssssssssssssssssssssssss.
                $total_star = $ava['media_nota'];
                if ($total_star == 0) {
                    $media_nota = 000;
                }else{
                    $media_nota = ($total_star / $total_ava) * 2;

                        }



                $video_url = $row['trailer']; 
                echo '<main class="content">';
                echo '<div class="container">';
                echo '<div class="movie-header">';
                echo '<h1 class="original-title">' . $row['nome'] . '</h1>';
                echo '<div class="rating">';
                echo '<span>⭐'. number_format($media_nota, 2) . '</span>';
                echo '<p>'. $total_ava.' avaliações</p>';
                echo '</div>';
                echo '</div>';
                echo '<div class="movie-details">';
                echo '<p class="synopsis">' . $row['descricao'] . '</p>';
                echo '<p>Tempo de duração: ' . $row['tempo_de_filme'] . '</p>';
                echo '<p>Diretor: ' . $row['diretor'] . '</p>';
                echo '<p>Elenco Principal: ' . $row['elenco_principal'] . '</p>';
                echo '</div>';
                echo '<br>';
                echo '<hr>';
                echo '<br>';
                echo '<div class="movie-content">';
                echo '<div class="movie-poster">';
                echo '<img src="' . $row['foto'] . '" alt="Poster do filme Coringa" style="max-width: 200px;">';
                 echo '<hr>';
                echo '<br>';
                if (!empty($video_url)) {
                    echo '<div class="video-container">';
                    echo '<iframe src="' . $video_url . '" frameborder="0" allowfullscreen></iframe>';
                    echo '</div>';
                }
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
        <h2> Certifique de estar logado antes de avaliar </h2>
        <h2>Deixe sua avaliação:</h2>
        <form action="" method="POST">
            <label for="avaliacao">Comentário:</label>
            <textarea name="avaliacao" required></textarea>
            <label for="nota">Nota (1 a 5):</label>
            <input type="number" name="nota" min="1" max="5" required>
            <button type="submit">Enviar Avaliação</button>
        </form>

        <?php
            // Exibição das avaliações
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
                echo "Usuário não está logado. Por favor, faça o login ou crie uma conta.";

            }

            else{

            $id_user = $_SESSION['id'];
            $avali = $_POST['avaliacao'];
            $nota = $_POST['nota'];

            // Inserindo avaliação
            $inserir = "INSERT INTO avaliacao (cadastro_id, avaliacao, nota, imagens_id) VALUES ('$id_user','$avali', '$nota', '$id')";
            $envio = $mysqli->query($inserir);

            if ($envio === TRUE) {
                echo "Avaliação enviada com sucesso!";
                header("Location: avaliacao.php");
                exit();
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
