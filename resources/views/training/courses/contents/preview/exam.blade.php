@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Exam Questions')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .incorrect-radio + .radio-mark:after {
            border: solid #f00 ;
            border-width: 0 3px 3px 0;
        }
    </style>
@endsection

@section('table')
    <style>
        .ql-container.ql-snow{
            height: 200px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">


    <div class="row mx-0" id="questions">

        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap my-3">
                <div class="d-flex align-items-center flex-wrap">
                    <h4 style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course : {{$content->course->trans_title}}</h4>
                    <h4 style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Exam : {{$content->title}}</h4>
                </div>
                @if(isset($next))
                    <div>
                        <?php
                        $NextPrevNavigation = \App\Helpers\CourseContentHelper::NextPrevNavigation($next, $previous);
                        $next_url = $NextPrevNavigation['next_url'];
                        $previous_url = $NextPrevNavigation['previous_url'];
                        ?>
                        @include('Html.next-prev-navigation', [
                            'next'=>$next,
                            'previous'=>$previous,
                            'previous_url'=>$previous_url,
                        ])
                        <script>
                            function NextBtn(){
                                document.querySelector(".next").addEventListener("click", function(event){
                                    window.location.href = '{{$next_url??null}}'
                                });
                            }
                            NextBtn();
                        </script>
                    </div>
                @endif
            </div>
        </div>
        @if(isset($next))
            <div class="col-md-12 col-12">
                <div  class="course_info mb-3 card p-3">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12">
                            <button type="button" @click="OpenModal('question')" class="btn-sm group_buttons mb-1" style="width: max-content;">
                                <i class="fa fa-plus"></i> Add multiple choice question
                            </button>

                            <button type="button" @click="OpenModal('fill_gap')" class="btn-sm group_buttons mb-1" style="width: max-content;">
                                <i class="fa fa-plus"></i> Add fill gap question
                            </button>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12">
                            @include('training.courses.contents.header',['course_id' => $course_id, 'contents' =>true , 'units' => false])
                        </div>

                    </div>
                </div>
            </div>


        <div class="col-md-12 col-12">
            <div class="card p-3">

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                    {{ implode('', $errors->all(':message')) }}
                    </div>
                @endif

                <form action="{{ route('training.importQuestions') }}" method="POST" enctype="multipart/form-data" class="row mx-0">

                    @csrf
                    <div class="col-md-6">
                    {!! Builder::Select('import_type', 'import_type', $import_types, null, ['col'=>'import_type', 'model_title'=>'trans_name']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="add-question-file">
                            {!!Builder::File('file', 'file', null, [])!!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="import-question-file">
                            {!! Builder::Submit('import', 'import_questions', 'green', null, [
                                'icon'=>'far fa-file-excel',
                            ]) !!}

                            {!! Builder::Submit('sample', 'sample', 'green', null, [
                                'icon'=>'far fa-file-excel',
                            ]) !!}
                            <input type="hidden" name="content_id" value="{{$content->id}}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
                <template v-for="(question,index) in content.questions">

                    <div :key="index" :id="'question_' + index" class="card p-30 q-card preview-exam">

                        <div class="action_preview">
                            {{-- <span class="setting"><i class="fa fa-cog" aria-hidden="true"></i></span> --}}
                            <button type="button" @click="OpenEditModal(question.id)" class="yellow" id="answer" >
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button @click="deleteQuestion(question.id)" class="red" >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <button v-if="question.shuffle_answers==1" class="green" title="Shuffled Answers">
                                <i class="fa fa-random" aria-hidden="true"></i>
                            </button>
                            {{-- <span class="left d-none"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> --}}
                        </div>

                        <div class="q-number">
                            <div>
                                <span>Q@{{index+1}}/@{{content.questions.length}}</span>
                                <span class="line"></span>
                                <small>(@{{question.mark}} Marks)</small>
                            </div>
                        </div>
                        <h3 style="padding-right: 15%;" v-html="question.title"></h3>

                            <template v-for="(answer,key) in question.answers">
                                <label class="custom-radio"> @{{answer.title}}
                                    <input type="checkbox" disabled="true" :checked="answer.check_correct == 1" >
                                    <span class="radio-mark"></span>
                                </label>
                            </template>


                        <div class="correct_feedback">
                            @{{ question.mark??0 }} Marks
                        </div>
                        <template v-if="question.feedback">
                            <div class="correct_feedback">
                                <div>
                                    <h6 class="m-0"><strong>Feedback :</strong> </h6>
                                    <div>@{{  question.feedback }}</div>
                                </div>
                            </div>
                        </template>

                    </div>
                </template>

        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card h-100 p-30 navigation_preview">
                <h2>Quiz Navigation</h2>
                <ol class="answers quiz">
                    @foreach($content->questions as $question)
                        <li>
                            <a href="#question_{{$loop->iteration}}">
                                <b style="color: #000;">{{$loop->iteration}}</b>
                                <div class="icon correct">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20.248" height="15.247" viewBox="0 0 20.248 15.247">
                                        <path id="Path_121" data-name="Path 121" d="M252.452,339.764l-11,11-6.414-6.414" transform="translate(-233.618 -338.35)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="4"/>
                                    </svg>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ol>
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

                            <div class="modal-diff-content form-group">
                                <label class="container-check" style="padding: 25px 30px 0; font-size: 15px;">
                                    Shuffle Answers
                                    <input class="mx-3" style="display: inline-block;" id="shuffle" type="checkbox" v-model="shuffle_answers" name="shuffle_answers">
                                    <span class="checkmark" style="top: 26px;"></span>
                                    <div v-show="'shuffle_answers' in errors">
                                        <span style="color: red;font-size: 13px">@{{ errors.shuffle_answers }}</span>
                                    </div>
                                </label>
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

                            <div v-if="model_type != 'fill_gap'" class="mt-4">
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
                                    <button  @click="deleteAnswer(question_id,answer.id,index)" class="red my-1" style="vertical-align: bottom;"><i class="fa fa-trash" aria-hidden="true"></i>
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
                    shuffle_answers: 1,
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
                        this.shuffle_answers = 1;
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
                                self.shuffle_answers = question.shuffle_answers;
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
                                'shuffle_answers': self.shuffle_answers,
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
                                    this.shuffle_answers = 1;
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
                        this.shuffle_answers = 1;
                    },
                }
            });
    </script>
    {{-- <script>
        $(document).ready(function(){
            $('.action_preview span.setting').click(function(){
                $('.action_preview button').show();
                $('.action_preview span.left').removeClass('d-none');
                $('.action_preview span.setting').hide();
            });

            $('.action_preview span.left').click(function(){
                $('.action_preview span.setting').show();
                $('.action_preview button').hide();
                $('.action_preview span.left').addClass('d-none');
            });
        });
    </script> --}}

@endsection


