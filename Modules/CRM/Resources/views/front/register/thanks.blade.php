@extends(FRONT.'.education.layouts.master')

@section('useHead')
    {{-- @include('SEO.head', ['eloquent'=>\App\Infastructure::find(85)??null]) --}}
    {{-- @include(FRONT.'.social_scripts.learning.thanks') --}}
@endsection

@section('content')
    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.RFP')])
    @if(isset($saved))
        <div class="alert alert-success m-5 p-5">
            <p style="text-align: center;"><strong>{{__('education.Your RFP registration was completed successfully')}}</strong></p>
            <p style="text-align: center;">{{__('education.We will contact you ASAP.')}}</p><br>
            <div style="text-align: center;">qq {{$id}}</div>
        </div>
    @else
        <div class="alert alert-danger m-5 p-5">
            <h3 style="text-align: center;"><strong>{{__('education.Your registration was not completed')}}</strong></h3>
            <p style="text-align: center;">{{__('education.You can try again please.')}}</p>
        </div>
    @endif
@endsection
