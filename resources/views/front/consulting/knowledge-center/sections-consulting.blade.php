<div class="consulting-sidebar-box mb-4 box-shadow">
    <h2> {{__('consulting.Our Services')}} </h2>
    <hr>
    <ul class="list-unstyled">
        @foreach($constants as $constant)
            <li><a href="{{CustomRoute('consulting.static.knowledge-center', ['post_type'=>$constant->slug])}}">{{$constant->trans_name}}</a></li>
        @endforeach
    </ul>
</div> <!-- /.consulting-sidebar-box -->
