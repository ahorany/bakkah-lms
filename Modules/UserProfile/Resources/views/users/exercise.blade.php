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
                    <div class="p-5">
                        <small>Dashboard / My Course / ITEL</small>
                        <h1 style="font-weight: 700; margin: 5px 0 10px;">ITEL Course</h1>
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-12">
                                <form action="">
                                    <div class="card position-relative p-5 mb-4" style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                                        <div class="position-absolute number_question">Q1</div>
                                        <p class="question">What is the Worranty?</p>
                                        <label>Select one:</label>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer1">
                                            <label for="answer1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer2">
                                            <label for="answer2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer3">
                                            <label for="answer3">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer4">
                                            <label for="answer4">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                    </div>
                                    <div class="card position-relative p-5 mb-4" style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                                        <div class="position-absolute number_question">Q1</div>
                                        <p class="question">What is the Worranty?</p>
                                        <label>Select one:</label>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer1">
                                            <label for="answer1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer2">
                                            <label for="answer2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer3">
                                            <label for="answer3">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                        <div class="answer my-2">
                                            <input type="radio" name="answer" id="answer4">
                                            <label for="answer4">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit, obcaecati.</label>
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-md-6 col-6 col-lg-6 p-0">
                                            <input type="submit" value="Submit">
                                        </div>
                                        <div class="col-md-6 col-6 col-lg-6 text-right p-0 py-1">
                                            <div class="arrow">
                                                <i class="fas fa-angle-left"></i>
                                                <i class="fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2 col-lg-2 col-12">
                                <div class="card py-4" style="width: 100%; height:100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                                    <div class="row m-0">
                                        <div class="col-md-12 col-lg-12 col-12 mb-3">
                                            <h5 class="title">Quiz Navigation</h5>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation done_question">1</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation done_question">2</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">3</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">4</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">5</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">6</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">7</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">8</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">9</label>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 text-center px-1">
                                            <label class="navigation">10</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
               