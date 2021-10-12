@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="questions">

    <div class="course_info">
        <label class="m-0">@{{content.title}}</label>
        <button type="button" @click="OpenModal('question')" class="btn btn-outline-dark">
            <i class="fa fa-plus"></i>  {{__('admin.add_question')}}
        </button>
    </div>

	<div  class="card" v-for="(question,index) in content.questions">
		<div class="card-header p-0" >
            <div class="clearfix mb-1 p-3 m-0">
                <div class="text-success">
                    <span>( @{{question.mark}} marks )</span>
                    <span>( @{{question.answers.length}} answers )</span>
                </div>
                <h3 class="BtnGroupRows float-left" style="font-size: 20px;">@{{question.title}}</h3>



                <div class="BtnGroupRows float-right" data-id="150">
                    <button @click="deleteQuestion(question.id)" class="btn btn-sm btn-outline-danger" >
                        <i class="fa fa-trash"></i><!-- Delete --> </button>
                    <button type="button" @click="OpenEditModal(question.id)" class="btn btn-outline-info btn-sm px-3" id="answer" ><i class="fa fa-pencil-alt"></i></button>
                </div>

            </div>


		</div>
	</div>

	<div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">@{{ model_type }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="col-md-12">
					{{-- <div class="form-group"> --}}
                        <div class="form-group">
                            <label>Title </label>
                            <textarea  v-model="title" name="title" class="form-control w-100" placeholder="title"></textarea>
{{--                            <input type="text" v-model="title" name="title" class="form-control" placeholder="title">--}}
                                <div v-show="'title' in errors">
                                  <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                               </div>
                        </div>


                        <div class="form-group">
                            <label>Mark </label>
                            <input type="number" v-model="mark" name="mark" class="form-control" placeholder="mark">
                            <div v-show="'mark' in errors">
                                <span style="color: red;font-size: 13px">@{{ errors.mark }}</span>
                            </div>
                        </div>

                       <div class="mt-5">
                           <div class="mb-2" v-show="'answers' in errors">
                               <span style="color: red;font-size: 13px">@{{ errors.answers }}</span>
                           </div>
                           <button @click.prevent="addAnswerBox()" class="btn btn-primary mb-3">Add Answers +</button>

                           <div v-for="(answer,index) in answers" class="form-group">
                                   <input class="mx-3" type="checkbox" v-model="answer.check_correct"  :checked="answer.check_correct" style="display: inline-block" >
                                   <input class="w-75" type="text" v-model="answer.title" name="title"  placeholder="title">
                               <button  @click="deleteAnswer(question_id,answer.id,index)" class="btn btn-sm btn-outline-danger mx-3" >
                                       <i class="fa fa-trash"></i><!-- Delete --> </button>
                                 </div>
                               </div>

				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-outline-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-outline-info" @click="Clear()">
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button"  @click="Add()" class="btn btn-outline-success">
					<i class="fa fa-save"></i> {{__('admin.save')}}</button>

			</div>
		</div>
	</div>
</div>


</div>



@endsection

@push('vue')
<script>
	window.content = {!! json_encode($content??[]) !!}

	var contents = new Vue({
            el: '#questions',
            data: {
                title: '',
                mark: 0,
                content: window.content,
                model_type: 'question',
                save_type: 'add',
                // content_id : '',
                question_id: '',
                correct: false,
                errors: {},
                answers: [],
                base_url: window.location.origin,
            },
            created: function () {
                console.log(this.content)
            },
            methods: {

                OpenModal: function (type) {
                    // clear
                    this.title = '';
                    this.mark = 0;
                    this.answers = [];
                    this.question_id = null;
                    this.save_type = 'add';
                    this.model_type = type;
                    // this.content_id = id;
                    this.correct = false;
                    this.errors = {};

                    $('#ContentModal').modal('show')
                },

                OpenEditModal: function (question_id) {
                    let self = this;
                    this.question_id = question_id;
                    this.save_type = 'edit';
                    this.errors = {};


                    this.content.questions.forEach(function (question) {
                        if (question.id == question_id) {
                            self.answers = question.answers
                            self.title = question.title;
                            self.mark = question.mark;
                        }
                        return true;
                    });


                    $('#ContentModal').modal('show')
                },


                addAnswerBox: function () {
                    this.answers.push({'id': null, 'title': '', 'check_correct': false});
                },

                deleteAnswer: function (question_id, answer_id,index) {
                    let self = this;
                    if (confirm("Are you sure ? ")) {
                        if(answer_id == null){
                            this.answers.splice(index, 1);
                        }else{
                            this.content.questions.filter(function (question) {
                                if (question.id == question_id) {
                                    question.answers = question.answers.filter(function (answer) {
                                        return answer.id != answer_id;
                                    })

                                    self.answers = question.answers;
                                }
                                return true;
                            });


                            axios.get("{{route('training.delete_answer')}}", {
                                params: {
                                    answer_id: answer_id
                                }
                            })
                                .then(response => {
                                })
                                .catch(e => {
                                    console.log(e)
                                });
                        }


                    }


                },

                deleteQuestion: function (question_id) {
                    let self = this;
                    if (confirm("Are you sure ? ")) {
                        this.content.questions =  this.content.questions.filter(function (question) {
                            return (question.id != question_id)
                        });


                        axios.get("{{route('training.delete_question')}}", {
                            params: {
                                question_id : question_id
                            }
                        })
                            .then(response => {
                            })
                            .catch(e => {
                                console.log(e)
                            });
                    }


                },



                Add: function () {
                    this.AddQuestion();
                },

                AddQuestion: function () {
                    let self = this;
                    if (self.title == null) {
                        self.errors = {'title': 'The title field is required.'};
                        return;
                    }


                    axios.post("{{route('training.add_question')}}",
                        {
                            'title': self.title,
                            'mark': self.mark,
                            'exam_id': self.content.id,
                            'answers': self.answers,
                            'question_id': self.question_id,
                            'save_type': self.save_type,
                        }
                    )
                        .then(response => {
                            console.log(response.data)
                            if (response['data']['errors']) {
                                self.errors = response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                if(self.save_type == 'add'){
                                    this.content.questions.push(response.data.data);
                                    // console.log(this.content.questions)

                                }else{
                                    this.content.questions.forEach(function (question,index) {
                                         if(question.id == self.question_id ){
                                             self.content.questions[index] = response.data.data;
                                         }
                                    })
                                    // console.log(this.content.questions)
                                }
                                this.title = '';
                                this.mark = 0;
                                this.content_id = '';
                                this.type = '';
                                self.errors = {};

                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            alert('ff')
                            console.log(e)
                        });
                },

            Clear: function () {
                this.title = '';
                this.answers.forEach(function (answer) {
                    answer.title = ''
                });
                this.mark = 0;
            },
        }


	});
</script>
@endpush
