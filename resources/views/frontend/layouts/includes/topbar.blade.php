<nav class="navbar navbar-top fixed-top navbar-slim justify-content-between navbar-expand-lg" id="navbarComboSlim" data-navbar-top="combo" data-move-target="#navbarVerticalNav" style="display:none;">
  <div class="navbar-logo">
     <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
     <a class="navbar-brand navbar-brand" href="{{ url('dashboard') }}">The Beauty <span class="text-body-highlight d-none d-sm-inline">Xtenso</span></a>
  </div>
  
  <ul class="navbar-nav navbar-nav-icons flex-row">
     <li class="nav-item">
        <div class="theme-control-toggle fa-ion-wait pe-2 theme-control-toggle-slim"><input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox" data-theme-control="phoenixTheme" value="dark" /><label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon me-1 d-none d-sm-block" data-feather="moon"></span><span class="fs-9 fw-bold">Dark</span></label><label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon me-1 d-none d-sm-block" data-feather="sun"></span><span class="fs-9 fw-bold">Light</span></label></div>
     </li>
     <li class="nav-item dropdown">
        <a class="nav-link" id="navbarDropdownNotification" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"><span data-feather="bell" style="height:12px;width:12px;"></span></a>
        <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
           <div class="card position-relative border-0">
              <div class="card-header p-2">
                 <div class="d-flex justify-content-between">
                    <h5 class="text-body-emphasis mb-0">Notificatons</h5>
                    <button class="btn btn-link p-0 fs-9 fw-normal" type="button">Mark all as read</button>
                 </div>
              </div>
              <div class="card-body p-0">
                 <div class="scrollbar-overlay" style="height: 27rem;">
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative read border-bottom">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset('')}}assets/img/team/40x40/30.webp" alt="" /></div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">10m</span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3">
                                <div class="avatar-name rounded-circle"><span>J</span></div>
                             </div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Jane Foster</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>📅</span>Created an event.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">20m</span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="{{ asset('')}}assets/img/team/40x40/avatar.webp" alt="" /></div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">1h</span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset('')}}assets/img/team/40x40/57.webp" alt="" /></div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Kiera Anderson</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset('')}}assets/img/team/40x40/59.webp" alt="" /></div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Herman Carter</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👤</span>Tagged you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                    <div class="px-2 px-sm-3 py-3 notification-card position-relative read ">
                       <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                             <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset('')}}assets/img/team/40x40/58.webp" alt="" /></div>
                             <div class="flex-1 me-sm-3">
                                <h4 class="fs-9 text-body-emphasis">Benjamin Button</h4>
                                <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
                             </div>
                          </div>
                          <div class="d-none d-sm-block">
                             <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                             <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="card-footer p-0 border-top border-translucent border-0">
                 <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85"><a class="fw-bolder" href="{{ asset('')}}pages/notifications.html">Notification history</a></div>
              </div>
           </div>
        </div>
     </li>
    
     <li class="nav-item dropdown">
        <a class="nav-link lh-1 pe-0 white-space-nowrap" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">{{ Auth::user()->name }} <span class="fa-solid fa-chevron-down fs-10"></span></a>
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
           <div class="card position-relative border-0">
              <div class="card-body p-0">
                 <div class="text-center pt-4 pb-3">
                    <div class="avatar avatar-xl ">
                       <img class="rounded-circle " src="{{ asset('assets/img/icons/logo.png') }}" alt="" />
                    </div>
                    <h6 class="mt-2 text-body-emphasis">{{ Auth::user()->name }}</h6>
                 </div>
              </div>
              <div class="overflow-auto scrollbar" >
                 <ul class="nav d-flex flex-column mb-2 pb-1">
                    <x-dropdown-link :href="route('profile.edit')">
                       <span class="me-2 text-body" data-feather="user"></span>{{ __('Profile') }}
                    </x-dropdown-link>
                    {{-- <x-dropdown-link :href="route('profile.edit')">
                       <span class="me-2 text-body" data-feather="lock"></span>{{ __('Posts & Activity') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('profile.edit')">
                       <span class="me-2 text-body" data-feather="settings"></span>{{ __('Settings & Privacy') }}
                    </x-dropdown-link> --}}
                 </ul>
              </div>
              <div class="">
                 <hr />
                 <div class="px-3">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-logout-link-button :href="route('logout')"
                       onclick="event.preventDefault();
                       this.closest('form').submit();">
                       {{ __('Log Out') }}
                    </x-logout-link-button>
                 </div>
                 <div class="my-2 text-center fw-bold fs-10 text-body-quaternary">
                    <a class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a class="text-body-quaternary ms-1" href="#!">Cookies</a>
                 </div>
              </div>
           </div>
        </div>
     </li>
  </ul>
</nav>
<script>
  var navbarTopShape = window.config.config.phoenixNavbarTopShape
  var navbarPosition = window.config.config.phoenixNavbarPosition;
  var body = document.querySelector('body');
  var navbarDefault = document.querySelector('#navbarDefault');
  var navbarTop = document.querySelector('#navbarTop');
  var navbarSlim = document.querySelector('#navbarSlim');
  var navbarTopSlimNew = document.querySelector('#navbarTopSlimNew');
  var navbarCombo = document.querySelector('#navbarCombo');
  var navbarComboSlim = document.querySelector('#navbarComboSlim');
  var navbarTopSlimNew = document.querySelector('#navbarTopSlimNew');
  var documentElement = document.documentElement;
  var navbarVertical = document.querySelector('.navbar-vertical');
  
  navbarComboSlim.removeAttribute('style');
  navbarVertical.removeAttribute('style');
  documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');;
  documentElement.setAttribute('data-navigation-type', 'combo');
</script>
@include('partials.modal')
<script>
//   var navbarTopShape = window.config.config.phoenixNavbarTopShape;
//   var navbarPosition = window.config.config.phoenixNavbarPosition;
//   var body = document.querySelector('body');
//   var navbarDefault = document.querySelector('#navbarDefault');
//   var navbarTop = document.querySelector('#navbarTop');
//   var topNavSlim = document.querySelector('#topNavSlim');
//   var navbarTopSlim = document.querySelector('#navbarTopSlim');
//   var navbarCombo = document.querySelector('#navbarCombo');
//   var navbarComboSlim = document.querySelector('#navbarComboSlim');
//   var dualNav = document.querySelector('#dualNav');
  
//   var documentElement = document.documentElement;
//   var navbarVertical = document.querySelector('.navbar-vertical');
  
//   if (navbarPosition === 'dual-nav') {
//    topNavSlim.remove();
//     navbarTop.remove();
//     navbarVertical.remove();
//     navbarTopSlim.remove();
//     navbarCombo.remove();
//     navbarComboSlim.remove();
//     navbarDefault.remove();
//     dualNav.removeAttribute('style');
//     document.documentElement.setAttribute('data-navigation-type', 'dual');
  
//   } else if (navbarTopShape === 'slim' && navbarPosition === 'vertical') {
//     navbarDefault.remove();
//     navbarTop.remove();
//     navbarTopSlim.remove();
//     navbarCombo.remove();
//     navbarComboSlim.remove();
//     topNavSlim.style.display = 'block';
//     navbarVertical.style.display = 'inline-block';
//     document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');
  
//   } else if (navbarTopShape === 'slim' && navbarPosition === 'horizontal') {
//     navbarDefault.remove();
//     navbarVertical.remove();
//     navbarTop.remove();
//    //  topNavSlim.remove();
//     navbarCombo.remove();
//     navbarComboSlim.remove();
//     navbarTopSlim.removeAttribute('style');
//     document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');
//   } else if (navbarTopShape === 'slim' && navbarPosition === 'combo') {
//     navbarDefault.remove();
//     navbarTop.remove();
//    //  topNavSlim.remove();
//     navbarCombo.remove();
//     navbarTopSlim.remove();
//     navbarComboSlim.removeAttribute('style');
//     navbarVertical.removeAttribute('style');
//     document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');
//   } else if (navbarTopShape === 'default' && navbarPosition === 'horizontal') {
//     navbarDefault.remove();
//    //  topNavSlim.remove();
//     navbarVertical.remove();
//     navbarTopSlim.remove();
//     navbarCombo.remove();
//     navbarComboSlim.remove();
//     navbarTop.removeAttribute('style');
//     document.documentElement.setAttribute('data-navigation-type', 'horizontal');
//   } else if (navbarTopShape === 'default' && navbarPosition === 'combo') {
//    //  topNavSlim.remove();
//     navbarTop.remove();
//     navbarTopSlim.remove();
//     navbarDefault.remove();
//     navbarComboSlim.remove();
//     navbarCombo.removeAttribute('style');
//     navbarVertical.removeAttribute('style');
//     document.documentElement.setAttribute('data-navigation-type', 'combo');
  
  
//   } else {
//    //  topNavSlim.remove();
//     navbarTop.remove();
//     navbarTopSlim.remove();
//     navbarCombo.remove();
//     navbarComboSlim.remove();
//     navbarDefault.removeAttribute('style');
//     navbarVertical.removeAttribute('style');
//   }
  
//   var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
//   var navbarTop = document.querySelector('.navbar-top');
//   if (navbarTopStyle === 'darker') {
//     navbarTop.setAttribute('data-navbar-appearance', 'darker');
//   }
  
//   var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
//   var navbarVertical = document.querySelector('.navbar-vertical');
//   if (navbarVerticalStyle === 'darker') {
//     navbarVertical.setAttribute('data-navbar-appearance', 'darker');
//   }
</script>