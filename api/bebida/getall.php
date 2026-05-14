<?php
// api/pizza/read.php
 
// Headers obrigatórios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo
include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';
 
// Instanciar o objeto Database e obter a conexão
$database = new Database();
$db = $database->getConnection();
 
// Instanciar o objeto Pizza
$bebidas = new Bebidas($db);
 
// try{ colocar para demonstrar erro com coluna errada mas lá no método read em pizza
    // Chamar o método read() para buscar as pizzas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $bebidas->getall();
    $num = $stmt->rowCount();
 
    // Verificar se mais de 0 registros foram encontrados
    if ($num > 0) {

        // Array de pizzas
        $bebidas_arr = array();
 
        // Percorrer o resultado da consulta
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // A função extract transforma $row['nome'] em apenas $nome
            extract($row);

            // Criar um array associativo para cada pizza encontrada
                $bebida_item = array(
                "id" => $idBebidas, 
                "nome" => $nome,
                "litros" => $litros,
                "valor" => $valor
            );

            // Adicionar o array associativo da bebida ao array de bebidas
            array_push($bebidas_arr, $bebida_item);
        }
 
        // Definir o código de resposta como 200 OK
        header("HTTP/1.1 200 OK");
 
        // Mostrar os dados das bebidas em formato JSON
        echo json_encode($bebidas_arr);
    } else {
        // Se nenhuma bebida for encontrada, definir o código de resposta como 404 Not Found
        header("HTTP/1.1 404 Not Found");
 
        // Informar ao usuário que nenhuma bebida foi encontrada
        echo json_encode(
            array("message" => "Nenhuma bebida encontrada.")
        );
    }
} else {
    // Se o método HTTP não for GET, definir o código de resposta como 405 Method Not Allowed
    header("HTTP/1.1 405 Method Not Allowed");
 
    // Informar ao usuário que o método não é permitido
    echo json_encode(
        array("message" => "Método não permitido. Use GET.")
    );
}

// }
// catch (Exception $e) {
//  echo json_encode(array("erro" => $e->getMessage()));
// }