<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController {
  
  public function get($id) {
    try {
      $user = new UserModel;
      $userData = $user->read($id);
      if (!$userData) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario nao encontrado']);
        return;
      }
      http_response_code(200);
      echo json_encode(['Dados do usuario' => $userData]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function post() {
    try {
      $data = $this->getJsonBody();
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
      $data = $this->getJsonBody();
      $user = new UserModel;
      if ($user->update($data, $id)) {
        http_response_code(204);
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
        http_response_code(201);
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

  // Valida o JSON recebido pela requisição
  private function getJsonBody() {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
      throw new \Exception('Dados invalidos no corpo da requisicao');
    }
    return $data;
  }
}