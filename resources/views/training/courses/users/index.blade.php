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

    .row-instructor{
        background: #f2f2f2;
    }

    .row-default{
        background: #ffffff;
    }

    .row-trainee{
        background: #ecdfdf;
    }
    @media (max-width: 1100px){
        table{
            display: block;
            overflow-x: scroll;
        }
    }

</style>


<link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">

@endsection

@section('table')



<div class="course_info">
{{--        <h4 style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course Name : {{$course->trans_title}}</h4>--}}

        <div class="card p-3 mb-3">
            <div class="row">
{{--                    <template>--}}
{{--                        <div style="direction: ltr;" class="alert alert-success alert-dismissible" :class="{'d-none': !alert}" role="alert"><!-- fade show-->--}}
{{--                            <div>--}}
{{--                                <strong v-text="msg_alert"></strong> Updated--}}
{{--                            </div>--}}
{{--                            <button type="button" class="close" data-dismiss="alert">--}}
{{--                                <span aria-hidden="true">&times;</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </template>--}}

                    <div class="col-lg-3 col-md-4 col-12">
                            <button type="button" @click="OpenModal()" class="group_buttons mb-1 btn-sm">
                                <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.add_user')}}
                            </button>

{{--                            <button type="button" @click="OpenModal('instructor')" class="group_buttons mb-1 btn-sm">--}}
{{--                                <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.add_instructor')}}--}}
{{--                            </button>--}}
                    </div>

                    <div class="col-lg-9 col-md-8 col-12 text-right">
{{--                        @include('training.courses.contents.header',['course_id' => $course->id, 'users' =>true])--}}
                    </div>
            </div>
      </div>

      <template v-if="course_users">
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Type</th>
                <th scope="col">Progress</th>
                <th scope="col">Is Free</th>
                <th scope="col">Expire Date</th>
                <th scope="col">Session</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(course_user,index) in course_users">
                    <th scope="row" v-text="index + 1"></th>
                    <td v-text="trans_title(course_user.user_name)"></td>
                    <td v-text="course_user.email"></td>
                    <td>
                        <span v-if="course_user.role_id == 2" class="badge-pink"> Instructor </span>
                        <span v-if="course_user.role_id == 3" class="badge-green"> Trainee </span>
                    </td>
                    <td v-text="(course_user.progress??0) + '%'"></td>

                        <td>
                            <span v-if="course_user.paid_status == 503" class="badge-pink"> Paid </span>
                            <span v-if="course_user.paid_status == 504" class="badge-green"> Free </span>
                        </td>


{{--                        <td>--}}
{{--                            <input :value="moment(users_expire_date[course_user.id]).format('YYYY-MM-DDTHH:mm')" @input="users_expire_date[course_user.id] = moment($event.target.value).format('YYYY-MM-DDTHH:mm')"  type="datetime-local" name="expire_date" class="form-control" placeholder="Expire date">--}}
{{--                        </td>--}}

                    <td>
                        <span v-if="course_user.expire_date"  class="badge-pink"> @{{ course_user.expire_date }}</span>
                    </td>


                    <td>
                        <span v-if="course_user.session_id"  class="badge-pink" v-text="'SID: ('+ course_user.session_id + ') | ' + moment(course_user.date_from).format('DD-MM-YYYY') +' | ' + moment(course_user.date_to).format('DD-MM-YYYY') "></span>
                    </td>

                        <td>
                            <button @click="deleteUser(course_user.user_id)" class="red" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa-trash"></i> Delete</button>
                        </td>
                </tr>

            </tbody>
        </table>




            <div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
               <div class="modal-dialog modal-lg" role="document">
                 <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add</h5>
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
{{--                        <div class="p-3">--}}
{{--                            <label class="form-group">Expire Date: </label>--}}
{{--                            <input   type="datetime-local" v-model="expire_date" name="expire_date" class="w-100 form-control d-inline-block" placeholder="Expire date">--}}
{{--                        </div>--}}

                        <table class="table" style="width: 97%; margin: 0 auto;">
                            <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Paid Status</th>
                                <th scope="col">Session</th>
                                <th scope="col">Expire date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr :class="{'row-instructor' : search_user.role_id == 2 , 'row-trainee' : search_user.role_id == 3 , 'row-default' : (search_user.role_id != 2 && search_user.role_id != 3) }" v-for="(search_user,index) in search_users">
                                <th scope="row"><input type="checkbox" @change="addUser(search_user,$event)" :checked="isCheckedUser(search_user.user_id)"></th>
                                <td v-text="trans_title(search_user.user_name)"></td>
                                <td v-text="search_user.email"></td>
                                <td>
                                    <select @change="changeRoleId(search_user,$event.target.value)" v-model="search_user.role_id">
                                        <option  value="2">Instructor</option>
                                        <option  value="3">Trainee</option>
                                    </select>
{{--                                    <div class="form-group"><input  type="checkbox" class="mx-3" style="display: inline-block;"></div>--}}
                                </td>

                                <td>
                                    <select @change="changePaidStatus(search_user,$event.target.value)" v-model="search_user.paid_status">
                                        <option value="504">Free</option>
                                        <option value="503">Paid</option>
                                    </select>
                                </td>


                                <td>
                                    <select @change="changeSession(search_user,$event.target.value)" v-model="search_user.session_id">
                                        <option v-for="(session,index) in sessions" :value="session.id" v-text="'SID: ('+ session.id + ') | ' + moment(session.date_from).format('DD-MM-YYYY') +' | ' + moment(session.date_to).format('DD-MM-YYYY') "></option>
                                    </select>
                                </td>

                                <td>
                                   <input @change="changeExpireDate(search_user,$event.target.value)"  :value="moment(search_user.expire_date).format('YYYY-MM-DDTHH:mm')" type="datetime-local" name="expire_date" class="w-100 form-control d-inline-block" placeholder="Expire date">
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
            </div>
       </template>
</div>
@endsection



@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
<script>
    Vue.config.devtools = true
        window.lang = '{!!app()->getLocale()!!}'
        window.course = {!! json_encode($course[0]??'') !!}
        window.course_users = {!! json_encode($course_users??[]) !!}
        window.sessions = {!! json_encode($sessions??[]) !!}
	var contents = new Vue({
        el:'#main-vue-element',
        data : {
            course_users : window.course_users,
            lang     : window.lang,
            course   : window.course,
            sessions : window.sessions,
            search_username : '',
            search_email    : '',
            search_users    : [] ,
            add_users : [],
            delete_users : [],
            // expire_date : '' ,
            // users_expire_date    : {} ,
            // alert:false,
            // msg_alert: ''
        },
        created(){
		    let self = this
            console.log(this.sessions)
        },
        methods : {
            OpenModal : function(type){
                $('#ContentModal').modal('show')
            },
            trans_title : function (data) {
                return JSON.parse(data)[this.lang];
            },

            search: function () {
                    let self = this;
                    axios.post("{{route('training.search_user_course')}}",
                        {
                            'course_id' : self.course.id ,
                            'name' : self.search_username ,
                            'email'    : self.search_email ,
                        }
                        )
                        .then(response => {
                             self.search_users = response.data.users;
                        })
                        .catch(e => {
                            console.log(e)
                        });
           },

            save : function(){
                    let self = this;
                    axios.post("{{route('training.add_users_course')}}",
                        {
                            'users' : self.add_users,
                            'delete_users' : self.delete_users,
                            'course_id' : self.course.id,
                        }
                    )
                    .then(response => {
                        var self = this
                        if(response.data.status == 'success'){

                            this.course_users.forEach(function (item,index) {
                                self.add_users.forEach(function (user,i) {
                                    if(user.user_id == item.user_id){
                                        self.course_users[index] = user
                                        self.add_users.splice(i,1)
                                    }
                                })
                            })

                            this.course_users.push(...(this.add_users))


                            this.course_users.forEach(function (item,index) {
                                 self.delete_users.forEach(function (user) {
                                     if(user.user_id == item.user_id){
                                         self.course_users.splice(index,1)
                                     }
                                 })
                             })

                            self.add_users    = [];
                            self.delete_users = [];
                            self.search_users = [];
                            $('#ContentModal').modal('hide')
                        }
                    })
                    .catch(e => {
                         console.log(e)
                     });
                },

            changeRoleId : function(search_user,value){
                var self = this;
                var lock = true

                // if search_users and not add
                this.search_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.search_users[index]['role_id'] = value
                    }
                })

                // if search_users and add
                this.add_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.add_users[index]['role_id'] = value
                        lock = false
                    }
                })


                // if course_users and search_user and not add => push to add
                if(lock && self.isCheckedUser(search_user.user_id)){
                    search_user['role_id'] = value
                    self.add_users.push(search_user);
                }

            },

            changePaidStatus : function(search_user,value){
                var self = this;
                var lock = true

                // if search_users and not add
                this.search_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.search_users[index]['paid_status'] = value
                    }
                })

                // if search_users and add
                this.add_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.add_users[index]['paid_status'] = value
                        lock = false
                    }
                })


                // if course_users and search_user and not add => push to add
                if(lock && self.isCheckedUser(search_user.user_id)){
                    search_user['paid_status'] = value
                    self.add_users.push(search_user);
                }

            },

            changeExpireDate: function(search_user,value){
                var self = this;
                var lock = true

                // if search_users and not add
                this.search_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.search_users[index]['expire_date'] = moment(value).format('YYYY-MM-DDTHH:mm')
                    }
                })

                // if search_users and add
                this.add_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.add_users[index]['expire_date'] = moment(value).format('YYYY-MM-DDTHH:mm')
                        lock = false
                    }
                })


                // if course_users and search_user and not add => push to add
                if(lock && self.isCheckedUser(search_user.user_id)){
                    search_user['expire_date'] = moment(value).format('YYYY-MM-DDTHH:mm')
                    self.add_users.push(search_user);
                }

            },
            changeSession : function(search_user,value){
                var self = this;
                var lock = true

                // if search_users and not add
                this.search_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.search_users[index]['session_id'] = value
                    }
                })

                // if search_users and add
                this.add_users.forEach(function (item,index) {
                    if(item.user_id == search_user.user_id){
                        self.add_users[index]['session_id'] = value
                        lock = false
                    }
                })


                // if course_users and search_user and not add => push to add
                if(lock && self.isCheckedUser(search_user.user_id)){
                    search_user['session_id'] = value
                    self.add_users.push(search_user);
                }

            },
            isCheckedUser : function (user_id) {
                    let self = this;
                     let user = self.course_users.filter(function (user,index) {
                          return user.user_id == user_id;
                     })
                       if(user && user.length == 0){
                           return false;
                       }
                       return true;
                },

            addUser : function (search_user,event) {
                    if (event.target.checked) {
                        if(!search_user['role_id']){
                            search_user['role_id'] = 2;
                        }

                        if(!search_user['paid_status']){
                            search_user['paid_status'] = 504;
                        }

                        this.delete_users = this.delete_users.filter(function (user) {
                            return user.user_id != search_user.user_id
                        })




                        if(!this.isCheckedUser(search_user.user_id)){
                            this.add_users.push(search_user);
                        }else{
                            var lock = true
                            this.add_users.forEach(function (user,index) {
                               if(user.user_id == search_user.user_id){
                                   this.add_users[index] = search_user;
                                   lock = false
                               }
                            })

                            if(lock){
                                this.add_users.push(search_user);
                            }
                        }

                    }else{
                        this.add_users = this.add_users.filter(function (user) {
                            return user.user_id != search_user.user_id
                        })

                        if(this.isCheckedUser(search_user.user_id)){
                            this.delete_users.push(search_user);
                        }
                    }
                },

            deleteUser : function (user_id) {
                    let self = this;
                    if(confirm('Are you sure ?')){
                        self.course_users = self.course_users.filter(function (user,index) {
                              return (user.user_id != user_id)
                        })
                        axios.post("{{route('training.delete_user_course')}}",
                            {
                                'course_id' : self.course.id ,
                                'user_id'   : user_id ,
                            }
                        ).then(response => {}
                        ).catch(e => { console.log(e) });
                    }
          },


            {{--    // trans_name : function (data) {--}}
            {{--    //    return JSON.parse(data)[this.lang];--}}
            {{--    //  },--}}
            {{--    updateUserExpireDate: function (user_id) {--}}
            {{--        let user_expire_date = this.users_expire_date[user_id];--}}
            {{--        let self = this;--}}
            {{--        axios.post("{{route('training.update_user_expire_date')}}",--}}
            {{--            {--}}
            {{--                'course_id' : self.course.id ,--}}
            {{--                'user_id'   : user_id ,--}}
            {{--                'expire_date'   : user_expire_date ,--}}
            {{--            }--}}
            {{--        )--}}
            {{--            .then(response => {--}}
            {{--                console.log(response)--}}
            {{--                // self.search_users = response.data.users;--}}
            {{--                // $('#ContentModal').modal('hide')--}}

            {{--                let user = self.course.users.filter(function (user,index) {--}}
            {{--                    return user.id == user_id;--}}
            {{--                })--}}

            {{--                console.log(user);--}}
            {{--                self.msg_alert = user[0].email--}}

            {{--                self.alert = true;--}}
            {{--                setTimeout(--}}
            {{--                    function() {--}}
            {{--                        self.alert = false;--}}
            {{--                    }, 2000);--}}

            {{--            })--}}
            {{--            .catch(e => {--}}
            {{--                console.log(e)--}}
            {{--            });--}}
            {{--    },--}}


            {{--   updateIsFree: function (e,user_id) {--}}
            {{--    let self = this;--}}
            {{--       console.log(user_id)--}}
            {{--    axios.post("{{route('training.update_user_is_free')}}",--}}
            {{--        {--}}
            {{--            'course_id' : self.course.id ,--}}
            {{--            'user_id'   : user_id ,--}}
            {{--            'is_free'   : e.target.checked,--}}
            {{--        }--}}
            {{--    )--}}
            {{--        .then(response => {--}}
            {{--            console.log(response)--}}
            {{--            // self.search_users = response.data.users;--}}
            {{--            // $('#ContentModal').modal('hide')--}}

            {{--            let user = self.course.users.filter(function (user,index) {--}}
            {{--                return user.id == user_id;--}}
            {{--            })--}}

            {{--            console.log(user);--}}
            {{--            self.msg_alert = user[0].email--}}

            {{--            self.alert = true;--}}
            {{--            setTimeout(--}}
            {{--                function() {--}}
            {{--                    self.alert = false;--}}
            {{--                }, 2000);--}}

            {{--        })--}}
            {{--        .catch(e => {--}}
            {{--            console.log(e)--}}
            {{--        });--}}
            {{--}--}}
            }
	});
</script>
@endsection
