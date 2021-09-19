@foreach($courses as $course)
    <?php
    $isFound = $sessions->where('id', $course->id)->count();
    $isFoundself = $course->trainingOptions->where('constant_id', 11)->first()->session->id??null;
    ?>
    @if($isFound != 0 || !is_null($isFoundself))
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="m-0"><a href="{{route('education.courses.single', ['slug'=>$course->slug])}}" class="course_name">{{$course->trans_title}}</a></h4>
            </div>

            <div class="bg-light py-5" id="trainig-schedule">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0">{{__('education.Online Training Schedule')}}</h2>

                        <?php $self = $course->trainingOptions->where('constant_id', 11)->first(); ?>
                            @if(isset($self->session->id))
                            <a href="{{route('education.courses.register', ['slug'=>$course->slug, 'session_id'=>$self->session->id])}}" class="btn btn-primary">{{__('education.Self Study Registration')}}</a>
                        @endif
                    </div>

                    <table class="tc-table table table-striped table-responsive bg-light text-center card border" id="table-5">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th>{{__('education.Date')}}</th>
                            <th>{{__('education.Duration')}}</th>
                            <th>{{__('education.Time')}}</th>
                            <th>{{__('education.language')}}</th>
                            <th>{{__('education.Price')}}</th>
                            <th>{{__('education.Registration')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sessions->where('id', $course->id) as $session)

                            {{$SessionHelper->SetCourse($session)}}
                            <tr>
                                <td>
                                    {{-- {{$session->title}} --}}
                                    <span>{{App\Helpers\Date::IsoFormat($session->session_date_from)}}</span> -
                                    <span>{{App\Helpers\Date::IsoFormat($session->session_date_to)}}</span>
                                </td>
                                <td>{{$SessionHelper->SessionDuration()}}</td>
                                <td>{!! $session->session_time !!}</td>
                                <td>{{App\Helpers\Lang::TransTitle($session->language_name)}}</td>
                                <td>{{NumberFormatWithComma($SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT())}} {{__('education.SAR')}}</td>
                                <td>
                                    <a class="btn btn-primary" name="session_id" href="{{route('education.courses.register', ['slug'=>$course->slug, 'session_id'=>$session->session_id])}}">{{__('education.Register')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div> <!-- #trainig-schedule -->

        </div>
    </div>
    @endif
@endforeach
