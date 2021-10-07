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
            <div class="col-md-9 col-lg-10" id="exam">
                <div class="main-user-content m-4">
                    <div class="p-5 exams">
                        <small>Dashboard / Exams / {{$exam->title}}</small>
                        <h1 style="font-weight: 700; margin: 5px 0 10px;">{{$exam->title}}</h1>
                        <div class="row">
                            <div class="col-md-9 col-lg-10 col-12 questions">
                                    <template v-for="(question, index) in paginated">
                                      <div :id="'question'+question.id" :key="index" class="card position-relative p-5 mb-4 exam" style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                                        <div class="position-absolute number_question" v-text="'Q' + (index+indexStart+1)"></div>
                                        <p class="question" v-text="question.title"></p>
                                        <label>Select one:</label>
                                          <template v-for="answer in question.answers">
                                              <div class="answer my-2">
                                                <input type="radio" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="answers[answer.question_id] == answer.id ? true:false " @change="addAnswer(answer.question_id,answer.id)" :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                                <label :for="answer.title + '_' + answer.id + '_' + answer.question_id" v-text="answer.title"></label>
                                              </div>
                                          </template>
                                    </div>
                                    </template>


                                    <div class="row m-0 my-2">
                                            <div class="col-md-4 col-4 col-lg-4 p-0">
                                                <template v-if="save_status">
                                                  <input type="submit" @click.prevent="save()"  value="Submit">
                                                </template>
                                            </div>

                                        <div class="col-md-4 col-4 col-lg-4 text-center p-0 py-2">
                                            <div class="time">
                                                <span>
                                                    <i class="far fa-clock"></i>
                                                    <span id="demo"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-4 col-lg-4 text-right p-0 py-1">
                                            <div class="arrow">
                                                <template v-if="prev_status">
                                                  <i @click.prevent="prev()"  class="fas fa-angle-left"></i>
                                                </template>

                                                <template v-if="!save_status" >
                                                   <i @click.prevent="next()" class="fas fa-angle-right"></i>
                                                </template>

                                            </div>
                                        </div>
                                    </div>


                            </div>

                            <div class="col-md-3 col-lg-2 col-12 px-0">
                                <div class="card py-4 navigation" style="width: 100%; height:100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;"  >
                                    <div class="row m-0">
                                        <div  class="col-md-12 col-lg-12 col-12 mb-3">
                                            <h5 class="title">Quiz Navigation</h5>
                                        </div>
                                        <template v-for="(question, index) in exam.questions">
                                            <div :key="question.id"  class="col-md-4 col-lg-4 col-4 text-center px-1">
                                                <label @click="searchAndOpenQuestion(question.id)" class="navigation" :class="{'done_question': answers[question.id]  ? true:false}" v-text="index+1"></label>
                                            </div>
                                        </template>
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


@section('scripts')

    <script>

        window.exam = {!! json_encode($exam??[]) !!}

        new Vue({
            'el' : '#exam',
            'data' : {
                exam: window.exam,
                current: 1,
                user_exam_id: '',
                indexStart: 0,
                pageSize: 3,
                save_status: false,
                prev_status: false,
                answers : [],
                hours: 0,
                minutes: 0,
                seconds: 0,
                question_id: null,
            },

            computed: {
                paginated() {
                    return this.exam.questions.slice(this.indexStart, this.pageSize * this.current );
                },
            },

            created(){
                this.pageSize = this.exam.exam.pagination
                this.user_exam_id = this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].id
                if(this.exam.questions.length == 1){
                    this.save_status = true
                }

                let self = this;
               if(this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].user_answers != undefined){
                   this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].user_answers.forEach(function (value) {
                       self.answers[value.question_id] = value.id;
                   })
               }


               if(Math.ceil( this.exam.questions.length / this.pageSize) == 1){
                   this.save_status = true
               }

                this.countdownTimeStart();
                // console.log(this.paginated)
                // console.log(this.exam)
            },
            methods : {

           countdownTimeStart : function(){
               var self = this;
            // if (localStorage.getItem("start_time_exam") === null) {
            //     var now = new Date().getTime();
            //     localStorage.setItem('start_time_exam', now);
            // }

            let t = new Date()
            t.setSeconds({{$exam->exam->duration}})
            var countDownDate = t.getTime();

// Update the count down every 1 second

            var x = setInterval(function() {

                // Get todays date and time
                // var now = new Date().getTime();
                // var now = localStorage.getItem('start_time_exam');
                // var now2 = new Date().getTime();
                var now = new Date().getTime();


                // Find the distance between now an the count down date
                var distance = countDownDate - now;
                // alert(now + " " + distance + " " + countDownDate)
                // Time calculations for days, hours, minutes and seconds
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // // Output the result in an element with id="demo"
                document.getElementById("demo").innerHTML = hours + "h "
                    + minutes + "m " + seconds + "s ";

                // alert(hours + "h " + minutes + "m " + seconds + "s ")

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("demo").innerHTML = "EXPIRED";
                    self.nextSaveAnswers('save');
                }

                // var now = new Date().getTime();
                // localStorage.setItem('start_time_exam', parseInt(now) + 1000);
            }, 1000);
        },


        searchAndOpenQuestion : function(question_id){
                    // index question
                    let current_page = this.current
                    let start_index = 0;
                    let page = 1;
                    let next = false;

                    for (let i = 0; i <  this.exam.questions.length; i++) {
                        if(next){
                            page++;
                            // alert(page)
                            start_index =  i;
                            next = false;
                        }

                        if( (i + 1) % this.pageSize == 0){ //
                            next = true
                        }

                        if(this.exam.questions[i].id == question_id ){
                            break;
                        }

                    }


                    this.prev_status = true;
                    this.save_status = false;

                    if(page == 1){
                        this.prev_status = false;
                    }


                    if((page) == Math.ceil( this.exam.questions.length / this.pageSize)){
                            this.save_status = true;
                    }

                    this.current = page;
                    this.indexStart= start_index
                    this.question_id = question_id

                },
                save : function(){
                    if(confirm('Are you sure (save answers) !!!')){
                        this.nextSaveAnswers('save');
                    }
                },
                prev() {
                    this.save_status = false;
                    this.indexStart -= this.pageSize;
                    --this.current;
                    // alert((this.current))

                    if((this.current) == 1){
                        this.prev_status = false;
                    }
                },
                next() {
                    this.prev_status = true;
                    this.indexStart += this.pageSize;
                    ++this.current;
                    // alert(this.current)

                    if((this.current) == Math.ceil( this.exam.questions.length / this.pageSize)){
                        this.save_status = true;
                    }

                    this.nextSaveAnswers();
                },
                addAnswer : function (question_id,answer_id) {
                    this.answers[question_id] = answer_id
                },
                nextSaveAnswers : function (status = null) {
                    let self = this;
                    let data = {
                        answers : self.answers,
                        user_exam_id : self.user_exam_id,
                        status : status
                    }

                    axios.post("{{route('user.exam.add_answers')}}",
                        data
                    )
                        .then(response => {
                            console.log(response)
                            if(response.data.status == 'success'){
                                window.location.replace(response.data.redirect_route);
                            }
                        })
                        .catch(e => {
                            console.log(e)
                        });
                }
            }
        })



    </script>



@endsection
