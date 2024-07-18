<?php

use PHPUnit\Framework\TestCase;
use App\Models\UserModel;
use App\Models\AddressModel;
use Config\Database;
 
class AddressTest extends TestCase {
  private $db;
  private $user;
  private $address;
  protected static $createdUserID;
  protected static $createdAddressId;

  protected function setUp(): void {
    $this->db = (new Database())->getConnection();
    $this->user = new UserModel($this->db);
    $this->address = new AddressModel($this->db);
  }

  // Como cada endereço está associado a um usuário, para os testes do endpoint address é necessário criar um usuário antes

  public function testUserCreate() {
    $data = ['nome' => 'Teste Validator Oliveira', 'email' => 'teste.oliveira@example.com', 'senha' => '1234', 'telefone' => '(11)9999-9999'];
    self::$createdUserID = $this->user->create($data);
    $this->assertIsString(self::$createdUserID, 'ID do usuário criado não é uma string.');
    $this->assertTrue(is_numeric(self::$createdUserID), 'ID do usuário criado não é numérico.');
  }

  public function testAddressCreate1() {
    $data = ['logradouro' => 'Av. Teste Souza', 'numero' => '9999', 'cep' => '12345678'];
    self::$createdAddressId[0] = $this->address->create($data, self::$createdUserID);
    $this->assertIsString(self::$createdAddressId[0], 'ID do endereco criado não é uma string.');
    $this->assertTrue(is_numeric(self::$createdAddressId[0]), 'ID do endereco criado não é numérico.');
  }

  public function testAddressCreate2() {
    $data = ['logradouro' => 'Rua. Teste Silva', 'numero' => '6666', 'cep' => '0000000'];
    self::$createdAddressId[1] = $this->address->create($data, self::$createdUserID);
    $this->assertIsString(self::$createdAddressId[1], 'ID do endereco criado não é uma string.');
    $this->assertTrue(is_numeric(self::$createdAddressId[1]), 'ID do endereco criado não é numérico.');
  }

  public function testAddressRead() {
    $address= $this->address->read(self::$createdUserID);
    $this->assertIsArray($address);
    $this->assertEquals('Av. Teste Souza', $address[0]['logradouro']);
    $this->assertEquals('Rua. Teste Silva', $address[1]['logradouro']);
    $this->assertEquals($address[0]['user_id'], $address[1]['user_id']); // Garante que ambos os endereços estão associados ao mesmo usuário
  }

  public function testAddressUpdate() {
    $data = ['logradouro' => 'Av. Ricardo Souza Melo', 'numero' => '1111', 'cep' => '00005-679'];
    $this->assertGreaterThan(0, $this->address->update($data, self::$createdUserID, self::$createdAddressId[0]));
  }

  public function testAddressDelete() {
    $this->assertGreaterThan(0, $this->address->delete(self::$createdUserID, self::$createdAddressId[0]));
  }

  public function testUserDelete() {
    $this->assertTrue($this->user->delete(self::$createdUserID));
  }
}