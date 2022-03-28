@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Units')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    <style>
        ul.tree, ul.tree ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        ul.tree ul {
            margin-left: 10px;
        }
        ul.tree li {
            margin: 0 0px;
            padding: 0 10px;
            line-height: 20px;
            color: #369;
            font-weight: bold;
            cursor: pointer;
            border-left:1px solid rgb(100,100,100);

        }
        ul.tree li:last-child {
            border-left:none;
        }
        ul.tree li:before {
            position:relative;
            top:-0.3em;
            /*height:1em;*/
            height:2.5em;
            width:12px;
            color:white;
            border-bottom:1px solid rgb(100,100,100);
            content:"";
            display:inline-block;
            left:-10px;
        }
        ul.tree li:last-child:before {
            border-left:1px solid rgb(100,100,100);
        }

        li button.btn-sm {
            padding: .25rem 0.2rem !important;
        }

        li button.btn-sm:first-of-type {
            margin-left: 10px;
        }

        .course_info button {
            padding: .375rem .75rem !important;
        }
    </style>
    <div class="toLoad" id="units">

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
                                <div class="row mx-0">
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>Unit id </label>
                                            <input class="form-control w-100" type="text" v-model="unit_no" placeholder="Unit id" >
                                            <div v-show="'unit_no' in errors">
                                                <span style="color: red;font-size: 13px">@{{ errors.unit_no }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <div class="form-group">
                                            <label>Title </label>
                                            <input  v-model="title" name="title" class="form-control w-100" placeholder="title" />
                                            <div v-show="'title' in errors">
                                                <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0">
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <div class="row mx-0">
                                        <div class="col-md-12 col-12">
                                            <button @click.prevent="addSubUnitBox()" class="main-color mb-3"><i class="fa fa-plus" aria-hidden="true"></i> Add Sub Unit</button>
                                            <div class="mb-2" v-show="'subunits' in errors">
                                                <span style="color: red;font-size: 13px">@{{ errors.subunits }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-for="(subunit,index) in subunits" class="form-group">
                                        <div class="row mx-0">
                                            <div class="col-md-3 col-12">
                                                <input class="form-control" style="display: inline-block;" type="text" v-model="subunit.unit_no" name="title" placeholder="unit no">
                                            </div>
                                            <div class="col-md-8 col-10">
                                                <input class="form-control" style="display: inline-block;" type="text" v-model="subunit.title" name="title" placeholder="title">
                                            </div>
                                            <div class="col-md-1 col-2">
                                                <button @click.stop="deleteUnit(course.units,subunit.id,index)" class="red">
                                                    <i class="fa fa-trash"></i><!-- Delete --> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="red" data-dismiss="modal">{{__('admin.close')}}</button>
                            <button @click="clear()" type="reset" class="cyan" @click="Clear()">{{__('admin.clear')}}</button>
                            <button type="button"  @click="save()" class="green">{{__('admin.save')}}</button>

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <h4 style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course Name : {{$course->trans_title}}</h4>

        <div  class="course_info mb-3 card p-3">
            <div class="row">

                <div class="col-lg-3 col-md-4 col-12">


                    <button type="button" @click="OpenModal()" class="group_buttons mb-1 btn-sm">
                        <i class="fa fa-plus"></i> {{__('admin.add_unit')}}
                    </button>
                </div>

                <div class="col-lg-9 col-md-8 col-12 text-right">
                    @include('training.courses.contents.header',['course_id' => $course->id, 'units' =>true])
                </div>

            </div>
        </div>

        <div  class="card">
            <div class="card-body p-0" >
                <div class="clearfix mb-1 p-3 m-0">
                    <ul class='tree'>
                        <li @click.stop="open = (!open)" >{{$course->trans_title}} Units
                            <tree v-show="open" v-for="item in course.units" :item="item" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>

        Vue.component('tree', {
            props: ['item'],
            data(){
              return {
                  open : false,
              }
            },
            template: `
                         <li @click.stop="open = (!open)"><span v-if="item.unit_no">(@{{item.unit_no }})</span>  @{{item.title }} <i v-if="item.s && item.s.length > 0" class="fa fa-chevron-down" aria-hidden="true"></i>

                                  <button @click.stop="$root.deleteUnit(course.units,item.id)" class="btn text-danger btn-sm" >
                                           <i class="fa fa-trash"></i></button>
                                  <button @click.stop="$root.edit(item,item.id)" type="button" class="btn text-info btn-sm" id="answer" ><i class="fa fa-pencil" style="color:#000;" aria-hidden="true"></i></button>

                             <tree-item-contents v-show="open" v-if="item.s" :items="item.s"/>
                           </li>`
        })

        Vue.component('tree-item-contents', {
             props: ['items'],
             data() {
                 return {
                     open: false,
                 }
             },
                template: `<ul v-if="items.length > 0">
                      <template v-for="child in items">
                         <tree v-if="child.s" :item="child"/>
                         <li @click.stop="open = (!open)" class="last" v-else><span v-if="child.unit_no">(@{{child.unit_no }})</span>  @{{child.title }}
                              <button @click.stop="$root.deleteUnit(course.units,child.id)"  class="btn btn-sm text-danger" >
                                       <i class="fa fa-trash"></i></button>
                              <button @click.stop="$root.edit(child,child.id)" type="button" class="btn text-info btn-sm px-3" id="answer" ><i style="color:#000;" class="fa fa-pencil"></i></button>
                           </li></template></ul>`
         })


        window.course = {!! json_encode($course??[]) !!}
        window.units = {!! json_encode($units??[]) !!}
        var contents = new Vue({
                el: '#units',
                data: {
                    title: '',
                    course: window.course,
                    units: window.units,
                    subunits  : [],
                    save_type: 'add',
                    open: true,
                    unit_id: '',
                    errors: {},
                    model_type : 'units',
                    counter : 0,
                    unit_no : '',
                },
                created: function () {
                    this.course.units = this.units;
                },
                methods: {
                    deleteUnit : function (arrayElements, unit_id,index) {
                        let self = this;
                        if (confirm("Are you sure ? ")) {
                            if(unit_id == null){
                                this.subunits.splice(index,1);
                            }else{
                                this.deleteNodeFromTree(arrayElements, unit_id)
                                axios.get("{{route('training.delete_unit')}}",{
                                    params : {
                                        id : unit_id,
                                        course_id : self.course.id,
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
                    deleteNodeFromTree : function (array, label) {
                        for (var i = 0; i < array.length; ++i) {
                            var obj = array[i];
                            if (obj.id === label) {
                                let dataDeleted = array[i]
                                array.splice(i, 1) ;
                                return dataDeleted;
                            }
                            if (obj.s) {
                                if ( d = this.deleteNodeFromTree(obj.s, label)) {
                                    return d;
                                }
                            }
                        }
                    },

                    searchNodeFromTree : function (array, label) {
                        for (var i = 0; i < array.length; ++i) {
                            var obj = array[i];

                            if(obj.parent_id == null){
                                this.counter = 1;
                            }else{
                                this.counter++;
                            }
                            if (obj.id === label) {
                                return true;
                            }


                            if (obj.s) {
                                if ( this.searchNodeFromTree(obj.s, label)) {
                                    return true;
                                }
                            }
                        }
                    },

                    OpenModal: function () {
                        // clear
                        this.title = '';
                        this.unit_no = '';
                        this.subunits = [];
                        this.save_type = 'add';
                        this.errors = {};
                        $('#ContentModal').modal('show')
                    },

                    searchTree : function (element, unit_id){
                        // this.counter++;
                        // console.log(this.counter)
                        // alert(unit_id)
                        if(element.id == unit_id){
                            return element;
                        }else if (element.s != null){
                            var i;
                            var result = null;
                            for(i=0; result == null && i < element.s.length; i++){

                                result = this.searchTree(element.s[i], unit_id);
                            }
                            return result;
                        }
                        return null;
                    },
                    edit : function(element, unit_id){
                        this.save_type = 'edit';
                        this.unit_id = unit_id;
                        this.errors = {};
                        this.counter = 0;
                        // this.searchTree(element, unit_id)
                        this.searchNodeFromTree(this.course.units,unit_id)
                        this.title = element.title;
                        this.unit_no = element.unit_no;
                        this.subunits = element.s??[];
                        $('#ContentModal').modal('show')
                    },

                    save : function () {
                        let self = this;
                        if(this.save_type  == 'add'){
                            axios.post("{{route('training.add_unit')}}",
                                {
                                    title : self.title,
                                    course_id : self.course.id,
                                    subunits : self.subunits,
                                    unit_no : self.unit_no,
                                })
                                .then(response => {
                                    console.log(response)
                                    if(response['data']['errors']) {
                                        self.errors =  response['data']['errors']
                                        for (let property in self.errors) {
                                            self.errors[property] = self.errors[property][0];
                                        }
                                    }else{
                                        this.course.units.push(response.data.data);
                                        this.errors = {};
                                        $('#ContentModal').modal('hide')
                                    }

                                })
                                .catch(e => {
                                    console.log(e)
                                });
                        }else if(this.save_type  == 'edit'){
                            axios.post("{{route('training.update_unit')}}",
                                {
                                    title : self.title,
                                    course_id : self.course.id,
                                    subunits : self.subunits,
                                    unit_id : self.unit_id,
                                    unit_no : self.unit_no,
                                })
                                .then(response => {
                                    console.log(response)
                                    if(response['data']['errors']) {
                                        self.errors =  response['data']['errors']
                                        for (let property in self.errors) {
                                            self.errors[property] = self.errors[property][0];
                                        }
                                    }else{
                                        this.updateElementInVue(response.data.data ,course.units,this.unit_id);
                                        this.errors = {};
                                        $('#ContentModal').modal('hide')
                                    }

                                })
                                .catch(e => {
                                    console.log(e)
                                });
                        }
                    },
                    updateElementInVue : function(data ,array, unit_id){
                            for (var i = 0; i < array.length; ++i) {
                                var obj = array[i];
                                if (obj.id == unit_id) {
                                    Vue.set(array, i, data)
                                    return true;
                                }
                                if (obj.s) {
                                    if (this.updateElementInVue(data ,obj.s, unit_id)) {
                                        return true;
                                    }
                                }
                            }
                    },



                    addSubUnitBox : function () {
                        this.subunits.push({'id': null, 'title': '','unit_no' : ''});
                    },



                    clear: function () {
                        this.title = '';
                        this.unit_no = '';
                        this.subunits = [];
                        this.errors = {};                  },
                }


            });
    </script>
@endsection
