<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 204 No Content");
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(array("message" => "Método não permitido. Use POST."));
    exit;
}
 
include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';
 

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->nome, $data->litros, $data->valor)) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("message" => "Envie JSON com nome, litros e valor."));
    exit;
}
 
$database = new Database();
$db = $database->getConnection();
if (!$db) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro de conexão com o banco."));
    exit;
}
 
$bebidas = new Bebidas($db);
$bebidas->nome = $data->nome;
$bebidas->litros = $data->litros;
$bebidas->valor = $data->valor;
 
if ($bebidas->create()) {
    
    header("HTTP/1.1 201 Created");
    echo json_encode(array(
        "message" => "Bebida criada.",
        "id" => (int) $bebidas->idBebidas,
        "nome" => $bebidas->nome,
        "litros" => $bebidas->litros,
        "valor" => (float) $bebidas->valor,
    ));
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Não foi possível criar a bebida."));
}