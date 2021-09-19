@extends(ADMIN.'.general.form')

@section('col9')

    <div class="col-md-6">
        <form-select
            change="true" v-on:on-change="courseChange" label="Product Name"  name="product_name"
            :options-data="all_courses" oprion-key="id" view-text="title" is-text-json="true"
            :lang="lang" select-value="-1" :old_msg="old_msg">
        </form-select>
    </div>

    <div class="col-md-6">
        <form-select
            change="true" v-on:on-change="sessionChange" label="Session"
            name="Session"  :options-data="sessions" oprion-key="id" :old_msg="old_msg"
            view-text="json_title" :loading="loading"  select-value="-1">
        </form-select>
    </div>




    <div class="col-md-12">
        <form-select
            select-value="-1" change="true"
            v-on:on-change="timeChange" label="Time"
            name="time" :options-data="times"
            oprion-key="id" view-text="name"
             is-text-json="true" :old_msg="old_msg"
            :lang="lang" select-value="-1">
        </form-select>
    </div>


{{--    trainer --}}
    <template v-if="session_choose.trainer">
        <div class="col-md-6">
            <form-select
                change="true" v-on:on-change="trainerChange" label="Trainer"  name="trainer"
                :options-data="trainers" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="session_choose.trainer.id" :old_msg="old_msg" disabled="true" >
            </form-select>
        </div>

        <div class="col-md-6">
            <form-input
                v-model="trainer_balance"
                v-on:input="trainer_balance = $event"
                type="text" label="Cost" :old_msg="old_msg"
                placeholder="Cost" name="trainer_cost" class="digit">
            </form-input>
        </div>

    </template>


    <template v-else>
        <div class="col-md-6">
            <form-select
                change="true" v-on:on-change="trainerChange" label="Trainer"  name="trainer"
                :options-data="trainers" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="-1" :old_msg="old_msg" disabled="true">
            </form-select>
        </div>


        <div class="col-md-6">
            <form-input
                v-model="trainer_balance"
                v-on:input="trainer_balance = $event"
                type="text" label="Cost" :old_msg="old_msg"
                placeholder="Cost" name="trainer_cost" class="digit">
            </form-input>
        </div>

    </template>

    {{--   developer  --}}

    <template v-if="session_choose.developer">
    <div class="col-md-6" >
        <form-select
            change="true" v-on:on-change="trainerChange" label="Developer"  name="developer"
            :options-data="developers" oprion-key="id" view-text="name" is-text-json="true"
            :lang="lang" :select-value="session_choose.trainer.id" :old_msg="old_msg" disabled="true" >
        </form-select>
    </div>

    <div class="col-md-6">
        <form-input
            v-model="developer_balance"
            v-on:input="developer_balance = $event"
            type="text" label="Cost" :old_msg="old_msg"
            placeholder="Cost" name="developer_cost" class="digit">
        </form-input>
    </div>

</template>

    <template v-else>
        <div class="col-md-6" >
            <form-select
                change="true" v-on:on-change="trainerChange" label="Developer"  name="developer"
                :options-data="developers" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="-1" :old_msg="old_msg" disabled="true">
            </form-select>
        </div>


        <div class="col-md-6">
            <form-input
                v-model="developer_balance"
                v-on:input="developer_balance = $event"
                type="text" label="Cost" :old_msg="old_msg"
                placeholder="Cost" name="developer_cost" class="digit">
            </form-input>
        </div>
</template>


    {{--    demand_teams --}}
    <template v-if="session_choose.demand">
        <div class="col-md-6">
            <form-select
                change="false"  label="On Demand"  name="demand_team"
                :options-data="demand_teams" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="session_choose.demand.id"  :old_msg="old_msg" disabled="true">
            </form-select>
        </div>

        <div class="col-md-6">
            <form-input
                v-model="demand_balance"
                v-on:input="demand_balance = $event"
                type="text" label="Cost" :old_msg="old_msg"
                placeholder="Cost" name="demand_team_cost" :old_msg="old_msg" class="digit">
            </form-input>
        </div>

    </template>


    <template v-else>
        <div class="col-md-6">
            <form-select
                change="true" v-on:on-change="trainerChange" label="On Demand"  name="demand_teams"
                :options-data="demand_teams" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="-1" :old_msg="old_msg" disabled="true">
            </form-select>
        </div>


        <div class="col-md-6">
            <form-input
                v-model="demand_balance"
                v-on:input="demand_balance = $event"
                type="text" label="Cost"
                placeholder="Cost" name="demand_team_cost" class="digit">
            </form-input>
        </div>

    </template>

    <div class="col-md-12">
        <form-input
            v-model="session_choose.zoom" v-on:input="session_choose.zoom = $event"
            type="text" label="Zoom" placeholder="Zoom"
            name="zoom" :old_msg="old_msg" class="digit">
        </form-input>
    </div>



@endsection

{{--<group-buttons :back-href="url"--}}
{{--               action="update" back-btn-text="back" :action-btn-text="action"--}}
{{--               v-on:on-click="test">--}}
{{--</group-buttons>--}}

@section('col3_block')
<div class="card card-default">
    <div class="card-body">
        <div class="col-md-12 date">
            <form-input  v-model="session_choose.date_from" disabled="true"
                        v-on:input="session_choose.date_from = $event"
                        type="date" label="Date From" placeholder="Date From"
                        name="date_from" :old_msg="old_msg">
            </form-input>
        </div>

        <div class="col-md-12 date">
            <form-input v-model="session_choose.date_to" disabled="true"
                        v-on:input="session_choose.date_to = $event"
                        type="date" label="Date To" placeholder="Date To"
                        name="date_to" :old_msg="old_msg">

            </form-input>
        </div>

        <div class="col-md-12">
            <div class="col-md-6 position-relative float-left">
                <form-input
                    v-model="session_choose.duration"
                    v-on:input="session_choose.duration = $event"
                    type="number" label="Duration (days)" :old_msg="old_msg"
                    placeholder="Duration"  name="duration" disabled="true">
                </form-input>
            </div>

            <div class="col-md-6 position-relative float-right">
                <form-input
                    v-model="session_choose.hours_per_day"
                    v-on:input="session_choose.hours_per_day = $event"
                    type="number" label="Hours per Day" :old_msg="old_msg"
                    placeholder="Hours per Day"  name="hours_per_day" disabled="true">
                </form-input>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-6 position-relative float-left">
                <form-select
                    select-value="334" change="true"
                    v-on:on-change="test" label="Currency"
                    name="currency" :options-data="coins"
                    oprion-key="id" view-text="name" :old_msg="old_msg"
                    is-text-json="true"  :lang="lang" disabled="true">
                </form-select>
            </div>

            <div class="col-md-6 position-relative float-right">
                <form-input
                    v-model="total_hours"
                    v-on:input="total_hours = $event"
                    type="number" label="Total Hours" :old_msg="old_msg"
                    placeholder="Total Hours" name="total_hours" :readonly="true">
                </form-input>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-6 position-relative float-left">
                <form-input
                    v-model="session_choose.carts_count" v-on:input="session_choose.carts_count = $event"
                    type="number" label="Trainees No." placeholder="Trainees No."
                    name="trainees_no" :old_msg="old_msg">
                </form-input>
            </div>

            <div class="col-md-6 position-relative float-right">
                <form-input
                    v-model="count_attendants" v-on:input="count_attendants = $event"
                    type="number" label="Attendants No." placeholder="Attendants No."
                    name="attendants_no" :old_msg="old_msg">
                </form-input>
            </div>
        </div>







    </div>
</div>
@endsection
