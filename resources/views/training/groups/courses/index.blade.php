@extends('layouts.crm.index')

@section('useHead')
    <title>{{$group->name}} {{ __('education.courses') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
<style>
    .course_info button {
        padding: .375rem .75rem !important;
    }

    .ql-container.ql-snow{
        height: 200px;
    }

    input.form-control {
        color: gray;
        font-size: 14px;
        width: 75%;
    }
</style>
@endsection

@section('table')

    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">


        <div class="course_info">
        <div class="card p-3 mb-3">
            <div class="row">
                <div class="col-md-9 col-9">

                    <span style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Group Name : {{$group->name}}</span>

                    <button type="button" @click="OpenModal()" style="padding: 2px 8px !important;" class="group_buttons mb-1 btn-sm">
                        <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.course')}}
                    </button>
                    <a class="group_buttons mb-1 btn-sm" href="{{route('training.group_users',['group_id' => $group->id])}}">Users</a>
                </div>


                <div class="col-md-3 col-3 text-right">
                    <div class="back">
                        <a href="{{route('training.groups.index')}}" class="cyan mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    </div>
                </div>



            </div>
        </div>
          <template v-if="group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(course,index) in group.courses">
                        <th scope="row" v-text="index + 1"></th>
                        <td v-text="trans_title(course.title)"></td>
                        <td>
                            <button @click="deleteCourse(course.id)" class="red" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>

                </tbody>
            </table>


              <div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>



                <div class="container mt-2">
                    <div class="form-group">
                        <label>Name: </label>
                        <input name="coursename" placeholder="Name.." class="form-control d-inline-block" v-model="search_coursename"/>
                    </div>

                    <div style="margin-left:40px;margin-top: 5px;">
                        <button @click.prevent="search()" type="submit" name="search" class="main-color"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>


                <div class="modal-body" style="overflow: auto;height: 150px;">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Choose</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(search_course,index) in search_courses">
                            <td v-text="trans_title(search_course.title)"></td>
                            <td>
                                <div class="form-group"><input @change="addCourse(search_course.id,$event)" :checked="isCheckedCourse(search_course.id)" type="checkbox" class="mx-3" style="display: inline-block;"></div>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="red" data-dismiss="modal">{{__('admin.close')}}</button>
                    <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                    <button type="button"  class="green" @click="save()">{{__('admin.save')}}</button>
                </div>
            </div>
        </div>
           </template>

    </div>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>

<script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
<script>
        window.lang = '{!!app()->getLocale()!!}'
        window.group = {!! json_encode($group??[]) !!}
	var contents = new Vue({
        el:'#main-vue-element',
        data : {
            group : window.group,
            lang : window.lang,
            expire_date : '' ,
            search_coursename : '' ,
            search_courses    : [] ,
            add_courses : {},
        },
        created(){
		    let self = this
            console.log(self.group)
        },
        methods : {
                OpenModal : function(){
                    $('#ContentModal').modal('show')
                },
                search: function () {
                    let self = this;
                    axios.post("{{route('training.search_course_group')}}",
                        {
                            'name' : self.search_coursename ,
                        }
                        )
                        .then(response => {
                            console.log(response)
                             self.search_courses = response.data.courses;
                                // $('#ContentModal').modal('hide')

                        })
                        .catch(e => {
                            console.log(e)
                        });
                },
                isCheckedCourse : function (course_id) {
                    let self = this;
                     let course = self.group.courses.filter(function (course,index) {
                          return course.id == course_id;
                     })
                       if(course && course.length == 0){
                           return false;
                       }
                       return true;
                },
                addCourse : function (course_id,event) {
                    if (event.target.checked) {
                        this.add_courses[course_id] = true;
                    }else{
                        this.add_courses[course_id] = false;
                    }
                },
                save : function(){
                    let self = this;
                    axios.post("{{route('training.add_courses_group')}}",
                        {
                            'courses' : self.add_courses ,
                            'group_id' : self.group.id ,
                        }
                    )
                        .then(response => {
                            console.log(response.data)
                            if(response.data.status == 'success'){
                                self.add_courses = {};
                                self.group = response.data.group;
                                $('#ContentModal').modal('hide')
                            }


                        })
                        .catch(e => {
                            console.log(e)
                        });
                },
                deleteCourse : function (course_id) {
                    let self = this;
                    if(confirm('Are you sure ?')){

                        self.group.courses = self.group.courses.filter(function (course,index) {
                              return (course.id != course_id)
                        })

                        axios.post("{{route('training.delete_course_group')}}",
                            {
                                'group_id' : self.group.id ,
                                'course_id'   : course_id ,
                            }
                        )
                            .then(response => {
                                console.log(response)
                            })
                            .catch(e => {
                                console.log(e)
                            });
                    }

               },
                trans_title : function (data) {
                   return JSON.parse(data)[this.lang];
                },
            }
	});
</script>
@endsection
