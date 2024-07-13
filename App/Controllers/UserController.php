<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController {
  public function get($id) {
    $user = new UserModel;
    $userData = $user->read($id);
    print_r($userData);
  }

  public function post() {
    $data = json_decode(file_get_contents('php://input'), true);
    $user = new UserModel;
    if ($user->create($data)) {
      echo json_encode(['message' => 'Usuario cadastrado com sucesso']);
    } else {
      echo json_encode(['message' => 'Falha ao cadastrar usuario']);
    }
  }

  public function put() {
    echo 'Tabela atualizada';
  }

  public function delete() {
    echo 'Usuário removido';
  }
}
