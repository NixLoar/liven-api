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

  public static function read($userID) {
    $conn = self::getConnection();
    $sql = 'SELECT * FROM '.self::$tableAddresses.' WHERE user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userID]);

    if($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } else {
      throw new \Exception('Nenhum endereco cadastrados');
    }
  }

  public static function create($data, $userID) {
    $conn = self::getConnection();
    $sql = 'INSERT INTO '.self::$tableAddresses.' (user_id, logradouro, numero, cep) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$userID, $data['logradouro'], $data['numero'], $data['cep']])){
      return $conn->lastInsertId();
    } else {
      return null;
    }
  }

  public static function update($data, $userID, $addressID) {
    $conn = self::getConnection();
    $checkSql = 'SELECT * FROM ' . self::$tableAddresses . ' WHERE user_id = ? AND id = ?';
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([$userID, $addressID]);
    $existingData = $checkStmt->fetch(\PDO::FETCH_ASSOC);
    
    if ($existingData['logradouro'] === $data['logradouro'] &&
        $existingData['numero'] === $data['numero'] &&
        $existingData['cep'] === $data['cep']) {
        return 'no changes';
    }

    $sql = 'UPDATE ' . self::$tableAddresses . ' SET logradouro = ?, numero = ?, cep = ? WHERE user_id = ? AND id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$data['logradouro'], $data['numero'], $data['cep'], $userID, $addressID]);
    
    return $stmt->rowCount();
  }

  public static function delete($userID, $addressID) {
    $conn = self::getConnection();
    $sql = 'DELETE FROM ' .self::$tableAddresses.' WHERE user_id=? AND id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userID, $addressID]);
    return $stmt->rowCount();
  }

  public static function readByCep($cep, $userID) {
    $conn = self::getConnection();
    $sql = 'SELECT * FROM '.self::$tableAddresses.' WHERE cep = ? AND user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cep, $userID]);

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } else {
        throw new \Exception('Nenhum endereco encontrado com o cep: ' . $cep);
    }
}
}