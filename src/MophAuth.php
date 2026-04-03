<?php
namespace sakmobile\MophAuth;

class MophAuth
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // 🔗 Step 1: URL สำหรับ Login
    public function getLoginUrl()
    {
        return $this->config['health_url'] . "/oauth/redirect?" . http_build_query([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'state' => bin2hex(random_bytes(16))
        ]);
    }

    // 🔑 Step 2: เอา code มาแลก token
    public function getHealthToken($code)
    {
        $response = $this->postForm(
            $this->config['health_url'] . "/api/v1/token",
            [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->config['redirect_uri'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret']
            ]
        );

        if (!isset($response['data']['access_token'])) {
            throw new Exception("Health Token Error: " . json_encode($response));
        }

        return $response['data']['access_token'];
    }

    // 🏥 Step 3: เอา Health Token ไปแลก Provider Token
    public function getProviderToken($healthToken)
    {
        $response = $this->postJson(
            $this->config['provider_url'] . "/api/v1/services/token",
            [
                'client_id' => $this->config['provider_client_id'],
                'secret_key' => $this->config['provider_secret'],
                'token_by' => 'Health ID',
                'token' => $healthToken
            ]
        );

    if (!isset($response['data']['access_token'])) {
        throw new Exception("Provider Token Error: " . json_encode($response));
    }

        return $response;
    }
    // Get Profile
    public function getProfile($providerToken)
    {
        $url = $this->config['provider_url'] . "/api/v1/services/profile?" . http_build_query([
            'moph_center_token' => 1,
            'moph_idp_permission' => 1,
            'position_type' => 1
        ]);

        $response = $this->getRequest($url, $providerToken);

        if (!isset($response['status']) || $response['status'] != 200) {
            throw new Exception("Profile Error: " . json_encode($response));
        }

        return $response['data'] ?? [];
}

    // 📡 Helper POST (form)
    private function postForm($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return json_decode($result, true);
    }

    // 📡 Helper POST (json)
    private function postJson($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return json_decode($result, true);
    }
// 📡 Helper GET (json)

    private function getRequest($url, $token)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'client-id: ' . $this->config['provider_client_id'],
                'secret-key: ' . $this->config['provider_secret'],
            ]
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return json_decode($result, true);
    }

    
} 
