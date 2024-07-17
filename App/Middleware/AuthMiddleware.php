<?php

namespace App\Middleware;

use App\Controllers\AuthController;

class AuthMiddleware {
  public function handle($next) {
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
      $authHeader = $headers['Authorization'];
      $token = str_replace('Bearer ', '', $authHeader);
      $authController = new AuthController();
      $decoded = $authController->validateJWT($token);

      if ($decoded) {
        return $next();
      } else {
        http_response_code(401);
        echo json_encode(['message' => 'Acesso nao autorizado']);
        return false;
      }
    } else {
      http_response_code(401);
      echo json_encode(['message' => 'Acesso nao autorizado: t
      oken nao encontrado']);
      return false;
    }
  }
}