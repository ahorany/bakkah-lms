<div class="col-md-12">
    <div class="form-group">
        <label>{{__('admin.training_option_id')}}</label>
        <select class="form-control" v-model="training_option_id" @change="getSessions($event)">
            <option value="-1">{{__('admin.choose_value')}}</option>
            <option v-for="option in options" :key="option.id" :value="option.id" :type_id="option.constant_id" :slug="option.slug">
                @{{option.course_title}} | @{{option.option_name}}
            </option>
        </select>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label>{{__('admin.session_id')}}</label>
        <select class="form-control" v-model="session_id">
            <option value="-1">{{__('admin.choose_value')}}</option>
            <option v-for="option in sessions" :key="option.id" :value="option.id" v-html="option.published_session">
            </option>
        </select>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group digit maxlength9">
        <label>{{__('admin.percentage')}}</label>
        <input type="number" class="form-control" v-model="value">
    </div>
</div>

<div class="row" v-show="slug=='custom-date'">
    <div class="col-md-6">
        <div class="form-group ml-2 mr-2">
            <label>{{__('admin.start_date')}}</label>
            <input type="datetime" class="form-control" v-on:blur="DateFrom" v-model="date_from" data-date="datetime" autocomplete="off">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group ml-2 mr-2">
            <label>{{__('admin.end_date')}}</label>
            <input type="datetime" class="form-control" v-on:blur="DateTo" v-model="date_to" data-date="datetime" autocomplete="off">
        </div>
    </div>
</div>
