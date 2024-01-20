@extends('client.layout.app')
@section('content')
    @php
        foreach ($seat_row as $data) {
            $matchId = $data->footballMatch->match_id;
            $matchDate = $data->footballMatch->date_time;
            $matchName = $data->footballMatch->home_team . ' - ' . $data->footballMatch->away_team;
        }
    @endphp
    <section class="details-banner event-details-banner hero-area seat-plan-banner"
        style="background:url('/img/banner/banner-2.jpg')">
        <div class="container">
            <div class="details-banner-wrapper">
                <div class="details-banner-content style-two">
                    <h3 class="title"><span class="d-block">Football</span>
                        <span class="d-block">League -2023</span>
                    </h3>
                    <div class="tags">
                        <span>{{ $matchName }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-title bg-one">
        <div class="container">
            <div class="page-title-area">
                <div class="item md-order-1">
                    <a href="{{ route('seat.plan', ['id' => $matchId]) }}">
                        <i class="fa-solid fa-reply"></i> Thay Đổi Kế Hoạch
                    </a>
                </div>
                <div class="item date-item">
                    <span class="date">{{ $matchDate }}</span>
                </div>
                <div class="item">
                    <small>TIME LEFT</small>
                    <span id="countdown" class="h4 font-weight-bold">15:00</span>
                </div>
            </div>
        </div>
    </section>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="movie-facility padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-widget checkout-contact">
                        <h5 class="title">Billing Info </h5>
                        @php
                            foreach ($seat_row as $data) {
                                $footballMatchId = (int) $data->footballMatch->id;
                            }
                        @endphp
                        <form class="checkout-contact-form" action="{{ route('payBooking', ['id' => $footballMatchId]) }}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" placeholder="Full Name" name="name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Enter email" name="email" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Enter Phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Enter address" name="address" required>
                            </div>
                            <div class="checkout__input__checkbox">
                                <label for="VNBANK">
                                    Thanh toán qua thẻ ATM/Tài khoản nội địa
                                    <input value="VNBANK" name="payment_method" type="radio" id="VNBANK" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="checkout__input__checkbox">
                                <label for="INTCARD">
                                    Thanh toán qua thẻ quốc tế
                                    <input value="INTCARD" name="payment_method" type="radio" id="INTCARD">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <input type="hidden" name="seats" value="{{ json_encode($seats) }}">
                            <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
                            <div class="form-group">
                                <button type="submit" class="custom-button">confirm payment</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="booking-summery bg-one side-shape">
                        <h4 class="title">booking summery</h4>
                        <ul>
                            <li>
                                <h6 class="subtitle">{{ $matchName }} </h6>
                                <span class="info">{{ $matchDate }}</span>
                            </li>
                            @foreach ($seat_row as $data)
                                @php
                                    $seat_name = json_decode($data->seat_name);
                                    $seatNums = array_column($seats, 'seatNum');
                                    $intersect = array_intersect($seatNums, $seat_name->available);
                                    foreach ($data->seats as $seat) {
                                        $seatPrice = $seat->seat_price;
                                    }
                                @endphp
                                @if (!empty($intersect))
                                    <li>
                                        <h6 class="subtitle"><span>Hàng
                                                {{ $data->name }}</span><span>{{ implode(', ', $intersect) }}</span>
                                        </h6>
                                    </li>
                                    <li>
                                        <h6 class="subtitle mb-0">
                                            <span>Tickets Price x1</span>
                                            <span>{{ $seatPrice }}$</span>
                                        </h6>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <ul>
                            <li>
                                <span class="info"><span>price</span><span>{{ $totalPrice }}$</span></span>
                            </li>
                        </ul>
                    </div>
                    <div class="proceed-area  text-center">
                        <h6 class="subtitle"><span> Pay
                                Amount</span><span>{{ $totalPrice }}$</span></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //  Kiểm tra xem có trạng thái đếm ngược đã được lưu trong sessionStorage chưa
        let countdownTime = sessionStorage.getItem('countdownTime');

        if (countdownTime === null) {
            //   Nếu chưa có trạng thái, set thời gian đếm ngược ban đầu là 15 phút
            countdownTime = 15 * 60;
        } else {
            //   Nếu đã có trạng thái, chuyển về kiểu số
            countdownTime = parseInt(countdownTime);
        }

        //   Hiển thị thời gian đếm ngược ban đầu
        displayCountdown();

        //   Bắt đầu đếm ngược
        const countdownInterval = setInterval(function() {
            countdownTime--;

            //   Hiển thị thời gian đếm ngược mới
            displayCountdown();

            //   Lưu trạng thái đếm ngược vào sessionStorage
            sessionStorage.setItem('countdownTime', countdownTime);

            //   Nếu đã hết thời gian, chuyển hướng đến tuyến đường mong muốn
            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                // Dừng đếm ngược
                sessionStorage.removeItem('countdownTime');
                // Xóa trạng thái từ sessionStorage
                window.location.href = "{{ route('seat.plan', ['id' => $data->footballMatch->match_id]) }}";
            }
        }, 1000);

        //   Hàm hiển thị thời gian đếm ngược
        function displayCountdown() {
            const minutes = Math.floor(countdownTime / 60);
            const seconds = countdownTime % 60;
            document.getElementById('countdown').innerText = `${padNumber(minutes)}:${padNumber(seconds)}`;
        }

        //   Hàm chèn số 0 trước các số từ 0 đến 9
        function padNumber(number) {
            return number < 10 ? `0${number}` : number;
        }
    </script>
@endsection
