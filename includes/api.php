<?php
require_once dirname(__DIR__) . '/config/config.php';

function api_request(string $url, string $method = 'GET', ?array $body = null): array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . API_TOKEN,
        'Content-Type: application/json',
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($error) return ['success' => false, 'error' => 'Connection error: ' . $error];
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE)
        return ['success' => false, 'error' => 'JSON decode error'];
    return array_merge(['http_code' => $httpCode], $data ?? []);
}

function get_products(): array
{
    $result = api_request(API_URL);
    if (!empty($result['success']) && isset($result['productos']))
        return $result['productos'];
    return ['error' => $result['error'] ?? 'Error desconocido'];
}

function crear_pedido(array $datos): array
{
    return api_request(API_PEDIDOS_URL, 'POST', $datos);
}