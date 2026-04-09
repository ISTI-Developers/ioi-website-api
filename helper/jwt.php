<?php



class JWT {

private $secret = 'Secret&LoveSong143==xD';


public function generate($id, $username) {

    $payload = [
        'iat'  => time(),
        'id'   => $id,
        'u'    => $username,
        'role' => 'admin',
        'exp'  => time() + 43200,
        'iss'  => 'innovation-one'
    ];


    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

    $encodedPayload = base64_encode(json_encode($payload));

    $signature = base64_encode(hash_hmac('sha256', "$header.$encodedPayload", $this->secret, true));


    return "$header.$encodedPayload.$signature";

}


public function getPayload($token) {

    $parts = explode('.', $token);
    if(count($parts) !== 3) return false;


    [$header, $encodedPayload, $signature] = $parts;

    $expected = base64_encode(hash_hmac('sha256', "$header.$encodedPayload", $this->secret, true));

    if(!hash_equals($expected, $signature)) return false;

    $data = json_decode(base64_decode($encodedPayload), true);


    if(!isset($data['exp']) || $data['exp'] < time()) return false;


    return $data;


}

}