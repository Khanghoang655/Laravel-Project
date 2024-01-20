@extends('admin.layouts.dashboard')
@section('content')
    <main class="ps-main">
        <div class="ps-main__wrapper">
            <header class="header--dashboard">
                <div class="header__left">
                    <h3>Dashboard</h3>
                    <p>Everything here</p>
                </div>
                <div class="header__center">
                    <form class="ps-form--search-bar" action="index.html" method="get">
                        <input class="form-control" type="text" placeholder="Search something">
                        <button><i class="icon-magnifier"></i></button>
                    </form>
                </div>
                <div class="header__right"><a class="header__site-link" href="{{ route('home') }}"><span>View your
                            store</span>
                        <i class="icon-exit-right"></i>
                    </a></div>
            </header>
            <section class="ps-dashboard">
                <div class="ps-section__left">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="ps-card ps-card--sale-report">
                                <div class="ps-card__header">
                                    <h4>Sales Reports</h4>
                                </div>
                                <div class="ps-card__content">
                                    <div id="chart"></div>
                                </div>
                                <div class="ps-card__footer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>Items Earning Sales ($)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="ps-card ps-card--earning">
                                <div class="ps-card__header">
                                    <h4>Earnings</h4>
                                </div>
                                <div class="ps-card__content">
                                    <div class="ps-card__chart">
                                        <div id="donut-chart"></div>
                                        <div class="ps-card__information"><i
                                                class="icon icon-wallet"></i><strong>{{ $totalSuccess + $totalUnSuccess }}$</strong><small>Balance</small>
                                        </div>
                                    </div>
                                    <div class="ps-card__status">
                                        <p class="yellow"><strong> ${{ $totalSuccess }}</strong><span>success</span></p>
                                        <p class="green"><strong> ${{ $totalUnSuccess }}</strong><span>UnSuccess</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ps-card">
                        <div class="ps-card__header">
                            <h4>Recent Orders</h4>
                        </div>
                        <div class="ps-card__content">
                            <div class="table-responsive">
                                <table class="table ps-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td><strong> {{ $order->created_at }}</strong></td>
                                                <td><strong>{{ $order->name }}</strong>
                                                </td>
                                                <td><span class="ps-badge success">{{ $order->payment_method }}</span>
                                                </td>
                                                <td><span class="ps-fullfillment success">{{ $order->status }}</span>
                                                </td>
                                                <td><strong>${{ $order->total }}</strong></td>
                                               
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="ps-card__footer"><a class="ps-card__morelink" href="{{ route('admin.dashboard.Order') }}">View
                            Full Orders<i class="icon icon-chevron-right"></i></a></div> --}}
                    </div>
                </div>
            </section>
        </div>
    </main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        var chartData = @json($result);
        var orderNumberData = @json($resultOrderNumber);
    </script>
@endsection
