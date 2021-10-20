@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

<style>
.userarea-wrapper{
    background: #fafafa;
}
.number_question {
    top: 0;
    right: 0;
    text-align: center;
    background: #fb4400;
    border-bottom-left-radius: 15px;
    color: #fff;
    font-size: 30px;
    font-weight: 700;
    width: 100px;
    height: 100px;
    padding: 30px 0;
}
.question{
    font-size: 20px;
    font-weight: 700;
}
.card> label {
    font-size: 16px;
    font-weight: 700;
}
.answer label {
    font-size: 15px;
}
.arrow i {
    border: 1px solid;
    border-radius: 50%;
    padding: 5px 10px;
    text-align: center;
    margin: 0 5px;
    font-size: 20px;
    cursor:pointer;
}
.arrow i:hover {
    color: #fb4400
}
input[type="submit"] {
    background: transparent;
    border: 1px solid #fb4400;
    padding: 5px 25px;
    font-size: 16px;
    border-radius: 5px;
}
input[type="submit"]:hover{
    background:#fb4400;
    color:#fff;
}
label.navigation {
    border: 2px solid #9a9a9a;
    border-radius: 7px;
    width: 90%;
    height: 40px;
    text-align: center;
    padding: 10px 0;
    background:transparent;
}
.done_question{
    background: #efefef !important;
}
</style>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.Exam') }}</h4>
                        <div class="row">

                            <div class="col-12 mt-5">
                                <table class="table">
                                    <caption>Exam Details</caption>
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Mark</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($unit_marks as $data)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$data->unit_title?? 'Other'}}</td>
                                            <td >{{$data->marks .' / ' . $data->total_marks}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function confirmNewAttempt(){
           if( confirm('Are u sure ?') == false)
               event.preventDefault()
        }

    </script>
@endsection
