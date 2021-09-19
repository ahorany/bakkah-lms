@if(isset($eloquent))
    <div id="candidate-app">
        {!!Builder::Submit('AddDetailsBtn', 'Add Details', 'btn-primary', 'plus-square', [
            'type'=>'button',
        ])!!}
        <!-- Modal -->
        <div class="modal fade" id="AddDetailsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Candidates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div class="modal-body">

                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input v-on:click="SearchFunction()" type="text" class="form-control" placeholder="Search...">
                                        {{-- <input v-on:keyup.enter="SearchFunction($event)" :value="searchTxt" type="text" class="form-control" placeholder="Search..."> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- @include('training.discounts.vue') --}}
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cart in carts">
                                    <td>@{{cart.id}}</td>
                                    <td>
                                        @if(App::isLocale('en'))
                                            @{{JSON.parse(cart.user_id.name).en}}
                                        @else
                                            @{{JSON.parse(cart.user_id.name).ar}}
                                        @endif
                                    </td>
                                    <td>@{{cart.user_id.email}}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- <div>{{ SearchFunction() }}</div> --}}

                        <!-- alerts -->
                        {{-- <div class="alert alert-danger mt-2" v-if="msgFail" id="msg-danger">
                            <ul class="list-unstyled">
                                <li v-for="error in errors" v-html="error"></li>
                            </ul>
                        </div>
                        <div class="alert alert-success mt-2" v-if="msgSuccess" id="msg-success">
                            {{__('flash.Inserted Successfully')}}
                        </div> --}}

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{__('admin.close')}}</button>
                    <button type="button" class="btn btn-primary" @click="Clear($event)">
                        <i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
                    <button type="button" class="btn btn-primary" @click="AddToAllSessions($event)">
                        <i class="fa fa-save"></i> {{__('admin.save')}}</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('[name="AddDetailsBtn"]').click(function(){
                // vm.training_option_id = -1;
                // vm.session_id = -1;
                $('#AddDetailsModal').modal('show');
                return false;
            });
        });

        var vm = new Vue({
            el:'#candidate-app',
            data:{
                carts:{!!$carts->toJson()!!},
                searchTxt:'hanisalah78',
            },
            methods:{
                SearchFunction:function(){

                    axios.get('{{route("crm::group_invoices.search")}}', {
                    // axios.get('{{route("crm::group_invoices.index")}}', {
                        params:{
                            searchTxt:'hanisalah78',
                            // searchTxt: this.searchTxt
                        }
                    })
                    .then(function(resp){
                        alert(resp.data);
                        // vm.carts = resp.data;
                    }.bind(this))
                    .catch(function(err){
                        console.log(err);
                    });

                },
            }
        });

    </script>
@endif
