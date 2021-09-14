<header class="education-header sticky-top {{isset($navbar_campaign)?'has-advertisment':''}} header-shadow">

    @include(FRONT.'.education.layouts.advertisement-popup')
    @include(FRONT.'.education.layouts.advertisement_bar')

    @include(FRONT.'.education.layouts.navbar')
    {{-- @include(FRONT.'.education.layouts.navbar-user-new') --}}

    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
      </div>

</header>
