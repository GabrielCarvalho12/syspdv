<?php

namespace App\Repositories;

use App\Models\Venda;
use App\Services\RelatorioPDFService\GerarRelatorioDeVendasPorPeriodoPDFService;
use App\Services\RelatorioXlsService\GerarRelatorioDeVendasPorPeriodoXlsService;

class RelatorioVendasPorPeriodoRepository
{
    protected $venda;

    public function __construct()
    {
        $venda = new Venda();
        $this->venda = $venda;
    }

    public function totalVendidoPorMeioDePagamento(array $periodo, $idCaixa = false, $idEmpresa = false)
    {
        $de = $periodo['de'];
        $ate = $periodo['ate'];

        $queryPorCaixa = false;
        if ($idCaixa) {
            $queryPorCaixa = "AND vendas.caixa_id = {$idCaixa}";
        }

        $query = $this->venda->query(
            "SELECT meios_pagamentos.id AS idMeioPagamento,
            meios_pagamentos.legenda, SUM(vendas.valor) AS totalVendas FROM vendas
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            WHERE vendas.id_empresa = {$idEmpresa}
            AND DATE(vendas.created_at) BETWEEN '{$de}' AND '{$ate}' {$queryPorCaixa}
            AND vendas.deleted_at IS NULL
            GROUP BY vendas.id_meio_pagamento"
        );

        return $query;
    }

    public function totalVendidoCaixaPorMeioDePagamento($idEmpresa = false)
    {
        $query = $this->venda->query(
            "SELECT meios_pagamentos.id AS idMeioPagamento,
            meios_pagamentos.legenda, SUM(vendas.valor) AS totalVendas FROM vendas
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            WHERE vendas.id_empresa = {$idEmpresa}
            AND vendas.deleted_at IS NULL
            AND vendas.caixa_id =(SELECT id FROM caixa ORDER BY id DESC LIMIT 1)
            GROUP BY vendas.id_meio_pagamento"
        );

        return $query;
    }

    public function totalDasVendas(array $periodo, $idCaixa = false, $idEmpresa = false)
    {
        $de = $periodo['de'];
        $ate = $periodo['ate'];

        $queryPorCaixa = false;
        if ($idCaixa) {
            $queryPorCaixa = "AND vendas.caixa_id = {$idCaixa}";
        }

        $query = $this->venda->query(
            "SELECT SUM(valor) AS totalVendas FROM vendas WHERE id_empresa = {$idEmpresa}
            AND DATE(vendas.created_at) BETWEEN '{$de}' AND '{$ate}' {$queryPorCaixa}
            AND vendas.deleted_at IS NULL"
        );

        return $query[0]->totalVendas;
    }

    public function totalDasVendasCaixa($idEmpresa = false)
    {
        $query = $this->venda->query(
            "SELECT SUM(valor) AS totalVendas FROM vendas WHERE id_empresa = {$idEmpresa}
            AND vendas.deleted_at IS NULL
            AND vendas.caixa_id =(SELECT id FROM caixa ORDER BY id DESC LIMIT 1)"
        );

        return $query[0]->totalVendas;
    }

    public function gerarRelatioDeVendasPorPeriodoXls(array $periodo, $idCaixa = false, $idEmpresa = false)
    {
        $periodo = ['de' => $periodo['de'], 'ate' => $periodo['ate']];
        $vendas = $this->vendasPorPeriodo($periodo, $idCaixa, $idEmpresa);

        $gerarXls = new GerarRelatorioDeVendasPorPeriodoXlsService();
        $gerarXls->setDiretorio('public/arquivos_temporarios');
        $gerarXls->setNomeDoArquivo('Relatorio de pedidos por periodo' . $idEmpresa);

        $gerarXls->setPeriodo([
            'de' => date('d/m/Y', strtotime($periodo['de'])),
            'ate' => date('d/m/Y', strtotime($periodo['ate']))
        ]);

        $gerarXls->gerarXsl($vendas);
    }

    public function vendasPorPeriodo(array $periodo, $idCaixa = false, $idEmpresa = false)
    {
        $de = $periodo['de'];
        $ate = $periodo['ate'];

        $queryPorCaixa = false;
        if ($idCaixa) {
            $queryPorCaixa = "AND vendas.caixa_id = {$idCaixa}";
        }

        $query = $this->venda->query(
            "SELECT produtos.imagem AS produtoImagem, produtos.nome AS produtoNome,
            vendas.id AS idVenda, vendas.valor, DATE_FORMAT(vendas.created_at, '%H:%i') AS hora,
            vendas.codigo_venda AS codigoVenda,
			DATE_FORMAT(vendas.created_at, '%d/%m/%Y') AS data,
            meios_pagamentos.legenda, usuarios.id, usuarios.nome AS nomeUsuario, usuarios.imagem,
            vendas.preco, vendas.quantidade, vendas.data_compensacao,
            produtos.id AS idProduto, produtos.nome AS nomeProduto
            FROM vendas INNER JOIN usuarios
            ON vendas.id_usuario = usuarios.id
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            LEFT JOIN produtos ON vendas.id_produto = produtos.id
            WHERE vendas.id_empresa = {$idEmpresa} AND DATE(vendas.created_at)
            BETWEEN '{$de}' AND '{$ate}' {$queryPorCaixa}
            AND vendas.deleted_at IS NULL
            ORDER BY vendas.created_at DESC");

        return $query;
    }

    public function agrupamentoDeVendasPorCaixa($idEmpresa = false)
    {

        $query = $this->venda->query(
            "SELECT COUNT(*) AS produtosNaVenda, SUM(vendas.valor) AS total,
            vendas.id AS idVenda, vendas.valor, DATE_FORMAT(vendas.created_at, '%H:%i') AS hora,
            vendas.codigo_venda AS codigoVenda,
			DATE_FORMAT(vendas.created_at, '%d/%m/%Y') AS data,
            meios_pagamentos.legenda, usuarios.id, usuarios.nome AS nomeUsuario, usuarios.imagem,
            vendas.preco, vendas.quantidade, vendas.data_compensacao
            FROM vendas INNER JOIN usuarios
            ON vendas.id_usuario = usuarios.id
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            WHERE vendas.id_empresa = {$idEmpresa} AND DATE(vendas.created_at)
            AND vendas.deleted_at IS NULL
            AND vendas.caixa_id =(SELECT id FROM caixa ORDER BY id DESC LIMIT 1)
            GROUP BY vendas.codigo_venda
            ORDER BY vendas.created_at DESC");

        return $query;
    }

    public function agrupamentoDeVendasPorPeriodo(array $periodo, $idCaixa = false, $idEmpresa = false)
    {
        $de = $periodo['de'];
        $ate = $periodo['ate'];

        $queryPorCaixa = false;
        if ($idCaixa) {
            $queryPorCaixa = "AND vendas.caixa_id = {$idCaixa}";
        }

        $query = $this->venda->query(
            "SELECT COUNT(*) AS produtosNaVenda, SUM(vendas.valor) AS total,
            vendas.id AS idVenda, vendas.valor, DATE_FORMAT(vendas.created_at, '%H:%i') AS hora,
            vendas.codigo_venda AS codigoVenda,
			DATE_FORMAT(vendas.created_at, '%d/%m/%Y') AS data,
            meios_pagamentos.legenda, usuarios.id, usuarios.nome AS nomeUsuario, usuarios.imagem,
            vendas.preco, vendas.quantidade, vendas.data_compensacao
            FROM vendas INNER JOIN usuarios
            ON vendas.id_usuario = usuarios.id
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            WHERE vendas.id_empresa = {$idEmpresa} AND DATE(vendas.created_at)
            BETWEEN '{$de}' AND '{$ate}' {$queryPorCaixa}
            AND vendas.deleted_at IS NULL
            GROUP BY vendas.codigo_venda
            ORDER BY vendas.created_at DESC");

        return $query;
    }
    
    public function itensDaVenda($idEmpresa = false, $codigoVenda)
    {
        $query = $this->venda->query(
            "SELECT produtos.imagem AS produtoImagem, produtos.nome AS produtoNome,
            vendas.id AS idVenda, vendas.valor, DATE_FORMAT(vendas.created_at, '%H:%i') AS hora,
            vendas.codigo_venda AS codigoVenda,
			DATE_FORMAT(vendas.created_at, '%d/%m/%Y') AS data,
            meios_pagamentos.legenda, usuarios.id, usuarios.nome AS nomeUsuario, usuarios.imagem,
            vendas.preco, vendas.quantidade, vendas.data_compensacao,
            produtos.id AS idProduto, produtos.nome AS nomeProduto
            FROM vendas INNER JOIN usuarios
            ON vendas.id_usuario = usuarios.id
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            LEFT JOIN produtos ON vendas.id_produto = produtos.id
            WHERE vendas.id_empresa = {$idEmpresa}
            AND vendas.codigo_venda = '{$codigoVenda}'
            AND vendas.deleted_at IS NULL
            ORDER BY vendas.created_at DESC");

        return $query;
    }

    public function itensDaUltimaVenda($idEmpresa)
    {
        $query = $this->venda->query(
            "SELECT produtos.imagem AS produtoImagem, produtos.nome AS produtoNome,
            vendas.id AS idVenda, vendas.valor, DATE_FORMAT(vendas.created_at, '%H:%i') AS hora,
            vendas.codigo_venda AS codigoVenda,
			DATE_FORMAT(vendas.created_at, '%d/%m/%Y') AS data,
            meios_pagamentos.legenda, usuarios.id, usuarios.nome AS nomeUsuario, usuarios.imagem,
            vendas.preco, vendas.quantidade, vendas.data_compensacao,
            produtos.id AS idProduto, produtos.nome AS nomeProduto, vendas.observacao
            FROM vendas INNER JOIN usuarios
            ON vendas.id_usuario = usuarios.id
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            LEFT JOIN produtos ON vendas.id_produto = produtos.id
            INNER JOIN (SELECT codigo_venda FROM vendas WHERE id = (SELECT MAX(id) FROM vendas)) AS ultima_venda ON vendas.codigo_venda = ultima_venda.codigo_venda
            WHERE vendas.id_empresa = 1
            AND vendas.deleted_at IS NULL;");

        return $query;
    }

    public function detalhesDePagamentoItensDaVenda($idEmpresa, $codigoVenda)
    {
        return $this->venda->query(
            "SELECT usuarios.nome AS nomeUsuario, codigo_venda,
            id_meio_pagamento, legenda as meioPagamento,
            SUM(valor) AS total, valor_recebido,
            valor_recebido - SUM(valor) AS troco FROM vendas
            INNER JOIN meios_pagamentos ON vendas.id_meio_pagamento = meios_pagamentos.id
            INNER JOIN usuarios ON vendas.id_usuario = usuarios.id
            WHERE vendas.id_empresa = {$idEmpresa}
            AND codigo_venda = '{$codigoVenda}'")[0];
    }

    public function gerarRelatioDeVendasPorPeriodoPDF(array $periodo, $idCaixa = false, $idEmpresa = false, $empresa)
    {
        $periodo = ['de' => $periodo['de'], 'ate' => $periodo['ate']];
        $vendas = $this->vendasPorPeriodo($periodo, $idCaixa, $idEmpresa);

        $pdfService = new GerarRelatorioDeVendasPorPeriodoPDFService();
        $pdfService->setDiretorio('public/arquivos_temporarios');
        $pdfService->setNomeDoArquivo('Relatorio de pedidos por periodo' . $idEmpresa);

        $pdfService->setPeriodo([
            'de' => date('d/m/Y', strtotime($periodo['de'])),
            'ate' => date('d/m/Y', strtotime($periodo['ate']))
        ]);

        $pdfService->setTotalVendas(
            $this->totalDasVendas(
                $periodo,
                $idCaixa,
                $idEmpresa
        ));

        $pdfService->setEmpresa($empresa);
        $pdfService->gerarPDF($vendas);
    }

    public function periodoDisponivelParaConsulta($idEmpresa)
    {
        $query = $this->venda->queryGetOne(
            "SELECT DATE_FORMAT(created_at, '%d/%m/%Y') AS primeiraVenda,
            (SELECT DATE_FORMAT(created_at, '%d/%m/%Y') FROM vendas WHERE
            id_empresa = {$idEmpresa}
            AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 1) AS ultimaVenda
            FROM vendas WHERE id_empresa = {$idEmpresa}
            AND deleted_at IS NULL ORDER BY created_at ASC LIMIT 1
        ");

        if ( ! isset($query->primeiraVenda) || ! isset($query->ultimaVenda)) {
            return (object) ['primeiraVenda' => null, 'ultimaVenda' => null];
        }

        return $query;
    }

    public function caixas()
    {
        $query = $this->venda->query(
            "SELECT id FROM caixa ORDER BY id DESC LIMIT 30;"
        );

        if (empty($query)) {
            return [(object) ['id' => null]];
        }

        $resultados = [];
        foreach ($query as $row) {
            $resultados[] = (object) ['id' => $row->id];
        }

        return $resultados;
    }
}
