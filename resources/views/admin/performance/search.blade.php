<style>
.form-inline .form-group {
    margin-bottom: 5px;
}
.form-inline .form-group label {
    font-weight: normal !important;
}
.form-inline .form-group .form-control {
    width: 100%;
    height: calc(2rem + 2px);
}
.form-inline > div {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
    .performance .custom-control.custom-checkbox {
        padding: 30px 50px;
    }
    </style>
    <form id="post-search" class="form-inline" method="get" action="{{route('admin.performance.index')}}">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <b>{{__('admin.search form')}}</b>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid performance">
                            <div class="row">
                                {!! Builder::Hidden('page', request()->page??1) !!}
                                {!! Builder::Hidden('post_type', $post_type) !!}
                                {!! Builder::Hidden('trash') !!}
                                <div class="col-md-4 mb-4">
                                    <label style="font-weight: normal; justify-content:start;">Partners</label>
                                        <select style="width: 100%;" class="form-control" name="partner">
                                            <option value="-1">Choose Value</option>
                                            @foreach ($partners as $partner)
                                                <option {{ (request()->partner == $partner->id) ? 'selected' : '' }} value="{{ $partner->id }}">{{ $partner->trans_name }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                        <label style="font-weight: normal; justify-content:start;">Products</label>
                                        <select style="width: 100%;" class="form-control" name="product">
                                            <option value="-1">Choose Value</option>
                                            @foreach ($products_for_combo as $product)
                                                <option {{ (request()->product == $product->id) ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->trans_title }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4 col-9">
                                    <label style="font-weight: normal; justify-content:start;">Status</label>
                                    <select style="width: 100%;" class="form-control" name="status">
                                        <option value="-1">Choose Value</option>
                                        <option {{ (request()->status == 'active') ? 'selected' : '' }} value="active">active</option>
                                        <option {{ (request()->status == 'inactive') ? 'selected' : '' }} value="inactive">inactive</option>
                                    </select>
                                </div>
                                {!! Builder::Date('product_date_from', 'product_date_from', null, ['col'=>'col-md-4']) !!}
                                {!! Builder::Date('product_date_to', 'product_date_to', null, ['col'=>'col-md-4']) !!}
                                <div class="col-md-4 p-4">
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
