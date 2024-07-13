<?php
  require_once '../vendor/autoload.php';
  require_once '../config/database.php';

  // Sanitização da URL
  $urlParam = filter_var($_GET['url'] ?? '', FILTER_SANITIZE_URL);
  if ($urlParam) {
    $url = explode('/', $urlParam);
    var_dump($url);
  }

  // Teste conexão DB
  $database = new Database();
  $conn = $database->getConnection();

  if ($conn) {
    echo "Conexão com a DB feita com sucesso!";
  } else{
    echo "Erro na conexão com a DB";
  }