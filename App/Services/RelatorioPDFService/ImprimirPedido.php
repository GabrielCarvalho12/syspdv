<?php

namespace App\Services\RelatorioPDFService;
use Exception;
require_once __DIR__ . '../../../../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ImprimirPedido
{
    public function imprimirPedido($dadosVenda, $informacaoPagamento)
    {
        $connector = new FilePrintConnector("php://stdout");
        $printer = new Printer($connector);

        try {
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            title($printer, "La Matilha Burguer\n");
            $printer -> text("Endereço: R. Luís Gomes - Bairro Açucena Velha\n");
            $printer -> text("(99) 99215-2539\n");
            $printer -> setJustification();
            $printer -> feed();

            $printer -> text($dadosVenda[0]->idVenda."\n");
            $printer -> text("Gerado em: ". date('d/m/Y')."às".date('H:i')."\n");
            $printer -> text("Forma de Pagamento: ".$informacaoPagamento->meioPagamento."\n");
            $printer -> feed();

            $printer -> text("|...Produto...|...Qtd...|...Preço...|...Total...|\n");
            foreach ($dadosVenda as $venda){
                $printer -> text("|...".stringAbreviation($venda->produtoNome, 15, '...')."...|...".$venda->quantidade."...|...R$ ". real($venda->valor)."...|...R$ ".real($venda->preco)."...|\n");
                $printer -> setEmphasis(true);
                $printer -> text("Obs.: ". $venda->observacao ."\n");
                $printer -> setEmphasis(false);          
            };
            $printer -> feed();

            $printer -> text("Total: R$ ".real($informacaoPagamento->total)."\n");
            if ($informacaoPagamento->id_meio_pagamento == 1){
                $printer -> text("À receber: R$ ".real($informacaoPagamento->valor_recebido)."\n");
                $printer -> text("Troco: R$ ".real($informacaoPagamento->troco)."\n");
            }
            $printer -> feed();

        } catch (Exception $e) {
            $printer -> text($e -> getMessage() . "\n");
        }

        $printer -> cut();
        $printer -> close();
    }
}
