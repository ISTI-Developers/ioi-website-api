<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../helper/jwt.php';


class AuthController extends Controller {

    public function add($data) {
        $username = $data['username'] ?? '';    
        $password = $data['password'] ?? '';

        if(!$username || !$password) {
            $this->send(['error' => 'Username and password are required', 422]);
        }

        $admin = $this->getRecords(
            'ioi_users',
            ['username'],
            [$username],
            'one'
        );

        if(!$admin || !password_verify($password, $admin->password)) {
            $this->send(['error' => 'Invalid credentials', 401]);
        }

        $jwt   = new JWT();
        $token = $jwt->generate($admin->user_id, $admin->username);


        $this->send([
            'token' => $token,
            'admin' => [
                'id' => $admin->user_id,
                'username' => $admin->username,
                'role'     => 'admin'
            ]
        ]);
    }

    public function get()        { $this->send(['error' => 'Not allowed'], 405); }
    public function getOne($id)  { $this->send(['error' => 'Not allowed'], 405); }
    public function edit($data)  { $this->send(['error' => 'Not allowed'], 405); }
    public function delete($id)  { $this->send(['error' => 'Not allowed'], 405); }
}