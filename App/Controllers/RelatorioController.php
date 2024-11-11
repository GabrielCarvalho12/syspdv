<?php

namespace App\Controllers;

use App\Models\Empresa;
use App\Repositories\RelatorioVendasPorPeriodoRepository;
use App\Rules\Logged;
use App\Services\Usuarios\BuscaUsuariosService;
use App\Services\RelatorioPDFService\RelatorioPdfDeUmaVenda;
use App\Services\RelatorioPDFService\ImprimirPedido;
use DateTime;
use System\Controller\Controller;
use System\Get\Get;
use System\Post\Post;
use System\Session\Session;

class RelatorioController extends Controller
{
    protected $post;
    protected $get;
    protected $layout;
    protected $idEmpresa;
    protected $idPerfilUsuarioLogado;
    protected $buscaUsuariosService;

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'default';

        $this->post = new Post();
        $this->get = new Get();
        $this->idEmpresa = Session::get('idEmpresa');
        $this->idPerfilUsuarioLogado = Session::get('idPerfil');

        $logged = new Logged();
        $logged->isValid();

        $this->buscaUsuariosService = new BuscaUsuariosService();
    }

    public function index()
    {
        $this->view('relatorio/index', $this->layout);
    }

    public function vendasPorPeriodo()
    {
        $usuarios = $this->buscaUsuariosService->ativos($this->idEmpresa, $this->idPerfilUsuarioLogado);

        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $periodoDisponivelParaConsulta = $relatorioVendas->periodoDisponivelParaConsulta($this->idEmpresa);
        $caixas = $relatorioVendas->caixas();

        $this->view('relatorio/vendasPorPeriodo/index', $this->layout,
        compact(
            'usuarios',
            'periodoDisponivelParaConsulta',
            'caixas'
        ));
    }

    public function vendasCaixaChamadaAjax()
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $vendas = [];

        if ($this->post->hasPost()) {

            $vendas = $relatorioVendas->agrupamentoDeVendasPorCaixa(
                $this->idEmpresa
            );

            $meiosDePagamento = $relatorioVendas->totalVendidoCaixaPorMeioDePagamento(
                $this->idEmpresa
            );

            $totalDasVendas = $relatorioVendas->totalDasVendasCaixa(
                $this->idEmpresa
            );
        }

        $this->view('relatorio/vendasPorPeriodo/tabelaVendasPorPeriodo', false,
            compact(
                'vendas',
                'meiosDePagamento',
                'totalDasVendas'
            ));
    }

    public function vendasChamadaAjax()
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $vendas = [];

        if ($this->post->hasPost()) {

            $de = $this->post->data()->de;
            $ate = $this->post->data()->ate;

            $idCaixa = false;
            if ($this->post->data()->id_caixa != 'todos') {
                $idCaixa = $this->post->data()->id_caixa;
            }

            $vendas = $relatorioVendas->agrupamentoDeVendasPorPeriodo(
                ['de' => $de, 'ate' => $ate],
                $idCaixa,
                $this->idEmpresa
            );

            if ($vendas && count($vendas) > 0) {
                $vendas = array_map(function ($venda) {
                    if ($venda->data_compensacao) {
                        $date = DateTime::createFromFormat("Y-m-d", $venda->data_compensacao);
                        $venda->data_compensacao = $date->format("d/m/Y");
                    }
                    return $venda;
                }, $vendas);
            }

            $meiosDePagamento = $relatorioVendas->totalVendidoPorMeioDePagamento(
                ['de' => $de, 'ate' => $ate],
                $idCaixa,
                $this->idEmpresa
            );

            $totalDasVendas = $relatorioVendas->totalDasVendas(
                ['de' => $de, 'ate' => $ate],
                $idCaixa,
                $this->idEmpresa
            );
        }

        $this->view('relatorio/vendasPorPeriodo/tabelaVendasPorPeriodo', false,
            compact(
                'vendas',
                'meiosDePagamento',
                'totalDasVendas'
            ));
    }

    public function itensDaVendaChamadaAjax($codigoVenda)
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $vendas = $relatorioVendas->itensDaVenda(
            $this->idEmpresa,
            out64($codigoVenda)
        );

        $detalhesDePagamentoItensDaVenda = $relatorioVendas->detalhesDePagamentoItensDaVenda(
            $this->idEmpresa,
            out64($codigoVenda)
        );

        $this->view('relatorio/vendasPorPeriodo/tabelaItensDaVenda', false,
        compact(
            'vendas',
            'detalhesDePagamentoItensDaVenda'
        ));
    }

    public function gerarXls($de, $ate, $opcao = false)
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $periodo = ['de' => $de, 'ate' => $ate];

        $idCaixa = ($opcao == 'todos') ? false : $opcao;
        $relatorioVendas->gerarRelatioDeVendasPorPeriodoXls($periodo, $idCaixa, $this->idEmpresa);
    }

    public function gerarPDF($de, $ate, $opcao = false)
    {
        $empresa = new Empresa();
        $empresa = $empresa->find($this->idEmpresa);

        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $periodo = ['de' => $de, 'ate' => $ate];

        $caixa = ($opcao == 'todos') ? false : $opcao;
        $relatorioVendas->gerarRelatioDeVendasPorPeriodoPDF(
            $periodo,
            $caixa,
            $this->idEmpresa,
            $empresa
        );
    }

    public function gerarPedidoDeUmaVenda($codigoVenda)
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $vendas = $relatorioVendas->itensDaVenda(
            $this->idEmpresa,
            out64($codigoVenda)
        );

        $detalhesDePagamentoItensDaVenda = $relatorioVendas->detalhesDePagamentoItensDaVenda(
            $this->idEmpresa,
            out64($codigoVenda)
        );

        $empresa = new Empresa();
        $empresa = $empresa->find($this->idEmpresa);

        $imprimirPedido = new ImprimirPedido();
        $imprimirPedido->imprimirPedido($vendas, $detalhesDePagamentoItensDaVenda);
    }

    public function gerarPedidoDaUltimaVenda()
    {
        $relatorioVendas = new RelatorioVendasPorPeriodoRepository();
        $vendas = $relatorioVendas->itensDaUltimaVenda(
            $this->idEmpresa
        );

        $detalhesDePagamentoItensDaVenda = $relatorioVendas->detalhesDePagamentoItensDaVenda(
            $this->idEmpresa,
            $vendas[0]->codigoVenda
        );

        $empresa = new Empresa();
        $empresa = $empresa->find($this->idEmpresa);

        $imprimirPedido = new ImprimirPedido();
        $imprimirPedido->imprimirPedido($vendas, $detalhesDePagamentoItensDaVenda);
    }
}
