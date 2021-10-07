@extends(FRONT.'.education.layouts.master')

{{-- @section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection --}}

@section('style')
    <style>
        textarea{
            display: none;
        }

        +label {
            background-color: red;
        }

        .alert.alert-success {
            background: #28a745cf;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 10px;
        }

        .alert.alert-danger{
            background: #dc3545c9;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class='alert alert-success'>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card p-5 user-info">
                        <form action="{{route('user.send_complaint')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-12 my-3">
                                    <p for="courses" class="mb-2">{{ __('education.Choose Course:') }}</p>
                                    <select name="courses" id="courses" class="px-2" style="width: 25%; height:35px">
                                            <option value="-1" disabled selected>Choose...</option>
                                        @foreach ($user->carts as $courses)
                                            <option value="{{$courses->course->id}}">{{$courses->course->trans_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- @dd($type) --}}
                                <div class="col-md-12 col-lg-12 col-12 my-3">
                                    @foreach ($questions as $question)
                                        {!! Builder::Hidden('question_id', $question->id) !!}
                                        {!! Builder::Hidden('post_type', $type) !!}
                                        <p>{{$question->title}}</p>
                                        <ul type="none">
                                            @foreach ($question->profile_answers as $answer)
                                                <li class="my-3">
                                                    <div class="answers" style="width: max-content">
                                                        <input type="radio" value="{{$answer->id}}" name="answer" id="reason{{$loop->iteration}}">
                                                        <label value="{{$answer->id}}" for="reason{{$loop->iteration}}">{{$answer->title}}</label><br>
                                                    </div>
                                                    @if ($answer->has_reason != 0)
                                                        <textarea  cols="50" rows="5"></textarea>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <input type="submit" value="Submit" class="btn" style="background: #fb4400; color: #fff;" />
                                </div>
                            </div>
                        </form>
                        {{-- {!!Builder::Select('course_id', 'course_id', ['col'=>'col-md-6', 'model_title'=>'trans_title'])!!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
            $("textarea").hide();
            $(".answers").click(function(){
                $("textarea").attr('name',"").hide();
                $("textarea").val('');
                $(this).siblings("textarea").attr('name',"reason").show();
            });
    </script>
@stop
