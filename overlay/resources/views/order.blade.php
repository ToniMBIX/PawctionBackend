<!doctype html>
<html><body>
<h1>Confirmación de pedido #{{ $order->id }}</h1>
<p>Subasta: {{ $auction->title }}</p>
<p>Total: €{{ number_format($order->total_amount/100,2) }}</p>
<p>Reparto: Pawction €{{ number_format($order->split_pawction/100,2) }} / Greenpeace €{{ number_format($order->split_greenpeace/100,2) }}</p>
</body></html>
