@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="questions">

    <div class="course_info">
        <label class="m-0">@{{content.title}}</label>
        <button type="button" @click="OpenModal('question',content.id)" class="btn btn-outline-dark">
            <i class="fa fa-plus"></i>  {{__('admin.add_question')}}
        </button>
    </div>

	<div  class="card" v-for="(question,index) in content.questions">
		<div class="card-header p-0" >
            <div class="clearfix mb-1 p-3 m-0">
                <h3 class="BtnGroupRows float-left" style="font-size: 20px;">@{{question.title}}</h3>

                <div class="BtnGroupRows float-right" data-id="150">
                    <button type="button" @click="OpenModal('answer',question.id)" class="btn btn-outline-info btn-sm px-3" id="answer" ><i class="fa fa-file-powerpoint"></i> {{__('admin.add_answer')}}</button>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col" class="text-center">TrueOrFalse</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
                </thead>

                <tbody>
                <tr v-if="question.answers"  v-for="(entry, index) in question.answers"  >
                    <td>@{{entry.title}}</td>
                    <td class="text-center" v-if="entry.check_correct == 1" v-html="`<i class='fas fa-check' style='color:#00d700;'></i>`"></td>
                    <td class="text-center" v-else v-html="`<i class='fas fa-times' style='color:#f02117;'></i>`"></td>
                    <td class="text-center">
                        <div class="BtnGroupRows" data-id="150">
                            <button @click="OpenEditModal(question.id,entry.id)" class="btn btn-sm btn-outline-warning" >
                                <i class="fa fa-pencil-alt"></i><!-- Edit --> </button>

                            <button @click="deleteAnswer(question.id,entry.id)" class="btn btn-sm btn-outline-danger" >
                                <i class="fa fa-trash"></i><!-- Delete --> </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
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
                            <input type="text" v-model="title" name="title" class="form-control" placeholder="title">
                                <div v-show="'title' in errors">
                                  <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                               </div>
                        </div>


                        <div class="form-group" v-if="model_type != 'answer'">
                            <label>Mark </label>
                            <input type="number" v-model="mark" name="mark" class="form-control" placeholder="mark">
                            <div v-show="'mark' in errors">
                                <span style="color: red;font-size: 13px">@{{ errors.mark }}</span>
                            </div>
                        </div>

                        <div class="boxes mt-4" v-if="model_type == 'answer'">
                            <input type="checkbox" v-model="correct" name="correct" id="correct" >
                            <label for="correct">Correct </label>
                            <div v-show="'check_correct' in errors">
                                <span style="color: red;font-size: 13px">@{{ errors.check_correct }}</span>
                            </div>
                        </div>

					{{-- </div> --}}
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
