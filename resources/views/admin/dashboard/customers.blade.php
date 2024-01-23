@extends('admin.layouts.dashboard')
@section('content')
    <div class="ps-main__wrapper">
        <header class="header--dashboard">
            <div class="header__left">
                <h3>Customers</h3>
                <p>Martfury Customers</p>
            </div>
            {{-- <div class="header__center">
                <form class="ps-form--search-bar" action="index.html" method="get">
                    <input class="form-control" type="text" placeholder="Search something" />
                    <button><i class="icon-magnifier"></i></button>
                </form>
            </div> --}}
            <div class="header__right"><a class="header__site-link" href="{{ url('#') }}"><span>View your
                        store</span><i class="icon-exit-right"></i></a></div>
        </header>
        @if (session('msg'))
            <div class="alert alert-success">
                {{ session('msg') }}
            </div>
        @endif
        <section class="ps-items-listing">
            {{-- <div class="ps-section__header simple">
                <div class="ps-section__filter">
                    <form class="ps-form--filter" action="index.html" method="get">
                        <div class="ps-form__left">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Search..." />
                            </div>
                            <div class="form-group">
                                <select class="ps-select">
                                    <option value="1">Status</option>
                                    <option value="2">Active</option>
                                    <option value="3">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="ps-form__right">
                            <button class="ps-btn ps-btn--gray"><i class="icon icon-funnel mr-2"></i>Filter</button>
                        </div>
                    </form>
                </div>
                <div class="ps-section__actions"><a class="ps-btn success" href="{{ url('#') }}"><i
                            class="icon icon-plus mr-2"></i>Add Customer</a></div>
            </div> --}}
            <div class="ps-section__content">
                <div class="table-responsive">
                    <table class="table ps-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>
                                    Total orders
                                    {{-- <button id="sortTotal" data-order="asc">
                                        <span id="sortArrow">&#9650;</span> <!-- Mũi lên -->
                                    </button> --}}
                                </th>
                                <th>Role</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $newUserCounts = 0;
                                foreach ($userCounts as $user) {
                                    $newUserCounts += $user;
                                }

                            @endphp
                            @foreach ($usersPaginated as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td><strong>{{ $customer->name }}</strong></td>
                                    <td>{{ $customer->phone }}</td>

                                    {{-- Hiển thị giá trị của $userCounts cho khách hàng hiện tại --}}
                                    <td>
                                        @if (isset($userCounts[$customer->id]))
                                            <p>User ID: {{ $customer->id }}, Total Count: {{ $userCounts[$customer->id] }}
                                            </p>
                                        @else
                                            <p>No data available</p>
                                        @endif
                                    </td>

                                    @if ($customer->is_admin == 1)
                                        <td><span class="ps-badge success">Admin</span></td>
                                    @else
                                        <td><span class="ps-badge gray">Guest</span></td>
                                    @endif

                                    <td>{{ $customer->created_at }}</td>
                                    <td>
                                        {{-- Dropdown menu --}}
                                        <div class="dropdown">
                                            <form action="{{ route('admin.dashboard.role', ['id' => $customer->id]) }}"
                                                method="post">
                                                @csrf
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        cài đặt
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <button type="submit" class="dropdown-item" name="action"
                                                            value="set_admin">Phân vai trò Admin</button>
                                                        <button type="submit" class="dropdown-item" name="action"
                                                            value="delete">Xóa</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ps-section__footer">
                <p>Show {{ $usersPaginated->firstItem() }} to {{ $usersPaginated->lastItem() }} in
                    {{ $usersPaginated->total() }} items.</p>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        {{-- Nút chuyển đến trang trước --}}
                        @if ($usersPaginated->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $usersPaginated->previousPageUrl() }}"
                                    aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Các nút trang --}}
                        @for ($i = 1; $i <= $usersPaginated->lastPage(); $i++)
                            <li class="page-item {{ $i == $usersPaginated->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $usersPaginated->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($usersPaginated->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $usersPaginated->nextPageUrl() }}"
                                    aria-label="Next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortTotalButton = document.getElementById('sortTotal');
            const sortArrow = document.getElementById('sortArrow');
            let sortOrder = 'asc';

            sortTotalButton.addEventListener('click', function() {
                sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
                const url = `{{ route('admin.dashboard.customers') }}?total_filter=${sortOrder}`;

                // Redirect to the sorted URL
                window.location.href = url;
            });

            // Update arrow direction based on the current sortOrder
            const updateArrow = function() {
                sortArrow.innerHTML = sortOrder === 'asc' ? '&#9650;' : '&#9660;';
            };

            updateArrow(); // Initial arrow direction
        });
    </script>
@endsection
