<style>
.sup_adn {display: flex;}  
.company-name{
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    font-weight: 600;
    color: #344767;
    list-style: none;
}

.company-name i{
    font-size: 22px;
    color: #0d6efd;
}

/* Navbar */
.navbar{
    background:#fff;
    padding:12px 20px;
    border-radius:16px;
    box-shadow:0 8px 30px rgba(15,23,42,.08);
}

/* Menu */
.navbar-nav .nav-link{
    display:flex;
    align-items:center;
    gap:8px;
    padding:10px 18px;
    border-radius:12px;
    font-weight:500;
    color:#475569;
    transition:.3s;
}

.navbar-nav .nav-link:hover{
    background:#f8fafc;
    color:#2563eb;
}

/* Company Badge */

.company-badge{
    display:flex;
    align-items:center;
    gap:12px;
    padding:8px 18px;
    background:#fff;
    border:1px solid #edf2f7;
    border-radius:14px;
    box-shadow:0 5px 18px rgba(0,0,0,.06);
}

.company-badge i{
    width:42px;
    height:42px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    color:#fff;
}

.company-badge small{
    display:block;
    color:#94a3b8;
    font-size:11px;
}

.company-badge strong{
    color:#1e293b;
    font-size:15px;
}

/* User */

.user-dropdown>.nav-link{
    padding:6px 12px;
    border-radius:14px;
}

.user-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:linear-gradient(135deg,#0ea5e9,#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    box-shadow:0 5px 15px rgba(37,99,235,.25);
}

.user-dropdown .dropdown-menu{
    margin-top:15px;
    border:none;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,.12);
}
.top_w {
    padding: 10px;    
    /*width: 170px;*/
}
.top_w img {
    width: 60px;
}

main.main-wrapper {
    margin-top: 77px;
}
table#example2 {
    border: 1px solid #33333326;
}

table#example2 th {
    border-bottom: 1px solid #3333331f;
}

table#example2 td {
    border-bottom: 1px solid #3333331f;
}
</style>
<div class="primary-menu">
    <nav class="navbar navbar-expand-xl align-items-center">
        <div class="offcanvas offcanvas-start w-260" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom h-70">
                <div class="d-flex align-items-center gap-2">
                    {{-- <div class="">
                        <img src="assets/images/logo-icon.png" class="logo-icon" width="45" alt="logo icon">
                    </div> --}}
                    <div class="">
                        <h4 class="logo-text">{{ config('app.name', 'Laravel') }}</h4>
                    </div>
                </div>
                <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
                    <i class="material-icons-outlined">close</i>
                </a>
            </div>
            <div class="offcanvas-body p-0">
                  @php
                      $userCompany = Auth::user()?->company();
                      $companyName = $userCompany->name ?? config('app.name', 'Laravel');
                  @endphp
                  <div class="logo top_w">
                        @if ($userCompany && $userCompany->hasMedia('system-user-image'))
                            <img src="{{ $userCompany->getFirstMediaUrl('system-user-image') }}" alt="{{ $companyName }}">
                        @else
                            <i class="material-icons-outlined">business</i>
                        @endif
                        <span>{{ $companyName }}</span>
                        </div>
                <ul class="navbar-nav align-items-center flex-grow-1  d-flex justify-content-center">
                    @can('Dashboard Show')
                    <li class="nav-item" style="margin-right:10px;">
                        <a class="nav-link {{ request()->segment(1) == 'dashboard' ? 'active' : '' }} {{-- dropdown-toggle dropdown-toggle-nocaret --}}" href="{{ route('dashboard') }}">
                            <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                            <div class="menu-title d-flex align-items-center">Dashboard</div>
                            {{-- <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div> --}}
                        </a>
                        {{-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.html"><i class='material-icons-outlined'>insights</i>Analysis</a></li>
                            <li><a class="dropdown-item" href="index2.html"><i class='material-icons-outlined'>shopping_cart</i>eCommerce</a></li>
                        </ul> --}}
                    </li>
                    @endcan

                    @can('Tracking Show')
                    <li class="nav-item" style="margin-right:10px;">
                        <a class="nav-link {{ request()->segment(2) == 'tracking' ? 'active' : '' }} {{-- dropdown-toggle dropdown-toggle-nocaret --}}" href="{{ route('tracking.index') }}">
                            <div class="parent-icon"><i class="material-icons-outlined">my_location</i></div>
                            <div class="menu-title d-flex align-items-center">Tracking</div>
                            {{-- <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div> --}}
                        </a>
                        {{-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.html"><i class='material-icons-outlined'>insights</i>Analysis</a></li>
                            <li><a class="dropdown-item" href="index2.html"><i class='material-icons-outlined'>shopping_cart</i>eCommerce</a></li>
                        </ul> --}}
                    </li>
                    @endcan

                    @can('SOS Alert Show')
                    <li class="nav-item" style="margin-right:10px;">
                        <a class="nav-link {{ request()->segment(2) == 'sos_alerts' ? 'active' : '' }} {{-- dropdown-toggle dropdown-toggle-nocaret --}}" href="{{ route('sos_alert.index') }}">
                            <div class="parent-icon"><i class="material-icons-outlined">notification_important</i></div>
                            <div class="menu-title d-flex align-items-center">SOS Alerts</div>
                            {{-- <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div> --}}
                        </a>
                        {{-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.html"><i class='material-icons-outlined'>insights</i>Analysis</a></li>
                            <li><a class="dropdown-item" href="index2.html"><i class='material-icons-outlined'>shopping_cart</i>eCommerce</a></li>
                        </ul> --}}
                    </li>
                    @endcan

                    {{-- @canany(['Role Show', 'Permission Show'])
                        <li class="nav-item dropdown">
                          
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="parent-icon"><i class='material-icons-outlined'>manage_accounts</i></div>
                                <div class="menu-title d-flex align-items-center">Settings</div>
                                <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class='material-icons-outlined'>person_outline</i>Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}"><i class='material-icons-outlined'>settings</i>General Settings</a></li>
                                @can('Wallet Show')
                                    <li><a class="dropdown-item" href="{{ route('company.wallet.mine') }}"><i class='material-icons-outlined'>account_balance_wallet</i>My Wallet</a></li>
                                @endcan
                                @can('Role Show')
                                    <li><a class="dropdown-item" href="{{ route('roles') }}"><i class='material-icons-outlined'>supervisor_account</i>Roles</a></li>
                                @endcan
                                @can('Permission Show')
                                    <li><a class="dropdown-item" href="{{ route('permission') }}"><i class='material-icons-outlined'>lock</i>Permission</a></li>
                                @endcan
                                @can('Permission Show')
                                    <li><a class="dropdown-item" href="{{ route('sos_alert.index') }}"><i class='material-icons-outlined'>supervisor_account</i>SOS alerts</a></li>
                                @endcan
                                <li class="nav-item ms-auto">
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="material-icons-outlined text-danger">power_settings_new</i> Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endcan --}}
                    {{-- @canany(['Role Show', 'Permission Show'])
                        <li class="nav-item dropdown">
                            <a class="nav-link {{ in_array(request()->segment(2), ['role', 'permission']) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="parent-icon"><i class='material-icons-outlined'>manage_accounts</i></div>
                                <div class="menu-title d-flex align-items-center">Role & Permission</div>
                                <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>
                            </a>
                            <ul class="dropdown-menu">
                                @can('Role Show')
                                    <li><a class="dropdown-item" href="{{ route('roles') }}"><i class='material-icons-outlined'>supervisor_account</i>Roles</a></li>
                                @endcan
                                @can('Permission Show')
                                    <li><a class="dropdown-item" href="{{ route('permission') }}"><i class='material-icons-outlined'>lock</i>Permission</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link {{ in_array(request()->segment(2), ['drivers','companys','branchs','employees','vehicle-types', 'vehicles', 'brands', 'models']) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                            data-bs-toggle="dropdown">
                            <div class="parent-icon"><i class='material-icons-outlined'>storage</i></div>
                            <div class="menu-title d-flex align-items-center">Entry</div>
                            <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>
                        </a>
                        <ul class="dropdown-menu">
                            @canany(['System User Show', 'Driver Show', 'User Show'])
                                {{-- <li class="nav-item dropdown"> --}}
                                <li class="nav-item dropend mb-2">
                                    <a class="nav-link {{ in_array(request()->segment(2), ['drivers','companys','branchs','employees']) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                        data-bs-toggle="dropdown">
                                        <div class="parent-icon"><i class='material-icons-outlined'>admin_panel_settings</i></div>
                                        <div class="menu-title d-flex align-items-center">Users</div>
                                        {{-- <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div> --}}
                                    </a>
                                    {{-- <ul class="dropdown-menu"> --}}
                                    <ul class="dropdown-menu submenu">
                                        {{-- @can('System User Show')
                                            <li><a class="dropdown-item" href="{{ route('system-user.index') }}"><i
                                                        class='material-icons-outlined'>supervised_user_circle</i>System User</a></li>
                                        @endcan --}}
                                
                                        @can('Driver Show')
                                            <li><a class="dropdown-item" href="{{ route('driver.index') }}"><i
                                                        class='material-icons-outlined'>drive_eta</i>Driver</a></li>
                                        @endcan

                                        @can('Company Show')
                                            <li><a class="dropdown-item" href="{{ route('company.index') }}"><i
                                                        class='material-icons-outlined'>business</i>Company</a></li>
                                        @endcan

                                        @can('Branch Show')
                                            <li><a class="dropdown-item" href="{{ route('branch.index') }}"><i
                                                        class='material-icons-outlined'>account_tree</i>Branch</a></li>
                                        @endcan

                                        @can('Employee Show')
                                            <li><a class="dropdown-item" href="{{ route('employee.index') }}"><i
                                                        class='material-icons-outlined'>groups</i>Employees</a></li>
                                        @endcan

                                        {{-- @can('User Show')
                                            <li><a class="dropdown-item" href="{{ route('user.index') }}"><i
                                                        class='material-icons-outlined'>person</i>User</a></li>
                                        @endcan --}}
                                    </ul>
                                </li>
                            @endcan
                            @canany([
                                'Bus Stop Show',
                                'Bus Stop Create',
                                'Route Show',
                                'Route Create',
                                'Vehicle Type Show',
                                'Vehicle Type Create',
                                'Vehicle Layout Show',
                                'Vehicle Layout Create',
                                'Vehicle Show',
                                'Vehicle
                                Create',
                                ])
                                {{-- <li class="nav-item dropdown"> --}}
                                <li class="nav-item dropend mb-2">
                                    <a class="nav-link {{ in_array(request()->segment(2), ['vehicle-types', 'vehicles', 'brands', 'models']) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                        data-bs-toggle="dropdown">
                                        <div class="parent-icon"><i class='material-icons-outlined'>storage</i></div>
                                        <div class="menu-title d-flex align-items-center">Master</div>
                                        {{-- <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div> --}}
                                    </a>
                                    {{-- <ul class="dropdown-menu"> --}}
                                    <ul class="dropdown-menu submenu">
                                
                                        @canany(['Vehicle Type Show', 'Vehicle Type Create'])
                                            <li class="nav-item dropend">
                                                {{-- <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"
                                                    href="javascript:;"><i class="material-icons-outlined">local_shipping</i>Vehicle
                                                    Types</a> --}}
                                                <ul class="dropdown-menu submenu">
                                                    @can('Vehicle Type Show')
                                                        <li><a class="dropdown-item" href="{{ route('vehicle-type.index') }}"><i
                                                                    class="material-icons-outlined">directions_car</i>List Vehicle
                                                                Types</a>
                                                        </li>
                                                    @endcan
                                                    @can('Vehicle Type Create')
                                                        <li><a class="dropdown-item" href="{{ route('vehicle-type.create') }}"><i
                                                                    class="material-icons-outlined">add_circle</i>Add Vehicle Types</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                
                                        @canany(['Vehicle Show', 'Vehicle Create'])
                                            <li class="nav-item dropend">
                                                <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"
                                                    href="javascript:;"><i class="material-icons-outlined">directions_bus</i>Vehicles
                                                </a>
                                                <ul class="dropdown-menu submenu">
                                                    @can('Vehicle Show')
                                                        <li><a class="dropdown-item" href="{{ route('vehicle.index') }}"><i
                                                                    class="material-icons-outlined">format_list_bulleted</i>List of
                                                                Vehicles </a></li>
                                                    @endcan
                                                    @can('Vehicle Create')
                                                        <li><a class="dropdown-item" href="{{ route('vehicle.create') }}"><i
                                                                    class="material-icons-outlined">add_circle</i>Add New Vehicle</a></li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                        @canany(['Brand Show', 'Brand Create'])
                                            <li class="nav-item dropend">
                                                {{-- <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"
                                                    href="javascript:;"><i
                                                        class="material-icons-outlined">branding_watermark</i>Brand</a> --}}
                                                {{-- <ul class="dropdown-menu submenu">
                                                    @can('Brand Show')
                                                        <li><a class="dropdown-item" href="{{ route('brand.index') }}"><i
                                                                    class="material-icons-outlined">branding_watermark</i>List Brands
                                                            </a></li>
                                                    @endcan
                                                    @can('Brand Create')
                                                        <li><a class="dropdown-item" href="{{ route('brand.create') }}"><i
                                                                    class="material-icons-outlined">add_circle</i>Add Brand</a>
                                                        </li>
                                                    @endcan
                                                </ul> --}}
                                            </li>
                                        @endcan
                                        @canany(['Model Show', 'Model Create'])
                                            {{-- <li class="nav-item dropend">
                                                <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"
                                                    href="javascript:;"><i class="material-icons-outlined">style</i>Model</a>
                                                <ul class="dropdown-menu submenu">
                                                    @can('Model Show')
                                                        <li><a class="dropdown-item" href="{{ route('model.index') }}"><i
                                                                    class="material-icons-outlined">style</i>List Models
                                                            </a></li>
                                                    @endcan
                                                    @can('Model Create')
                                                        <li><a class="dropdown-item" href="{{ route('model.create') }}"><i
                                                                    class="material-icons-outlined">add_circle</i>Add Model</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li> --}}
                                        @endcan
                                        @canany(['Color Show', 'Color Create'])
                                            <!--<li class="nav-item dropend">-->
                                            <!--    <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"-->
                                            <!--        href="javascript:;"><i class="material-icons-outlined">palette</i>Color</a>-->
                                            <!--    <ul class="dropdown-menu submenu">-->
                                            <!--        @can('Color Show')-->
                                            <!--            <li><a class="dropdown-item" href="{{ route('color.index') }}"><i-->
                                            <!--                        class="material-icons-outlined">palette</i>List Colors-->
                                            <!--                </a></li>-->
                                            <!--        @endcan-->
                                            <!--        @can('Color Create')-->
                                            <!--            <li><a class="dropdown-item" href="{{ route('color.create') }}"><i-->
                                            <!--                        class="material-icons-outlined">add_circle</i>Add Color</a>-->
                                            <!--            </li>-->
                                            <!--        @endcan-->
                                            <!--    </ul>-->
                                            <!--</li>-->
                                        @endcan
                                    
                                    


                                    </ul>
                                </li>
                            @endcan
                            @can('Plan Show')
                            <li class="nav-item">
                                <a class="nav-link {{ in_array(request()->segment(2), ['plans']) ? 'active' : '' }}" href="{{ route('plans.index') }}">
                                    <div class="parent-icon"><i class='material-icons-outlined'>layers</i></div>
                                    <div class="menu-title d-flex align-items-center">Plans</div>
                                </a>
                            </li>
                            @endcan
                            @can('Shop Show')
                            <li class="nav-item mb-2 mt-2">
                        <a class="nav-link {{ request()->segment(2) == 'shop' ? 'active' : '' }} {{-- dropdown-toggle dropdown-toggle-nocaret --}}" href="{{ route('shop.index') }}">
                            <div class="parent-icon"><i class="material-icons-outlined">storefront</i></div>
                            <div class="menu-title d-flex align-items-center">Shops</div>
                        </a>
                    </li>
                            @endcan
                        </ul>
                    </li>

                    @canany(['Shop Show', 'Shop Create', 'Delivery Schedule Show', 'Delivery Schedule Create'])
                        <!--<li class="nav-item dropdown">-->
                        <!--    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"-->
                        <!--        data-bs-toggle="dropdown">-->
                        <!--        <div class="parent-icon"><i class="material-icons-outlined">local_shipping</i></div>-->
                        <!--        <div class="menu-title d-flex align-items-center">Delivery</div>-->
                        <!--        <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>-->
                        <!--    </a>-->
                        <!--    <ul class="dropdown-menu">-->
                        <!--        @canany(['Shop Show', 'Shop Create'])-->
                        <!--            <li class="nav-item dropend">-->
                        <!--                <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"-->
                        <!--                    href="javascript:;"><i class="material-icons-outlined">storefront</i>Shop</a>-->
                        <!--                <ul class="dropdown-menu submenu">-->
                        <!--                    @can('Shop Show')-->
                        <!--                        <li><a class="dropdown-item" href="{{ route('shop.index') }}">-->
                        <!--                                <i class='material-icons-outlined'>format_list_bulleted</i>List of-->
                        <!--                                Shop's</a>-->
                        <!--                        </li>-->
                        <!--                    @endcan-->
                        <!--                    @can('Shop Create')-->
                        <!--                        <li><a class="dropdown-item" href="{{ route('shop.create') }}">-->
                        <!--                                <i class='material-icons-outlined'>add_circle</i>Add New Shop</a>-->
                        <!--                        </li>-->
                        <!--                    @endcan-->
                        <!--                </ul>-->
                        <!--            </li>-->

                        <!--            <li class="nav-item dropend">-->
                        <!--                <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"-->
                        <!--                    href="javascript:;"><i class="material-icons-outlined">date_range</i>Delivery-->
                        <!--                    Schedule</a>-->
                        <!--                <ul class="dropdown-menu submenu">-->
                        <!--                    @can('Delivery Schedule Show')-->
                        <!--                        <li><a class="dropdown-item" href="{{ route('delivery-schedule.index') }}">-->
                        <!--                                <i class='material-icons-outlined'>format_list_bulleted</i>All-->
                        <!--                                Schedules</a>-->
                        <!--                        </li>-->
                        <!--                    @endcan-->
                        <!--                    @can('Delivery Schedule Create')-->
                        <!--                        <li><a class="dropdown-item" href="{{ route('delivery-schedule.create') }}">-->
                        <!--                                <i class='material-icons-outlined'>add_circle</i>Add New Schedule</a>-->
                        <!--                        </li>-->
                        <!--                    @endcan-->
                        <!--                </ul>-->
                        <!--            </li>-->
                        <!--        @endcan-->
                        <!--    </ul>-->
                        <!--</li>-->
                    @endcan
                   
                    
                    <li class="nav-item" style="margin-right:10px;">
                        <a class="nav-link {{ request()->segment(2) == 'delivery-schedule' ? 'active' : '' }} {{-- dropdown-toggle dropdown-toggle-nocaret --}}" href="{{ route('delivery-schedule.index') }}" target="_blank">
                            <div class="parent-icon"><i class="material-icons-outlined">local_shipping</i></div>
                            <div class="menu-title d-flex align-items-center">Tasks</div>
                        </a>
                    </li>
                    
                    @canany([
                        'Trip Summary Report Show',
                        'Route History Report Show',
                        'Run Idle Report Show',
                        'Distance Report Show',
                        'Geo Fence Report Show',
                        'Overstay Report Show',
                        'Attendance Report Show',
                        'Login Logout Report Show',
                        'Login Time Report Show',
                        'Emergency SOS Report Show',
                        'Task Report Show',
                        'Dispatch Details Report Show'
                    ])
                    <li class="nav-item dropdown">
                        <a class="nav-link {{ in_array(request()->segment(2), [
                            'reports', 'trip-summary', 'route-history', 'run-idle',
                            'distance', 'geofence', 'overstay', 'attendance',
                            'login-logout', 'login-time', 'emergency-sos', 'task-report', 'dispatch-details'
                        ]) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                            <div class="parent-icon"><i class='material-icons-outlined'>bar_chart</i></div>
                            <div class="menu-title d-flex align-items-center">Reports</div>
                            <div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>
                        </a>
                        <ul class="dropdown-menu">
                            @can('Dispatch Details Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.dispatchDetails') }}">
                                    <i class='material-icons-outlined'>local_shipping</i>Dispatch Details
                                </a></li>
                            @endcan

                            @can('Task Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.taskReport') }}">
                                    <i class='material-icons-outlined'>fact_check</i>Task Report
                                </a></li>
                            @endcan

                            @can('Trip Summary Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.tripSummary') }}">
                                    <i class='material-icons-outlined'>map</i>Trip Summary
                                </a></li>
                            @endcan

                            @can('Route History Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.routeHistory') }}">
                                    <i class='material-icons-outlined'>timeline</i>Route History
                                </a></li>
                            @endcan

                            @can('Run Idle Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.runIdle') }}">
                                    <i class='material-icons-outlined'>speed</i>Run & Idle
                                </a></li>
                            @endcan

                            @can('Distance Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.distance') }}">
                                    <i class='material-icons-outlined'>alt_route</i>Distance
                                </a></li>
                            @endcan

                            @can('Geo Fence Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.geofence') }}">
                                    <i class='material-icons-outlined'>my_location</i>Geo-fence
                                </a></li>
                            @endcan

                            @can('Overstay Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.overstay') }}">
                                    <i class='material-icons-outlined'>schedule</i>Overstay
                                </a></li>
                            @endcan

                            <li><hr class="dropdown-divider"></li>

                            <li class="dropdown-header text-muted px-3 small">Driver Behaviour & Safety</li>

                            @can('Attendance Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.attendance') }}">
                                    <i class='material-icons-outlined'>event_available</i>Monthly Attendance
                                </a></li>
                            @endcan

                            @can('Login Logout Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.loginLogout') }}">
                                    <i class='material-icons-outlined'>login</i>Login/Logout
                                </a></li>
                            @endcan

                            @can('Login Time Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.loginTime') }}">
                                    <i class='material-icons-outlined'>access_time</i>Login Time
                                </a></li>
                            @endcan

                            @can('Emergency SOS Report Show')
                                <li><a class="dropdown-item" href="{{ route('reports.emergencySos') }}">
                                    <i class='material-icons-outlined'>emergency</i>Emergency SOS
                                </a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                   


                    <!--<li class="nav-item ms-auto">-->
                    <!--    <form method="POST" action="{{ route('logout') }}" style="display: inline;">-->
                    <!--        @csrf-->
                    <!--        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);"-->
                    <!--            onclick="event.preventDefault(); this.closest('form').submit();">-->
                    <!--            <i class="material-icons-outlined text-danger">power_settings_new</i> <!-- Logout -->
                    <!--        </a>-->
                    <!--    </form>-->
                    <!--</li>-->
                    <!--<li class="nav-item dropdown ms-auto sup_adn">-->
                    <!--       <span>Super Admin</span>-->
                    <!--    <a class="nav-link {{ in_array(request()->segment(2), ['role', 'permission','profile','settings']) ? 'active' : '' }} dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">-->
                    <!--        <div class="parent-icon"><i class='material-icons-outlined'>manage_accounts</i></div>-->
                            <!--<div class="menu-title d-flex align-items-center"></div>-->
                            <!--<div class="ms-auto dropy-icon"><i class='material-icons-outlined'>expand_more</i></div>-->
                    <!--    </a>-->
                    <!--    <ul class="dropdown-menu" style="left:-100px;">-->
                    <!--        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class='material-icons-outlined'>person_outline</i>Profile</a></li>-->
                    <!--        <li><a class="dropdown-item" href="{{ route('settings') }}"><i class='material-icons-outlined'>settings</i>General Settings</a></li>-->
                    <!--        @can('Role Show')-->
                    <!--            <li><a class="dropdown-item" href="{{ route('roles') }}"><i class='material-icons-outlined'>supervisor_account</i>Roles</a></li>-->
                    <!--        @endcan-->
                    <!--        @can('Permission Show')-->
                    <!--            <li><a class="dropdown-item" href="{{ route('permission') }}"><i class='material-icons-outlined'>lock</i>Permission</a></li>-->
                    <!--        @endcan-->
                    <!--        <li class="nav-item ms-auto">-->
                    <!--            <form method="POST" action="{{ route('logout') }}" style="display: inline;">-->
                    <!--                @csrf-->
                    <!--                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);"-->
                    <!--                    onclick="event.preventDefault(); this.closest('form').submit();">-->
                    <!--                    <i class="material-icons-outlined text-danger">power_settings_new</i> Logout-->
                    <!--                </a>-->
                    <!--            </form>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                  
                </ul>
                
                  <div class="nav-item dropdown user-dropdown ms-3">
    <a class="nav-link dropdown-toggle d-flex align-items-center gap-3"
       href="#"
       data-bs-toggle="dropdown"
       aria-expanded="false">

        <div class="user-avatar">
            <i class="material-icons-outlined">manage_accounts</i>
        </div>

        <div class="user-info d-none d-lg-block">
            <h6 class="mb-0">{{ Auth::user()->name ?? 'Guest' }}</h6>
            <small>{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</small>
        </div>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">

        <li class="px-3 py-2 border-bottom mb-2">
            <h6 class="mb-0 fw-bold">{{ Auth::user()->name ?? 'Guest' }}</h6>
            <small class="text-muted">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</small>
        </li>

        <li>
            <a class="dropdown-item rounded-3" href="{{ route('profile.edit') }}">
                <i class="material-icons-outlined me-2">person_outline</i>
                Profile
            </a>
        </li>

        @can('Settings Show')
        <li>
            <a class="dropdown-item rounded-3" href="{{ route('settings') }}">
                <i class="material-icons-outlined me-2">settings</i>
                General Settings
            </a>
        </li>
        @endcan

        @can('Role Show')
        <li>
            <a class="dropdown-item rounded-3" href="{{ route('roles') }}">
                <i class="material-icons-outlined me-2">supervisor_account</i>
                Roles
            </a>
        </li>
        @endcan

        @can('Permission Show')
        <li>
            <a class="dropdown-item rounded-3" href="{{ route('permission') }}">
                <i class="material-icons-outlined me-2">lock</i>
                Permissions
            </a>
        </li>
        @endcan

        <li><hr class="dropdown-divider"></li>

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="javascript:void(0);"
                   class="dropdown-item rounded-3 text-danger"
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="material-icons-outlined me-2">power_settings_new</i>
                    Logout
                </a>
            </form>
        </li>

    </ul>
</div>
            </div>
        </div>
    </nav>
</div>
