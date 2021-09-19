@if(!is_null($eloquent->discountDetails))
<table class="table table-condensed" style="width:100%;">
    <thead>
        <tr>
            <th></th>
            <th width="50%">{{__('admin.course_id')}}</th>
            <th>{{__('admin.session_id')}}</th>
            <th style="width:100px;">{{__('admin.percentage')}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $DateTimeNow = DateTimeNow();
        ?>
        @foreach($eloquent->discountDetails as $DiscountDetail)
            <?php
                $expired=null;
                // if($DiscountDetail->date_to < $DateTimeNow){
                //     $expired='expired';
                // }
            ?>
        <tr data-tr="{{$DiscountDetail->id}}" class="{{$expired}}">
            <td>
                @if(is_null($expired))
                    <button data-id="{{$DiscountDetail->id}}" class="btn btn-sm btn-danger btn-table btn-delete" style="visibility:visible;">
                        <i class="fa fa-trash"></i> {{__('admin.delete')}}
                    </button>
                    <button data-id="{{$DiscountDetail->id}}" data-training_option_id="{{$DiscountDetail->training_option_id}}"
                        data-session_id="{{$DiscountDetail->session_id}}"
                        data-value="{{$DiscountDetail->value}}"
                        data-option_type_id="{{$DiscountDetail->training_option_type_id}}"
                        data-date_from="{{$DiscountDetail->date_from}}"
                        data-date_to="{{$DiscountDetail->date_to}}"
                        data-slug="{{$DiscountDetail->discountType->slug??null}}"
                        class="btn btn-sm btn-primary btn-table btn-edit" style="visibility:visible;">
                        <i class="fa fa-save"></i> {{__('admin.edit')}}
                    </button>
                @endif
            </td>
            <td style="white-space: normal;">{{$DiscountDetail->trainingOption->training_name??null}}</td>
            <td>{{$DiscountDetail->discount_interval??null}}</td>
            {{-- <td>{{$DiscountDetail->session->published_session??null}}</td> --}}
            <td class="digit maxlength9">
                {{-- <mark>{{$DiscountDetail->value}}%</mark> --}}
                <div class="input-group digit">
                    <input name="discounts[]" type="hidden" value="{{$DiscountDetail->id}}">
                    <input style="width:130px" name="discount_value_{{$DiscountDetail->id}}" type="number" step="any" class="form-control" placeholder="Discount Value" value="{{$DiscountDetail->value}}">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                  </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
<script>
    $(function(){
        $('[name="AddDetailsBtn"]').click(function(){
            vm.training_option_id = -1;
            vm.session_id = -1;
            $('#AddDetailsModal').modal('show');
            return false;
        });

        $('.btn-edit').click(function(){

            var id = $(this).data('id');
            var training_option_id = $(this).data('training_option_id');
            var session_id = $(this).data('session_id');
            var value = $(this).data('value');
            var option_type_id = $(this).data('option_type_id');
            var date_from = $(this).data('date_from');
            var date_to = $(this).data('date_to');
            vm.details_id = id;
            vm.training_option_id = training_option_id;
            vm.option_type_id = option_type_id;
            vm.value = value;
            vm.date_from = date_from;
            vm.date_to = date_to;
            vm.slug = $(this).data('slug');

            $.ajax({
                type:'get',
                url:"{{route('training.sessions.getSessionByCourse')}}",
                data:{
                    training_option_id:training_option_id,
                },
                success:function(data){
                    vm.sessions = data;
                    vm.session_id = session_id;
                },
                errors:function(e){
                    console.log(e);
                },
            });
            $('#AddDetailsModal').modal('show');
            return false;
        });

        $('.btn-delete').click(function(){

            var frm = confirm('Sure To Delete');
            if(frm){
            var id = $(this).data('id');
                $.ajax({
                    type:'get',
                    url:"{{route('training.discounts.destroy_from_details')}}",
                    data:{
                        discount_id:id,
                    },
                    success:function(data){
                        $('tr[data-tr="'+id+'"]').remove();
                    },
                    errors:function(e){
                        console.log(e);
                    },
                });
            }
            return false;
        });

        $('#selectall').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });

        $('[data-date="datetime"]').datetimepicker({
            format:'Y-m-d H:i',
            dayOfWeekStart : 6,
            // lang:'en',
            // disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
            // startDate: '1986/01/05'
        });

        // $('[name="type_id"]').change(function(){
        //     if($('option:selected', this).val()==322){
        //         $('.value').addClass('hide');
        //     }
        //     else{
        //         $('.value').removeClass('hide');
        //     }
        // });
    });
</script>
