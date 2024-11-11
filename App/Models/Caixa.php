<?php

namespace App\Models;

use System\Model\Model;

class Caixa extends Model
{
    protected $table = 'caixa';
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    // Função para abrir o caixa
    public function abrirCaixa($usuarioId)
    {
        // Verifica se já existe um caixa aberto
        $result = $this->verificarStatusCaixa();
        if ($result->status === 'aberto') {

            return false;

        }else{
            $this->insert("INSERT INTO caixa (data_abertura, status, usuario_abertura) VALUES (NOW(), 'aberto', ?)", [$usuarioId]);

            return true;
        }
    }

    // Função para fechar o caixa
    public function fecharCaixa($usuarioId)
    {
        // Verifica se existe um caixa aberto para ser fechado
        $result = $this->verificarStatusCaixa();
        if ($result->status === 'fechado') {

            return false;

        }else{
            $this->query("UPDATE caixa SET data_fechamento = NOW(), 
                             status = 'fechado', usuario_fechamento = {$usuarioId} 
                             WHERE status = 'aberto' ORDER BY id DESC LIMIT 1");

            return true;
        }
    }

    // Função para verificar o status atual do caixa
    public function verificarStatusCaixa()
    {
        $caixa = $this->queryGetOne("SELECT status FROM caixa ORDER BY id DESC LIMIT 1");

        return $caixa;
    }

    // Função para verificar o status atual do caixa
    public function caixaAberto()
    {
        $caixa = $this->queryGetOne("SELECT id FROM caixa ORDER BY id DESC LIMIT 1");

        return $caixa;
    }
}
