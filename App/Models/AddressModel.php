<?php 

namespace App\Models;

use Config\Database; 

class AddressModel{
  private static $tableAddresses = 'addresses';
  private static $conn = null;

  private static function getConnection() {
    if (self::$conn === null) {
        $database = new Database;
        self::$conn = $database->getConnection();
    }
    return self::$conn;
  }

  public static function read($id) {
    $conn = self::getConnection();
    $sql = 'SELECT * FROM '.self::$tableAddresses.' WHERE user_id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } else {
      throw new \Exception('Nenhum endereco cadastrados');
    }
  }

  public static function create($data, $id) {
    $conn = self::getConnection();
    $sql = 'INSERT INTO '.self::$tableAddresses.' (user_id, logradouro, numero, cep) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$id, $data['logradouro'], $data['numero'], $data['cep']])){
      return $data['cep'];
    } else {
      return null;
    }
  }

  public static function update($data, $addressId) {
    $conn = self::getConnection();
    $sql = 'UPDATE ' .self::$tableAddresses.' SET logradouro = ?, numero = ?, cep = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$data['logradouro'], $data['numero'], $data['cep'], $addressId]);
  }

  public static function delete($addressId) {
    $conn = self::getConnection();
    $sql = 'DELETE FROM ' .self::$tableAddresses.' WHERE id=?';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$addressId]);
  }
}