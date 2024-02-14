<div>
    <p class="text-lg font-semibold uppercase">Envío</p>
    @if($order->envio_type == 1)
        <p class="text-sm">Los productos deben ser recogidos en tienda</p>
        <p class="text-sm">Calle Falsa 123</p>
    @else
        <p class="text-sm">Los productos serán enviados a:</p>
        <p class="text-sm">{{ $envio->address }}</p>
        <p>{{ $envio->department }} - {{ $envio->city }} - {{ $envio->district }}</p>
    @endif
</div>
