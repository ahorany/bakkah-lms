@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="questions">

    <div style="margin-bottom: 20px">
        <button type="button" @click="OpenModal('question',content.id)" class="btn btn-warning">
            {{__('admin.add_question')}}
        </button>
    </div>


    <h3>@{{content.title}}</h3>

	<div  class="card" v-for="(question,index) in content.questions">
		<div class="card-header" >
            <div  style="margin: 20px;">@{{question.title}}</div>
			<button type="button" @click="OpenModal('answer',question.id)" class="btn btn-primary btn-sm px-3" id="answer" ><i class="fa fa-file-powerpoint"></i> {{__('admin.answer')}}</button>
            <br>



            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>

                <tbody>
                <tr v-if="question.answers"  v-for="(entry, index) in question.answers"  >
                    <td>@{{entry.title}}</td>
                    <td>
                        <div class="BtnGroupRows" data-id="150">
                            <button @click="OpenEditModal(question.id,entry.id)"  class="btn btn-sm btn-primary" >
                                <i class="fa fa-pencil-alt"></i> Edit</button>

                            <button @click="deleteAnswer(question.id,entry.id)"  class="btn btn-sm btn-danger" >
                                <i class="fa fa-trash"></i> Delete</button>

                        </div>
                    </td>


                </tr>

                </tbody>
            </table>


			<br>
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
					<div class="form-group">
                        <div>
                            <label>Title </label>
                            <input type="text" v-model="title" name="title" class="form-control" placeholder="title">
                                <div v-show="'title' in errors">
                                  <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                               </div>
                        </div>


                        <div v-if="model_type != 'answer'">
                            <label>Mark </label>
                            <input type="number" v-model="mark" name="mark" class="form-control" placeholder="mark">
                            <div v-show="'mark' in errors">
                                <span style="color: red;font-size: 13px">@{{ errors.mark }}</span>
                            </div>
                        </div>

                        <div v-if="model_type == 'answer'">
                            <label>Correct  </label>
                            <input type="checkbox" v-model="correct" name="correct"  >
                            <div v-show="'check_correct' in errors">
                                <span style="color: red;font-size: 13px">@{{ errors.check_correct }}</span>
                            </div>
                        </div>

					</div>
				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-primary" @click="Clear()">
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button"  @click="Add()" class="btn btn-primary">
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
		el:'#questions',
		data:{
			title: '',
			mark: 0,
            content: window.content,
            model_type : 'question',
            save_type : 'add',
            content_id : '',
            question_id : '',
            correct : false,
            errors:{},
            base_url : window.location.origin,
		},
            created :function(){
		      console.log(this.content)
            },
		methods: {

           OpenModal : function(type,id){
               // clear
               this.title = '';
               this.mark = 0;
               this.save_type  = 'add';
               this.model_type = type;
               this.content_id = id;
               this.correct = false;
               this.errors = {};

               $('#ContentModal').modal('show')
            },

            OpenEditModal : function(question_id,answer_id){
                let self = this;

                this.model_type = 'answer';
                this.content_id = answer_id;
                this.question_id = question_id;
                this.save_type  = 'edit';
                this.errors = {};



                this.content.questions.forEach(function (question) {
                    if(question.id == question_id){
                        question.answers.forEach(function (answer) {
                            if(answer.id == answer_id) {
                                    self.title = answer.title;
                                    self.correct = answer.check_correct;
                            }
                        })
                    }
                    return true ;
                });


                $('#ContentModal').modal('show')
            },


            deleteAnswer : function (question_id,answer_id){
               let self = this;
               if(confirm("Are you sure ? ")){
                    this.content.questions.filter(function (question) {
                       if(question.id == question_id){
                           question.answers = question.answers.filter(function (answer) {
                               return  answer.id != answer_id;
                           })
                       }
                       return true ;
                   });


                   axios.get("{{route('training.delete_answer')}}",{
                       params : {
                           answer_id : answer_id
                       }
                   })
                       .then(response => {
                       })
                       .catch(e => {
                           console.log(e)
                       });
               }


            },

            Add : function(){
               if(this.model_type == 'question'){
                   this.AddQuestion();
               }else{
                   this.AddAnswer();
               }
            },
			AddAnswer: function(){
				let self = this;
				if(self.title == null){
                    self.errors = {'title' : 'The title field is required.'};
				    return ;
                }
                if(self.save_type == 'add'){
                    axios.post("{{route('training.add_answer')}}",
                        {
                            'title': self.title,
                            'question_id': self.content_id,
                            'check_correct': self.correct,
                        }
                        )
                        .then(response => {
                            console.log(response)

                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                this.content.questions.forEach(function (question) {
                                    if(question.id == response.data.data.question_id){
                                        if( question.answers === undefined ){
                                            question.answers = [];
                                        }
                                        question.answers.push(response.data.data);
                                    }
                                });

                                this.title 		= '';
                                this.content_id = '';
                                self.errors = {};

                                $('#ContentModal').modal('hide')
                            }





                        })
                        .catch(e => {
                            console.log(e)
                        });
                }else{

                    this.content.questions.forEach(function (question) {
                        if(question.id == self.question_id){
                            question.answers.forEach(function (answer) {
                                if(answer.id == self.content_id) {
                                    answer.title = self.title;
                                    answer.check_correct = self.correct ;
                                }
                            })
                        }
                        return true ;
                    });


                    axios.post("{{route('training.update_answer')}}",
                        {
                            'title': self.title,
                            'answer_id': self.content_id,
                            'check_correct': self.correct,
                        }
                        )
                        .then(response => {
                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                this.title 		= '';
                                this.content_id = '';
                                this.question_id = '';
                                self.errors = {};

                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                }

			},
            AddQuestion: function(){
                let self = this;
                if(self.title == null){
                    self.errors = {'title' : 'The title field is required.'};
                    return ;
                }
                if(self.save_type == 'add'){
                    axios.post("{{route('training.add_question')}}",
                        {
                            'title': self.title,
                            'mark': self.mark,
                            'exam_id': self.content_id,
                        }
                    )
                        .then(response => {
                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                this.content.questions.push(response.data.data);
                                this.title 		= '';
                                this.mark 		= 0;
                                this.content_id = '';
                                this.type = '';
                                self.errors = {};

                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                }

                {{--else{--}}
                {{--    axios.post("{{route('training.update_content')}}",--}}
                {{--        formData--}}
                {{--        ,config)--}}
                {{--        .then(response => {--}}
                {{--            if(this.file != '' ){--}}
                {{--                this.$refs.inputFile.type='text';--}}
                {{--                this.$refs.inputFile.type='file';--}}
                {{--            }--}}

                {{--            this.title 		= '';--}}
                {{--            this.excerpt 	= '';--}}
                {{--            this.file 	= '';--}}
                {{--            this.url 	= '';--}}
                {{--            this.progress 	= '0%';--}}
                {{--            this.contents = response.data;--}}

                {{--            $('#ContentModal').modal('hide')--}}
                {{--        })--}}
                {{--        .catch(e => {--}}
                {{--            console.log(e)--}}
                {{--        });--}}
                {{--}--}}

            },


			Clear:function(){
				this.title 		= '';
			},
		},
	});
</script>
@endpush
