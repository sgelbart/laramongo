var paginator = $('[data-nextpage]');
var content = "{{ escape_for_js (
                    View::make('templates.base.categories._products', view_vars($__data))
                        ->render()
                ) }}";

if(content != "") // If receive some content
{
    // Show content
    paginator.before(content);
    paginator.attr('data-nextpage', paginator.attr('data-nextpage') + 1);
    paginator.removeAttr('loading');
}
else // Else, dispay 'End of results'
{
    paginator.text('Fim dos resultados');
}
