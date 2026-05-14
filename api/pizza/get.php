<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo
include_once '../../config/Database.php';
include_once '../../models/Pizza.php';
 

$database = new Database();
$db = $database->getConnection();
 

$pizza = new Pizza($db);
 
$pizza->idPizza = isset($_GET['id']) ? $_GET['id'] : null;
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ID não informado
    if ($pizza->idPizza <= 0) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array(
            "message" => "id não informado."
        ));
    } elseif ($pizza->get()) {

    header("HTTP/1.1 200 OK");

    $pizza_arr = array(
        "idPizza" => $pizza->idPizza,
        "nome" => $pizza->nome,
        "ingredientes" => $pizza->ingredientes,
        "valor" => $pizza->valor
    );

    echo json_encode($pizza_arr);
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