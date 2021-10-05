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
                <div class="main-user-content m-4" id="exam">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> @{{ exam.title }}</h4>
                            <div class="row">
                                <div class="col-12 "></div>
                                <div class="col-12">
{{--                                        <div v-for="question in exam.questions" class="card pt-3 pl-3" >--}}
                                        <div class="card pt-3 pl-3" >
                                            <h5 class="card-title">@{{question.title}}</h5>

                                            <div class="card-body">
                                                     <label v-for="answer in question.answers" :for="answer.title + answer.id"  class="card-text d-block"><input :checked="true" :id="answer.title + answer.id" :name="answer.question_id"  type="radio" > @{{answer.title}}</label>
                                            </div>
                                        </div>

                                </div>

                            </div>
                        <div class="clearfix">
                            <button v-if="save_status" @click="save()" class="btn btn-primary float-left" >Save</button>
                            <button v-else @click="next()" class="btn btn-primary float-left" >Next</button>
                            <button v-if="prev_status" @click="prev()" class="btn btn-primary float-right" >Prev</button>
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
            question_id: '',
            question: '',
            save_status: false,
            prev_status: false,
        },
        created(){
          // console.log(this.exam.exam.pagination)
          console.log(this.exam)
            this.question  = this.exam.questions[0];
            this.question_id  = this.question.id;
        },
        methods : {
            save : function(){
               alert('save !!!')
            },
            next : function () {
                let self = this;
                self.prev_status = true;
                let question_id = self.question_id;
                let indexVal = 0;
                this.exam.questions.forEach(function (question,index) {
                    if( question.id == self.question_id ){
                        if( (index+1) >=  self.exam.questions.length){
                            // self.save_status = true;
                        }else{
                            self.question  =  self.exam.questions[index+1];
                            question_id  = self.question.id;
                            indexVal = index+1;
                        }
                    }

                })
                self.question_id = question_id;

                if( indexVal ==  (self.exam.questions.length - 1) ){
                    self.save_status = true;
                }

            },
            prev : function () {
                let self = this;
                self.save_status = false
                let question_id = self.question_id;
                let indexVal = 0;
                this.exam.questions.forEach(function (question,index) {
                    if( question.id == self.question_id ){
                        if( (index-1) >= 0){
                            self.question  =  self.exam.questions[index-1];
                            question_id  = self.question.id;
                            indexVal  = index-1;
                        }
                    }

                })
                self.question_id = question_id;


                if( indexVal ==  0){
                    self.prev_status = false;
                }
            },




            paginateQuestions : function () {
                let pagination = this.exam.exam.pagination
                console.log(this.exam.exam.pagination)
            }
        }
    })
</script>



@endsection
