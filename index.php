<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\PizzaController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriParts = array_values(array_filter(explode('/', trim($uri, '/'))));
$method = $_SERVER['REQUEST_METHOD'];


$key = array_search('pizzas', $uriParts);

if ($key !== false) {
    $controller = new PizzaController();
    
 
    $idIndex = $key + 1;
    $id = isset($uriParts[$idIndex]) && is_numeric($uriParts[$idIndex]) ? (int) $uriParts[$idIndex] : null;

    switch ($method) {
        case 'GET':
            if ($id) {
                $controller->buscar($id);
            } else {
                $controller->listar();
            }
            break;

        case 'POST':
            $controller->cadastrar();
            break;

        case 'PUT':
            if ($id) {
                $controller->atualizar($id);
            } else {
                http_response_code(400);
                echo json_encode(["sucesso" => false, "mensagem" => "ID é obrigatório para atualização."], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'DELETE':
            if ($id) {
                $controller->deletar($id);
            } else {
                http_response_code(400);
                echo json_encode(["sucesso" => false, "mensagem" => "ID é obrigatório para exclusão."], JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["sucesso" => false, "mensagem" => "Método não permitido."], JSON_UNESCAPED_UNICODE);
            break;
    }
} else {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["sucesso" => false, "mensagem" => "Rota não encontrada."], JSON_UNESCAPED_UNICODE);
}