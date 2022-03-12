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
                            <input disabled :checked="course_user.paid_status == 504 ? true : false"  type="checkbox" >
                        </td>


{{--                        <td>--}}
{{--                            <input :value="moment(users_expire_date[course_user.id]).format('YYYY-MM-DDTHH:mm')" @input="users_expire_date[course_user.id] = moment($event.target.value).format('YYYY-MM-DDTHH:mm')"  type="datetime-local" name="expire_date" class="form-control" placeholder="Expire date">--}}
{{--                        </td>--}}

                    <td>
                        <span  class="badge-pink"> @{{ course_user.expire_date }}</span>
                    </td>

                        <td>
                            <button @click="deleteUser(course_user.id)" class="red" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa-trash"></i> Delete</button>
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

                        <table class="table table-striped" style="width: 97%; margin: 0 auto;">
                            <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Paid Status</th>
                                <th scope="col">Expire date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(search_user,index) in search_users">
                                <th scope="row"><input type="checkbox"></th>
                                <td v-text="trans_title(search_user.user_name)"></td>
                                <td v-text="search_user.email"></td>
                                <td>
                                    <select @change="changeRoleId(index,search_user.user_id,$event.target.value)">
                                        <option value="-1">Choose Value..</option>
                                        <option :selected="search_user.role_id == 2" value="2">Trainee</option>
                                        <option :selected="search_user.role_id == 3" value="3">Instructor</option>
                                    </select>
{{--                                    <div class="form-group"><input @change="addUser(search_user.id,$event)" :checked="isCheckedUser(search_user.id)" type="checkbox" class="mx-3" style="display: inline-block;"></div>--}}
                                </td>

                                <td>
                                    <select @change="changePaidStatus(index,search_user.user_id,$event.target.value)" >
                                        <option value="-1">Choose Value..</option>
                                        <option :selected="search_user.paid_status == 504" value="504">Free</option>
                                        <option :selected="search_user.paid_status == 503" value="503">Paid</option>
                                    </select>
                                </td>

                                <td>
                                   <input @change="changeExpireDate(index,search_user.user_id,$event.target.value)"  :value="moment(search_user.expire_date).format('YYYY-MM-DDTHH:mm')" type="datetime-local" name="expire_date" class="w-100 form-control d-inline-block" placeholder="Expire date">
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
	var contents = new Vue({
        el:'#main-vue-element',
        data : {
            course_users : window.course_users,
            lang : window.lang,
            course : window.course,
            search_username : '',
            search_email    : '',
            search_users    : [] ,
            add_users : {},
            // expire_date : '' ,
            // users_expire_date    : {} ,
            // alert:false,
            // msg_alert: ''
        },
        created(){
		    let self = this
            console.log(this.course_users)
           // this.course_users.forEach(function (course_user,index) {
           //     self.users_expire_date[course_user.id] = course_user.expire_date;
           // })
           //
           //  console.log(self.users_expire_date)
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
            changeRoleId : function(index,user_id,value){
                this.search_users[index]['role_id'] = value
                this.add_users[user_id] =  this.search_users[index];
            },
            changePaidStatus: function(index,user_id,value){
                this.search_users[index]['paid_status'] = value
                this.add_users[user_id] =  this.search_users[index];
            },
            changeExpireDate: function(index,user_id,value){
                this.search_users[index]['expire_date'] = moment(value).format('YYYY-MM-DDTHH:mm')
                this.add_users[user_id] =  this.search_users[index];
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
                        // console.log(response.data)
                        var self = this
                        if(response.data.status == 'success'){
                                Object.keys(self.add_users).forEach((key) => {
                                    var index = self.checkUserIfExists(self.add_users[key]['user_id'])
                                    if(index != -1){
                                        self.course_users[index] = self.add_users[key]
                                    }else{
                                        self.course_users.push( self.add_users[key])
                                    }
                                });

                            self.add_users = {};
                            $('#ContentModal').modal('hide')
                        }
                    })
                    .catch(e => {
                         console.log(e)
                     });
                },

            checkUserIfExists : function (user_id){
                var i =  -1;
                this.course_users.forEach(function (course_user,index) {
                    if(user_id == course_user['user_id']){
                        i =  index;
                    }
                })
                return i;
            }





            {{--    isCheckedUser : function (user_id) {--}}
            {{--        let self = this;--}}
            {{--         let user = self.course.users.filter(function (user,index) {--}}
            {{--              return user.id == user_id;--}}
            {{--         })--}}
            {{--           if(user && user.length == 0){--}}
            {{--               return false;--}}
            {{--           }--}}
            {{--           return true;--}}
            {{--    },--}}
            {{--    addUser : function (user_id,event) {--}}
            {{--        if (event.target.checked) {--}}
            {{--            this.add_users[user_id] = true;--}}
            {{--        }else{--}}
            {{--            this.add_users[user_id] = false;--}}
            {{--        }--}}
            {{--    },--}}

            {{--    deleteUser : function (user_id) {--}}
            {{--        let self = this;--}}
            {{--        if(confirm('Are you sure ?')){--}}

            {{--            self.course.users = self.course.users.filter(function (user,index) {--}}
            {{--                  return (user.id != user_id)--}}
            {{--            })--}}

            {{--            axios.post("{{route('training.delete_user_course')}}",--}}
            {{--                {--}}
            {{--                    'course_id' : self.course.id ,--}}
            {{--                    'user_id'   : user_id ,--}}
            {{--                }--}}
            {{--            )--}}
            {{--                .then(response => {--}}
            {{--                    console.log(response)--}}
            {{--                    // self.search_users = response.data.users;--}}
            {{--                    // $('#ContentModal').modal('hide')--}}

            {{--                })--}}
            {{--                .catch(e => {--}}
            {{--                    console.log(e)--}}
            {{--                });--}}
            {{--        }--}}
            {{--   },--}}
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
