<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pedido Fechado</title>
</head>

<body>
    <h2>Pedido #{{ $pedido->id }} — Fechado</h2>
    <p>Olá {{ $user->name }},</p>

    <p>Seu pedido foi fechado com as informações abaixo:</p>

    <ul>
        <li>Pedido: #{{ $pedido->id }}</li>
        <li>Valor: R$ {{ number_format($pedido->total ?? 0, 2, ',', '.') }}</li>
        <li>Data de fechamento: {{ $pedido->closed_at ?? now() }}</li>
    </ul>

    <p><a href="{{ url("/pedidos/{$pedido->id}") }}">Ver pedido</a></p>

    <p>Atenciosamente,<br />Equipe Mini ERP</p>
</body>

</html>
