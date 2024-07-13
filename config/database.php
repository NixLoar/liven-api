<?php

namespace Config;

if (file_exists(__DIR__ . '/../.env')) {
  $env = parse_ini_file(__DIR__ . '/../.env');
  foreach ($env as $key => $value) {
    putenv("$key=$value");
  }
}

class Database {
  private $conn;

  public function getConnection() {
      $this->conn = null;
      $env = getenv('APP_ENV');

      $host = '';
      $db_name = '';
      $username = '';
      $password = '';

      if ($env === 'local') {
        $host = getenv('LOCAL_DB_HOST');
        $db_name = getenv('LOCAL_DB_NAME');
        $username = getenv('LOCAL_DB_USER');
        $password = getenv('LOCAL_DB_PASS');
      }

      try {
          $this->conn = new \PDO("mysql:host=$host;dbname=$db_name", $username, $password);
          $this->conn->exec("set names utf8");
      } catch (\PDOException $exception) {
          echo "Connection error: " . $exception->getMessage();
      }

      return $this->conn;
  }
}