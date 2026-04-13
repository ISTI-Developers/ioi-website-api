<?php



class JWT {

private $secret = 'Secret&LoveSong143==xD';

private function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

private function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}


public function generate($id, $username) {

    $payload = [
        'iat'  => time(),
        'id'   => $id,
        'u'    => $username,
        'role' => 'admin',
        'exp'  => time() + 43200,
        'iss'  => 'innovation-one'
    ];


    $header = $this->base64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

    $encodedPayload = $this->base64url_encode(json_encode($payload));

    $signature = $this->base64url_encode(hash_hmac('sha256', "$header.$encodedPayload", $this->secret, true));

    return "$header.$encodedPayload.$signature";

}


public function getPayload($token) {

    $parts = explode('.', $token);
    if(count($parts) !== 3) return false;


    [$header, $encodedPayload, $signature] = $parts;

    $expected = $this->base64url_encode(hash_hmac('sha256', "$header.$encodedPayload", $this->secret, true));

    if(!hash_equals($expected, $signature)) return false;

    $data = json_decode($this->base64url_decode($encodedPayload), true);


    if(!isset($data['exp']) || $data['exp'] < time()) return false;


    return $data;


}

}