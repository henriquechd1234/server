<?php 

    include('conexao.php');
    session_start();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfinityHUB</title>
    <link rel="stylesheet" href="css/ini.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" a>
     <link rel="stylesheet" href="css/reset.css">

    
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="img/icons8-infinito-96.png" alt="RateHub Logo"></a>
        </div>
        <form class="search-container"  method="GET">
                <input  type="text" placeholder="Pesquisar na Biblioteca"  id="busca" name="busca">
                <button type="submit">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Magnifying_glass_icon.svg" alt="Lupa">
                </button>
            
            <?php 
            ?>
        </form>
          <?php

    if(!isset($_SESSION)){
             session_start();
            }
            if ($_SESSION == TRUE){
                
             echo ('<a style="text-decoration: none; color: aliceblue;" href="desconectar.php"><span>Sair</span></a>');
         
            }else{
                echo('<a style="text-decoration: none; color: aliceblue;" href="login.php">Login</a>');
            }
        ?>
           
    </nav>
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
                     echo '<div class="container">'; 

while($row = $result->fetch_assoc()) {
    echo '<div class="movie">'; 
        echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '">'; 
        echo '<h3 style="color: white;">' . $row['nome'] . '</h3>'; 
        echo '<a class="info" href="avaliacao.php?id='  . $row['id'] . '">Mais Informações</a>'; 
    echo '</div>';
}

echo '</div>'; 

                }
            }
        }else{
            $code_filmes = "SELECT * FROM imagens LIMIT 10";
            $sql_query = $mysqli -> query($code_filmes);
        ?>
     <hr class="espacamento">
                    <?php while ($row2 = $sql_query -> fetch_assoc()){ ?>
        <div class="top-ten-section">
            <h2 class="styletext">Top 10 no InfinityHUB!</h2>
            <div class="top-ten-container">
                <button class="prev-button">&#10094;</button> 
                <div class="top-ten-grid">
                    
                    <div class="movie-card">
                        <img src="<?php echo $row2['foto']; ?>" alt="">
                        <div class="movie-info">
                            <h3><?php echo $row2 ['nome']?></h3>
                            <?php echo '<a class="info" href="avaliacao.php?id='  . $row2['id'] . '">Mais Informações</a>'?>
                        </div>
                    </div>
               
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <!-- Corrigido para exibir a imagem corretamente usando echo -->
                <img src="<?php echo $row3['foto']; ?>" alt="The Batman">
                <div class="slide-info">
                    <h3>Filme da Semana</h3>
                    <h2><?php echo $row3['nome']; ?></h2>
                </div>
            </div>
            <div class="slide">
                <!-- Exibindo a imagem dinamicamente -->
                <img src="<?php echo $row3['foto']; ?>" alt="Another Movie">
                <div class="slide-info">
                    <h3>Filme da Semana</h3>
                    <h2><?php echo $row3['nome']; ?></h2>
                </div>
            </div>
            <div class="slide">
                <!-- Exibindo uma imagem estática -->
                <img src="https://imgs.search.brave.com/XG3MfJAYNN9eEsQ_igKEz5rnQJm-zaIIJeQuz_JycCM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9sZWdh/ZG9kYWRjLmNvbS5i/ci93cC1jb250ZW50/L3VwbG9hZHMvMjAy/Mi8wNy90aGUtYmF0/bWFuLTItdmlsYW8t/bGVnYWRvZGFkYy53/ZWJw" alt="Third Movie">
                <div class="slide-info">
                    <h3>Filme da Semana</h3>
                    <h2>Third Movie</h2>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
                <?php } ?>

    <style>
.container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap; /* Permite que os filmes desçam para a linha de baixo se não houver espaço */
    margin-top: 120px;
    gap: 20px; /* Espaçamento entre os filmes */
}

.movie {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 200px; /* Largura fixa para cada filme */
    margin: 10px;
    padding: 15px;
    background-color: #333;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.movie img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info {
    display: block;
    width: 100%;
    padding: 5px 0;
    background-color: #555;
    border: none;
    border-radius: 5px;
    color: #fff;
    text-align: center;
    margin-top: 10px;
    text-decoration: none;
}

</style>

    <script src="../js/ini.js"></script>
</body>
</html>
