<?php use App\Constant; $training_option_id = request()->training_option_id??null; use App\Http\Controllers\Training\SessionController; $SessionController = new SessionController; ?>
<style>
    .width{
        width: 8.5%;
    }
</style>
<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}

        <div class="float-right d-inline-flex align-items-center justify-content-between">

            {{-- Abdullah, Hani, Tamer, Tariq --}}
            @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==5074 || auth()->user()->id==7085 )
                {{-- @dump($training_option_id); --}}
                {{-- Self-Study or Exam Simulation --}}
                @if($training_option_id == 11 || $training_option_id == 353)
                    <?php
                        $constant = Constant::where('id',$training_option_id)->first();
                        $option_name = $constant->en_name??null;
                    ?>
                    <small><span style="color: #fb4400; font-weight: bold;">IMPORTANT:</span> <span>This will export all paid invoices for the <span style="color: #fb4400;">{{$option_name}}</span> without any filter results of the above fields except the <span style="color: #fb4400;">Delivery Methods</span></span></small>
                    <a class="btn-warning btn-sm float-right mx-3" target="_blank" id="export-btn-xero" href="/xero/authorization?post_type=invoices&session=&training_option_id={{$training_option_id}}"><i class="fa fa-fa-solid fa-file-export"></i> {{__('admin.xero_invoices_run')}}</a>
                @endif
            @endif

            {!!Builder::TableAllPosts($count, $sessions->count())!!}
        </div>

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.course_id')}}</th>
                <th class="">{{__('admin.session_start_time')}}</th>
                <th class="">{{__('Attendance session status')}}</th>
                <th class="">{{__('admin.date_from')}}</th>
                <th class="">{{__('admin.date_to')}}</th>
                <th class="">{{__('admin.duration')}}</th>
                <th class="width">{{__('admin.price')}}</th>
                <th class="width">{{__('admin.exam_price')}}</th>
                <th class="width">{{__('admin.total')}}</th>
                <th class="">{{__('training.evaluation_api_code')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
                <th class="">{{__('Gross Margin')}}</th>
                {{-- <th class="">Attendance</th> --}}
            </tr>
            </thead>
            <tbody>
            <?php
            use App\Models\Training\Cart;$DateTimeNow = DateTimeNow();
            ?>
            @foreach($sessions as $post)
                     {{-- <input type="text" name="attendance_count" value="{{$post->attendance_count}}">--}}
                    {!!Builder::Hidden('session_id',$post->id??'')!!}
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->trainingOption->training_name??null}}
                            @if ($post->type_id == 370)
                                | B2B
                            @endif
                        </span>
                            {{--@dump($post->trainingOption->constant_id??11)--}}
                            {{--@dump($post->cart)--}}
                        <?php
                        $btns = [];
                        if($post->attendants_status_id != "429" && $post->attendants_status_id != "430" ){
                            $btns = ['Edit', 'Destroy'];
                        }

                        $constant_id = $post->trainingOption->constant_id??11;
                        if($constant_id==13){
                            if($post->carts){
                                array_push($btns, 'Attendance');
                            }
                          }
                        ?>
                        @if(($post->date_to < $DateTimeNow))
                            @if(((auth()->user()->id==1) || (auth()->user()->id==2) || (auth()->user()->id==6174 || auth()->user()->id==13094 || auth()->user()->id==5074)))
                                {!!Builder::BtnGroupRows($post->trainingOption->training_name??null, $post->id, $btns, [
                                    'post'=>$post->id,
                                ])!!}
                            @endif
                        @else
{{--                           @if($post->cart)--}}
                            {!!Builder::BtnGroupRows($post->trainingOption->training_name??null, $post->id, $btns, [
                                'post'=>$post->id,
                            ])!!}
{{--                            @else--}}
{{--                                {!!Builder::BtnGroupRows($post->trainingOption->training_name??null, $post->id, $btns, [--}}
{{--                                 'post'=>$post->id,--}}
{{--                             ])!!}--}}
{{--                           @endif--}}
                        @endif
                    </td>
                    <td>
                        <span class="light">
                            {{$post->session_start_time}}
                        </span>
                    </td>

                    <td>
                        <?php
                           $color = "danger";

                           if($post->attendants_status->id == 429){
                               $color = "warning";
                           }else if ($post->attendants_status->id == 428){
                               $color = "dark";
                           }else if ($post->attendants_status->id == 430){
                               $color = "success";
                           }

                        ?>
                        <span class="d-block badge badge-{{$color}} mb-1">{{$post->attendants_status->trans_name}}</span>
                        <span class="d-block author ">{{$post->attendantsStatusUpdated_by->trans_name??null}}</span>
                        <span class="d-block author">{{$post->attendants_status_updated_at}}</span>
                    </td>


                    <td>
                        <span class="light">
                            {{$post->published_from}}
                        </span>
                    </td>
                    <td>
                        <span class="light">
                            {{$post->published_to}}
                        </span>
                    </td>
                    <td>
                        <span class="light">
                            {{$post->duration}} {{$post->durationType->trans_name??null}}
                        </span>
                    </td>
                    <td>
                        <span class="light">
                            {{$post->price}}
                        </span>
                    </td>
                    <td>
                        <span class="light">
                            {{$post->exam_price}}
                        </span>
                    </td>
                    <td>
                        <span class="light">
                            {{$post->total}}
                        </span>
                    </td>
                    <td>
                        <span style="display: block;">
                            {{$post->evaluation_api_code??null}}
                        </span>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$post->published_at!!}
                      </span>
                    </td>
                    <td>
                        <?php


                        // Use other controller's method in this controller's method

                            // $href_s = null;
                            // $sid = $post->id??null;
                            // if($post->id){
                            //     $gross = __('Gross Margin');
                            //     $href_s = env('APP_URL');
                            //     $href_s .= '/training/gross-margin/create?sid=';
                            //     $href_s .= $sid;
                            //     $href_s = '<a target="_blank" href="'.$href_s.'" class="btn btn-sm text-white d-block badge badge-success p-1">'.$gross.'</a>';
                            // }
                            // // http://localhost:8000/training/gross-margin/create
                            $gross = $SessionController->calculate_gross_margin(array('session_id' => $post->id));

                            if($gross['gross_margin'] < 20)
                            {
                                $color = "danger";
                                $sign = "fa-arrow-down";
                            }
                            else if ($gross['gross_margin'] < 49)
                            {
                                $color = "warning";
                                $sign = "fa-arrow-right";
                            }
                            else
                            {
                                $color = "success";
                                $sign = "fa-arrow-up";
                            }


                        ?>
                        <span class="d-block badge badge-{{$color}} mb-1 "><i class="fas {{ $sign }}"></i>
                            {!! round($gross['gross_margin'],2) !!}
                        </span>

                    </td>
                    {{-- <td>
                        {{$attend_count}}
                    </td> --}}
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
