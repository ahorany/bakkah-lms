<html>
    <head>
        {{-- <script src="{{CustomAsset('js/vue.js')}}"></script> --}}
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}" /> --}}
        <script defer src="{{ CustomAsset('js/app.js') }}"></script>
        <style>
            em {
                background-color: yellow;
            }
        </style>
    </head>
    <body>
        {{-- <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.5.1/dist/algoliasearch-lite.umd.js" integrity="sha256-EXPXz4W6pQgfYY3yTpnDa3OH8/EPn16ciVsPQ/ypsjk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-instantsearch@3.4.2/dist/vue-instantsearch.js" integrity="sha256-n2IafdANKRLjFjtPQVSQZ6QlxBrYqPDZfi3IkZjDT84=" crossorigin="anonymous"></script> --}}
        <div id="app">
            <my-search title="{{app()->getLocale()}}_title" excerpt="{{app()->getLocale()}}_short_excerpt"
                course_title="{{__('education.courses')}}" post_title="{{__('education.Knowledge Center')}}"
                locale="{{app()->getLocale()}}" placeholder="{{__('education.Search')}}" />
        </div>
        </body>
    </body>

</html>
