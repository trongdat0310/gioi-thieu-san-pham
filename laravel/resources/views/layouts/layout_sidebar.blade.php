<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/default-user.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->user_name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link" id="sidebar_dashboard_main">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Thống kê
                        </p>
                    </a>
                </li>

                <li class="nav-item" id="sidebar_setting_main">
                    <a href="#" class="nav-link" id="sidebar_setting">
                        <i class="nav-icon fa-solid fa-gear"></i>
                        <p>
                            Cài đặt
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('setting.account') }}" class="nav-link" id="sidebar_setting_account">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>Tài khoản</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('setting.role') }}" class="nav-link" id="sidebar_setting_role">
                                <i class="nav-icon fa-solid fa-user-shield"></i>
                                <p>Quyền</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>

    </div>
    <!-- /.sidebar -->
</aside>

<script>
    // Lấy URL hiện tại
    $currentUrl = window.location.pathname;

    //tách các tên trong url
    $currentUrl = $currentUrl.split('/');

    if ($currentUrl.length == 2) {
        if ($currentUrl[1] == '') {
            //thêm menu-is-opening menu-open
            $siderbarMain = $("#sidebar_dashboard_main")
            $siderbarMain.addClass("active")
        }
        //thêm menu-is-opening menu-open
        $siderbarMain = $("#sidebar_" + $currentUrl[1] + "_main")
        $siderbarMain.addClass("active")

        //thêm class active cho thẻ a
        $siderbarMain = $("#sidebar_" + $currentUrl[1])
        $siderbarMain.addClass("active")
    } else {
        //thêm menu-is-opening menu-open
        $siderbarMain = $("#sidebar_" + $currentUrl[1] + "_main")
        $siderbarMain.addClass("menu-is-opening menu-open")

        //thêm class active cho thẻ a
        $siderbarMain = $("#sidebar_" + $currentUrl[1])
        $siderbarMain.addClass("active")

        //thêm class active cho thẻ a hiện tại
        $siderbarMain = $("#sidebar_" + $currentUrl[1] + "_" + $currentUrl[2])
        $siderbarMain.addClass("active")
    }
</script>