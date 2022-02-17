@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
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

    @media (max-width: 1100px){
        table{
            display: block;
            overflow-x: scroll;
        }
    }
    /* @media (min-width: 1110px){
        table{
            display: table;
        }
    } */
</style>
@endsection

@section('table')

    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">


        <div class="course_info">
            <h4 style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course Name : {{$course->trans_title}}</h4>

            <div class="card p-3 mb-3">
            <div class="row">

                <template>
                    <div style="direction: ltr;" class="alert alert-success alert-dismissible" :class="{'d-none': !alert}" role="alert"><!-- fade show-->
                        <div>
                            <strong v-text="msg_alert"></strong> Updated
                        </div>
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </template>

                <div class="col-lg-3 col-md-4 col-12">
                    @if(!checkUserIsTrainee())
                        <button type="button" @click="OpenModal('trainee')" class="group_buttons mb-1 btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.add_trainee')}}
                        </button>

                        <button type="button" @click="OpenModal('instructor')" class="group_buttons mb-1 btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.add_instructor')}}
                        </button>
                    @endif
                </div>

                <div class="col-lg-9 col-md-8 col-12 text-right">
                    @include('training.courses.contents.header',['course_id' => $course->id, 'users' =>true])
                </div>

            </div>
        </div>
          <template v-if="course">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Type</th>
                    <th scope="col">Progress</th>
                    @if(!checkUserIsTrainee())
                        <th scope="col">Is Free</th>
                        <th scope="col">Expire Date</th>
                        <th scope="col">Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(user,index) in course.users">
                        <th scope="row" v-text="index + 1"></th>
                        <td v-text="trans_title(user.name)"></td>
                        <td v-text="user.email"></td>
                        <td>
                            <span v-if="user.pivot != null && user.pivot.role_id == 2" class="badge-pink"> Instructor </span>
                            <span v-if="user.pivot != null && user.pivot.role_id == 3" class="badge-green"> Trainee </span>
                        </td>
                        <td v-text="(user.pivot.progress??0) + '%'"></td>

                    @if(!checkUserIsTrainee())
                            <td>
                                <input :checked="user.pivot.paid_status == 504 ? true : false"  @change="updateIsFree($event,user.id)"   type="checkbox" >
                            </td>


                            <td>
                                <input :value="moment(users_expire_date[user.id]).format('YYYY-MM-DDTHH:mm')" @input="users_expire_date[user.id] = moment($event.target.value).format('YYYY-MM-DDTHH:mm')"  type="datetime-local" name="expire_date" class="form-control" placeholder="Expire date">

                            </td>

                            <td>
                                <button @click="updateUserExpireDate(user.id)" class="primary" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa"></i> Update</button>
                                <button @click="deleteUser(user.id)" class="red" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        @endif
                    </tr>

                </tbody>
            </table>


            @if(!checkUserIsTrainee())
              <div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add @{{ type_user }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>



                <div class="container row mx-0 mt-2">

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Name: </label>
                            <input name="username" placeholder="Name.." class="w-100 form-control d-inline-block" v-model="search_username"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Email: </label>
                            <input name="email" placeholder="Email.." class="w-100 form-control d-inline-block"  v-model="search_email" />
                        </div>
                    </div>

                    <div>
                        <button @click.prevent="search()" type="submit" name="search" class="main-color"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

                <div class="modal-body" style="overflow: auto;height: 200px;">
                    <div class="p-3">
                        <label class="form-group">Expire Date: </label>
                        <input   type="datetime-local" v-model="expire_date" name="expire_date" class="w-100 form-control d-inline-block" placeholder="Expire date">
                    </div>

                    <table class="table table-striped" style="width: 97%; margin: 0 auto;">
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
                    <button type="button" class="red" data-dismiss="modal">{{__('admin.close')}}</button>
                    <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                    <button type="button"  class="green" @click="save()">{{__('admin.save')}}</button>
                </div>
            </div>
        </div>
             @endif
           </template>
    </div>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>

<script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
<script>
        window.lang = '{!!app()->getLocale()!!}'
        window.course = {!! json_encode($course??[]) !!}
	var contents = new Vue({
        el:'#main-vue-element',
        data : {
            course : window.course,
            lang : window.lang,
            expire_date : '' ,
            search_username : '' ,
            search_email    : '' ,
            search_users    : [] ,
            users_expire_date    : {} ,
            add_users : {},
            type_user : 'trainee',
            alert:false,
            msg_alert: ''
        },
        created(){
		    let self = this
           this.course.users.forEach(function (user,index) {
               self.users_expire_date[user.id] = user.pivot.expire_date;
           })

            console.log(self.users_expire_date)
        },
        methods : {
                OpenModal : function(type){
                    this.type_user = type;
                    $('#ContentModal').modal('show')
                },
                search: function () {
                    let self = this;
                    axios.post("{{route('training.search_user_course')}}",
                        {
                            'name' : self.search_username ,
                            'email'    : self.search_email ,
                            'type_user'    : self.type_user ,
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
                            'expire_date' : self.expire_date ,
                            'type' : self.type_user ,
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
                },
                // trans_name : function (data) {
                //    return JSON.parse(data)[this.lang];
                //  },
                updateUserExpireDate: function (user_id) {
                    let user_expire_date = this.users_expire_date[user_id];
                    let self = this;
                    axios.post("{{route('training.update_user_expire_date')}}",
                        {
                            'course_id' : self.course.id ,
                            'user_id'   : user_id ,
                            'expire_date'   : user_expire_date ,
                        }
                    )
                        .then(response => {
                            console.log(response)
                            // self.search_users = response.data.users;
                            // $('#ContentModal').modal('hide')

                            let user = self.course.users.filter(function (user,index) {
                                return user.id == user_id;
                            })

                            console.log(user);
                            self.msg_alert = user[0].email

                            self.alert = true;
                            setTimeout(
                                function() {
                                    self.alert = false;
                                }, 2000);

                        })
                        .catch(e => {
                            console.log(e)
                        });
                },


               updateIsFree: function (e,user_id) {
                let self = this;
                   console.log(user_id)
                axios.post("{{route('training.update_user_is_free')}}",
                    {
                        'course_id' : self.course.id ,
                        'user_id'   : user_id ,
                        'is_free'   : e.target.checked,
                    }
                )
                    .then(response => {
                        console.log(response)
                        // self.search_users = response.data.users;
                        // $('#ContentModal').modal('hide')

                        let user = self.course.users.filter(function (user,index) {
                            return user.id == user_id;
                        })

                        console.log(user);
                        self.msg_alert = user[0].email

                        self.alert = true;
                        setTimeout(
                            function() {
                                self.alert = false;
                            }, 2000);

                    })
                    .catch(e => {
                        console.log(e)
                    });
            }
            }
	});
</script>
@endsection
