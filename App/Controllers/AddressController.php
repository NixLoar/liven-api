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
        echo json_encode(['error' => 'Nenhum endereco encontrado']);
        return;
      }

      $formattedAddresses = array_map(function($address) {
        return [
          'Logradouro' => $address['logradouro'],
          'Numero' => $address['numero'],
          'Cep' => $address['cep']
        ];
      }, $addressData);

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
      $createdAddressCep = $address->create($data, $id);
      if ($createdAddressCep !== null) {
        http_response_code(201);
        echo json_encode(['message' => 'Endereco cadastrado com sucesso', 'cep' => $createdAddressCep]);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao cadastrar endereco']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function put($addressId) {
    try {
      $data = $this->getJsonBody();
      $address = new AddressModel;
      if ($address->update($data, $addressId)) {
        echo json_encode(['message' => 'Endereco atualizado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao atualizar endereco']);
      }
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function delete($addressId) {
    try {
      $address = new AddressModel;
      if ($address->delete($addressId)) {
        echo json_encode(['message' => 'Endereco deletado com sucesso']);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'Falha ao deletar endereco']);
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
      throw new \Exception('Dados inválidos no corpo da requisição');
    }
    return $data;
  }
}