<?php

use PHPUnit\Framework\TestCase;
use App\Models\UserModel;
use Config\Database;
 
class UserTest extends TestCase {
  private $db;
  private $user;
  protected static $createdUserID;

  protected function setUp(): void {
    $this->db = (new Database())->getConnection();
    $this->user = new UserModel($this->db);
  }

  public function testCreate() {
    $data = ['nome' => 'Teste Validator Oliveira', 'email' => 'teste.oliveira@example.com', 'senha' => '1234', 'telefone' => '(11)9999-9999'];
    self::$createdUserID = $this->user->create($data);
    $this->assertIsString(self::$createdUserID, 'ID do usuário criado não é uma string.');
    $this->assertTrue(is_numeric(self::$createdUserID), 'ID do usuário criado não é numérico.');
  }

  public function testRead() {
    $user = $this->user->read(self::$createdUserID);
    $this->assertIsArray($user);
    $this->assertEquals('Teste Validator Oliveira', $user['nome']);
  }

  public function testUpdate() {
    $data = ['nome' => 'Novo Teste Validator Oliveira', 'email' => 'teste.oliveira@example.com', 'senha' => '1234', 'telefone' => '(11)9999-9999'];
    $this->assertTrue($this->user->update($data, self::$createdUserID));
  }

  public function testDelete() {
    $this->assertTrue($this->user->delete(self::$createdUserID));
  }
}