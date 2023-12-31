
<form id="cart-search" class="" method="get" action="{{route('training.projects.index')}}">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            {!! Builder::Hidden('page', request()->page??1) !!}
                            {!! Builder::Hidden('trash') !!}
                            {!! Builder::Input('title', 'project_name', request()->name??null, ['col'=>'col-md-6'])!!}
                                     
                        </div>
                        {!!Builder::Submit('search', 'search', 'btn-primary', 'search')!!}
                        {!!Builder::Reset('clear', 'clear', 'btn-default', 'clear')!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
