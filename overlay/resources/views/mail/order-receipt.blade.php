@component('mail::message')
# ¡Gracias por tu apoyo!
**Pedido:** {{ $order->id }} — **Importe:** €{{ number_format($order->total,2) }}
Gracias, **Pawction**
@endcomponent
