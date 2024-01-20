<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{ asset('/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <title>Ticket Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/fonts/Linearicons/Font/demo-files/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/owl-carousel/assets/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/apexcharts-bundle/dist/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/css/style.css') }}">
</head>

<body>
    <header class="header--mobile">
        <div class="header__left">
            <button class="ps-drawer-toggle"><i class="icon icon-menu"></i></button><img src="{{ asset('/') }}"
                alt="">
        </div>
        <div class="header__center"><a class="ps-logo" href="{{ url('#') }}"><img
                    src="{{ asset('/img/logo.png') }}" alt=""></a></div>
        <div class="header__right"><a class="header__site-link" href="{{ url('#') }}"><i
                    class="icon-exit-right"></i></a></div>
    </header>
    <aside class="ps-drawer--mobile">
        <div class="ps-drawer__header">
            <h4> Menu</h4>
            <button class="ps-drawer__close"><i class="icon icon-cross"></i></button>
        </div>
        <div class="ps-drawer__content">
            <ul class="menu">
                <li><a class="active" href="{{ route('dashboard.guest') }}"><i class="icon-home"></i>Dashboard</a></li>
                <li><a href="{{ route('home') }}"><i class="icon-database"></i>Home</a>
                </li>
                <li><a href="{{ route('admin.update-competition') }}"><i class="icon-bag2"></i>Order</a></li>
                {{-- <li><a href="{{ route('admin.seat.index') }}"><i class="icon-papers"></i>Seat</a></li> --}}
            </ul>
        </div>
    </aside>
    <div class="ps-site-overlay"></div>
    <main class="ps-main">
        <div class="ps-main__sidebar">
            <div class="ps-sidebar">
                <div class="ps-sidebar__top">
                    <div class="ps-block--user-wellcome">
                        <div class="ps-block__left"></div>
                        <div class="ps-block__right">
                            <p>Hello,<a href="{{ route('dashboard.guest') }}">{{ Auth::user()->name }}</a></p>
                        </div>
                        <div class="ps-block__action">
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button type="submit" class="icon-exit">Logout</button>
                            </form>
                        </div>
                    </div>
                    <div class="ps-block--earning-count"><small>Earning</small>
                    </div>
                </div>
                <div class="ps-sidebar__content">
                    <div class="ps-sidebar__center">
                        <ul class="menu">
                            <li><a class="active" href="{{ route('dashboard.guest') }}"><i
                                        class="icon-home"></i>Dashboard</a></li>
                            <li><a href="{{ route('home') }}"><i class="icon-database"></i>Home</a>
                            </li>
                            <li><a href="{{ route('admin.update-competition') }}"><i class="icon-bag2"></i>Order</a>
                            </li>
                            {{-- <li><a href="{{ route('admin.seat.index') }}"><i class="icon-papers"></i>Seat</a></li> --}}
                        </ul>
                    </div>
                    <div class="ps-sidebar__footer">
                    </div>
                </div>
            </div>
        </div>
        @yield('content')
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="{{ asset('/admin/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/jquery.matchHeight-min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/apexcharts-bundle/dist/apexcharts.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('/admin/js/chart.js') }}"></script>
    <!-- custom code-->
    <script src="{{ asset('/admin/js/main.js') }}"></script>
</body>
