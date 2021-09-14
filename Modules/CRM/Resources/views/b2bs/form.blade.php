@extends(ADMIN.'.general.form')

{!!Builder::SetNameSpace('')!!}
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder('crm::b2bs')}}
{{Builder::SetPrefix('crm::admin.')}}

@section('col9')

    <div id="cart-search">
        <form  class="form-inline" method="get" action="{{route('crm::b2bs.store')}}">

            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {!! Builder::Hidden('page', request()->page??1) !!}
                        {!! Builder::Hidden('trash') !!}
                        {!!Builder::Select('organization_id', 'organization_id', $organizations, null, ['model_title'=>'trans_title'])!!}
                        {!! Builder::Hidden('training_option_id',$training_id??null) !!}

                        {!!Builder::Select('owner_user_id', 'user_id', $users, null, ['col'=>'col-md-6'])!!}
                        {!!Builder::Select('status_id', 'status', $status, null, ['col'=>'col-md-6'])!!}
                        @include('training.carts.components.course_combo')
                    </div>
                </div>
            </div>

        </form>
    </div>
{{--    @include('crm::b2bs.components.vue')--}}
{{--    @include('crm::b2bs.components.course_combo')--}}

    @include('training.carts.components.vue')
@endsection

