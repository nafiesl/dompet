@foreach (['en', 'id'] as $langKey)
{!! FormField::formButton(
    [
        'method' => 'patch',
        'route' => 'lang.switch',
        'title' => __('app.switch_'.$langKey.'_lang')
    ],
    $langKey,
    [
        'class' => 'btn btn-default btn-xs navbar-btn',
        'id' => 'lang_'.$langKey
    ] + (config('app.locale') == $langKey ? ['disabled' => 'disabled'] : []),
    ['lang' => $langKey]
) !!}
@endforeach
