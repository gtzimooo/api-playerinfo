<?php
header("Content-Type: application/json");

// Verifica se enviou o nick
if (!isset($_GET['nick']) || empty($_GET['nick'])) {
    echo json_encode([
        "status" => false,
        "erro" => "Envie ?nick=NomeDoPlayer"
    ]);
    exit;
}

$nick = urlencode($_GET['nick']);

// API de busca (Rolimons)
$searchUrl = "https://api.rolimons.com/players/v1/playersearch?searchstring={$nick}";

// Função para pegar dados com fallback de proxy
function fetchData($url) {
    $proxies = [
        "https://corsproxy.io/?",
        "https://api.allorigins.win/raw?url="
    ];

    foreach ($proxies as $proxy) {
        $fullUrl = $proxy . urlencode($url);

        $response = @file_get_contents($fullUrl);
        if ($response !== false) {
            return json_decode($response, true);
        }
    }

    return null;
}

// Busca jogador
$data = fetchData($searchUrl);

if (!$data || !$data['success'] || empty($data['players'])) {
    echo json_encode([
        "status" => false,
        "erro" => "Jogador não encontrado"
    ]);
    exit;
}

// Pega o primeiro resultado
$player = $data['players'][0];
$id = $player[0];
$name = $player[1];

// Avatar
$avatarUrl = "https://thumbnails.rolimons.com/avatar?userIds={$id}&size=150x150";
$avatarData = fetchData($avatarUrl);

$avatar = null;

if (isset($avatarData['thumbnails'][$id]['url'])) {
    $avatar = $avatarData['thumbnails'][$id]['url'];
} else {
    $avatar = "https://tr.rbxcdn.com/default-avatar.png";
}

// Resposta final
echo json_encode([
    "status" => true,
    "nick" => $name,
    "id" => $id,
    "avatar" => $avatar,
    "perfil" => "https://www.roblox.com/users/{$id}/profile"
], JSON_PRETTY_PRINT);