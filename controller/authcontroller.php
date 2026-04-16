<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../helper/jwt.php';


class AuthController extends Controller {

    public function add($data) {
        
    $username = $data['username'] ?? '';    
    $password = $data['password'] ?? '';

    if(!$username || !$password) {
        $this->send(['error' => 'Username and password are required'], 422);
        return;
    }

    $admin = $this->getRecords(
        'ioi_users',
        ['username'],
        [$username],
        'one'
    );

    $adminPassword = is_array($admin) ? $admin['password'] : $admin->password;
    $adminId       = is_array($admin) ? $admin['user_id'] : $admin->user_id;
    $adminUsername = is_array($admin) ? $admin['username'] : $admin->username;

    if(!$admin || !password_verify($password, $adminPassword)) {
        $this->send(['error' => 'Invalid credentials'], 401);
        return;
    }

    $jwt   = new JWT();
    $token = $jwt->generate($adminId, $adminUsername);

    $this->send([
        'accessToken' => $token,
        'user' => [
            'user_id'  => $adminId,
            'username' => $adminUsername,
            'role'     => 'admin'
        ]
    ]);
    }   

    public function get() {
        $headers = getallheaders();
        $jwtHeader = $headers['Authorization'] ?? '';

        $token = str_replace('Bearer ', '', $jwtHeader);

        $jwt = new JWT();
        $payload = $jwt->getPayload($token);

        if(!$payload) {
        return $this->send([
            'error' => 'Invalid or expired token'
        ], 401);
        }

       $this->send([
        'accessToken' => $token,
        'user' => [
            'user_id'  => $payload -> user_id,    
            'username' => $payload -> username,   
            'role'     => 'admin' 
        ]
    ]);
    }

    public function getOne($id)  { $this->send(['error' => 'Not allowed'], 405); }
    public function edit($data)  { $this->send(['error' => 'Not allowed'], 405); }
    public function delete($id)  { $this->send(['error' => 'Not allowed'], 405); }
}