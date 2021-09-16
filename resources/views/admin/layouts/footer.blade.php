<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    Made with <i class="fas fa-heart"></i> by IT Department
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; <?php echo date('Y') ?> <a href="{{CustomRoute('admin.home')}}">{{__('education.app_title')}}</a>.</strong> All rights reserved.
</footer>

@stack('vue')
@stack('scripts')