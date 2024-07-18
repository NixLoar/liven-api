<?php 

namespace App\Models;

use Config\Database; 

class UserModel{
  private static $tableUsers = 'users';
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
    $sql = 'SELECT * FROM '.self::$tableUsers.' WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0) {
      $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

      $sqlAddress = 'SELECT * FROM ' .self::$tableAddresses.' WHERE user_id = ?';
      $stmtAddress = $conn->prepare($sqlAddress);
      $stmtAddress->execute([$id]);

      if ($stmtAddress->rowCount() > 0) {
          $addresses = $stmtAddress->fetchAll(\PDO::FETCH_ASSOC);
          $userData['enderecos'] = $addresses;
      } else {
          $userData['enderecos'] = 'Nenhum endereco cadastrado';
      }

      return $userData;
    } else {
      throw new \Exception('Erro: nenhum usuario encontrado');
    }
  }

  public static function create($data) {
    $conn = self::getConnection();
    $hashedPassword = password_hash($data['senha'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO '.self::$tableUsers.' (nome, email, senha, telefone) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$data['nome'], $data['email'], $hashedPassword, $data['telefone']])){
      return $conn->lastInsertId();
    } else {
      return null;
    }
  }

  public static function update($data, $id) {
    $conn = self::getConnection();
    $sql = 'UPDATE ' .self::$tableUsers.' SET nome = ?, email = ?, senha = ?, telefone = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$data['nome'], $data['email'], $data['senha'], $data['telefone'], $id]);
    return $stmt->execute([$data['nome'], $data['email'], $data['senha'], $data['telefone'], $id]);
  }

  public static function delete($id) {
    $conn = self::getConnection();
    $sql = 'DELETE FROM ' .self::$tableUsers.' WHERE id=?';
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$id]);
  }

  public function checkCredentials($email, $senha) {
    $conn = self::getConnection();
    $sql = 'SELECT id, senha FROM ' .self::$tableUsers.' WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$user) {
      return [
        'success' => false,
        'message' => 'Usuario não encontrado'
      ];
    }

    if (!password_verify($senha, $user['senha'])) {
      return [
        'success' => false,
        'message' => 'Senha incorreta'
      ];
    }

    return [
      'success' => true,
      'user_id' => $user['id'],
      'message' => 'Login feito com sucesso'
    ];
  }
}