<?php
  use  App\Helpers\Lang;
?>

<div class="card">
  <div class="card-header">
  {{-- {!!Builder::TableAllPosts($count, $departments->count())!!} --}}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>

            <th width="3%">{{__('admin.index')}}</th>
            <th width="6%">{{__('training.s_id')}}</th>
            <th width="20%">{{__('training.course')}}</th>
            <th width="6%"> {{__('training.training_option')}}  </th>
            <th width="20%">{{__('training.date')}}</th>
            <th width="6%">{{__('training.registeration')}}</th>
            <th width="6%">{{__('training.not_complete')}}</th>
            <th width="6%">{{__('training.complete')}}</th>
            <th width="6%">{{__('training.bakkah_emp')}}</th>
            <th width="6%">{{__('training.refund')}}</th>
            <th width="6%">{{__('training.po')}}</th>
            <th width="6%">{{__('training.free_seat')}}</th>

        </tr>
      </thead>
      <tbody>
      @foreach($results as $result)
      <tr data-id="{{$result->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span><a target="_blank" href="{{route('training.carts.index',['session_id'=>$result->id])}}">{{$result->id}}</a></span>

        </td>
        <td>
        {!!Lang::TransTitle($result->course_title??null)!!}

          @if(isset($result->type_id))
              <?php
              $class = [
                  370 => 'dark', // B2B
                  372 => 'info', // Group
                  373 => 'warning', // RFQ
                  374 => 'success', // B2C
                  // 373 => 'danger',
              ];
              ?>
              <span class="d-block badge badge-{{$class[$result->type_id]??null}}">
                  {!!Lang::TransTitle($result->type_name??null)!!}

              </span>
          @endif
        </td>
        <td>
        {!!Lang::TransTitle($result->tr_option??null)!!}

        </td>
        <td>
           {{$result->date_from??null}}<br>{{$result->date_to??null}}
        </td>
        <td>
        <?php
              $getSales = App\Models\Training\Cart::getSales($result->id,$with_vat,null);
        ?>
          @foreach($getSales as $getSale)
              <span class="d-block">
                  <span class="ml-2 paid-value badge {{($getSale['coin_id']==334)?'badge-success':'badge-warning'}}">
                      {{($getSale['total_after_vat']!=0)?NumberFormatWithComma($getSale['total_after_vat']):0}} <small>{{($getSale['coin_id']==334) ? ' SAR' : ' USD'}}</small>
                  </span>
                  <span class="ml-2 paid-value badge badge-dark text-white">{{$getSale['trainees_no']}} Trainees</span>
              </span>
            @endforeach
        </td>
        </td>
        <?php
              $arr=[63,68,315,316,317,332];
              foreach ($arr as $value)
              {
                  $getSales = App\Models\Training\Cart::getSales($result->id,$with_vat,$value);
                  ?>
                      <td>

                            @foreach($getSales as $getSale)
                                <span class="d-block">
                                <span><a class="ml-2 paid-value badge {{($getSale['coin_id']==334)?'badge-success':'badge-warning'}}" target="_blank"
                                          href="{{route('training.carts.index',['session_id'=>$result->id,'course_id'=>$result->course_id,'payment_status'=>$value,'coin_id'=>$getSale['coin_id']])}}">{{($getSale['total_after_vat']!=0)?NumberFormatWithComma($getSale['total_after_vat']):0}}
                                          <small>{{($getSale['coin_id']==334) ? ' SAR' : ' USD'}}</small>
                                        </a>
                                </span>

                                <span class="ml-2 paid-value badge badge-dark text-white">{{$getSale['trainees_no']}} Trainees</span>
                                </span>
                              @endforeach
                        </td>
                  <?php
              }
        ?>
      </tr>
      @endforeach
      </tbody>
    </table>
    {{$paginator->render()}}
  </div>
</div>
