<?php
//CRIAÇÃO ROTA GET.PHP
// Headers obrigatórios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo
include_once '../../config/Database.php';
include_once '../../models/bebidas.php';
 
// Instanciar o objeto Database e obter a conexão
$database = new Database();
$db = $database->getConnection();
 
// Instanciar o objeto Bebidas
$bebidas = new Bebidas($db);
 
$bebidas->idBebidas = isset($_GET['id']) ? $_GET['id'] : null;
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ID não informado
    if ($bebidas->idBebidas <= 0) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array(
            "message" => "id não informado."
        ));
    } elseif ($bebidas->get()) {

    header("HTTP/1.1 200 OK");

    $bebidas_arr = array(
        "idBebidas" => $bebidas->idBebidas,
        "nome" => $bebidas->nome,
        "litros" => $bebidas->litros,
        "valor" => $bebidas->valor
    );

    echo json_encode($bebidas_arr);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(array(
            "message" => "id inválido."
        ));
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(array(
        "message" => "Método não permitido."
    ));
}
?>