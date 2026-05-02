<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite que o bot acesse

if (isset($_GET['nick'])) {
    $nick = $_GET['nick'];
    
    // Aqui vai a sua lógica de busca (provavelmente usando a API do Rolimons ou Roblox)
    // Exemplo de como o PHP deve responder:
    $ch = curl_init("https://api.rolimons.com/players/v1/playersearch?searchstring=" . urlencode($nick));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $response = curl_exec($ch);
    $data = json_decode($response, true);

    if ($data && $data['success'] && count($data['players']) > 0) {
        $id = $data['players'][0][0];
        $name = $data['players'][0][1];
        
        echo json_encode([
            "status" => true,
            "nick" => $name,
            "id" => $id,
            "avatar" => "https://tr.rbxcdn.com/30DAY-Avatar-Headshot-E93C3B9138096181F5B472856E243163-Png/150/150/AvatarHeadshot/Webp/noFilter",
            "perfil" => "https://www.roblox.com/users/$id/profile"
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Não encontrado"]);
    }
}
?>
