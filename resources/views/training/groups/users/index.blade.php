@extends('layouts.crm.index')

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

                    <button type="button" @click="OpenModal('trainee')" style="padding: 2px 8px !important;" class=" mb-1 group_buttons btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> add trainee
                    </button>


                    <button type="button" @click="OpenModal('instructor')" style="padding: 2px 8px !important;" class="group_buttons  mb-1 btn-sm">
                        <i class="fa fa-plus" aria-hidden="true"></i> add instructor
                    </button>
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
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(user,index) in group.users">
                        <th scope="row" v-text="index + 1"></th>
                        <td v-text="trans_title(user.name)"></td>
                        <td v-text="user.email"></td>

                        <td>
                            <button @click="deleteUser(user.id)" class="red" style="padding: 4px 8px !important; font-size: 12px;" ><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>

                </tbody>
            </table>


              <div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add @{{ type_user }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>



                <div class="container mt-2">
                    <div class="form-group">
                        <label>Name: </label>
                        <input name="username" placeholder="Name.." class="form-control d-inline-block" v-model="search_username"/>
                    </div>

                    <div class="form-group">
                        <label>Email: </label>
                        <input name="email" placeholder="Email.." class="form-control d-inline-block"  v-model="search_email" />
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
            search_username : '' ,
            search_email    : '' ,
            search_users    : [] ,
            users_expire_date    : {} ,
            add_users : {},
            type_user : 'trainee',
        },
        created(){
		    let self = this
           // this.group.users.forEach(function (user,index) {
           //     self.users_expire_date[user.id] = user.pivot.expire_date;
           // })
           //
            console.log(self.group)
        },
        methods : {
                OpenModal : function(type){
                    this.type_user = type;
                    $('#ContentModal').modal('show')
                },
                search: function () {
                    let self = this;
                    axios.post("{{route('training.search_user_group')}}",
                        {
                            'name' : self.search_username ,
                            'email'    : self.search_email ,
                            'user_type' : self.type_user ,
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
                     let user = self.group.users.filter(function (user,index) {
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
                    axios.post("{{route('training.add_users_group')}}",
                        {
                            'users' : self.add_users ,
                            'group_id' : self.group.id ,
                            'expire_date' : self.expire_date ,
                            'user_type' : self.type_user ,
                        }
                    )
                        .then(response => {
                            console.log(response.data)
                            if(response.data.status == 'success'){
                                self.add_users = {};
                                self.group = response.data.group;
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

                        self.group.users = self.group.users.filter(function (user,index) {
                              return (user.id != user_id)
                        })

                        axios.post("{{route('training.delete_user_group')}}",
                            {
                                'group_id' : self.group.id ,
                                'user_id'   : user_id ,
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
