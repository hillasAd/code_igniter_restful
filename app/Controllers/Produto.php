<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use CodeIgniter\RESTful\ResourceController;

class Produto extends ResourceController
{
    private $produtoModel;
    private $token = "hillasAd2022boss85000MzGR";

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    private function _validateToken()
    {
        return $this->request->getHeaderLine('token') == $this->token;
    }

    //GET ALL
    function list() {
        $data = $this->produtoModel->findAll();
        return $this->response->setJSON($data);
    }

    //INSERT NEW PRODUCT
    public function create()
    {
        $response = [];
        //validate token
        if ($this->_validateToken() == true) {
            //Catch data from body
            $newProduto['nome'] = $this->request->getPost('nome');
            $newProduto['valor'] = $this->request->getPost('valor');

            try {
                if ($this->produtoModel->insert($newProduto)) {
                    $response = [
                        'response' => 'success',
                        'msg' => 'Produto inserido com sucesso.',
                    ];
                } else {
                    $response = [
                        'response' => 'error',
                        'msg' => 'Erro ao salvar o produto',
                        'errors' => $this->produtoModel->errors(),
                    ];
                }
            } catch (\Exception$e) {
                $response = [
                    'response' => 'error',
                    'msg' => 'Erro ao salvar o produto',
                    'errors' => [
                        'exception' => $e->getMessage(),
                    ],
                ];
            }
        } else {
            $response = [
                'response' => 'error',
                'msg' => 'Token invalido',
            ];
        }
        return $this->response->setJSON($response);

    }
}
