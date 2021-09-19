<div class="col-md-6">
    <div class="form-group">
        <label>{{$lable}}</label><br>
        <input type="{{$type??'text'}}" name="{{$name}}" value="{{old($name)??$value}}" class="form-control">
    </div>
</div>
