@extends(ADMIN.'.general.form')

{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder($folder)}}
{!!Builder::SetPostType($post_type)!!}

<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">
<div id="app">
@section('col9')

    {!!Builder::Input('en_excerpt', 'en_excerpt', null, [
        'row'=>2,
        'attr'=>'maxlength="300"', 'col'=>'col-md-6',
    ])!!}
    {!!Builder::Input('ar_excerpt', 'ar_excerpt', null, [
        'row'=>2,
        'attr'=>'maxlength="300"', 'col'=>'col-md-6',
    ])!!}
    {!!Builder::Input('code', 'code', $eloquent->code??$code, ['col'=>'col-md-6'])!!}
    {!!Builder::Select('coin_id', 'currency', $currencyConstants, null, ['col'=>'col-md-6'])!!}

    {!!Builder::DateTime('start_date', 'start_date', $eloquent->start_date??null, ['col'=>'col-md-6'])!!}
    {!!Builder::DateTime('end_date', 'end_date', $eloquent->end_date??null, ['col'=>'col-md-6'])!!}

    {{-- <div class="col-md-6"></div> --}}

    @if(isset($trainingOptions))
        {!!Builder::Submit('AddDetailsBtn', 'Add Details', 'btn-primary', 'plus-square', [
            'type'=>'button',
        ])!!}

        <div id="discount_details" class="col-md-12 mt-2">
            @include('training.discounts.details', ['DiscountDetails'=>null])
        </div>
    @endif
    {{-- @includeWhen(isset($eloquent), 'training.discounts.courses') --}}

@endsection

@section('col3')
    <?php $title = __('admin.table'); ?>

    {!!Builder::CheckBox('is_private', $eloquent->is_private??1, ['col'=>'col-md-12'])!!}
    <div id="candidates_no" style="display: {{$eloquent->is_private??1 == 1 ? 'block' : 'none'}}">
        {!!Builder::Number('candidates_no', 'candidates_no', null, ['col'=>'col-md-12 number'])!!}
    </div>

    <script>
        $('#is_private').on('change', function() {
            if($('#is_private').is(':checked')) {
                $('#candidates_no').show();
            }else {
                $('#candidates_no').hide();
            }
        })
    </script>

    {{-- <br> --}}
    {{-- {!!Builder::Select('training_option_id', 'training_option_id', $optionConstants, null, [
        'col'=>'col-md-12',
    ])!!} --}}
    {{-- {!!Builder::DateTime('start_date', 'start_date', null, [ 'col'=>'col-md-12'])!!}
    {!!Builder::DateTime('end_date', 'end_date', null, ['col'=>'col-md-12'])!!} --}}
    {!!Builder::Input('current_time', 'current_time', DateTimeNow(), [
            'attr'=>'disabled',
    ])!!}
    {{-- {!!Builder::Select('country_id', 'country_id', $countries, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!} --}}
@endsection

@section('col3_block')
    <div class="card card-default">
        <div class="card-header">{{__('admin.country_id')}}</div>
        <div class="card-body">
            @foreach($countries as $country)
              <!-- create object for this -->
              <?php
              $checked='';
              if(isset($country->discounts)){
                  $d = $country->discounts()->where('discount_id', $eloquent->id??null)->count();
                  if($d!=0){
                    $checked='checked="checked"';
                  }
              }
              ?>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="country-{{$country->id}}" name="countries[]" value="{{$country->id}}" {{$checked}}>
                <label for="country-{{$country->id}}" class="custom-control-label">{{$country->trans_name}}</label>
              </div>
            @endforeach
        </div>
    </div>
@endsection

@if(isset($trainingOptions))
    <!-- Modal -->
    <div class="modal fade" id="AddDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Discount Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">

                    @include('training.discounts.vue')

                    <!-- alerts -->
                    <div class="alert alert-danger mt-2" v-if="msgFail" id="msg-danger">
                        <ul class="list-unstyled">
                            <li v-for="error in errors" v-html="error"></li>
                        </ul>
                    </div>
                    <div class="alert alert-success mt-2" v-if="msgSuccess" id="msg-success">
                        {{__('flash.Inserted Successfully')}}
                    </div>

                </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> {{__('admin.close')}}</button>
                <button type="button" class="btn btn-primary" @click="Clear($event)">
                    <i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
                <button type="button" class="btn btn-primary" @click="AddToAllSessions($event)">
                    <i class="fa fa-save"></i> {{__('admin.save')}}</button>
            </div>
            </div>
        </div>
    </div>
@endif

</div>
<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>
<script>
    $(function(){
        $('[data-date="datetime"]').datetimepicker({
            format:'Y-m-d H:i',
            dayOfWeekStart : 6,
        });
    });
</script>
@if(isset($trainingOptions))
<script>
window.options = {!!$trainingOptions!!}
var vm = new Vue({
    el: '#app',
    data: {
        details_id: null,
        training_option_id: -1,
        session_id: -1,
        date_from: null,
        date_to: null,
        value: 0,
        allSessions: [],
        sessions: [],
        options: options,
        errors: [],
        msgSuccess:false,
        msgFail:false,
        option_type_id:13,
        slug:'auto-date'
    },
    mounted() {
        console.log(this.is_private);
    },
    methods: {
        AddToAllSessions:function(){
            // var vm = this;
            var training_option_id = vm.training_option_id;
            axios.get("{{route('training.discounts.add_details')}}", {
                params:{
                    details_id: vm.details_id,
                    training_option_id: training_option_id,
                    session_id:vm.session_id,
                    value:vm.value,
                    master_id:{{$eloquent->id??null}},
                    date_from:vm.date_from,
                    date_to:vm.date_to,
                }
            })
            .then(response => {

                if(typeof(response.data.errors) != 'undefined'){
                    vm.errors = response.data.errors;
                    vm.msgSuccess = false;
                    vm.msgFail = true;
                }
                else{
                    // vm.sessions = response.data;
                    $('#discount_details').html(response.data);
                    // vm.Clear();
                    vm.msgSuccess = true;
                    vm.msgFail = false;
                    vm.details_id = null;
                }
            })
            .catch(e => {
                vm.errors.push(e)
            });
        },
        getSessions:function(event){
            vm.session_id = -1;
            // console.log(event.target.options[event.target.options.selectedIndex].attributes.type_id.value);
            // console.log(event.target.selectedOptions[0].attributes.type_id.value);
            // var training_option_id = event.target.value;
            var training_option_id = vm.training_option_id;
            vm.option_type_id = event.target.selectedOptions[0].attributes.type_id.value;
            vm.slug = event.target.selectedOptions[0].attributes.slug.value;
            // console.log(event.target.selectedOptions[0].attributes.slug.value);
            axios.get("{{route('training.sessions.getSessionByCourse')}}", {
                params:{
                    training_option_id: training_option_id,
                }
            })
            .then(response => {
                // JSON responses are automatically parsed.
                // console.log(response);
                vm.sessions = response.data;
                // console.log(vm.sessions);
            })
            .catch(e => {
                vm.errors.push(e)
            });
        },
        Clear:function(){
            vm.training_option_id = -1;
            vm.session_id = -1;
            vm.msgSuccess = false;
            vm.msgFail = false;
            // errors: [],
        },
        DateFrom: function(event){
            vm.date_from = event.target.value;
        },
        DateTo: function(event){
            vm.date_to = event.target.value;
        }
    }
});

</script>
@endif
