@extends(ADMIN.'.general.index')

@section('table')
    <style>
        .course_info button {
            padding: .375rem .75rem !important;
        }

        .ql-container.ql-snow{
            height: 200px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">

<div class="toLoad" id="contents">
{{--    {!!Builder::Tinymce('details', 'details')!!}--}}

    <div class="course_info">
        <label class="m-0">{{$course->trans_title}}</label>
       <div>
           <button type="button" @click="OpenModal()" class="btn btn-outline-dark mx-2">
           <i class="far fa-plus-square mr-2"></i> {{__('admin.add_users')}}
           </button>
       </div>
    </div>



    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="(user,index) in course.users">
                <th scope="row" v-text="index + 1"></th>
                <td v-text="trans_title(user.name)"></td>
                <td v-text="user.email"></td>
                <td>
{{--                    <form action="{{CustomRoute('training.delete_user_course',[$course->id,$user->id])}}">--}}
                        <button @click="deleteUser(user.id)" class="btn btn-sm btn-outline-danger btn-table" ><i class="fa fa-trash"></i> Delete</button>
{{--                    </form>--}}
                </td>
            </tr>

        </tbody>
    </table>





	<div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>



            <div class="container mt-2">
                <div class="form-group">
                    <label>Name </label>
                    <input name="username" placeholder="Name.." class="form-control" v-model="search_username"/>
                </div>

                <div class="form-group">
                    <label>Email </label>
                    <input name="email" placeholder="Email.." class="form-control"  v-model="search_email" />
                </div>

                <div style="margin-left:25px;margin-top: 5px;">
                    <button @click.prevent="search()" type="submit" name="search" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>


			<div class="modal-body" style="overflow: auto;height: 200px;">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Choose</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(search_user,index) in search_users">
                        <td v-text="trans_title(search_user.name)"></td>
                        <td v-text="search_user.email"></td>
                        <td>
                            <div class="form-group"><input @change="addUser(search_user.id,$event)" :checked="isCheckedUser(search_user.id)" type="checkbox" class="mx-3" style="display: inline-block;"></div>
                        </td>
                    </tr>


                    </tbody>
                </table>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-outline-danger" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-outline-info" >
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button"  class="btn btn-outline-success" @click="save()">
					<i class="fa fa-save"></i> {{__('admin.save')}}</button>

			</div>
		</div>
	</div>
</div>
</div>

@endsection

@push('vue')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>

<script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
<script>
        window.lang = '{!!app()->getLocale()!!}'
        window.course = {!! json_encode($course??[]) !!}
	var contents = new Vue({
		el:'#contents',
        data : {
            course : window.course,
            lang : window.lang,
            search_username : '' ,
            search_email    : '' ,
            search_users    : [] ,
            add_users : {},
        },
        methods : {
                OpenModal : function(){
                    $('#ContentModal').modal('show')
                },
                search: function () {
                    let self = this;
                    axios.post("{{route('training.search_user_course')}}",
                        {
                            'name' : self.search_username ,
                            'email'    : self.search_email ,
                        }
                        )
                        .then(response => {
                            console.log(response)
                             self.search_users = response.data.users;
                                // $('#ContentModal').modal('hide')

                        })
                        .catch(e => {
                            console.log(e)
                        });
                },
                isCheckedUser : function (user_id) {
                    let self = this;
                     let user = self.course.users.filter(function (user,index) {
                          return user.id == user_id;
                     })
                       if(user && user.length == 0){
                           return false;
                       }
                       return true;
                },
                addUser : function (user_id,event) {
                    if (event.target.checked) {
                        this.add_users[user_id] = true;
                    }else{
                        this.add_users[user_id] = false;
                    }
                },
                save : function(){
                    let self = this;
                    axios.post("{{route('training.add_users_course')}}",
                        {
                            'users' : self.add_users ,
                            'course_id' : self.course.id ,
                        }
                    )
                        .then(response => {
                            console.log(response.data)
                            if(response.data.status == 'success'){
                                self.add_users = {};
                                self.course = response.data.course;
                                $('#ContentModal').modal('hide')
                            }


                        })
                        .catch(e => {
                            console.log(e)
                        });
                },
                deleteUser : function (user_id) {
                    let self = this;
                    if(confirm('Are you sure ?')){

                        self.course.users = self.course.users.filter(function (user,index) {
                              return (user.id != user_id)
                        })

                        axios.post("{{route('training.delete_user_course')}}",
                            {
                                'course_id' : self.course.id ,
                                'user_id'   : user_id ,
                            }
                        )
                            .then(response => {
                                console.log(response)
                                // self.search_users = response.data.users;
                                // $('#ContentModal').modal('hide')

                            })
                            .catch(e => {
                                console.log(e)
                            });
                    }

               },
                trans_title : function (data) {
                   return JSON.parse(data)[this.lang];
                }
            }
	});
</script>
@endpush
