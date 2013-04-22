<table class='table table-stripped'>
    <tbody>
        <tr>
            <td colspan="2">
                <b>LMs:</b> {{ $search }}
            </td>
            <td>
                <a
                    class="btn btn-primary btn-relate-product" data-method="POST"
                    href='{{ URL::action('Admin\ContentsController@addProduct', ['id'=>$aditional_id, 'product_id'=>$search]) }}'
                >
                    <i class="icon-magnet icon-white"></i>
                    <small>{{ l('content.mass_relate_button') }}</small>
                </a>
            </td>
        </tr>
    </tbody>
</table>
