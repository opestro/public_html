<style>
    .list-group-item li, a {
        color: {{$web_config['primary_color']}};
    }

    .list-group-item li, a:hover {
        color: {{$web_config['secondary_color']}};
    }
</style>
<ul class="list-group list-group-flush">
    @foreach($products as $product)
        <li class="list-group-item">
            <a href="{{route('product',$product->slug)}}" onmouseover="$('.search-bar-input-mobile').val('{{$product['name']}}');$('.search-bar-input').val('{{$product['name']}}');">
                {{$product['name']}}
            </a>
        </li>
    @endforeach
</ul>
