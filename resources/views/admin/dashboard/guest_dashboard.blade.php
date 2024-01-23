@extends('admin.layouts.guest')
@section('content')
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
                                                <th scope="col" class="bg-primary text-white"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr
                                                    class="@if ($order->status == 'success') table-success @elseif ($order->status == 'cancel') table-danger @endif">
                                                    <th scope="row">{{ $order->id }}</th>
                                                    <th scope="row">{{ $order->name }}</th>
                                                    <th scope="row">{{ $order->date_time }}</th>
                                                    <th scope="row">{{ $order->seat_name }}</th>
                                                    <td>{{ $order->payment_method }}</td>
                                                    <td>{{ $order->status }}</td>
                                                    <td>${{ $order->total }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <form action="{{ route('order.guest', ['id' => $order->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="dropdown">
                                                                    <button class="btn btn-secondary dropdown-toggle"
                                                                        type="button" data-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">
                                                                        Cài đặt
                                                                    </button>
                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton">
                                                                        <button type="submit" class="dropdown-item"
                                                                            name="action" value="cancel-order">Hủy Vé
                                                                        </button>
                                                                        {{-- <button type="submit" class="dropdown-item" name="action" value="delete">Xóa</button> --}}
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach


                                            {{-- @endforeach --}}
                                            {{-- @endfor --}}
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
@endsection
