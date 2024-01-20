@extends('client.layout.app')
@section('content')
    <section class="details-banner hero-area seat-plan-banner"
        style="background:url('assets/img/banner/banner-movie-details.jpg')">
        <div class="container">
            <div class="details-banner-wrapper">
                <div class="details-banner-content style-two">
                    <h3 class="title">Irregular</h3>
                    <div class="tags">
                        <a href="{{ url('#') }}">{{ $match->home_team }} - {{ $match->away_team }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-title bg-one">
        <div class="container">
            <div class="page-title-area">
                <div class="item md-order-1">
                    <a href="{{ route('matches.seat', ['id' => $match->match_id]) }}" class="custom-button back-button">
                        <i class="far fa-reply"></i> Change Plan
                    </a>
                </div>
                <div class="item date-item">
                    <span class="date">{{ $match->date_time }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="seat-plan-section padding-bottom padding-top">
        <div class="container">
            <div class="screen-area">
                <h4 class="screen">Stadium</h4>
                <div class="screen-thumb">
                    <img src="{{ asset('/img/movie/theater.png') }}" alt="movie">
                </div>
                <h5 class="subtitle">Normal seat plan</h5>
                <div class="screen-wrapper">
                    @foreach ($match->seat_rows as $row)
                        <ul class="seat-area">
                            @php
                                $maxSeatCount = 25;
                                $rowCount = ceil($row->total_seats / $maxSeatCount);
                                $seatPrice = 0;
                                foreach ($row->seats as $seat) {
                                    $seatPrice = $seat->seat_price;
                                }
                                $seatRemaining = $row->total_seats;
                                $seatCount = 0;
                            @endphp
                            @for ($i = 1; $i <= $rowCount; ++$i)
                                <li class="seat-line">
                                    <ul class="seat--area">
                                        @php
                                            $seatCount = $seatRemaining > $maxSeatCount ? $maxSeatCount : $seatRemaining;
                                            $seatRemaining -= $seatCount;
                                            if ($seatRemaining < 0) {
                                                $seatRemaining = 0;
                                            }
                                            $seatAvailable =  json_decode($row->seat_name)->available;

                                            $isUnavailable = 0

                                        @endphp

                                        @for ($j = 1; $j <= $seatCount; ++$j)
                                            @foreach ($seatAvailable as $seat)
                                                @if ( ($row->name .(($i - 1) * $maxSeatCount + $j)) == $seat)
                                                   @php
                                                       $isUnavailable = 1;
                                                   @endphp
                                                @endif
                                            @endforeach
                                            @if ($isUnavailable == 1)
                                            <li class="front-seat">
                                                <ul>
                                                    <li class="single-seat seat-free">
                                                        <img src="{{ asset('/img/movie/seat-1-free.png') }}" alt="seat">
                                                        <span class="sit-num"
                                                            value ="{{ $row->name }}{{ ($i - 1) * $maxSeatCount + $j }}"
                                                            data-seat-price="{{ $seatPrice }}">
                                                            <small>{{ $row->name }}{{ ($i - 1) * $maxSeatCount + $j }}</small></span>
                                                    </li>
                                                </ul>
                                            </li>
                                            @else
                                            <li class="front-seat">
                                                <ul>
                                                    <li class="single-seat">
                                                        <img src="{{ asset('/img/movie/seat-unavailable.png') }}" alt="seat">
                                                        <span class="sit-num"
                                                            value ="{{ $row->name }}{{ ($i - 1) * $maxSeatCount + $j }}"
                                                            data-seat-price="{{ $seatPrice }}">
                                                            <small>{{ $row->name }}{{ ($i - 1) * $maxSeatCount + $j }}</small></span>
                                                    </li>
                                                </ul>
                                            </li>
                                            @endif
                                            @php
                                                $isUnavailable = 0;
                                            @endphp
                                           
                                        @endfor
                                        @if ($seatRemaining == 0)
                                            @for ($j = 1; $j <= $maxSeatCount - $seatCount; ++$j)
                                                <li class="front-seat">
                                                    <ul>
                                                        <li class="single-seat">
                                                            <img src="{{ asset('/img/movie/seat-1.png') }}" alt="seat">
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endfor
                                            @for ($j = 1; $j <= $maxSeatCount - $seatCount; ++$j)
                                                <li class="front-seat">
                                                    <ul>
                                                        <li class="single-seat">
                                                            <img src="{{ asset('/img/movie/seat-unavailable.png') }}" alt="seat">
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endfor
                                        @endif
                                    </ul>
                                </li>
                            @endfor
                        </ul>
                    @endforeach
                </div>
            </div>
            <div class="proceed-book">
                <div class="proceed-to-book">
                    <div class="book-item">
                        <span>Your Selected Seat</span>
                        <h3 id="selected-seats-display" class="title"></h3>
                    </div>
                    <div class="book-item">
                        <span>Total Price</span>
                        <h3 id="total-price-display" class="title">$0</h3>
                    </div>
                    <form id="checkout-form" action="{{ route('checkout', ['id' => $match->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seats" id="seats-input" value="">
                        <input type="hidden" name="totalPrice" id="total-price-input" value="">
                        <input type="hidden" name="maxSeatCount" id="maxSeatCount" value="{{$maxSeatCount}}">
                        <input type="hidden" name="matchId" id="matchId" value="{{$match->match_id}}">
                        <div class="book-item">
                            <button type="submit" class="custom-button">checkout now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Danh sách ghế đã chọn và giá tiền
        var selectedSeats = [];
        var totalPrice = 0;

        function updateSelectedSeatsDisplay() {
            var selectedSeatsDisplay = document.getElementById('selected-seats-display');
            selectedSeatsDisplay.textContent = selectedSeats.map(function(seat) {
                return seat.seatNum;
            }).join(', ');
        }

        function updateTotalPriceDisplay() {
            var totalPriceDisplay = document.getElementById('total-price-display');
            totalPriceDisplay.textContent = '$' + totalPrice;
        }

        function toggleSeatSelection(seat, seatPrice) {
            var seatIndex = selectedSeats.findIndex(function(selectedSeat) {
                return selectedSeat.seatNum === seat;
            });

            if (seatIndex === -1) {
                // Ghế chưa được chọn, thêm vào danh sách và cộng giá tiền
                selectedSeats.push({
                    seatNum: seat,
                    seatPrice: seatPrice
                });
                totalPrice += seatPrice;
            } else {
                // Ghế đã được chọn, loại bỏ khỏi danh sách và trừ giá tiền
                selectedSeats.splice(seatIndex, 1);
                totalPrice -= seatPrice;
            }

            // Cập nhật hiển thị ghế đã chọn và tổng giá tiền
            updateSelectedSeatsDisplay();
            updateTotalPriceDisplay();
            console.log(selectedSeats);
        }

        updateSelectedSeatsDisplay();
        updateTotalPriceDisplay();

        // Bắt sự kiện click trên ghế
        $(".seat-free img").on('click', function(e) {
            // Lấy giá trị ghế và giá tiền từ thuộc tính value và data-seat-price
            var seatValue = $(this).siblings('.sit-num').attr('value');
            var seatPrice = parseFloat($(this).siblings('.sit-num').attr('data-seat-price'));
            console.log(seatPrice);

            // Toggle chọn ghế và cập nhật giá tiền
            toggleSeatSelection(seatValue, seatPrice);

            // Đổi đường dẫn ảnh
            if ($(this).attr('src') === '/img/movie/seat-1-booked.png') {
                $(this).attr('src', '/img/movie/seat-1-free.png');
            } else {
                $(this).attr('src', '/img/movie/seat-1-booked.png');
            }
        });
        document.getElementById('checkout-form').addEventListener('submit', function() {
        // Đặt giá trị của selectedSeats và totalPrice vào input hidden
        document.getElementById('seats-input').value = JSON.stringify(selectedSeats);
        document.getElementById('total-price-input').value = totalPrice;
    });
    </script>
@endsection
