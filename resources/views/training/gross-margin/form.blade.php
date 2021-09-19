@extends(ADMIN.'.general.form')
<?php //phpinfo(); ?>
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{-- {{Builder::SetPublishName('publish')}} --}}

{{-- @extends(ADMIN.'.general.form') --}}
{{-- @dd($folder) --}}
{{-- eloquent.course_id --}}
{{-- eloquent.session_id --}}
@section('col9')

    <div class="col-md-6">
        <form-select
            change="true" v-on:on-change="courseChange" label="Product Name"  name="product_name"
            :options-data="all_courses" oprion-key="id" view-text="title" is-text-json="true"
            :lang="lang" :select-value="session_choose.course_id">
        </form-select>
    </div>

    <div class="col-md-5">
        <form-select
            change="true" v-on:on-change="sessionChange" label="Session"
            name="Session"  :options-data="sessions" oprion-key="id" :old_msg="old_msg"
            view-text="json_title" :loading="loading" :select-value="session_choose.session_id">
        </form-select>
    </div>

    <div class="col-md-1 align-self-center" style="margin-top: 15px;">
        <template>
            <span v-html="href_s"></span>
        </template>
    </div>

    <div class="col-md-12">
        <form-select
            change="true"
            v-on:on-change="timeChange" label="Time"
            name="time" :options-data="times"
            oprion-key="id" view-text="name"
            is-text-json="true" :old_msg="old_msg"
            :lang="lang" :select-value="time">
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
            :lang="lang" :select-value="session_choose.developer.id" :old_msg="old_msg" disabled="true" >
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
                change="true"  label="On Demand"  name="demand_team"
                :options-data="demand_teams" oprion-key="id" view-text="name" is-text-json="true"
                :lang="lang" :select-value="session_choose.demand.id" :old_msg="old_msg" disabled="true">
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
                change="true" v-on:on-change="trainerChange" label="On Demand"  name="demand_team"
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

    <div class="col-md-6">
        <form-input
            v-model="session_choose.zoom" v-on:input="session_choose.zoom = $event"
            type="text" label="Zoom" placeholder="Zoom"
            name="zoom" :old_msg="old_msg" class="digit">
        </form-input>
    </div>

    <div class="col-md-6">
        <form-input
            v-model="session_choose.material_cost_course"
            v-on:input="session_choose.material_cost_course = $event"
            type="number" label="Course Material Cost" :old_msg="old_msg"
            placeholder="Course Material Cost" name="material_cost_course" disabled="true">
        </form-input>
    </div>

{{-- Strat Calculations --}}
<div class="card card-default col-md-12 mb-4" style="border: 1px solid #fb44005c;">
    <div class="card-header text-bold pl-2" style="font-weight: bold;color: #fb4400;">Totals</div>
    <div class="card-body px-0">

        <div class="col-md-6 position-relative float-left">
            <form-input
                v-model="on_demand_cost"
                v-on:input="on_demand_cost = $event"
                type="text" label="On-Demand Team Cost" :old_msg="old_msg"
                placeholder="On-Demand Team" name="on_demand_cost" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-right">
            <form-input
                v-model="trainer_cost"
                v-on:input="trainer_cost = $event"
                type="text" label="Trainer Cost" :old_msg="old_msg"
                placeholder="Trainer Cost" name="trainer_cost" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-left">
            <form-input
                v-model="material_cost"
                v-on:input="material_cost = $event"
                type="text" label="Material Cost" :old_msg="old_msg"
                placeholder="Material Cost" name="material_cost" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-right">
            <form-input
                v-model="delivery_cost"
                v-on:input="delivery_cost = $event"
                type="text" label="Cost Of Delivery" :old_msg="old_msg"
                placeholder="Cost Of Delivery" name="delivery_cost" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-left">
            <form-input
                v-model="sales_value"
                v-on:input="sales_value = $event"
                type="text" label="Sales Value" :old_msg="old_msg"
                placeholder="Sales Value" name="sales_value" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-right">
            <form-input
                v-model="gross_profit"
                v-on:input="gross_profit = $event"
                type="text" label="Gross Profit" :old_msg="old_msg"
                placeholder="Gross Profit" name="gross_profit" disabled="true">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-left">
            <form-input
                v-model="gross_margin"
                v-on:input="gross_margin = $event"
                type="text" label="Gross Margin" :old_msg="old_msg"
                placeholder="Gross Margin" name="gross_margin" disabled="true" style="font-weight: bold;color: #fb4400;">
            </form-input>
        </div>

        <div class="col-md-6 position-relative float-right align-self-center" style="margin-top: 36px;">
            <template>
                <span v-html="attendants_link"></span>
            </template>
        </div>


    </div>
</div>
{{-- ُEnd of Calculations --}}
@endsection

{{--<group-buttons :back-href="url"--}}
{{--               action="update" back-btn-text="back" :action-btn-text="action"--}}
{{--               v-on:on-click="test"> --}}
{{-- </group-buttons> --}}

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
            <div class="col-md-5 position-relative float-left">
                <form-input
                    v-model="session_choose.duration"
                    v-on:input="session_choose.duration = $event"
                    type="number" label="Duration (days)" :old_msg="old_msg"
                    placeholder="Duration"  name="duration" disabled="true">
                </form-input>
            </div>

            <div class="col-md-5 position-relative float-right">
                <form-input
                    v-model="session_choose.hours_per_day"
                    v-on:input="session_choose.hours_per_day = $event"
                    type="number" label="Hours per Day" :old_msg="old_msg"
                    placeholder="Hours per Day"  name="hours_per_day" disabled="true">
                </form-input>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-5 position-relative float-left">
                <form-select
                    select-value="334" change="true"
                    v-on:on-change="test" label="Currency"
                    name="currency" :options-data="coins"
                    oprion-key="id" view-text="name" :old_msg="old_msg"
                    is-text-json="true"  :lang="lang" disabled="true">
                </form-select>
            </div>

            <div class="col-md-5 position-relative float-right">
                <form-input
                    v-model="total_hours"
                    v-on:input="total_hours = $event"
                    type="number" label="Total Hours" :old_msg="old_msg"
                    placeholder="Total Hours" name="total_hours" :readonly="true">
                </form-input>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-5 position-relative float-left">
                <form-input
                    v-model="session_choose.carts_count" v-on:input="session_choose.carts_count = $event"
                    type="number" label="Trainees No." placeholder="Trainees No."
                    name="trainees_no" :old_msg="old_msg">
                </form-input>
            </div>

            <div class="col-md-5 position-relative float-right">
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
