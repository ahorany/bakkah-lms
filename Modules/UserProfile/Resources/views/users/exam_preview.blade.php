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
            /* font-size: 30px; */
            font-weight: 700;
            width: 85px;
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
        .done_answer{
            color:#fff;
            background: #2a9055 !important;
        }

        .fail_answer{
            color:#fff;
            background: #da4f49 !important;
        }
        .exam-preview .col-md-3.col-lg-2.col-12.px-0 {
            position: static!important;
            margin: 60px 0 30px;
        }
    </style>
    <div class="userarea-wrapper exam-preview">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10" id="exam">
                <div class="main-user-content m-4">
                    <div class="p-5 exams">
                        <small>Dashboard / Exams / {{$exam->title}}</small>
                        <h1 style="font-weight: 700; margin: 5px 0 10px;">{{$exam->title}}</h1>
                        <div class="row">
                            <div  class="col-md-9 col-lg-9 col-12 questions">
                                    <template v-for="(question, index) in paginated" >
                                        {{-- <div v-text="question.mark + 'marks'"></div> --}}
                                      <div :ref="'question'+question.id" :id="'question'+question.id" :key="index" class="card position-relative p-5 mb-4 exam " style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                                        <div class="position-absolute number_question">
                                            <span class="d-block" v-text="'Q' + (index+indexStart+1)" ></span>
                                            <span class="d-block" v-text="question.mark + ' marks'" ></span>
                                        </div>
                                        <p class="question" v-text="question.title"></p>
                                        <label>Select one:</label>

                                          <template v-if="page_type == 'exam'">
                                              <div  v-if="question.answers_count == 1" v-for="answer in question.answers" class="answer my-2">
                                                  <input   type="radio" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="answers[answer.question_id] == answer.id ? true:false " @change="addAnswer(answer.question_id,answer.id)" :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                                  <label :for="answer.title + '_' + answer.id + '_' + answer.question_id" v-text="answer.title"></label>
                                              </div>

                                              <div  v-if="question.answers_count > 1" v-for="answer in question.answers" class="answer my-2">
                                                  <input   type="checkbox" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="searchMultiAnswers(answer.question_id,answer.id) ? true:false " @change="addMultiAnswers(answer.question_id,answer.id)"  :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                                  <label :for="answer.title + '_' + answer.id + '_' + answer.question_id" v-text="answer.title"></label>
                                              </div>

                                          </template>


                                          <template v-if="page_type != 'exam'">
                                              <template v-if="countCorrectAnswers(question) == 1" >
                                                  <div  v-for="answer in question.answers" class="answer my-2" :class="{'text-success' : answer.check_correct == 1 , 'text-danger' : (answer.check_correct == 0 && (answers.length > 0) && (answers[answer.question_id] != undefined) && (answers[answer.question_id].id == answer.id)) }">
                                                      <input   disabled="true" type="radio" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked=" (answers.length > 0) && (answers[answer.question_id] != undefined) ? answers[answer.question_id].id == answer.id ? true:false : false " :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                                      <label :for="answer.title + '_' + answer.id + '_' + answer.question_id" v-text="answer.title"></label>
                                                  </div>

                                                  <div v-if="(answers.length == 0 || (answers[question.id] == undefined)) || answers[question.id].check_correct == 0">
                                                      <span>Answers correct : </span>
                                                      <div class="text-success" v-for="answer in correct_answers(question)">
                                                          @{{  answer.title }}
                                                      </div>

                                                  </div>

                                                  <div v-if="question.feedback" class="mt-3">
                                                      <h3>Feedback : </h3>
                                                      <p class="p-2">@{{  question.feedback }}</p>
                                                  </div>
                                              </template>

                                              <template v-if="countCorrectAnswers(question) > 1">
                                                  <div  v-for="answer in question.answers" class="answer my-2" :class="{'text-success' : answer.check_correct == 1 , 'text-danger' : (answer.check_correct == 0 && (answers.length > 0) && (searchReviewMultiAnswers(answer.question_id,answer.id) ) ) }">
                                                      <input   disabled="true" type="checkbox" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="(answers.length > 0) ? (searchReviewMultiAnswers(answer.question_id,answer.id) ) : false" :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                                      <label :for="answer.title + '_' + answer.id + '_' + answer.question_id" v-text="answer.title"></label>
                                                  </div>
                                                  <div v-if="(answers.length == 0) || checkIfQuestionHasInCorrectAnswers(question.id)">
                                                      <span>Answers correct : </span>
                                                          <div class="text-success" v-for="answer in correct_answers(question)">
                                                              @{{  answer.title }}
                                                          </div>
                                                  </div>

                                                  <div v-if="question.feedback" class="mt-3">
                                                      <h3>Feedback : </h3>
                                                      <p class="p-2">@{{  question.feedback }}</p>
                                                  </div>
                                              </template>


                                          </template>



                                    </div>
                                    </template>


                                    <div class="row m-0 my-2">
                                            <div class="col-md-6 col-6 col-lg-6 p-0">
                                                <template v-if="save_status && page_type == 'exam' ">
                                                   <input type="submit" @click.prevent="save()"  value="Submit">
                                                </template>
                                            </div>

                                        {{-- <div class="col-md-4 col-4 col-lg-4 text-center p-0 py-2">


                                        </div> --}}
                                        <div class="col-md-6 col-6 col-lg-6 text-right p-0 py-1">
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

                            <div class="col-md-3 col-lg-3 col-12 px-0">
                                <div class="card py-4 navigation" style="width: 100%; height:100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;"  >
                                    <div class="row m-0">

                                        <div  class="col-md-12 col-lg-12 col-12 mb-3">
                                            <h5 class="title">Quiz Navigation</h5>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-12 mb-3 text-center p-0">
                                            <template v-if="page_type == 'exam' && !without_timer">
                                                <div style="background: #ffe13e; background: #00bcb3; color: #fff;" class="time">
                                                    <span>
                                                        <i class="far fa-clock"></i>
                                                        <span id="demo"></span>
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                        <template v-if="page_type == 'exam' "  v-for="(question, index) in exam.questions">
                                            <div :key="question.id"  class="col-md-4 col-lg-4 col-4 text-center px-1">
                                                <label  @click="searchAndOpenQuestion(question.id)" class="navigation" :class="{'done_question': answers[question.id]  ? true:false}" v-text="index+1"></label>
                                            </div>
                                        </template>

                                        <template v-if="page_type != 'exam' "  v-for="(question, index) in exam.questions">
                                            <div v-if="countCorrectAnswers(question) == 1" :key="question.id"  class="col-md-4 col-lg-4 col-4 text-center px-1">
                                                <label  @click="searchAndOpenQuestion(question.id)" class="navigation" :class="{'done_answer' : answers[question.id] ? answers[question.id].check_correct == 1 : false , 'fail_answer' : answers[question.id] ? answers[question.id].check_correct == 0 : true}" v-text="index+1"></label>
                                            </div>

                                            <div v-else :key="question.id"  class="col-md-4 col-lg-4 col-4 text-center px-1">
                                                <label  @click="searchAndOpenQuestion(question.id)" class="navigation" :class="{'done_answer' : !checkIfQuestionHasInCorrectAnswers(question.id) , 'fail_answer' : answers.length == 0 || checkIfQuestionHasInCorrectAnswers(question.id)}" v-text="index+1"></label>
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
        window.start_user_attepmt = {!! json_encode($start_user_attepmt??[]) !!}
        window.page_type = {!! json_encode($page_type??'exam') !!}
        window.without_timer = {!! json_encode($without_timer??false) !!}

        new Vue({
            'el' : '#exam',
            'data' : {
                exam: window.exam,
                page_type: window.page_type,
                without_timer: window.without_timer,
                start_user_attepmt: window.start_user_attepmt,
                current: 1,
                user_exam_id: '',
                indexStart: 0,
                pageSize: 3,
                save_status: false,
                prev_status: false,
                answers : [],
                multi_answers : [],
                hours: 0,
                minutes: 0,
                seconds: 0,
                question_id: null,
                request_answers : null,
                request_question : null,
            },

            computed: {
                paginated() {
                    return this.exam.questions.slice(this.indexStart, this.pageSize * this.current );
                },
            },

            created(){
                this.pageSize = this.exam.exam.pagination
                if(this.page_type == 'exam'){
                    this.user_exam_id = this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].id

                    if(this.exam.questions.length == 1){
                        this.save_status = true
                    }

                    let self = this;
                    if(this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].user_answers != undefined){
                        this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].user_answers.forEach(function (value) {
                           if(self.answers[value.question_id] != undefined && self.answers[value.question_id] !=null ){
                                if(Array.isArray(self.answers[value.question_id])){
                                    self.answers[value.question_id].push(value.id)
                                }else{
                                    self.answers[value.question_id] = [ self.answers[value.question_id] ,value.id ]
                                }
                           }else{
                               self.answers[value.question_id] = value.id;
                           }
                        })
                    }

                    if(Math.ceil( this.exam.questions.length / this.pageSize) == 1){
                        this.save_status = true
                    }

                    this.countdownTimeStart();

                }else{
                    let self = this;
                    if(this.exam.user_answers != undefined){
                        this.exam.user_answers.forEach(function (value) {
                            // self.answers[value.question_id] = value;
                            if(self.answers[value.question_id] != undefined && self.answers[value.question_id] !=null ){
                                if(Array.isArray(self.answers[value.question_id])){
                                    self.answers[value.question_id].push(value)
                                }else{
                                    self.answers[value.question_id] = [ self.answers[value.question_id] ,value ]
                                }
                            }else{
                                self.answers[value.question_id] = value;
                            }



                        })
                    }
                    this.exam = this.exam.exam.content;

                }

            },

            methods : {

                // shared methods bettwen exam and review
                searchAndOpenQuestion : function(question_id){
                    // index question
                    let current_page = this.current
                    let start_index = 0;
                    let page = 1;
                    let next = false;

                    for (let i = 0; i <  this.exam.questions.length; i++) {
                        if(next){
                            page++;
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
                    // const  [element] = this.$refs["question"+question_id];
                    // element.scrollIntoView({ behavior: "smooth", block: "end" });

                    // var top = element.offsetTop;
                    // alert(top)
                    // window.scrollTo(0, top);

                    // console.log($('.questions').find($("#question"+question_id)).offset().top)

                    // $('html, body').animate({
                    //     scrollTop: $("#question"+question_id).offset().top - 100
                    // }, 500);
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

                    // this.nextSaveAnswers();
                },



                // review methods
                countCorrectAnswers : function(question){
                   return this.correct_answers(question).length
                },
                search_correct_answer: function(question,answer){
                    let answers = this.correct_answers(question)
                    answer = answers.filter(function (value) {
                        return  value.id == answer.id;
                    })
                    if(answer)
                        return true;

                     return false;
                },
                correct_answers: function(question){
                   return question.answers.filter(function (value) {
                        return  value.check_correct == 1;
                    })
                },
                searchReviewMultiAnswers : function(question_id,answer_id){
                    if(this.answers[question_id] == undefined)
                        return false;

                    let answer = null;
                    if(Array.isArray(this.answers[question_id])) {
                        answer = this.answers[question_id].filter(function (answer,index) {
                            return( answer.id == answer_id )
                        })
                    }else{
                        answer = [this.answers[question_id]]
                    }

                    if(answer.length > 0)
                        return true;

                    return false;
                },
                checkIfQuestionHasInCorrectAnswers : function(question_id){
                    // if(this.answers[question_id] == undefined)
                    //     return false;

                    let answer = null;
                    if(Array.isArray(this.answers[question_id])){
                        answer = this.answers[question_id].filter(function (answer,index) {
                            return( answer.check_correct == 0 )
                        })
                    }else{
                        answer = [this.answers[question_id]]
                    }

                    if(answer.length > 0)
                        return true;

                    return false;
                },



                // exam methods
                countdownTimeStart : function(){
                    var self = this;
                    let t = this.start_user_attepmt
                        t = new Date(t)
                        t.setSeconds({{$exam->exam->duration??null}})
                    var countDownDate = t.getTime();

                    var x = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = countDownDate - now;
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if(!without_timer){
                            document.getElementById("demo").innerHTML = hours + "h "
                                + minutes + "m " + seconds + "s ";
                        }

                        if (distance < 0) {
                            clearInterval(x);
                            if(!without_timer){
                                document.getElementById("demo").innerHTML = "EXPIRED";
                                self.nextSaveAnswers('save');
                            }
                        }

                    }, 1000);
        },
                save : function(){
                    if(this.page_type != 'exam') {
                        return ;
                    }

                    if(confirm('Are you sure (save answers) !!!')){
                        this.nextSaveAnswers('save');
                    }
                },
                addAnswer : function (question_id,answer_id) {
                    if(this.page_type != 'exam') {
                        return ;
                    }

                    this.answers[question_id] = answer_id
                    this.request_question = question_id
                    this.request_answers = answer_id
                    this.nextSaveAnswers();
                },
                searchMultiAnswers : function(question_id,answer_id){
                    if(this.answers[question_id] == undefined)
                        return false;
                    let answer = this.answers[question_id].filter(function (answer,index) {
                        return( answer == answer_id )
                    })

                    if(answer.length > 0)
                        return true;

                    return false;
                },
                addMultiAnswers : function (question_id,answer_id) {
                    if(this.page_type != 'exam') {
                        return ;
                    }

                    if(event.target.checked){
                        if(this.answers[question_id] == undefined){
                            this.answers[question_id] = [answer_id];
                        }else{
                            let answer_index = null;
                            this.answers[question_id].forEach(function (answer,index) {
                                if( answer == answer_id ){
                                    answer_index = index
                                }
                            })
                            if(answer_index){
                                this.answers[question_id][answer_index] = answer_id;
                            }else{
                                this.answers[question_id].push(answer_id);
                            }
                        }
                    }else{
                        let answer_index = null;
                        this.answers[question_id].forEach(function (answer,index) {
                            if( answer == answer_id ){
                                answer_index = index
                            }
                        })
                        if(answer_index >= 0){
                                this.answers[question_id].splice(answer_index,1);
                        }
                    }

                    this.request_question = question_id
                    this.request_answers =   this.answers[question_id];

                    this.nextSaveAnswers();
                },
                nextSaveAnswers : function (status = null) {
                    let self = this;

                    if(this.page_type != 'exam') {
                        return ;
                    }


                       let  data = {
                            question_id : self.request_question,
                            answer : self.request_answers,
                            user_exam_id : self.user_exam_id,
                            status : status
                        }



                    axios.post("{{route('user.exam.add_answers')}}",
                        data
                    )
                        .then(response => {
                            if(response.data.status == 'success'){
                                window.location.replace(response.data.redirect_route);
                            }

                            self.request_answers = []
                        })
                        .catch(e => {
                            console.log(e)
                        });
                }
            }
        })

    </script>



@endsection
