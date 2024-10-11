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
                        echo '<div class ="container">';
                        echo '<div class="movie">';
                        echo '<img src="' . $row['foto'] . '" alt="' . $row['nome'] . '" style="width:200px; height:auto;">';
                        echo '<h2 style="color: white";>' . $row['nome'] . '</h2>';
                        echo '<a class="info" href="avaliacao.php?id='  . $row['id'] . '">Mais Informações</a>';
                        echo '</div>';
                        echo '</div>';
                }
            }
            }else{
          $code_filmes = "SELECT i.id, i.nome, i.descricao, i.foto, AVG(a.nota) AS media_avaliacao
                FROM imagens i
                JOIN avaliacao a ON i.id = a.imagens_id
                GROUP BY i.id
                ORDER BY media_avaliacao DESC
                LIMIT 10";

            $sql_query = $mysqli -> query($code_filmes);
        ?>
        <div class="space"> <hr class= espacamento><div/>
        
        
        <div class="top-ten-section">
            <h2 class="styletext">Top 10 no InfinityHUB!</h2>
            <div class="top-ten-container">

                <div class="top-ten-grid">
                    <?php while ($row2 = $sql_query -> fetch_assoc()){ ?>

                    <div class="movie-card">
                        <img src="<?php echo $row2['foto']; ?>" alt="">
                        <div class="movie-info">
                            <h3><?php echo $row2 ['nome']?></h3>
                            <?php  echo '<a class="info" href="avaliacao.php?id='  . $row2['id'] . '">Mais Informações</a>'; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <p class="espacamento"></p>
                    </div>
                
            </div>

           <?php
    $fotos_container = "SELECT * FROM baner WHERE id IN (1)";
    $fotos_query = $mysqli->query($fotos_container);

    $baners = [];
    while ($row3 = $fotos_query->fetch_assoc()) {
        $baners[] = $row3;
    }
?>
<div class="slider-container">
    <div class="slider" id="slider">
        <?php foreach ($baners as $baner): ?>
        <div class="slide">
            <img src="<?php echo $baner['url']; ?>" alt="The Batman">
            <div class="slide-info">
                
                <h2><?php echo $baner['nome'] ?></h2>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>
        


  <section class="carousel">
    <h2>Sugestões dos Criadores</h2>
    <div class="carousel-container">
      <div class="carousel-items">
        <div class="item">
          <img src="https://imgs.search.brave.com/xdrYLI9wHLN1PeAjWPKM7Az5HFSS__PCtLmc_kr3hFA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/bXVuZG9jb25lY3Rh/ZG8uY29tLmJyL3dw/LWNvbnRlbnQvdXBs/b2Fkcy8yMDIzLzA1/L2NhcGEtZG8tZmls/bWUtZGUtdm9sdGEt/cGFyYS1vLWZ1dHVy/by0yLmpwZw" alt="Filme 1">
          <h3>De volta para o Futuro</h3>
          <span>Recomendado por: Felipe Garcia</span>
          <p>Masterpiece!</p>
        </div>
        <div class="item">
          <img src="https://www.comingsoon.net/wp-content/uploads/sites/3/2020/11/the-batman-rpatz-e1606244760795.jpeg" alt="Filme 2">
          <h3>The Batman</h3>
          <span>Recomendado por: David</span>
          <p>Filme simplesmente sensacional! Revolucionou a indústria de filmes da DC, que há tempos não apresentava algo de qualidade.</p>
        </div>
        <div class="item">
          <img src="https://img.odcdn.com.br/wp-content/uploads/2024/03/Destaque-Duna-2021.jpg" alt="Filme 3">
          <h3>Dune 2</h3>
          <span>Recomendado por: Cauã Henrique</span>
          <p>Filmaço da porra!</p>
        </div>
      </div>
    </div>
  </section>
<?php  } ?>

<footer class="footer">
  <div class="footer-content">
    <p>&copy; 2024 David Martins e Caua Henrique. Todos os direitos reservados.</p>
    <p>Desenvolvido por <strong>Pinova</strong></p>
  </div>
</footer>

  
    <style>
.movie {
    display: flex; /* Mantém o layout flex para alinhar os conteúdos do filme */
    flex-direction: column; /* Coloca a imagem e o texto um embaixo do outro */
    align-items: center; /* Centraliza os itens dentro do filme */
    width: 200px; /* Largura fixa para cada filme */
    margin: 10px; /* Espaço ao redor de cada filme */
    padding: 15px; /* Espaço dentro de cada filme */
    background-color: #333; /* Cor de fundo do filme */
    border-radius: 10px; /* Bordas arredondadas */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
    transition: transform 0.3s ease; /* Animação suave ao passar o mouse */
}

/* Estilo para a imagem do filme */
.movie img {
    width: 100%; /* Imagem ocupa toda a largura do container */
    height: auto; /* Mantém a proporção da imagem */
    border-radius: 10px; /* Bordas arredondadas para a imagem */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra na imagem */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animação suave ao passar o mouse */
}

/* Botão de informações */
.info {
    display: block; /* O botão ocupa 100% da largura */
    width: 100%; /* Largura total do botão */
    padding: 5px 0; /* Espaçamento interno */
    background-color: #555; /* Cor de fundo do botão */
    border: none; /* Sem borda */
    border-radius: 5px; /* Bordas arredondadas */
    color: #fff; /* Cor do texto */
    margin-top: auto; /* Botão vai para o final do container do filme */
    text-align: center; /* Centraliza o texto no botão */
    text-decoration: none; /* Remove o sublinhado do texto */
    margin-top: 10px;
}
.footer {
  background-color: #141313;
  color: white;
  text-align: center;
  padding: 20px 10px;
  position: relative;
  bottom: 0;
  width: 100%;
}

.footer-content p {
  margin: 5px 0;
  font-size: 14px;
}

.footer-content strong {
  color: #FFD700; /* Dourado para destacar o nome Pinova */
}

@media (max-width: 600px) {
  .footer {
    padding: 15px;
  }

  .footer-content p {
    font-size: 12px;
  }
}

.espacamento {
  border: none;
  height: 2px;
  background-color: white;
  margin: 10px 0;
</style>

    <script src="js/ini.js"></script>
</body>
</html>
