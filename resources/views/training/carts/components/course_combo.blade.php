<div class="col-md-6">
    <div class="form-group">
        <label>{{__('admin.course_name')}}</label>
        <select class="form-control" name="course_id" @change="courseChange($event.target.value)" >
            <option value="-1">{{__('admin.choose_value')}}</option>
            @foreach($all_courses as $all_course)
                <option value="{{$all_course->id}}" {{($all_course->id == request()->course_id)?'selected':''}}>{{$all_course->trans_title}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>{{__('admin.session_id')}}</label>
        <select class="form-control" name="session_id">
            <option value="-1">@{{ session_choose_value }}</option>
            @if(isset( request()->session_id ) && request()->session_id >0)
                <option v-for="(list , index) in sessions" :value="list.id" :selected= "list.id == {{request()->session_id}}"  > @{{list.json_title}}</option>
            @else
                <option v-for="(list , index) in sessions" :value="list.id" > @{{list.json_title}}</option>
            @endif        
        </select>
    </div>
</div>
