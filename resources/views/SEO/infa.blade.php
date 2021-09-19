@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find($infa_id)??null])
@endsection
