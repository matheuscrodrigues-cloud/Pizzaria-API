<?php

namespace App\Controller;

use App\Model\PizzaModel;

class PizzaController {
    private PizzaModel $model;

    public function __construct() {
        $this->model = new PizzaModel();
    }

    public function listar(): void {
        $pizzas = $this->model->buscarTodas();
        $this->responderJSON(200, [
            "sucesso" => true,
            "dados" => $pizzas
        ]);
    }

    public function buscar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if (!$pizza) {
            $this->responderJSON(404, [
                "sucesso" => false,
                "mensagem" => "Pizza não encontrada."
            ]);
            return;
        }

        $this->responderJSON(200, [
            "sucesso" => true,
            "dados" => $pizza
        ]);
    }

    public function cadastrar(): void {
        $dados = json_decode(file_get_contents('php://input'), true);

        if (empty($dados['nome']) || empty($dados['ingredientes']) || !isset($dados['preco'])) {
            $this->responderJSON(400, [
                "sucesso" => false,
                "mensagem" => "Campos obrigatórios: nome, ingredientes e preco."
            ]);
            return;
        }

        $id = $this->model->criar($dados);
        $this->responderJSON(201, [
            "sucesso" => true,
            "mensagem" => "Pizza cadastrada com sucesso!",
            "id" => $id
        ]);
    }

    public function atualizar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if (!$pizza) {
            $this->responderJSON(404, [
                "sucesso" => false,
                "mensagem" => "Pizza não encontrada."
            ]);
            return;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($dados['nome']) || empty($dados['ingredientes']) || !isset($dados['preco'])) {
            $this->responderJSON(400, [
                "sucesso" => false,
                "mensagem" => "Campos obrigatórios: nome, ingredientes e preco."
            ]);
            return;
        }

        $this->model->atualizar($id, $dados);
        $this->responderJSON(200, [
            "sucesso" => true,
            "mensagem" => "Pizza atualizada com sucesso!"
        ]);
    }

    public function deletar(int $id): void {
        $pizza = $this->model->buscarPorId($id);
        if (!$pizza) {
            $this->responderJSON(404, [
                "sucesso" => false,
                "mensagem" => "Pizza não encontrada."
            ]);
            return;
        }

        $this->model->deletar($id);
        $this->responderJSON(200, [
            "sucesso" => true,
            "mensagem" => "Pizza removida com sucesso!"
        ]);
    }

    private function responderJSON(int $status, array $dados): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}