<?php

namespace App\Controllers;

use App\Models\AddressModel;

class AddressController {

  public function get($id) {
    try {
      $address = new AddressModel;
      $addressData = $address->read($id);
      if (!$addressData) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario nao possui enderecos cadastrados']);
        return;
      }

      $formattedAddresses = array_map(function($address) {
        return [
          'ID' => $address['id'],
          'Logradouro' => $address['logradouro'],
          'Numero' => $address['numero'],
          'Cep' => $address['cep']
        ];
      }, $addressData);

      http_response_code(200);
      echo json_encode(['Enderecos do usuario' => $formattedAddresses]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function post($id) {
    try {
      $data = $this->getJsonBody();
      $address = new AddressModel;
      $createdAddressId = $address->create($data, $id);
      if ($createdAddressId !== null) {
        http_response_code(201);
        echo json_encode(['message' => 'Endereco cadastrado com sucesso', 'id' => $createdAddressId]);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao cadastrar endereco']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function put($userID, $addressID) {
    try {
      $data = $this->getJsonBody();
      $address = new AddressModel;
      $updateResult = $address->update($data, $userID, $addressID);
      if ($updateResult === 'no changes') {
        http_response_code(200);
        echo json_encode(['message' => 'Dados nao alterados: os dados inseridos sao iguais aos ja cadastrados']);
      } elseif ($updateResult > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'Endereco atualizado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao atualizar endereco: endereco nao encontrado ou nao pertence ao usuario']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function delete($userID, $addressID) {
    try {
      $address = new AddressModel;
      $affectedRows = $address->delete($userID, $addressID);
      if ($affectedRows > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'Endereco deletado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao deletar endereco: endereco nao encontrado ou nao pertence ao usuario']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function getByCep($cep, $userID) {
    try {
      $address = new AddressModel;
      $addressData = $address->readByCep($cep, $userID);
      if (!$addressData) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario nao possui um endereco com o cep: ' . $cep]);
        return;
      }

      $formattedAddresses = array_map(function($address) {
          return [
              'Logradouro' => $address['logradouro'],
              'Numero' => $address['numero'],
              'Cep' => $address['cep']
          ];
      }, $addressData);

      http_response_code(200);
      echo json_encode(['Endereco do usuario' => $formattedAddresses]);
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

   // Valida o JSON recebido pela requisição
   private function getJsonBody() {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
      throw new \Exception('Dados invalidos no corpo da requisição');
    }
    return $data;
  }
}