<?php

require_once '../vendor/autoload.php';

use App\Controllers\UserController;

// Sanitização da URL
$urlParam = filter_var($_GET['url'] ?? '', FILTER_SANITIZE_URL);

// Definindo as rotas
if ($urlParam) {
  $url = explode('/', $urlParam);
  $method = $_SERVER['REQUEST_METHOD'];

  if (!empty($url[0]) && $url[0] === 'api') {

    // Rota /api/user/nome_servico
    if ($url[1] === 'user' && $url[2]) {
      
      if ($url[2] === 'read' && $method === 'GET' && $url[3]) {
        $controller = new UserController();
        $id = $url[3];
        $controller->get($id);
      }

      if ($url[2] === 'create' && $method === 'POST') {
        $controller = new UserController();
        $controller->post();
      }

      if ($url[2] === 'update' && $method === 'PUT') {
        $controller = new UserController();
        $controller->put();
      }

      if ($url[2] === 'delete' && $method === 'DELETE') {
        $controller = new UserController();
        $controller->delete();
      }
    } 
  } else {
    echo "Rota da API não definida: " . implode('/', $url);
  }
} else {
  echo "URL não definida";
} 