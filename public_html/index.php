<?php

require_once '../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

// Sanitização da URL
$urlParam = filter_var($_GET['url'] ?? '', FILTER_SANITIZE_URL);

if ($urlParam) {
  
  $url = explode('/', $urlParam);
  
  if ($url[0] === 'api' && $url[1]) {
    
    // Definindo as rotas
    $endpoint = $url[1];
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Rota /api/login
    if($endpoint === 'login' && $method === 'POST') {
      $controller = new AuthController();
      $controller->login();
    } 

    // Rota /api/user/*
    elseif ($endpoint === 'user' && $url[2]) {
      $controller = new UserController();
      $authMiddleware = new AuthMiddleware();
      $service = $url[2];

      // Serviços da rota /api/user/*
      if ($service === 'read' && $method === 'GET' && $url[3]) {
        $authMiddleware->handle(function() use ($controller, $url) {
          $id = $url[3];
          $controller->get($id);
        });
      }

      elseif ($service === 'create' && $method === 'POST') {
        $controller->post();
      }

      elseif ($service === 'update' && $method === 'PUT' && $url[3]) {
        $authMiddleware->handle(function() use ($controller, $url) {
          $id = $url[3];
          $controller->put($id);
        });
      }

      elseif ($service === 'delete' && $method === 'DELETE' && $url[3]) {
        $authMiddleware->handle(function() use ($controller, $url) {
          $id = $url[3];
          $controller->delete($id);
        });
      }

      else{
        echo "Erro: servico da API não definido: " . $service;
      }
    } 
    
    else {
      echo "Erro: endpoint da API não definido: " . $endpoint;
    }
  } 
  
  else {
    echo "Erro: rota inválida: " . implode('/', $url);
  }
} 

else {
  echo "Erro: URL não definida";
} 

/* Disclaimer para a avaliação do código: sei que existem método mais limpos de escrita das rotas, não explicitando cada uma delas e fazendo de forma mais dinâmica. 
Por exemplo, algo como call_user_func_array(array(new $controller, $method), $url). 
Essa é uma das poucas exceções em que opto por um código mais verboso, evidenciando cada item e seus tratamentos. 
Claro que em um caso prático, seguiria os padrões de codificação definidos pela empresa em que estou :)
*/