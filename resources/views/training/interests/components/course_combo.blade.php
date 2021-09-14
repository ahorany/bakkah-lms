<div class="col-md-6">
    <div class="form-group">
        <label>{{__('admin.course_name')}}</label>
        <select class="form-control" name="course_id" @change="courseChange($event.target.value)">
            <option value="-1">{{__('admin.choose_value')}}</option>
            @foreach($all_courses as $all_course)
                <option value="{{$all_course->id}}" {{(request()->course_id==$all_course->id)?'selected="selected"':''}}>{{$all_course->trans_title}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>{{__('admin.session_id')}}</label>
        <select class="form-control" name="session_id">
            <option value="-1">@{{ session_choose_value }}</option>
            <option v-for="(list, index) in sessions" :value="list.id">@{{list.json_title}}</option>
        </select>
    </div>
</div>

