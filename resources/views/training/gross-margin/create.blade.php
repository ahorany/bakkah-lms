@extends(ADMIN.'.general.create')
{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder('gross-margin')}}
{{Builder::SetObject('gross_margin')}}

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
        <?php $sid = request()->sid??0; ?>
        {{-- @dump($sid) --}}
        <div id="root" class="cart-table">
            @include('training.gross-margin.form')
        </div>

    <script src="{{CustomAsset('js/app-vue-components.js')}}"></script>
    <script>
           window.sid = {!! $sid !!};
           window.eloquent = {!!$eloquent!!};
           window.all_courses = {!!$all_courses!!};
           window.developers = {!!$developers!!};
           window.trainers = {!!$trainers!!};
           window.times = {!!$times!!};
           window.demand_teams = {!!$demand_teams!!};
           window.coins = {!!$coins!!};
           window.lang = {!! $lang !!};
           window.sessions = {!!$session_array!!};
           window.action = {!! json_encode( __('admin.publish') )!!};
           window.old_msg = @json(old());

           window.on_demand_cost = @json($on_demand_cost??null);
           window.trainer_cost = @json($trainer_cost??null);
           window.material_cost = @json($material_cost??null);
           window.delivery_cost = @json($delivery_cost??null);
           window.sales_value = @json($sales_value??null);
           window.gross_profit = @json($gross_profit??null);
           window.gross_margin = @json($gross_margin??null)+'%';
           window.href_s = @json($href_s??null);
           window.attendants_link = @json($attendants_link??null);

        //    console.log('aaaaa ',window.eloquent);
        //    console.log(@json(old()));
        //    console.log(window.gross_profit);

        vm = new Vue({
            'el' : "#root",
            'data': {
                eloquent: window.eloquent,
                all_courses: window.all_courses,
                trainers: window.trainers,
                sessions: window.sessions,
                old_msg: window.old_msg,
                developers : window.developers,
                demand_teams : window.demand_teams,
                lang: window.lang,
                coins: window.coins,
                times: window.times,
                time: -1,
                loading:false,
                count_attendants : 0 ,
                session_choose: {
                    'date_from' : '',
                    'date_to' : '',
                    'hours_per_day' : '0',
                    'zoom' : 86,
                    'duration' : '0',
                    'training_option_type' : 0,
                    'material_cost_course' : 0,
                    'course_id' : '',
                    'session_id' : '',
                    'trainer' : '',
                    'developer' : '',
                    'demand_team' : '',
                },
                total_hours : 0,
                trainer_balance : '',
                developer_balance : '',
                demand_balance : '',

                on_demand_cost : window.on_demand_cost,
                trainer_cost : window.trainer_cost,
                material_cost: window.material_cost,
                delivery_cost : window.delivery_cost,
                sales_value : window.sales_value,
                gross_profit : window.gross_profit,
                gross_margin : window.gross_margin,
                href_s : window.href_s,
                attendants_link : window.attendants_link,

                url:'https://www.youtube.com/watch?v=HIO8mSD5NeM',
                action: @json(__('admin.publish') ),

                inputVal: 'dsds' ,
                inputValNum:10,
                optionType: {
                        '11' : '',
                        '13' : '_online',
                        '353' : '',
                        '383'  :'_classroom',
                    },

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

                            // console.log(self.session_choose.training_option.course_id);
                            console.log(self.session_choose);

                            self.session_choose.course_id = self.session_choose.training_option.course_id;
                            self.session_choose.session_id = self.session_choose.id;

                            // console.log(self.session_choose.course_id);

                            self.session_choose.training_option_type = self.session_choose.training_option.constant_id??13;

                            // console.log(self.session_choose.training_option_type);

                            self.total_hours = (self.session_choose.hours_per_day??0) * (self.session_choose.duration??0);

                            // console.log(self.session_choose.hours_per_day, self.session_choose.duration, self.total_hours);

                            if(self.session_choose.trainer)
                                self.session_choose.trainer.name = JSON.parse(self.session_choose.trainer.name)[self.lang]

                            if(self.session_choose.developer)
                                self.session_choose.developer.name = JSON.parse(self.session_choose.developer.name)[self.lang]

                            if(self.session_choose.demand)
                                self.session_choose.demand.name = JSON.parse(self.session_choose.demand.name)[self.lang]
                            
                            self.count_attendants = self.session_choose.carts.length;

                            self.session_choose.zoom = self.eloquent.zoom??86;
                            self.session_choose.material_cost_course = self.session_choose.training_option.course.material_cost;

                            // console.log(self.session_choose.session_start_time.toLocaleString('en-US', { hour: 'numeric', hour12: true }));

                            var time_s = new Date(self.session_choose.session_start_time);
                            var amOrPm = (time_s.getHours() < 12) ? "AM" : "PM";
                            // 432 AM

                            (amOrPm == "AM") ? self.time = 432 : self.time = 431;
                            self.timeChange(self.time);

                            console.log(eloquent);
                            console.log(self.time);
                            // console.log(amOrPm);
                            // console.log(self.times);

                            // console.log(
                            //     time.toLocaleString('en-US', { hour: 'numeric', hour12: true });
                            // );

                            // self.on_demand_cost = self.window.on_demand_cost,
                            // self.trainer_cost = window.trainer_cost,
                            // self.attendants_link : window.attendants_link,
                            // self.material_cost: window.material_cost,
                            // self.delivery_cost : window.delivery_cost,
                            // self.sales_value : window.sales_value,
                            // self.gross_profit : window.gross_profit,
                            // self.gross_margin : window.gross_margin,

                                // console.log('xxxxxxxxxx '+self.times);
                                // self.timeChange(eloquent.time??self.times);

                                // console.log(self.session_choose)

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
                    var trainingOptionType = this.getTypeCode(this.session_choose.training_option_type??13);
                    // console.log(trainingOptionType);

                        if(this.session_choose.trainer && this.session_choose.trainer.profile){
                            var person_rate_field = this.personRateField('trainer', trainingOptionType, value);
                            console.log(person_rate_field);
                            this.trainer_balance = eloquent.trainer_cost ? eloquent.trainer_cost : person_rate_field;
                        }

                        if(this.session_choose.developer && this.session_choose.developer.profile){
                            var person_rate_field = this.personRateField('developer', trainingOptionType, value);
                            this.developer_balance = eloquent.developer_cost ? eloquent.developer_cost : person_rate_field;
                        }

                        if(this.session_choose.demand && this.session_choose.demand.profile){
                            var person_rate_field = this.personRateField('demand', trainingOptionType, value);
                            this.demand_balance = eloquent.demand_team_cost ? eloquent.demand_team_cost : person_rate_field;
                        }

                },
                getTypeCode : function(index){
                    return this.optionType[index];
                },
                personRateField : function(person, trainingOptionType, time){

                    if(person == 'trainer'){
                        if(time == 431){ // Evening
                            var rate_type = `evening_rate${trainingOptionType}`;
                        }else{ // Morning
                            var rate_type = `morning_rate${trainingOptionType}`;
                        }
                    }else if(person == 'developer' || person == 'demand'){
                        var rate_type = `daily_rate${trainingOptionType}`;
                    }
                    
                    return eval('this.session_choose.'+person+'.profile.'+rate_type);
                },
            },
            created(){
                // console.log( window.old_msg )
                // console.log( eloquent )
                // console.log('aaaaaaaaaaa')


                if(eloquent){
                    // console.log('before '+this.session_choose.training_option_type);
                    this.sessionChange(eloquent.session_id);
                    // console.log('after '+this.session_choose.training_option_type);
                    // this.session_choose.trainingOption->constant_id??13
                    // this.timeChange(eloquent.time);

                }else{
                    // console.log(this.$route.params.id);
                    this.sessionChange(sid);

                    // eloquent.course_id = self.session_choose.training_option.course_id;
                    // eloquent.Session = self.session_choose.id;
                    // console.log(eloquent.Session);
                    // this.trainer_balance=eloquent.trainer_cost;
                    // this.developer_balance=eloquent.developer_cost;
                    // this.demand_balance=eloquent.demand_team_cost;
                }

                this.trainer_balance=eloquent.trainer_cost??0;
                this.developer_balance=eloquent.developer_cost??0;
                this.demand_balance=eloquent.demand_team_cost??0;

            }
        })

    </script>


@endsection

