@extends('layouts.crm.form')
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    @isset($eloquent->id)
      {{-- <div class="col-12 mb-3">Category ID: <span class="bg-dark text-white px-2 py-1" style="border-radius: 5px">{{$eloquent->id}}</span></div> --}}
    @endisset

    <div class="col-12">
        <div class="row mx-0 inputs">
            {!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
            {!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
        </div>
    </div>

@endsection


