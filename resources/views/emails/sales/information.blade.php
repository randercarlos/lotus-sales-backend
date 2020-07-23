<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style type="text/css">
        h1 {
            text-align: center;
        }
        h3 {
            text-align: center;
            border: 1px solid black; background: #DCDCDC;
            padding: 10px;
        }

        table {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            border-style: hidden;
        }

        table td, table th {
            border: 1px solid black;
        }
    </style>
</head>
<body>

    <h1>Informações Semanais de Venda</h1>
    <h3>Totais de Venda: {{ $sales }}</h3>
    <h3>Faturamento: {{ "R$ " . number_format($revenue, 2, ",", ".")  }}</h3>
    <h3>Lucro: {{ "R$ " . number_format($profit, 2, ",", ".")  }}</h3>

    <h3>Top 10 Produtos Mais Vendidos: </h3>
    <table >
        <thead>
        <tr>
            <th style="width: 80%">Produto</th>
            <th>Vendas</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($top10_product_most_sale as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->order_details_count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <hr />
    <br />
    <h3>Top 10 Produtos Menos Vendidos: </h3>
    <table>
        <thead>
        <tr>
            <th style="width: 80%">Produto</th>
            <th>Vendas</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($top10_product_less_sale as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->order_details_count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>



