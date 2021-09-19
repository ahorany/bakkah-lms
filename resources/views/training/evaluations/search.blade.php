<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 150px;
    }
    .form-inline .form-group .form-control {
        width: 60%;
        height: calc(2rem + 2px);
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    input[name="user_search"] {
        width:80% !important;
    }
</style>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row mt-2">
                            <form id="cart-search" class="form-inline" method="get" action="{{route('evaluation.search')}}">
                                {!! Builder::Hidden('page', request()->page??1) !!}
                                {!! Builder::Hidden('post_type', request()->post_type??'evaluation') !!}
                                {!! Builder::Hidden('trash') !!}

                                @include('training.carts.components.course_combo')

                                {{-- {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, request()->training_option_id??-1, [
                                    'col'=>'col-md-6',
                                ]) !!} --}}

                                {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, -1, [
                                    'col'=>'col-md-6',
                                ]) !!}

                                {!! Builder::Select('payment_status', 'payment_status', $status, request()->payment_status??-1, [
                                    'col'=>'col-md-6',
                                ]) !!}

                                {!! Builder::Date('date_from', 'register_from', null, ['col'=>'col-md-6']) !!}
                                {!! Builder::Date('date_to', 'register_to', null, ['col'=>'col-md-6']) !!}

                                {!! Builder::Input('user_search', 'trainee', null, [
                                    'col'=>'col-md-12',
                                    'placeholder'=>__('admin.trainee_search'),
                                ]) !!}

                                <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                    {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
{{-- @include('training.carts.components.vue') --}}
<script>
    function AjaxSearch(btnName, page=1){

        var btnHtml = btnName.html();
        btnName.html('Loading...');

        jQuery('[name="page"]').val(page);
        var form = jQuery('#cart-search');
        console.log(form.attr('action'));
        jQuery.ajax({
            type:'get',
            url:form.attr('action'),
            data:form.serialize(),
            success:function(data){
                jQuery('.cart-table').html(data);
                btnName.html(btnHtml);
            }
            ,errors:function(er){
                console.log(er);
            }
        });
    }

    jQuery(function (){
        jQuery('[name="search"]').click(function (){
            var btnName = jQuery(this);
            AjaxSearch(btnName);
            return false;
        });

        jQuery('[name="clear"]').click(function (){
            jQuery('[name="course_id"], [name="payment_status"], [name="country_id"], [name="session_id"], [name="category_id"], [name="training_option_id"]').val(-1);
            jQuery('[name="invoice_number"], [name="user_search"], [name="date_from"], [name="date_to"], [name="register_from"], [name="register_to"]').val('');
            return false;
        });
    });

    window.sessions = {!!$session_array!!}
    window.choose_value = {!! json_encode(__('admin.choose_value')) !!}
    var vm = new Vue({
        el: '#cart-search',
        data: {
            selected_session_id:-1,
            session_choose_value: window.choose_value,
            sessions: window.sessions,
        },
        methods: {
            courseChange: function (val) {
                vm.session_choose_value = 'loading...';
                axios.get('{{route("evaluation.sessionsJson")}}', {
                    params: {
                        'course_id' : val
                    }
                })
                .then(function(resp){
                    vm.sessions = resp.data;
                    vm.session_choose_value = window.choose_value;
                }.bind(this))
                .catch(function(err){
                    console.log(err);
                });
                // this.selected_training_option_id = parseInt(index);
            },
        }
    });
</script>
