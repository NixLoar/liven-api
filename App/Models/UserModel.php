<?php 

namespace App\Models;

use Config\Database; 

class UserModel{
  private static $table = 'users';

  public static function read($id) {
    $database = new Database();
    $conn = $database->getConnection();
    $sql = 'SELECT * FROM '.self::$table.' WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

      if($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } else {
        throw new \Exception('Nenhum usuário encontrado!');
    }
  }

  public static function create($data) {
    $database = new Database();
    $conn = $database->getConnection();
    $hashedPassword = password_hash($data['senha'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO '.self::$table.' (nome, email, senha, telefone) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$data['nome'], $data['email'], $hashedPassword, $data['telefone']]);
  }

  public static function update($data, $id) {
    $database = new Database();
    $conn = $database->getConnection();
    $sql = 'UPDATE ' .self::$table.' SET nome = ?, email = ?, senha = ?, telefone = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$data['nome'], $data['email'], $data['senha'], $data['telefone'], $id]);
  }

  public static function delete($id) {
    $database = new Database();
    $conn = $database->getConnection();
    $sql = 'DELETE FROM ' .self::$table.' WHERE id=?';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$id]);
  }
}