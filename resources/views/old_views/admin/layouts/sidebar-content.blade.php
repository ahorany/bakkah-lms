@if(auth()->check())
    <!--Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <!--<h5>Title</h5>
            <p>Sidebar content</p>
            -->
            <div>
                @include(env('Dashboard').'.layouts.login-li')
            </div>

        </div>
    </aside>
    <!-- /.control-sidebar-->
@endif
