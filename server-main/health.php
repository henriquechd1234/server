<?php
// health.php

// Simples resposta para verificar a saúde do serviço
http_response_code(200);
echo json_encode(["status" => "UP"]);
?>