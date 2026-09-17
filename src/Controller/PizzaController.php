<?php

namespace App\Controller;

use App\Model\PizzaModel;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API Rest de Pizzas",
    version: "1.0.0",
    description: "Documentação do catálogo de pizzas da API REST"
)]
#[OA\Server(
    url: "http://localhost/pizzaria_api",
    description: "Servidor de desenvolvimento local da API"
)]
#[OA\Tag(
    name: "Pizzas",
    description: "Endpoints para gerenciamento do catálogo de pizzas"
)]
class PizzaController {
    private PizzaModel $model;

    public function __construct() {
        $this->model = new PizzaModel();
    }

    private function responderJson(mixed $dados, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
        exit;
    }

    #[OA\Get(
        path: "/pizzas",
        summary: "Lista todas as pizzas registradas",
        tags: ["Pizzas"]
    )]
    #[OA\Response(response: 200, description: "Requisição concluída com sucesso")]
    #[OA\Response(response: 500, description: "Erro ao listar pizzas")]
    public function listar(): void {
        $pizzas = $this->model->buscarTodas();
        $this->responderJson(["sucesso" => true, "dados" => $pizzas]);
    }

    #[OA\Post(
        path: "/pizzas",
        summary: "Registro de nova pizza",
        tags: ["Pizzas"]
    )]
    #[OA\Response(response: 201, description: "Pizza cadastrada com sucesso")]
    #[OA\Response(response: 400, description: "Dados inválidos enviados")]
    public function cadastrar(): void {
        $dados = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($dados['nome']) || empty($dados['ingredientes']) || !isset($dados['preco'])) {
            $this->responderJson(["sucesso" => false, "mensagem" => "Campos obrigatórios: nome, ingredientes e preco."], 400);
        }

        $id = $this->model->criar($dados);
        $this->responderJson(["sucesso" => true, "mensagem" => "Pizza cadastrada com sucesso!", "id" => $id], 201);
    }

    #[OA\Get(
        path: "/pizzas/{id}",
        summary: "Obtendo informações de uma pizza",
        tags: ["Pizzas"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID único da pizza"
    )]
    #[OA\Response(response: 200, description: "Pizza encontrada com sucesso")]
    #[OA\Response(response: 404, description: "Pizza não encontrada")]
    public function buscar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if ($pizza) {
            $this->responderJson(["sucesso" => true, "dados" => $pizza]);
        } else {
            $this->responderJson(["sucesso" => false, "mensagem" => "Pizza não encontrada."], 404);
        }
    }

    #[OA\Put(
        path: "/pizzas/{id}",
        summary: "Atualizar pizza",
        tags: ["Pizzas"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID único da pizza"
    )]
    #[OA\Response(response: 200, description: "Pizza atualizada com sucesso")]
    #[OA\Response(response: 400, description: "Dados inválidos")]
    #[OA\Response(response: 404, description: "Pizza não encontrada")]
    public function atualizar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if (!$pizza) {
            $this->responderJson(["sucesso" => false, "mensagem" => "Pizza não encontrada para atualização."], 404);
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($dados['nome']) || empty($dados['ingredientes']) || !isset($dados['preco'])) {
            $this->responderJson(["sucesso" => false, "mensagem" => "Campos obrigatórios: nome, ingredientes e preco."], 400);
        }

        $this->model->atualizar($id, $dados);
        $this->responderJson(["sucesso" => true, "mensagem" => "Pizza atualizada com sucesso!"]);
    }

    #[OA\Delete(
        path: "/pizzas/{id}",
        summary: "Exclusão de pizza",
        tags: ["Pizzas"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID único da pizza"
    )]
    #[OA\Response(response: 200, description: "Pizza removida com sucesso")]
    #[OA\Response(response: 404, description: "Pizza não encontrada")]
    public function deletar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if (!$pizza) {
            $this->responderJson(["sucesso" => false, "mensagem" => "Pizza não encontrada para exclusão."], 404);
        }

        $this->model->deletar($id);
        $this->responderJson(["sucesso" => true, "mensagem" => "Pizza removida com sucesso!"]);
    }
}