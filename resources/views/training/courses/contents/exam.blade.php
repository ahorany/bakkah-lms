@extends('layouts.crm.index')

@section('table')
    <style>
        .ql-container.ql-snow{
            height: 200px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">


    <div class="toLoad" id="questions">

    <div class="course_info card p-3 mb-3">
        {{-- <label class="m-0">@{{content.title}}</label> --}}
        <button type="button" @click="OpenModal('question')" class="btn-sm group_buttons" style="width: max-content;">
            <i class="fa fa-plus"></i> {{__('admin.add_question')}}
        </button>
    </div>

        <div class="card p-3 mb-3">
            <form action="{{ route('training.importQuestions') }}" method="POST" enctype="multipart/form-data" class="row mx-0">
                @csrf
                    <div class="col-md-6 px-0">
                        <div class="add-question-file">
                            {!!Builder::File('file', 'file', null, [])!!}
                        </div>
                    </div>
                    <div class="col-md-6 px-0">
                        <div class="import-question-file">
                            {!!Builder::Submit('importQuestions', 'import_questions', 'green', null, [
                                'icon'=>'far fa-file-excel',
                            ])!!}
                            <a href="{{CustomAsset('samples/examQuestionsAnswers.xlsx')}}" download class="cyan" role="button"> Sample </a>
                            <input type="hidden" name="content_id" value="{{$content->id}}">
                        </div>
                    </div>
            </form>
            <hr>
            <form action="{{ route('training.importResults') }}" method="POST" enctype="multipart/form-data" class="row mx-0">
                @csrf
                    <div class="col-md-6 px-0">
                        <div class="add-question-file">
                            {!!Builder::File('file', 'file', null, [])!!}
                        </div>
                    </div>
                    <div class="col-md-6 px-0">
                        <div class="import-question-file">
                            {!! Builder::Submit('importResults', 'importResults', 'green', null, [
                                'icon'=>'far fa-file-excel',
                            ]) !!}
                            <a href="{{CustomAsset('samples/learnerAnswers.xlsx')}}" download class="cyan" role="button"> Sample </a>
                            <input type="hidden" name="content_id" value="{{$content->id}}">
                        </div>
                    </div>

            </form>
        </div>

        <template v-if="questions">
            <div  class="card mb-1" v-for="(question,index) in content.questions">
                <div class="card-body p-0" >
                    <div class="clearfix mb-1 p-3 m-0">
                        <div class="text-success float-left">
                            <span>( @{{question.mark}} marks )</span>
                            <span>( @{{question.answers.length}} answers )</span>
                        </div>
                        <div class="BtnGroupRows float-right mb-3" data-id="150">
                            <button type="button" @click="OpenEditModal(question.id)" class="yellow" id="answer" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                            <button @click="deleteQuestion(question.id)" class="red" ><i class="fa fa-trash" aria-hidden="true"></i> Delete<!-- Delete --> </button>
                        </div>
                        <div style="clear: both;"></div>
                        <h3 style="font-size: 20px"  v-html="question.title"></h3>
                    </div>
                </div>
            </div>

        </template>

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
                            <editor v-model="title" theme="snow" :options="options" :placeholder="'title'"></editor>

{{--                            <textarea  v-model="title" name="title" class="form-control w-100" placeholder="title"></textarea>--}}
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

                    <div class="form-group">
                        <label>Unit </label>
                        <select multiple v-model="units_select" class="form-control">
                            <option value="-1">Choose unit</option>
                            <option v-for="unit in compo_units" :value="unit.id" v-text="unit.title"></option>
                        </select>
                       {{-- <div v-show="'mark' in errors">
                           <span style="color: red;font-size: 13px">@{{ errors.mark }}</span>
                       </div> --}}
                    </div>

                       <div class="mt-5">
                           <div class="mb-2" v-show="'answers' in errors">
                               <span style="color: red;font-size: 13px">@{{ errors.answers }}</span>
                           </div>
                           <button @click.prevent="addAnswerBox()" class="main-color mb-3"><i class="fa fa-plus"></i> Add Answers</button>

                           <div v-for="(answer,index) in answers" class="form-group">
                            <label class="container-check" style="display: inline-block;">
                                <input class="mx-3" type="checkbox" v-model="answer.check_correct"  :checked="answer.check_correct" style="display: inline-block" >
                                <span class="checkmark"></span>
                              </label>

                                   <input class="w-75 form-control" type="text" v-model="answer.title" name="title"  placeholder="title" style="display: inline-block;">
                               <button  @click="deleteAnswer(question_id,answer.id,index)" class="red mx-3" ><i class="fa fa-trash" aria-hidden="true"></i>
                                 Delete<!-- Delete --> </button>
                                 </div>
                               </div>

                    <div class="form-group">
                        <label>Feedback </label>
                        <textarea  v-model="feedback" name="feedback" class="form-control w-100" placeholder="feedback"></textarea>
                        {{--                            <input type="text" v-model="title" name="title" class="form-control" placeholder="title">--}}
                        <div v-show="'feedback' in errors">
                            <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                        </div>
                    </div>


				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="red" data-dismiss="modal">{{__('admin.close')}}</button>
				<button type="reset" class="cyan" @click="Clear()">{{__('admin.clear')}}</button>
				<button type="button"  @click="save()" class="green">{{__('admin.save')}}</button>

			</div>
		</div>
	</div>
</div>

</div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>

    <script>
	window.content = {!! json_encode($content??[]) !!}
    window.units = {!! json_encode($units??[]) !!}

	var contents = new Vue({
            el:'#main-vue-element',
            data: {
                title: '',
                feedback: '',
                mark: 0,
                unit_id: -1,
                units_select : [],
                content: window.content,
                units: window.units,
                model_type: 'question',
                save_type: 'add',
                // content_id : '',
                question_id: '',
                correct: false,
                errors: {},
                answers: [],
                compo_units : [],
                compo_title : '',
                base_url: window.location.origin,
                options: {
                    modules: {
                        'toolbar': [
                            [{ 'size': [] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'script': 'super' }, { 'script': 'sub' }],
                            [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }, { 'align': [] }],
                            ['link', 'image', 'video', 'formula'],
                            ['clean']
                        ],
                    },
                },
            },
            created: function () {
                console.log(this.getLeafNodes(this.units));
                console.log(this.compo_units)
                console.log(this.content)
            },
            methods: {
                image : function(data){
                    if(data.search("img")){
                        return (data + '"/></p>')
                    }
                    return data;
                },
                getLeafNodes :  function (nodes, result = []){
                    for(var i = 0, length = nodes.length; i < length; i++){
                        // this.compo_title == '' ?   this.compo_title += nodes[i].title : this.compo_title += ' > ' + nodes[i].title ;
                        if(!nodes[i].s || nodes[i].s.length === 0){
                            this.compo_units.push( {id : nodes[i].id , title :  nodes[i].title} );
                            // this.compo_title = '';
                            result.push(nodes[i]);
                        }else{
                            result = this.getLeafNodes(nodes[i].s, result);
                        }
                    }
                return result;
            },
                OpenModal: function (type) {
                    // clear
                    this.title = '';
                    this.feedback = '';
                    this.units_select = [];
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
                            self.feedback = question.feedback;
                            self.mark = question.mark;
                            self.units_select = [];
                            if(question.units){
                                question.units.forEach(function (unit) {
                                    self.units_select.push(unit.id)
                                });
                            }

                            // self.unit_id = question.unit_id ? question.unit_id : -1;
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



                save: function () {
                    let self = this;
                    if (self.title == null) {
                        self.errors = {'title': 'The title field is required.'};
                        return;
                    }


                    axios.post("{{route('training.add_question')}}",
                        {
                            'title': self.title,
                            'mark': self.mark,
                            'feedback': self.feedback,
                            'units_select': self.units_select,
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
                                this.feedback = '';
                                this.mark = 0;
                                this.content_id = '';
                                this.type = '';
                                self.errors = {};
                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                    },

            Clear: function () {
                this.title = '';
                this.unit_id = -1
                this.answers.forEach(function (answer) {
                    answer.title = ''
                });
                this.mark = 0;
            },
        }


	});


</script>
@endsection

