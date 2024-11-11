<style type="text/css">
    .imagem-perfil {
        width: 30px;
        height: 30px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
    }

    .cool-btn {
        background: #1da1f2;
        color: white !important;
    }
    @media only screen and (max-width: 600px) {
        .imagem-perfil {
            width: 20px;
            height: 20px;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;

        }
    }
    .div-agrupamento-de-vendas {
        border:1px solid #e5e5e5;
        padding:10px;
        border-radius:10px;
        margin-bottom:5px;
    }
    .sub-div {
        background:#e5e5e5;
        padding:10px;
        border-radius:10px;
        margin-right:10px;
    }
</style>

<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title">
                <?php iconFilter(); ?>
                Filtros
            </h5>
        </div>

        <form method="POST" action="<?php echo BASEURL; ?>/relatorio/vendasChamadaAjax" id="form">

            <!-- token de segurança -->
            <input type="hidden" name="_token" value="<?php echo TOKEN; ?>"/>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="periodo_de">Período de</label>
                        <input type="date" class="form-control busca-sem-codigo" name="de" id="periodo_de"
                               value="<?php echo date('Y') . '-' . date('m') . '-' . '01' ?>">
                        <small style="color:#999999">Primeiro Pedido: <?php echo ( ! is_null($periodoDisponivelParaConsulta->primeiraVenda) ? $periodoDisponivelParaConsulta->primeiraVenda : 'Não realizada!');?></small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="periodo_ate">Período até</label>
                        <input type="date" class="form-control busca-sem-codigo" name="ate" id="periodo_ate"
                               value="<?php echo date('Y-m-d') ?>">
                        <small style="color:#999999">Ultimo Pedido:
                        <?php echo ( ! is_null($periodoDisponivelParaConsulta->ultimaVenda) ? $periodoDisponivelParaConsulta->ultimaVenda : 'Não realizada!');?></small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="id_caixa">Caixas</label>
                        <select class="form-control js-example-basic-single busca-sem-codigo" name="id_caixa" id="id_caixa">
                            <option value="todos">Todos</option>
                            <?php foreach ($caixas as $caixa) : ?>
                                <option value="<?php echo $caixa->id; ?>">
                                    <?php echo $caixa->id; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-success text-right pull-right" id="buscar-vendas"
                            style="margin-left:10px">
                        <i class="fas fa-search"></i> Buscar
                    </button>

                    <a onclick="baixarEmFormatoXls()" class="cool-btn btn btn-sm btn-defoult text-right pull-right"
                       id="baixar-xls" title="Baixar em formato XLS!">
                        <i class="fas fa-cloud-download-alt"></i> Xls
                    </a>

                    <a onclick="baixarEmFormatoPDF()" class="cool-btn btn btn-sm btn-defoult text-right pull-right"
                       id="baixar-pdf" title="Baixar em formato PDF!">
                        <i class="fas fa-cloud-download-alt"></i> PDF
                    </a>

                </div>

            </div>
            <!--end row-->
        </form>

        <br>

    </div>
</div>

<div class="row">
    <div class="card col-lg-12 content-div">
        <!--Renderiza a tabela de vendas-->
        <div id="div-tabela-vendas"></div>
        <br>
    </div>
</div>

<script src="<?php echo BASEURL; ?>/public/assets/js/core/jquery.min.js"></script>

<script type="text/javascript">
    $("#buscar-vendas").click(function () {
        vendas();
        return false;
    });

    function vendas() {
        $('#div-tabela-vendas').html('<br><center><h3>Carregando...</h3></center>');
        var rota = $('#form').attr('action');
        $.post(rota, $('#form').serialize(), function (resultado) {
            $('#div-tabela-vendas').empty();
            $('#div-tabela-vendas').append(resultado);
        });
    }
    
    vendasCaixa();

    function vendasCaixa() {
        var rota = "<?php echo BASEURL; ?>/relatorio/vendasCaixaChamadaAjax";
        $.post(rota, $('#form').serialize(), function (resultado) {
            $('#div-tabela-vendas').empty();
            $('#div-tabela-vendas').append(resultado);
        });
    }

    function baixarEmFormatoXls() {
        var rota = "<?php echo BASEURL; ?>/relatorio/gerarXls";
        rota += "/" + $("#periodo_de").val();
        rota += "/" + $("#periodo_ate").val();
        rota += "/" + $("#id_caixa").val();

        window.location.href = rota;
    }

    function baixarEmFormatoPDF() {
        var rota = "<?php echo BASEURL; ?>/relatorio/gerarPDF";
        rota += "/" + $("#periodo_de").val();
        rota += "/" + $("#periodo_ate").val();
        rota += "/" + $("#id_caixa").val();

        window.location.href = rota;
    }
</script>
