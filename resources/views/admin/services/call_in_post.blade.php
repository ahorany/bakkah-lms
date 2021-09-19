@if(isset($eloquent))
<div class="card card-default">
    <div class="card-header"><i class="fas fa-list"></i> List of service</div>
    <div class="card-body">

        <a class="btn btn-primary btn-sm" href="{{route('admin.services.index', ['master_id'=>$eloquent->id])}}"><i class="fas fa-chalkboard"></i> Do you Know</a>
        <a class="btn btn-primary btn-sm" href="{{route('admin.service_archives.index', ['master_id'=>$eloquent->id])}}"><i class="fas fa-list"></i> Achieve Results</a>
        {{-- <ul class="list-unstyled">
            <li>
            </li>
        </ul> --}}
    </div>
</div>
@endif
