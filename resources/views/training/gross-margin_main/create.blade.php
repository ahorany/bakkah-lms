@extends(ADMIN.'.general.create')
{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder('gross-margin')}}

<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 150px;
    }
    .form-inline .form-group .form-control {
        width: 60%;
        height: calc(2rem + 2px);
    }
    .custom-control.custom-checkbox {
        margin-left: 160px;
        margin-top: 5px;
    }
</style>
@section('create')
        <div id="root" class="cart-table">
            @include('training.gross-margin.form')
        </div>

    <script src="{{CustomAsset('js/app-vue-components.js')}}"></script>
    <script>

           window.all_courses = {!!$all_courses!!};
           window.developers = {!!$developers!!};
           window.trainers = {!!$trainers!!};
           window.times = {!!$times!!};
           window.demand_teams = {!!$demand_teams!!};
           window.coins = {!!$coins!!};
           window.lang = {!! $lang !!};
           window.sessions = {!!$session_array!!};
           window.action = {!! json_encode( __('admin.publish') )!!}
           window.old_msg = @json(old());
        new Vue({
            'el' : "#root" ,
            'data':{
                all_courses: window.all_courses,
                trainers: window.trainers,
                sessions: window.sessions,
                old_msg: window.old_msg,
                developers : window.developers,
                demand_teams : window.demand_teams,
                lang: window.lang,
                coins: window.coins,
                times: window.times,
                loading:false,
                count_attendants : 0 ,
                session_choose: {
                    'date_from' : '',
                    'date_to' : '',
                    'hours_per_day' : '0',
                    'zoom' : 0,
                    'duration' : '0',
                },
                total_hours : 0,
                trainer_balance : '',
                developer_balance : '',
                demand_balance : '',
                url:'https://www.youtube.com/watch?v=HIO8mSD5NeM',
                action: @json(__('admin.publish') ),

                inputVal: 'dsds' ,
                inputValNum:10,

            },
            methods:{
                convertJSON : function(value){
                    return  JSON.parse(value)[this.lang]
                },
                test : function (value) {
                    alert(value)
                },
                courseChange  : function (val) {
                    let self = this;
                    this.loading = true;
                    axios.get('{{route("training.carts.sessionsJson")}}', {
                        params: {
                            'course_id' : val
                        }
                    })
                        .then(function(resp){
                            self.loading = false;
                            self.sessions = resp.data;
                        }.bind(this))
                        .catch(function(err){
                            console.log(err);
                        });
                },
                sessionChange : function (val) {
                    let self = this;
                    axios.get('{{route("training.gross-margin.getSession")}}', {
                        params: {
                            'id' : val
                        }
                    })
                        .then(function(resp){
                            self.session_choose = resp.data;

                            self.total_hours = (self.session_choose.hours_per_day??0) * (self.session_choose.duration??0);
                            console.log(self.session_choose.hours_per_day, self.session_choose.duration, self.total_hours);

                            if(self.session_choose.trainer)
                            self.session_choose.trainer.name = JSON.parse(self.session_choose.trainer.name)[self.lang]

                            if(self.session_choose.developer)
                                self.session_choose.developer.name = JSON.parse(self.session_choose.developer.name)[self.lang]

                                if(self.session_choose.demand)
                                self.session_choose.demand.name = JSON.parse(self.session_choose.demand.name)[self.lang]

                            self.count_attendants = self.session_choose.carts.length;
                                console.log(self.session_choose)
                            //  self.old_msg = [];
                            // console.log(self.old_msg)
                        }.bind(this))
                        .catch(function(err){
                            console.log(err);
                        });

                },
                trainerChange : function (val) {
                  alert(val);
                },
                timeChange : function(value){
                    // trainer_balance
                    // developer_balance
                    // demand_balance
                    if(value == 431){ // Evening
                        if(this.session_choose.trainer &&this.session_choose.trainer.profile){
                            this.trainer_balance = this.session_choose.trainer.profile.evening_rate;
                        }

                        if(this.session_choose.developer && this.session_choose.developer.profile){
                            this.developer_balance = this.session_choose.developer.profile.evening_rate;
                        }

                        if(this.session_choose.demand && this.session_choose.demand.profile){
                            this.demand_balance = this.session_choose.demand.profile.evening_rate;
                        }
                    }else{ // Morning
                        if(this.session_choose.trainer && this.session_choose.trainer.profile){
                            this.trainer_balance = this.session_choose.trainer.profile.morning_rate;
                        }

                        if(this.session_choose.developer && this.session_choose.developer.profile){
                            this.developer_balance = this.session_choose.developer.profile.morning_rate;
                        }

                        if(this.session_choose.demand && this.session_choose.demand.profile){
                            this.demand_balance = this.session_choose.demand.profile.morning_rate;
                        }
                    }



                },
            },
            created(){
            //    console.log( window.old_msg )
            }
        })

    </script>


@endsection

