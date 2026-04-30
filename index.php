<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
 
echo json_encode(array("Mensagem" => "Ola! Bem-Vindo ao JucaPizzas!"));
//echo json_encode(array("Mensagem" => "Ola! Bem-Vindo ao JucaPizzas!"));