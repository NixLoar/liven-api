<?php 
  namespace App\Controllers;

  use App\Models\UserModel;

  class AuthController {
    private $secretKey = 'f1d2d2f1c4e2b8f1e0f9b8e1d4e2b7c6d1e8a7b5f1e0f8c2b1d7a8f9b1c2d3e4a7b8d9c0e2f1a6b5d4e3f1c2a0d7e8a9c6b1e0d3f4a1b8c7d2e5a0b9d8e1c2f3d4b5a6';

    public function login() {
      $data = json_decode(file_get_contents('php://input'), true);
      $email = $data['email'];
      $password = $data['senha'];

      $user = new UserModel;
      $result = $user->checkCredentials($email, $password);

      if ($result['success']) {
        $payload = [
          'iat' => time(),
          'exp' => time() + 3600,
          'data' => [
            'id' => $result['user_id'],
            'email' => $email
          ]
        ];
        $jwt = $this->encodeJWT($payload);
        echo json_encode([
          'message' => 'Login feito com sucesso',
          'token' => $jwt
        ]);
      } else {
          http_response_code(401);
          echo json_encode(['message' => $result['message']]);
      }
    } 

    private function encodeJWT($payload) {
      $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
      $payload = json_encode($payload);
  
      $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
      $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
      $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
  
      return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function validateJWT($jwt) {
      $tokenParts = explode('.', $jwt);
      $header = base64_decode($tokenParts[0]);
      $payload = base64_decode($tokenParts[1]);
      $signatureProvided = $tokenParts[2];
  
      $expiration = json_decode($payload)->exp;
      $isTokenExpired = ($expiration - time()) < 0;
  
      $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
      $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
      $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
  
      $isSignatureValid = ($base64UrlSignature === $signatureProvided);
  
      if ($isTokenExpired || !$isSignatureValid) {
        return false;
      }
  
      return json_decode($payload);
    }
  }