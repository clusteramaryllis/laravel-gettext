{{ Gettext::getText('Hello World') }}
{{ \Clusteramaryllis\Gettext\Facades\Gettext::nGetText('Cat', 'Cats', 1) }}
{{ app('gettext')->dGetText('messages', 'Hello World') }}
{{ $app['gettext']->dNGetText('messages', 'Cat', 'Cats', 1) }}
{{ $gettext->dCGetText('messages', 'Hello World', LC_ALL) }}
{{ $arr[CustomFacade::dCNGetText('messages', 'Cat', 'Cats', 1, LC_ALL)] }}
{{ urlencode(MyClass\Gettext::pGetText('greeting', 'Hello World')) }}
{{ $arr[ MyClass\Gettext::dPGetText('messages', 'greeting', 'Hello World') ] }}
{{ urlencode( $app['gettext']->nPGetText('devices', 'Mouse', 'Mouses', 1) ) }}
