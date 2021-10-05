@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

    <div id="timecountdown"></div>

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div id="demo"></div>
                <div class="main-user-content m-4" id="exam">
                    <div class="card p-5 user-info" >
                        <h4 class="mb-4" ><i class="fas fa-graduation-cap"></i> <span >{{$exam->title}}</span></h4>
                            <div class="row">
                                <div class="col-12 "></div>
                                <div class="col-12">
                                    <template v-for="(question, index) in paginated">
                                        <div  :key="index" class="card pt-3 pl-3 mb-3" >
                                            <h6 class="text-danger" v-text="`${question.mark} mark(s)`"></h6>
                                            <h5 class="card-title" v-text="question.title"></h5>
                                            <div class="card-body">
                                                <template v-for="answer in question.answers">
                                                    <label  :for="answer.title + '_' + answer.id + '_' + answer.question_id"  class="card-text d-block" ><input :key="answer.title + '_' + answer.id + '_' + answer.question_id" :checked="answers[answer.question_id] == answer.id ? true:false " @change="addAnswer(answer.question_id,answer.id)" :name="answer.question_id" :id="answer.title + '_' + answer.id + '_' + answer.question_id"  type="radio" > <span v-text="answer.title"></span></label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>


                                </div>

                            </div>
                        <div class="clearfix mt-3">
                            <template  v-if="save_status">
                                <button  @click="save()" class="btn btn-primary float-left" >Save</button>
                            </template>
                            <template v-else>
                                <button @click="next()" class="btn btn-primary float-left" >Next</button>
                            </template>

                            <template  v-if="prev_status">
                                <button   @click="prev()" class="btn btn-primary float-right" >Prev</button>
                            </template>
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
            this.exam.exam.users_exams[this.exam.exam.users_exams.length-1].user_answers.forEach(function (value) {
                self.answers[value.question_id] = value.id;
            })

          console.log(this.exam)
        },
        methods : {
            save : function(){
               if(confirm('Are you sure (save answers) !!!')){
                   this.nextSaveAnswers('save');
               }
        },
            prev() {
                this.save_status = false;
                this.indexStart -= this.pageSize;
                --this.current;

                if((this.current) == 1){
                    this.prev_status = false;
                }
            },
            next() {
                this.prev_status = true;
                this.indexStart += this.pageSize;
                ++this.current;
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



    // Set the date we're counting down to
    // var countDownDate = new Date("Aug 31, 2019 22:55:00").getTime();

    if (localStorage.getItem("start_time_exam") === null) {
        var now = new Date().getTime();
        localStorage.setItem('start_time_exam', now);
    }

    countdownTimeStart()


    function countdownTimeStart(){

        let t = new Date()
        t.setSeconds({{$exam->exam->duration * 60}})
        var countDownDate = t.getTime();

// Update the count down every 1 second

        var x = setInterval(function() {

            // Get todays date and time
            // var now = new Date().getTime();
            var now = localStorage.getItem('start_time_exam');
            // var now2 = new Date().getTime();


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
                // alert("EXPIRED")

                document.getElementById("demo").innerHTML = "EXPIRED";
            }

            // var now = new Date().getTime();
            localStorage.setItem('start_time_exam', parseInt(now) + 1000);
        }, 1000);
    }

</script>



@endsection
