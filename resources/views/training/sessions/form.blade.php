@extends(ADMIN.'.general.form')
{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder($folder)}}
<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">

@section('col9')

    {!!Builder::Select('training_option_id', 'course_id', $training_option, null, [
        'col'=>'col-md-12', 'model_title'=>'training_name',
    ])!!}

    {!!Builder::Date('date_from', 'date_from',null,[ 'col'=>'col-md-6'])!!}
    {!!Builder::Date('date_to', 'date_to',null,[ 'col'=>'col-md-6'])!!}

    {!!Builder::Input('duration', 'duration',null,[ 'col'=>'col-md-6 digit'])!!}
    {!!Builder::Select('duration_type', 'duration_type', $duration_type, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}
    {!! Builder::Textarea('session_time', 'session_time', null, [
        // 'row'=>1,
        'tinymce'=>'tinymce-small',
    ]) !!}

    <div class="col-md-12">
        {!!Builder::CheckBox('except_fri_sat')!!}
    </div>

    {{-- {!!Builder::CheckBox('retarget_discount')!!} --}}
    {{-- {!!Builder::Input('retarget_discount', 'retarget_discount',null,[ 'col'=>'col-md-6'])!!} --}}
    {!!Builder::Input('vat', 'vat', VAT, ['attr'=>'readonly', 'col'=>'col-md-6'])!!}
    {!!Builder::DateTime('session_start_time', 'session_start_time', null, [ 'col'=>'col-md-4'])!!}
    {!!Builder::Input('hours_per_day', 'hours_per_day',null,[ 'col'=>'col-md-2 digit'])!!}

    {{-- ======================== Price Tab ======================= --}}
    <div class="price-tabs my-4">
        <div class="nav nav-pills flex-column flex-sm-row" id="pills-tab" role="tablist">
            <a class="flex-sm-fill nav-link btn btn-light active" data-toggle="pill" href="#price-sar">Price (SAR)</a>
            <a class="flex-sm-fill nav-link btn btn-light" data-toggle="pill" href="#price-usd">Price (USD)</a>
        </div>
        <div class="tab-content border rounded p-3" id="pills-tabContent">
            {{-- ======================== SAR ======================= --}}
            <div class="tab-pane fade show active" id="price-sar" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">
                    {!!Builder::Input('price', 'price',null,[ 'col'=>'col-md-6 digit'])!!}
                    {{-- remove it from db --}}
                    {{--Builder::Input('show_price', 'show_price',null,[ 'col'=>'col-md-6'])--}}
                    {{--{!!Builder::Input('discount_percentage', 'discount_percentage',null,[ 'col'=>'col-md-6'])!!}--}}
                    {{--{!!Builder::Input('discount_value', 'discount_value',null,[ 'col'=>'col-md-6'])!!}--}}
                    {!!Builder::Input('exam_price', 'exam_price',null,[ 'col'=>'col-md-6 digit'])!!}
                    {!!Builder::Input('total', 'total',null,[ 'col'=>'col-md-12 digit'])!!}
                </div>
            </div>
            {{-- ======================== USD ======================= --}}
            <div class="tab-pane fade" id="price-usd" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    {!!Builder::Input('price_usd', 'price_usd',null,[ 'col'=>'col-md-6'])!!}
                    {!!Builder::Input('exam_price_usd', 'exam_price_usd',null,[ 'col'=>'col-md-6'])!!}
                    {!!Builder::Input('total_usd', 'total_usd',null,[ 'col'=>'col-md-12'])!!}
                </div>
            </div>
        </div>
    </div>

    {!!Builder::Input('zoom_link', 'zoom_link',null,['col'=>'col-md-12'])!!}
    
       
    {{-- Gross Margin--}}
    <div class="card card-default col-md-12 mb-4" style="border: 1px solid #fb44005c;">
    <div class="card-header text-bold pl-2" style="font-weight: bold;color: #fb4400;">Gross Margin</div>
    <div class="card-body px-0">
    <div class="row">
    
         {!!Builder::Hidden('session_id',$eloquent->id??'')!!}

        {!! Builder::Select('trainer_id', 'trainer_id', $trainers, $eloquent->usersSessions()->where('post_type', 'trainer')->first()->user_id??null, [
                'col'=>'col-md-6', 'model_title'=>'trans_name',
            ]) !!}
        {!! Builder::Input('trainer_cost', 'trainer_cost',$eloquent->usersSessions()->where('post_type', 'trainer')->first()->cost??null,[ 'col'=>'col-md-6','attr'=>'readonly'])!!}

        {!! Builder::Select('developer_id', 'developer_id', $developers, $eloquent->usersSessions()->where('post_type', 'developer')->first()->user_id??null, [
                'col'=>'col-md-6', 'model_title'=>'trans_name',
            ]) !!}
        {!! Builder::Input('developer_cost', 'developer_cost',$eloquent->usersSessions()->where('post_type', 'developer')->first()->cost??null,[ 'col'=>'col-md-6','attr'=>'readonly'])!!}

        {!! Builder::Select('demand_team_id', 'demand_team_id', $demand_teams, $eloquent->usersSessions()->where('post_type', 'demand')->first()->user_id??null, [
                'col'=>'col-md-6', 'model_title'=>'trans_name',
            ]) !!}
        {!! Builder::Input('demand_cost', 'demand_cost',$eloquent->usersSessions()->where('post_type', 'demand')->first()->cost??null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
   
        {!! Builder::Input('total_hours', 'total_hours',null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
        {!! Builder::Input('zoom_cost', 'zoom_cost',($eloquent->zoom_cost)??ZOOM_COST,[ 'col'=>'col-md-6 digit']) !!}
        {!! Builder::Input('material_cost', 'material_cost',null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
        {!! Builder::Input('sales_value', 'sales_value',null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
        {!! Builder::Input('gross_profit', 'gross_profit',null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
        {!! Builder::Input('gross_margin', 'gross_margin',null,[ 'col'=>'col-md-6','attr'=>'readonly']) !!}
    </div>        

    </div>
</div>
@endsection

@section('col3_block')
    <div class="card card-default">
        <div class="card-header">{{__('admin.options')}}</div>
        <div class="card-body">
            {!!Builder::Select('lang_id', 'lang_id', $lang, null, [
                'col'=>'col-md-12', 'model_title'=>'trans_name',
            ])!!}

            <div class="col-md-12">
                {!!Builder::CheckBox('money_back_guarantee')!!}
            </div>
            <div class="col-md-12">
                {!!Builder::CheckBox('retarget_discount')!!}
            </div>
            <div class="col-md-12">
                {!!Builder::CheckBox('send_reminder_before_start')!!}
            </div>

            <hr>
            <?php $city_id = \App\Constant::where('post_type', 'cities')->get(); ?>
            {!! Builder::Select('city_id', 'city_id', $city_id, null, ['col'=>'col-md-12']) !!}
            <?php $type_id = \App\Constant::where('id', 370)->get(); ?>
            {!! Builder::Select('type_id', 'type_id', $type_id, null, ['col'=>'col-md-12']) !!}
            {!!Builder::CheckBox('show_in_website')!!}

            <hr>
            <div class="card card-default">
                <div class="card-header">{{__('admin.evaluation_options')}}</div>
                <div class="card-body">
                    {!!Builder::Input('evaluation_api_code', 'evaluation_api_code', null, ['col'=>'col-md-12'])!!}
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr class="text-success">
                            <td>B2C Online</td>
                            <td>A/baf87a8f</td>
                        </tr>
                        <tr class="text-secondary">
                            <td>Self-Study</td>
                            <td>A/e1c6433a</td>
                        </tr>
                        <tr class="text-primary">
                            <td>Exam Simulation</td>
                            <td>A/c5899261</td>
                        </tr>
                        <tr class="text-danger">
                            <td>B2B</td>
                            <td>A/16d08364</td>
                        </tr>
                    </table>
                </div>
            </div>
           
            
            <div class="col-md-12 mt-4">
                {!!Builder::Input('current_time', 'current_time', DateTimeNow(), [
                    'attr'=>'disabled',
                ])!!}
            </div>

        </div>
    </div>
@endsection
<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>
<script>
    $(function(){
        $('input[name="price"],input[name="discount_percentage"],input[name="exam_price"],input[name="vat"],input[name="discount_value"],input[name="price_usd"],input[name="exam_price_usd"]').change(function () {
            calculate();
        });

       function calculate(){
           // var discount_final=0;
            var vat_final=0
            var price = parseFloat($('input[name="price"]').val());
            var exam_price = parseFloat($('input[name="exam_price"]').val());
            var vat = parseFloat($('input[name="vat"]').val());

            var vat_final_usd=0
            var price_usd = parseFloat($('input[name="price_usd"]').val());
            var exam_price_usd = parseFloat($('input[name="exam_price_usd"]').val());

            if(vat>0){
                var vat_final = (price+exam_price)*vat*.01;
                var vat_final_usd = (price_usd+exam_price_usd)*vat*.01;
            }
            var total_final = (price+exam_price)+vat_final;
            var total_final_usd = (price_usd+exam_price_usd)+vat_final_usd;
           if(!isNaN(total_final)) {
               $('input[name="total"]').val(total_final);
           }
           if(!isNaN(total_final_usd)) {
               $('input[name="total_usd"]').val(total_final_usd);
           }
       }

       $('[name="session_start_time"]').datetimepicker({
            format:'Y-m-d H:i',
            formatTime:'H:i A',
            dayOfWeekStart : 6,
            // hours12: false,
            // lang:'en',
            // disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
            // startDate: '1986/01/05'
        });

    });
</script>
<style>
    .tox-tinymce {
        height: 210px !important;
    }
</style>
{{-- https://xdsoft.net/jqplugins/datetimepicker/
https://github.com/xdan/datetimepicker --}}

<script>
    $(function(){

        $('input[name="total_hours"]').val($('input[name="duration"]').val()*$('input[name="hours_per_day"]').val());

        $('input[name="duration"],input[name="hours_per_day"]').keyup(function(){

            $('input[name="total_hours"]').val($('input[name="duration"]').val()*$('input[name="hours_per_day"]').val());

        });

        $('select[name="trainer_id"],select[name="developer_id"],select[name="demand_team_id"]').change(function(){
           
            var clicked = $(this).attr('name');
            var changed = '';var value = '';
            if(clicked == 'trainer_id')
            {
                changed = 'trainer_cost';
                value = 'trainer_cost';
            }
            else if(clicked == 'developer_id')
            {
                changed = 'developer_cost';
                value = 'on_demand_cost';
            }
            else if(clicked == 'demand_team_id')
            {
                changed = 'demand_cost';
                value = 'on_demand_cost';
            }
                
            // alert(variable);
            axios.get('{{route("training.sessions.calculate_cost")}}', {
                        params: {
                            'total_hours'   : $('input[name="total_hours"]').val(),
                            'session_id'    : $('input[name="session_id"]').val(),
                            'user_id'       : $(this).val(),
                        }
                    })
                    .then(function(resp){
                            
                            $('input[name='+changed+']').val(resp.data[value].toFixed(2));

                        }.bind(this))
                    .catch(function(err){
                        });
             });

             

            axios.get('{{route("training.sessions.calculate_gross_margin")}}', {
                params: {
                    'session_id'        : $('input[name="session_id"]').val(),
                    'on_demand_cost'    : $('input[name="demand_cost"]').val(),
                    'trainer_cost'      : $('input[name="trainer_cost"]').val(),
                    'zoom_cost'         : $('input[name="zoom_cost"]').val(),
                    
                }
            })
                .then(function(resp){
                    // alert(resp.data['gross_margin']);
                    $('input[name="material_cost"]').val(resp.data['material_cost'].toFixed(2));
                    $('input[name="sales_value"]').val(resp.data['sales_value'].toFixed(2));
                    $('input[name="gross_profit"]').val(resp.data['gross_profit'].toFixed(2));
                    $('input[name="gross_margin"]').val(resp.data['gross_margin'].toFixed(2));

                }.bind(this))
                .catch(function(err){
                });

    });
</script>