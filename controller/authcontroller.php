<?php
require_once 'controller.php';

require_once __DIR__ . "/../middleware/auth.php";
require_once __DIR__ . "/../config/cors.php";
require_once __DIR__ . "/../helper/response.php";
require_once __DIR__ . "/../helper/validation.php";


class AuthController extends Controller {

    public function login()
    {
        setCorsHeaders();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            respondError('Method not allowed', 405);
        }

        $data = getRequestBody();

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($username) || empty($password)) {
            respondError('Username and password are required.', 422);
        }

        $stmt = $this->connection->prepare(
            "SELECT user_id, username, password, role FROM ioi_users WHERE username = ?"
        );

        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user->password)) {
            respondError('Invalid username or password.', 401);
        }

        startSecureSession();
        session_regenerate_id(true);

        $_SESSION['user_id']  = $user->user_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role']     = $user->role;

        respondSuccess([
            'message' => 'Logged in successfully.',
            'user' => [
                'user_id'  => $user->user_id,
                'username' => $user->username,
                'role'     => $user->role,
            ]
        ]);
    }

    public function me()
    {
        setCorsHeaders();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            respondError('Method not allowed', 405);
        }

        startSecureSession();

        if (empty($_SESSION['user_id'])) {
            respondError('Unauthenticated', 401);
        }

        $stmt = $this->connection->prepare(
            "SELECT user_id, username, role, created_at FROM ioi_users WHERE user_id = ?"
        );

        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user) {
            respondError('User not found', 404);
        }

        respondSuccess(['user' => $user]);
    }

    public function logout()
    {
        setCorsHeaders();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            respondError('Method not allowed', 405);
        }

        startSecureSession();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        respondSuccess(['message' => 'Logged out successfully.']);
    }
}