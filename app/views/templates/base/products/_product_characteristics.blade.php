<table class='table'>
    @foreach ($category->characteristics() as $charac)
        @if ( is_array($product->details) && isset($product->details[clean_case($charac->name)]) )
            <tr>
                <td class='attr_header'>
                    {{ $charac->name }}
                </td>
                <td>
                    {{ 
                        $charac->displayLayout($product->details[clean_case($charac->name)])
                    }}
                </td>
            </tr>
        @endif
    @endforeach
</table>
