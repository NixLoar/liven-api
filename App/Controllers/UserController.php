<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController {
  
  public function get($id) {
    try {
      $user = new UserModel;
      $userData = $user->read($id);
      echo json_encode($userData);
    } catch (\Exception $e) {
      http_response_code(404);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function post() {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $user = new UserModel;
      $createdUserId = $user->create($data);
      if ($createdUserId !== null) {
        http_response_code(201);
        echo json_encode(['message' => 'Usuario cadastrado com sucesso', 'id' => $createdUserId]);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao cadastrar usuario']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function put($id) {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $user = new UserModel;
      if ($user->update($data, $id)) {
        echo json_encode(['message' => 'Usuario atualizado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao atualizar usuario']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $user = new UserModel;
      if ($user->delete($id)) {
        echo json_encode(['message' => 'Usuario deletado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao deletar o usuario']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}