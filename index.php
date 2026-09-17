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
$uriParts = explode('/', trim($uri, '/'));
$method = $_SERVER['REQUEST_METHOD'];

// Rota base para pizzas
if (isset($uriParts[0]) && $uriParts[0] === 'pizzas') {
    $controller = new PizzaController();
    $id = isset($uriParts[1]) && is_numeric($uriParts[1]) ? (int) $uriParts[1] : null;

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
                echo json_encode(["sucesso" => false, "mensagem" => "ID é obrigatório para atualização."]);
            }
            break;

        case 'DELETE':
            if ($id) {
                $controller->deletar($id);
            } else {
                http_response_code(400);
                echo json_encode(["sucesso" => false, "mensagem" => "ID é obrigatório para exclusão."]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["sucesso" => false, "mensagem" => "Método não permitido."]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(["sucesso" => false, "mensagem" => "Rota não encontrada."]);
}