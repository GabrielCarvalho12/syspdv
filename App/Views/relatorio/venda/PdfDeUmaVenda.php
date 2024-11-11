<!-- <!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <base href="<?php echo BASEURL; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Impressão de Pedido</title>
  <style>
    /* Reset geral para impressão */
    * {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #000;
    }
    /* Layout da página */
    body, html {
      width: 100%;
      padding: 10px;
      background: #fff;
    }
    .receipt {
      width: 100%;
      max-width: 80mm;
      margin: auto;
    }
    /* Cabeçalho */
    .header {
      text-align: center;
      margin-bottom: 10px;
    }
    .header h1 {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .header p {
      font-size: 12px;
    }
    /* Informação do pedido */
    .order-info {
      margin-bottom: 10px;
    }
    .order-info p {
      margin: 3px 0;
    }
    /* Tabela de produtos */
    .product-table {
      width: 100%;
      border-collapse: collapse;
    }
    .product-table th, .product-table td {
      text-align: left;
      padding: 3px 0;
    }
    .product-table th {
      border-bottom: 1px solid #000;
    }
    /* Total */
    .total {
      text-align: right;
      margin-top: 10px;
      font-size: 14px;
      font-weight: bold;
    }
    /* Mensagem final */
    .footer {
      text-align: center;
      margin-top: 15px;
      font-size: 10px;
    }
  </style>
</head>
<body>
  <div class="receipt">
    <div class="header">
      <h1>La Matilha</h1>
      <p>Endereço: R. Luís Gomes - Bairro Açucena Velha</p>
      <p>(99) 99215-2539</p>
    </div>

    <div class="order-info">
      <p><strong>Pedido <?php echo $dadosVenda[0]->idVenda;?></strong></p>
      <p>Gerado em: <?php echo date('d/m/Y');?> às <?php echo date('H:i');?></p>
      <p>Forma de Pagamento: <?php echo $informacaoPagamento->meioPagamento;?></p>
    </div>

    <table class="product-table">
      <thead>
        <tr>
          <th>Produto</th>
          <th>Qtd</th>
          <th>Preço</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php foreach ($dadosVenda as $venda):?>
            <tr>
                <td><?php echo stringAbreviation($venda->produtoNome, 15, '...');?></td>
                <td><?php echo $venda->quantidade;?></td>
                <td><?php echo 'R$ ' . real($venda->valor);?></td>
                <td><?php echo 'R$ ' . real($venda->preco);?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
    </table>

    <div class="total">
      <p>Total: R$ <?php echo real($informacaoPagamento->total);?></p>
      <?php if ($informacaoPagamento->id_meio_pagamento == 1) :?>
            <p>À receber: R$ <?php echo real($informacaoPagamento->valor_recebido);?></p>
            <p>Troco: R$ <?php echo real($informacaoPagamento->troco);?></p>
        <?php endif;?>
    </div>

    <div class="footer">
      <p>Obrigado pela preferência!</p>
      <p>Volte sempre!</p>
    </div>
  </div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo BASEURL; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="public/img/favicon_tonie.png"/>
    <title>PDV - <?php echo $nomeEmpresa; ?></title>
    <link rel="shortcut icon" href="public/img/blue-dollar.png"/>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #666;
            font-size:12px;
            margin-top:5px;
        }
        table, th, td {
            border: 1px solid black;
            text-align:center;
        }
        thead tr {
            background: #cccccc;
        }
        th {
            height: 20px;
        }
    </style>
</head>
<body>

<center><h2 style="margin-bottom:3"><?php echo $nomeEmpresa;?></h2></center>
<center>
    <h4 style="margin-top:0;opacity:0.70">Relatório de uma venda  especifica.</h4>
    <small style="opacity:0.50">Gerado em: <?php echo date('d/m/Y');?> às <?php echo date('H:i');?></small>
</center>
<hr style="border:1px dotted silver">
<br>
<center>
    <span style="opacity:0.70;font-size:15px;">
        <b>PAG: <?php echo $informacaoPagamento->meioPagamento;?></b> |
        <b>Total Geral: R$ <?php echo real($informacaoPagamento->total);?></b>
        <?php if ($informacaoPagamento->id_meio_pagamento == 1) :?>
            |
            <b>Recebido: R$ <?php echo real($informacaoPagamento->valor_recebido);?></b> |
            <b>Troco: R$ <?php echo real($informacaoPagamento->troco);?></b>
        <?php endif;?>
    </span>
</center>
<br>
<center><small style="opacity:0.70;font-size:13px;">Vendedor: <?php echo $informacaoPagamento->nomeUsuario;?></small></center>
<br>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Produto</th>
            <th>QTD</th>
            <th>Preço</th>
            <th>Sub Total</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0;?>
        <?php foreach ($dadosVenda as $venda):?>
            <?php
                $i++;
                $styleTd = "style=''";
                if ($i % 2 == 0) {
                    $styleTd = "style='background:#eeeaea'";
                }
            ?>
            <tr <?php echo $styleTd;?>>
                <td><?php echo $i;?></td>
                <td><?php echo stringAbreviation($venda->produtoNome, 15, '...');?></td>
                <td><?php echo $venda->quantidade;?></td>
                <td><?php echo 'R$ ' . real($venda->valor);?></td>
                <td><?php echo 'R$ ' . real($venda->preco);?></td>
                <td>
                    <?php echo $venda->data . ' ' . $venda->hora;?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>

</table>
</body>
</html>