@extends(ADMIN.'.general.index')

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
            margin: 0 30px;
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
                                <div class="form-group">
                                    <label>Title </label>
                                    <textarea  v-model="title" name="title" class="form-control w-100" placeholder="title"></textarea>
                                    <div v-show="'title' in errors">
                                        <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                                    </div>
                                </div>




                                <div class="mt-5">

                                    <button @click.prevent="addSubUnitBox()" class="btn btn-primary mb-3">Add Subunit +</button>
                                    <div class="mb-2" v-show="'subunits' in errors">
                                        <span style="color: red;font-size: 13px">@{{ errors.subunits }}</span>
                                    </div>
                                    <div v-for="(subunit,index) in subunits" class="form-group">
                                        <input class="w-75" type="text" v-model="subunit.title" name="title"  placeholder="title">
                                        <button @click.stop="deleteUnit(course.units,subunit.id,index)"  class="btn btn-sm btn-outline-danger mx-3" >
                                            <i class="fa fa-trash"></i><!-- Delete --> </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                                <i class="fas fa-times"></i> {{__('admin.close')}}</button>
                            <button @click="clear()" type="reset" class="btn btn-outline-info" @click="Clear()">
                                <i  class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
                            <button type="button"  @click="save()" class="btn btn-outline-success">
                                <i class="fa fa-save"></i> {{__('admin.save')}}</button>

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div  class="course_info">
            <label class="m-0">{{$course->trans_title}}</label>
            <div>
                <button type="button" @click="OpenModal()" class="btn btn-outline-dark mx-3">
                    <i class="fa fa-plus"></i>  {{__('admin.add_unit')}}
                </button>
                <a href="{{route('training.contents',['course_id'=>$course->id])}}"  class="btn btn-outline-light">
                    {{__('admin.contents')}}
                </a>
            </div>

        </div>

        <div  class="card">
            <div class="card-header p-0" >
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


{{--    <ul class="tree">--}}
{{--        <li>Animals--}}
{{--            <ul>--}}
{{--                <li>Birds</li>--}}
{{--                <li>Mammals--}}
{{--                    <ul>--}}
{{--                        <li>Elephant</li>--}}
{{--                        <li class="last">Mouse</li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="last">Reptiles</li>--}}
{{--            </ul>--}}
{{--        </li>--}}
{{--        <li class="last">Plants--}}
{{--            <ul>--}}
{{--                <li>Flowers--}}
{{--                    <ul>--}}
{{--                        <li>Rose</li>--}}
{{--                        <li class="last">Tulip</li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="last">Trees</li>--}}
{{--            </ul>--}}
{{--        </li>--}}
{{--    </ul>--}}

@endsection

@push('vue')
    <script>

        Vue.component('tree', {
            props: ['item'],
            data(){
              return {
                  open : false,
              }
            },
            template: `
                         <li @click.stop="open = (!open)">@{{item.title }} <i class="fas fa-sort-down"></i>

                                  <button @click.stop="$root.deleteUnit(course.units,item.id)"  class="btn text-danger btn-sm" >
                                           <i class="fa fa-trash"></i></button>
                                  <button @click.stop="$root.edit(item,item.id)" type="button" class="btn text-info  btn-sm" id="answer" ><i class="fa fa-pencil-alt"></i></button>

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
                         <li @click.stop="open = (!open)" class="last" v-else>@{{child.title }}
                              <button @click.stop="$root.deleteUnit(course.units,child.id)"  class="btn btn-sm text-danger" >
                                       <i class="fa fa-trash"></i></button>
                              <button @click.stop="$root.edit(child,child.id)" type="button" class="btn text-info btn-sm px-3" id="answer" ><i class="fa fa-pencil-alt"></i></button>
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
                                        id : unit_id
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

                    OpenModal: function () {
                        // clear
                        this.title = '';
                        this.subunits = [];
                        this.save_type = 'add';
                        this.errors = {};
                        $('#ContentModal').modal('show')
                    },

                    searchTree : function (element, unit_id){
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
                        element = this.searchTree(element, unit_id)
                        this.title = element.title;
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
                        this.subunits.push({'id': null, 'title': ''});
                    },



                    clear: function () {
                        this.title = '';
                        this.subunits = [];
                        this.errors = {};                  },
                }


            });
    </script>
@endpush
