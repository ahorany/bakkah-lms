@extends('layouts.app')

@section('useHead')
    <title>{{$exam->title}} {{ __('education.Exam') }} | {{ __('home.DC_title') }}</title>
@endsection
<style>
.prev[disabled="disabled"], .next[disabled="disabled"] {
    opacity: 0.3;
}
.navigation {
    margin-left: auto;
    margin-right: auto;
}

.custom-radio .radio-mark::after {
    left: 2px !important;
    top: 2px !important;
    width: 10px !important;
    height: 10px !important;
    border: solid #06ae60;
    border-top-width: medium;
    border-right-width: medium;
    border-bottom-width: medium;
    border-left-width: medium;
    border-width: 3px !important;
    background-color: #06ae60;
    border-radius: 50% !important;
    -webkit-transform: none !important;
    -ms-transform: none !important;
    transform: none !important;
}
</style>
@section('content')


                  <div class="row MX-0 justify-content-end">
                    <div class="col-xl-9 col-lg-8 col-md-12">
                        <div class="dash-header course_info">
                            <h2>{{$exam->title}}</h2>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-12">
                        <p v-if="page_type == 'exam' && !without_timer" class="time-remaining main-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34.151" height="35.854" viewBox="0 0 34.151 35.854">
                                <g id="Group_122" data-name="Group 122" transform="translate(-1085.293 -313.029)">
                                <g id="Group_121" data-name="Group 121" transform="translate(1085.293 313.029)">
                                    <g id="Group_108" data-name="Group 108" transform="translate(19.269 20.974)">
                                    <path id="Path_117" data-name="Path 117" d="M1110.64,352.445v-1.3a10.538,10.538,0,0,0,10.527-10.526h1.3A11.84,11.84,0,0,1,1110.64,352.445Z" transform="translate(-1110.64 -340.618)" fill="#575756"/>
                                    </g>
                                    <g id="Group_109" data-name="Group 109" transform="translate(16.13 17.836)">
                                    <path id="Path_118" data-name="Path 118" d="M1109.65,342.771a3.14,3.14,0,1,1,3.141-3.14A3.143,3.143,0,0,1,1109.65,342.771Zm0-4.979a1.839,1.839,0,1,0,1.84,1.84A1.842,1.842,0,0,0,1109.65,337.792Z" transform="translate(-1106.51 -336.491)" fill="#575756"/>
                                    </g>
                                    <g id="Group_110" data-name="Group 110" transform="translate(15.623 22.712)">
                                    <rect id="Rectangle_72" data-name="Rectangle 72" width="3.198" height="1.301" transform="matrix(0.548, -0.836, 0.836, 0.548, 0, 2.674)" fill="#575756"/>
                                    </g>
                                    <g id="Group_111" data-name="Group 111" transform="translate(20.106 12.488)">
                                    <rect id="Rectangle_73" data-name="Rectangle 73" width="7.246" height="1.301" transform="translate(0 6.06) rotate(-56.746)" fill="#575756"/>
                                    </g>
                                    <g id="Group_112" data-name="Group 112" transform="translate(4.387 2.785)">
                                    <path id="Path_119" data-name="Path 119" d="M1105.946,349.762a14.779,14.779,0,0,1-5.873-1.2l.514-1.2a13.583,13.583,0,1,0,7.132-25.948.651.651,0,0,1-.566-.645v-2.776h-2.413v2.776a.651.651,0,0,1-.566.645,13.606,13.606,0,0,0-11.808,13.465,13.8,13.8,0,0,0,.214,2.416l-1.281.228a15.088,15.088,0,0,1-.234-2.644,14.913,14.913,0,0,1,12.375-14.67v-2.867a.649.649,0,0,1,.65-.65h3.714a.651.651,0,0,1,.651.65v2.867a14.882,14.882,0,0,1-2.508,29.552Z" transform="translate(-1091.064 -316.693)" fill="#575756"/>
                                    </g>
                                    <g id="Group_113" data-name="Group 113" transform="translate(15.054)">
                                    <path id="Path_120" data-name="Path 120" d="M1112.875,317.116h-7.129a.651.651,0,0,1-.651-.651v-2.786a.65.65,0,0,1,.651-.65h7.129a.651.651,0,0,1,.651.65v2.786A.652.652,0,0,1,1112.875,317.116Zm-6.479-1.3h5.829V314.33H1106.4Z" transform="translate(-1105.095 -313.029)" fill="#575756"/>
                                    </g>
                                    <g id="Group_114" data-name="Group 114" transform="translate(8.281 7.553)">
                                    <rect id="Rectangle_74" data-name="Rectangle 74" width="1.301" height="2.435" transform="matrix(0.803, -0.596, 0.596, 0.803, 0, 0.776)" fill="#575756"/>
                                    </g>
                                    <g id="Group_115" data-name="Group 115" transform="translate(7.23 6.538)">
                                    <rect id="Rectangle_75" data-name="Rectangle 75" width="2.953" height="1.301" transform="matrix(0.803, -0.596, 0.596, 0.803, 0, 1.76)" fill="#575756"/>
                                    </g>
                                    <g id="Group_116" data-name="Group 116" transform="translate(27.834 7.553)">
                                    <rect id="Rectangle_76" data-name="Rectangle 76" width="2.435" height="1.301" transform="matrix(0.596, -0.803, 0.803, 0.596, 0, 1.955)" fill="#575756"/>
                                    </g>
                                    <g id="Group_117" data-name="Group 117" transform="translate(28.235 6.539)">
                                    <rect id="Rectangle_77" data-name="Rectangle 77" width="1.301" height="2.953" transform="translate(0 1.044) rotate(-53.414)" fill="#575756"/>
                                    </g>
                                    <g id="Group_118" data-name="Group 118" transform="translate(1.922 24.822)">
                                    <rect id="Rectangle_78" data-name="Rectangle 78" width="6.776" height="1.301" fill="#575756"/>
                                    </g>
                                    <g id="Group_119" data-name="Group 119" transform="translate(7.575 29.158)">
                                    <rect id="Rectangle_79" data-name="Rectangle 79" width="4.716" height="1.301" fill="#575756"/>
                                    </g>
                                    <g id="Group_120" data-name="Group 120" transform="translate(0 32.522)">
                                    <rect id="Rectangle_80" data-name="Rectangle 80" width="8.701" height="1.301" fill="#575756"/>
                                    </g>
                                </g>
                                </g>
                          </svg>
                         <span id="demo"></span> Remaining
                        </p>
                    </div>
                </div>

                <div class="row mx-0">
                    <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
                        <template v-for="(question, index) in paginated" >
                           <div :ref="'question'+question.id" :id="'question'+question.id" :key="index" class="card p-30 q-card"><!-- h-100 -->
                            <div class="q-number">
                                <div>
                                    <span v-text="'Q' + (index+indexStart+1) + '/' + (this.exam.questions.length) "></span>
                                    <span class="line"></span>
                                    <small v-if="question.answers_count == 1" v-text=" '(' + (question.mark) + ' Mark)'"></small>
                                    <small v-if="question.answers_count > 1" v-text=" '(' + (question.mark) + ' Marks)'"></small>
                                </div>

                            </div>
                            <h3 v-html="question.title"></h3>

                           <template v-if="page_type == 'exam'">
                                <label :for="answer.title + '_' + answer.id + '_' + answer.question_id"  v-if="question.answers_count == 1" v-for="answer in question.answers" class="custom-radio" > @{{ answer.title }}
                                    <input  type="radio" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="answers[answer.question_id] == answer.id ? true:false " @change="addAnswer(answer.question_id,answer.id)" :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id" >
                                    <span class="radio-mark"></span>
                                </label>

                               <label  v-if="question.answers_count > 1" v-for="answer in question.answers" class="custom-radio" :for="answer.title + '_' + answer.id + '_' + answer.question_id"  > @{{ answer.title }} dddd
                                   <input type="checkbox" :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="searchMultiAnswers(answer.question_id,answer.id) ? true:false " @change="addMultiAnswers(answer.question_id,answer.id)"  :id="answer.title + '_' + answer.id + '_' + answer.question_id">
                                   <span class="radio-mark check-mark"></span>
                               </label>

                           </template>
                        </div>
                        </template>

                        <div :class="{'last-question':save_status && page_type == 'exam' }" class="d-flex algin-items-center justify-content-between mt-4 buttons">

                            <div class="navigation">
                                {{-- <template v-if="prev_status"> --}}
                                <template>
                                    <button @click.prevent="prev()" class="prev" :disabled="!prev_status">
                                        <svg id="Group_92" data-name="Group 92" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                                        <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#8a8a8a"/>
                                      </svg>
                                    </button>
                                </template>

                                {{-- <template v-if="!save_status" > --}}
                                <template>
                                  <button @click.prevent="next()" class="next" :disabled="save_status">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                                        <defs>
                                          <linearGradient id="linear-gradient" x1="-1623.535" y1="17.172" x2="-1624.535" y2="17.172" gradientUnits="objectBoundingBox">
                                            <stop offset="0" stop-color="#8a8a8a"/>
                                            <stop offset="0.564" stop-color="#f7ba50"/>
                                            <stop offset="1" stop-color="#f7b243"/>
                                          </linearGradient>
                                        </defs>
                                        <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="url(#linear-gradient)"/>
                                      </svg>
                                </button>
                                </template>
                            </div>

                            <template v-if="save_status && page_type == 'exam' ">
                               <button class="form-control main-color" @click.prevent="save()">Submit</button>
                            </template>

                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4">
                        <div class="card quiz h-100 p-30">
                            <h2 class="mt-0">Quiz</h2>
                            <ol class="answers">
                                <template v-if="page_type == 'exam' "  v-for="(question, index) in exam.questions">
                                    <li @click="searchAndOpenQuestion(question.id)" :key="question.id" >
                                        <b v-text="index+1"></b>
                                        <div  class="icon empty" :style="{'background': answers[question.id]  ? '#83827d':''}"></div>
                                    </li>
                                </template>
                            </ol>
                        </div>
                    </div>
                </div>
@endsection


@section('script')
    <script>

        window.exam = {!! json_encode($exam??[]) !!}
            window.start_user_attepmt = {!! json_encode($start_user_attepmt??[]) !!}
            window.page_type = {!! json_encode($page_type??'exam') !!}
            window.without_timer = {!! json_encode($without_timer??false) !!}

        new Vue({
            'el' : '#main-vue-element',
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
                // exam methods
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
                    // if(confirm('Are you sure (save answers) !!!')){
                    //     this.nextSaveAnswers('save');
                    // }
                    Swal.fire({
                    title: 'Are you sure?',
                    text: "Once the answers are submitted, it cannot be undone",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.nextSaveAnswers('save');
                        }
                    })
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

                    if(Array.isArray(this.answers[question_id]) ){
                        let answer = this.answers[question_id].filter(function (answer,index) {
                            return( answer == answer_id )
                        })

                        if(answer.length > 0)
                            return true;
                    }else{
                        if(this.answers[question_id] == answer_id){
                            return true;
                        }
                    }


                    return false;
                },
                addMultiAnswers : function (question_id,answer_id) {
                    if(this.page_type != 'exam') {
                        return ;
                    }

                    if(event.target.checked){
                        if(this.answers[question_id] == undefined ){
                            this.answers[question_id] = [answer_id];
                        }else if(!Array.isArray(this.answers[question_id])){
                            this.answers[question_id] = [this.answers[question_id],answer_id];
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

                       if(this.answers[question_id] && !Array.isArray(this.answers[question_id])){
                           this.answers[question_id] = [];
                        }else{
                           this.answers[question_id].forEach(function (answer,index) {
                               if( answer == answer_id ){
                                   answer_index = index
                               }
                           })
                           if(answer_index >= 0){
                               this.answers[question_id].splice(answer_index,1);
                           }
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
