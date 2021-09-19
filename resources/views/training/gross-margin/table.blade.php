<?php
use App\Http\Controllers\Training\SessionController; $SessionController = new SessionController;
?>
<div class="card">
  
  <style>
      th{font-size: 15px;}
      .user-td {width: 140px;}
  </style>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.course_name')}}</th>
            {{--<th class="">{{__('admin.training_option_id')}}</th>--}}
            <th class="">{{__('admin.session_id')}}</th>
            <th class="">{{__('admin.duration')}}</th>
            <th class="">{{__('admin.total_hours')}}</th>
            <th class="">{{__('admin.zoom')}}</th>
           
            {{--<th class="">{{__('admin.trainer_id')}}</th>--}}

            <th class="">{{__('admin.trainees_no')}}</th>
            <th class="">{{__('admin.on_demand_cost')}}</th>
            <th class="">{{__('admin.trainer_cost')}}</th>
            <th class="">{{__('admin.material_cost')}}</th>
            <th class="">{{__('admin.delivery_cost')}}</th>
            <th class="">{{__('admin.sales_value')}}</th>
            <th class="">{{__('admin.gross_profit')}}</th>
            <th class="">{{__('admin.gross_margin')}}</th>
            {{-- <th class="d-sm-table-cell user-td">{{__('admin.user')}}</th>--}}
            {{-- <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th> --}}
        </tr>
      </thead>
      <tbody>
      @foreach($sessions as $grossMargin)

        <?php
            // dd( $grossMargin->id );
            $GetCalculations = $SessionController->calculate_gross_margin(array('session_id' => $grossMargin->id));
            // dd($GetCalculations);
            $total_hours            = $GetCalculations['total_hours'];
            $on_demand_cost         = $GetCalculations['on_demand_cost'];
            $trainer_cost           = $GetCalculations['trainer_cost'];
            $trainees_no            = $GetCalculations['trainees_no'];
            $sid                    = $GetCalculations['session_id'];
            $href_s = null;
            if($sid){
                $session_date = $grossMargin->published_from.' - <br>'.$grossMargin->published_to;
                $href_s = env('APP_URL');
                $href_s .= '/training/sessions/';
                $href_s .= $sid;
                $href_s .= '/edit?post_type=session';
                $href_s = '<a target="_blank" href="'.$href_s.'" class="btn btn-sm text-primary">'.$session_date.'</a>';
            }

            $href                   = $GetCalculations['href'];
            $attendants_link = null;
            if($href){
                $href_new = env('APP_URL');
                $href_new .= '/training/session/attendant?session=';
                $href_new .= $sid;
                $href_new .= '&post_type=session';
                $attendants_link = '<a target="_blank" href="'.$href_new.'" class="btn btn-sm text-primary"><i class="fa fa-users"></i><span class="d-block">'.$trainees_no.'</span></a>';
            }

            $material_cost_course   = $GetCalculations['material_cost_course'];
            $material_cost          = $GetCalculations['material_cost'];
            $delivery_cost          = $GetCalculations['delivery_cost'];
            $sales_value            = $GetCalculations['sales_value'];
            $gross_profit           = $GetCalculations['gross_profit'];
            $gross_margin           = $GetCalculations['gross_margin'];
        ?>
      <tr data-id="{{$grossMargin->id}}">
        <td>
            <span class="light">{{$loop->iteration}}</span>
        </td>
        <td>
            <span class="light">{{$grossMargin->trainingOption->course->trans_title??null}}</span>
            
        </td>
        {{--<td>
            <span class="light">{{$grossMargin->trainingOption->type->trans_name??null}}</span>
        </td>--}}
        <td style="width:120px;">
        {{--<span class="light">{!! $href_s !!}</span>--}}
             <span class="light">{{$grossMargin->published_from??null}} <br> {{$grossMargin->published_to??null}}</span> 
        </td>
        <td>
            <span class="light">
                {{($grossMargin->duration)?$grossMargin->duration.' '.$grossMargin->durationType->trans_name??null:''}} <small class="d-block">{{($grossMargin->hours_per_day)?$grossMargin->hours_per_day.' Hrs/day':''}}</small>
            </span>
        </td>
        <td>
            <span class="light">
                {{($total_hours)?$total_hours.' Hours':''}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($grossMargin->zoom_cost)? NumberFormatWithComma($grossMargin->zoom_cost??0).' SAR':''}}
            </span>
        </td>
       
      
        <td>
            <span class="light">
                {{$trainees_no??null}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($on_demand_cost)? NumberFormatWithComma($on_demand_cost??0).' SAR':''}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($trainer_cost)? NumberFormatWithComma($trainer_cost??0).' SAR':''}}
            </span>
        </td>
        <td>
            <span class="light">
                <small class="text-secondary">{{($material_cost_course)?$material_cost_course.' SAR/one':''}}</small>
                {{($material_cost)? NumberFormatWithComma($material_cost??0).' SAR':''}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($delivery_cost)? NumberFormatWithComma($delivery_cost??0).' SAR':''}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($sales_value)? NumberFormatWithComma($sales_value??0).' SAR':''}}
            </span>
        </td>
        <td>
            <span class="light">
                {{($gross_profit)? NumberFormatWithComma($gross_profit??0).' SAR':''}}
            </span>
        </td>
        <td>
            <?php
                

                    if($gross_margin < 20)
                    {
                        $color = "danger";
                        $sign = "fa-arrow-down";
                    } 
                    else if ($gross_margin < 49)
                    {
                        $color = "warning";
                        $sign = "fa-arrow-right";
                    }
                    else
                    {
                        $color = "success"; 
                        $sign = "fa-arrow-up";
                    }
                    if($grossMargin->is_confirmed == 1)
                    {
                        $cancel_show = 'block';
                        $confirm_show = 'none';
                    }
                    else
                    {
                        $cancel_show = 'none';
                        $confirm_show = 'block';
                    }

                    ?>
                    <span class=" d-block badge badge-{{$color}} mb-1 "><i class="fas {{ $sign }}"></i>
                         {{($gross_margin)? NumberFormatWithComma($gross_margin??0).' %':''}}
                    </span>   
                                    
                    <button type="button" style="display:{{$cancel_show}}" class="btn btn-sm px-3 btn-danger" id="cancel_{{$grossMargin->id}}" onclick="confirm_msg(0,{{$grossMargin->id}})">{{__('admin.cancel')}}</button>
                    <button type="button" style="display:{{$confirm_show}}" class="btn btn-sm px-3  btn-success" id="confirm_{{$grossMargin->id}}" onclick="confirm_msg(1,{{$grossMargin->id}})">{{__('admin.confirm')}}</button>
        </td>
       {{-- <td class="d-sm-table-cell">
          <span class="author">
            {!!$grossMargin->published_at??null!!}<br>
          </span>
        </td>--}}
        {{-- <td class="d-none d-sm-table-cell">{!!Builder::UploadRow($grossMargin)!!}</td> --}}
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<?php
    $array = Builder::get_appends(request()->all());
?>
{{ $sessions->appends($array)->links() }}
<script>
   function confirm_msg(is_confirm,session_id) {
        var txt;
        // alert($confirm);
        
        var r = confirm("ِAre You Sure!");
        if (r == true) {
            axios.get('{{route("training.sessions.confirm_session")}}', {
                params: {
                    'session_id' : session_id, 
                    'confirm'    : is_confirm,
                }
            })
                .then(function(resp){
                    console.log(resp);
                    if(is_confirm == 0)
                    {
                        $("#confirm_"+session_id).css('display','block');
                        $('#cancel_'+session_id).css('display','none');
                    }
                    else
                    {
                        $('#confirm_'+session_id).css('display','none');
                        $('#cancel_'+session_id).css('display','block');
                    }
                        
                }.bind(this))
                .catch(function(err){
                });
        }


        
    }
</script>