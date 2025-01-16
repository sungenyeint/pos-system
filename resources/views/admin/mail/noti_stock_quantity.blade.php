Stock ကုန်ခါနီး ပစွည်းများ

@foreach ($products as $category => $product)
{{ $category }}
@foreach ($product as $item)
    {{ $item['name'] }} - {{ $item['stock_quantity'] }}
@endforeach
@endforeach
