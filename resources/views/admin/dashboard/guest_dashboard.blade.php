<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h1>Welcome to Your Dashboard, {{ Auth::user()->name }}!</h1>

                        {{-- Thông tin cá nhân --}}
                        <div class="card">
                            <div class="card-header">
                                Your Profile
                            </div>
                            <div class="card-body">
                                <p>Name: {{ Auth::user()->name }}</p>
                                <p>Email: {{ Auth::user()->email }}</p>
                                {{-- Thêm thông tin cá nhân khác nếu cần --}}
                            </div>
                        </div>

                        {{-- Đơn hàng gần đây --}}
                        <div class="card mt-4">
                            <div class="card-header">
                                Your Recent Orders
                            </div>
                            <div class="card-body">
                                @if (count($orders) > 0)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="bg-primary text-white">Order ID</th>
                                                <th scope="col" class="bg-primary text-white">Name</th>
                                                <th scope="col" class="bg-primary text-white">Date</th>
                                                <th scope="col" class="bg-primary text-white">Seat</th>
                                                <th scope="col" class="bg-primary text-white">Payment Method</th>
                                                <th scope="col" class="bg-primary text-white">Status</th>
                                                <th scope="col" class="bg-primary text-white">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                foreach ($match as $footballMatch) {
                                                    $matchName = $footballMatch->home_team . ' - ' . $footballMatch->away_team;
                                                    $matchDate = $footballMatch->date_time;
                                                    $matchCompetition = $footballMatch->competition_name;
                                                    $imageHome = $footballMatch->emblem_home;
                                                    $imageAway = $footballMatch->emblem_away;
                                                }
                                            @endphp
                                            @foreach ($orders as $order)
                                            <tr class="@if ($order->status == 'success') table-success @elseif ($order->status == 'cancel') table-danger @endif">
                                                <th scope="row">{{ $order->id }}</th>
                                                <th scope="row">{{ $matchName }}</th>
                                                <th scope="row">{{ $matchDate }}</th>
                                                <th scope="row">{{ $order->name }}</th>
                                                <td>{{ $order->payment_method }}</td>
                                                <td>{{ $order->status }}</td>
                                                <td>${{ $order->total }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No recent orders found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
