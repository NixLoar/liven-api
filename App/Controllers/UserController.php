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

  public function put($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $user = new UserModel;
    if ($user->update($data, $id)) {
      echo json_encode(['message' => 'Usuario atualizado com sucesso']);
    } else {
      echo json_encode(['message' => 'Falha ao atualizar usuario']);
    }
  }

  public function delete($id) {
    $user = new UserModel;
    if ($user->delete($id)) {
      echo json_encode(['message' => 'Usuario deletado com sucesso']);
    } else {
      echo json_encode(['message' => 'Falha ao deletar o usuario']);
    }
  }
}
