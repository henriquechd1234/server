<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Consulta SQL para buscar os filmes
$sql = "SELECT titulo, ano FROM filmes";

try {
    // Prepara a consulta
    $stmt = $pdo->prepare($sql);
    // Executa a consulta
    $stmt->execute();

    // Recupera todos os resultados em formato de array associativo
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define o cabeçalho da resposta como JSON
    header('Content-Type: application/json');
    
    // Retorna os filmes em formato JSON
    echo json_encode($filmes);
    
} catch (PDOException $e) {
    // Retorna o erro caso a consulta falhe
    echo json_encode(["erro" => $e->getMessage()]);
}
?>
