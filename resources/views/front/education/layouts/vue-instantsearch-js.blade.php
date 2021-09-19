<html>
    <head>
        {{-- <script src="{{CustomAsset('js/vue.js')}}"></script> --}}
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}" /> --}}
        <script defer src="{{ mix('js/app.js') }}"></script>
        <style>
            em {
                background-color: yellow;
            }
        </style>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.1.0/themes/algolia.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.css">
    </head>
    <body>
        {{-- <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.5.1/dist/algoliasearch-lite.umd.js" integrity="sha256-EXPXz4W6pQgfYY3yTpnDa3OH8/EPn16ciVsPQ/ypsjk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-instantsearch@3.4.2/dist/vue-instantsearch.js" integrity="sha256-n2IafdANKRLjFjtPQVSQZ6QlxBrYqPDZfi3IkZjDT84=" crossorigin="anonymous"></script>
        <div id="app">
            <my-search />
        </div> --}}
        <div class="container">
            <h1>InstantSearch.js - Results page with an autocomplete</h1>
            <div id="autocomplete"></div>
            <div id="hits"></div>
          </div>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4/dist/algoliasearch-lite.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4"></script>
          <script src="{{CustomAsset('js/instantsearch.js')}}"></script>
        </body>
    </body>

</html>
