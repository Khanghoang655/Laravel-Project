@extends('client.layout.app')
@section('content')
    <section class="banner-section">
        <div class="banner-bg bg-fixed" style="background:url('/img/banner/banner-event.jpg')"></div>
        <div class="container">
            <div class="banner-content">
                <h1 class="title bold"> <span class="color-theme">Match</span> details</h1>
                <p> </p>
            </div>
        </div>
    </section>
    <section class="event-book-search padding-top pt-lg-0">
        <div class="container">
            <div class="event-search">
                <div class="event-search-top">
                    <div class="left">
                        <h3 class="title">{{ $match->home_team }} - {{ $match->away_team }}</h3>
                    </div>
                    <div class="right">
                        @php
                            $current_time = now()->setTimezone('Asia/Ho_Chi_Minh');
                            $new_time = $current_time->addMinutes(30);
                        @endphp
                        @if ($match->date_time > $new_time)
                            <ul class="countdown">
                                <li>
                                    <h2><span id="days">00</span></h2>
                                    <p class="days_text">days</p>
                                </li>
                                <li>
                                    <h2><span id="hours">00</span></h2>
                                    <p class="hours_text">hrs</p>
                                </li>
                                <li>
                                    <h2><span id="minutes">00</span></h2>
                                    <p class="minu_text">min</p>
                                </li>
                                <li>
                                    <h2><span id="seconds">00</span></h2>
                                    <p class="seco_text">sec</p>
                                </li>
                            </ul>
                            <a href="{{ route('seat.plan', ['id' => $match->match_id]) }}" class="custom-button">book
                                tickets</a>
                        @else
                            <p>Match end</p>
                        @endif
                    </div>
                </div>
                <div class="event-search-bottom">
                    <div class="contact-side">
                        <div class="item">
                            <div class="item-thumb">
                                <i class="fal fa-calendar-alt"></i>
                            </div>
                            <div class="item-content">
                                <span class="up">10 Days
                                    (Monday-Friday)</span>
                                <span>50+ Workshops</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-thumb">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="item-content">
                                <span class="up"> 290 Private Lane
                                    Street</span>
                                <span>New York, USA</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-thumb">
                                <i class="fal fa-phone"></i>
                            </div>
                            <div class="item-content">
                                <span class="up">Call Us:</span>
                                <a href="{{ url('#') }}">+1 123 456 7894</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="event-about padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-between flex-wrap-reverse">
                <div class="col-lg-7 col-xl-6">
                    <div class="event-about-content">
                        <div class="section-header-3 left-style m-0">
                            <span class="cate">join our event</span>
                            <h2 class="title">Learning Conference -
                                <span>2023</span>
                            </h2>
                            <p>
                                There are many variations of passages of
                                Lorem Ipsum available, but the majority have
                                suffered alteration in some form, by
                                injected humour, or randomised words which
                                don't look even slightly believable. If you
                                are going to use a passage of Lorem Ipsum,
                                you need to be sure there isn't anything
                                embarrassing hidden in the middle of text.
                                All the Lorem Ipsum generators on the
                                Internet tend to repeat predefined chunks as
                                necessary, making this the first true
                                generator on the Internet. It uses a
                                dictionary of over 200 Latin words.
                            </p>
                            <p>
                                Sure there isn't anything embarrassing
                                hidden in the middle of text. All the Lorem
                                Ipsum generators on the Internet tend to
                                repeat predefined chunks as necessary,
                                making this the first true generator on the
                                Internet. It uses a dictionary of over 200
                                Latin words.
                            </p>
                            <a href="{{ route('seat.plan', ['id' => $match->match_id]) }}" class="custom-button">book
                                tickets</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-7">
                    <div class="event-about-thumb">
                        <img src="{{ $match->competitions->emblem }}" alt="event">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="speaker-gallery">
    <div class="row m-0">
        <div class="col-md-6 p-0">
            <div class="row m-0">
                <div class="col-sm-6 p-0">
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="{{ url("/img/gallery/8.jpg") }}"
                                class="img-pop">
                                <i class="fal fa-compress"></i>
                            </a>
                            <img src="{{ asset("/img/gallery/9.jpg") }}"
                                alt="gallery">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 p-0">
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="{{ url("/img/gallery/.jpg") }}"
                                class="img-pop">
                                <i class="fal fa-compress"></i>
                            </a>
                            <img src="{{ asset("/img/gallery/2.jpg") }}"
                                alt="gallery">
                        </div>
                    </div>
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="{{ url("/img/gallery/3.jpg") }}"
                                class="img-pop">
                                <i class="fal fa-compress"></i>
                            </a>
                            <img src="{{ asset("/img/gallery/3.jpg") }}"
                                alt="gallery">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-0">
            <div class="gallery-item">
                <div class="gallery-thumb">
                    <a href="{{ url("/img/gallery/4.jpg") }}" class="img-pop">
                        <i class="fal fa-compress"></i>
                    </a>
                    <img src="{{ asset("/img/gallery/4.jpg") }}" alt="gallery">
                </div>
            </div>
        </div>
    </div>
</div> --}}

    <section class="speaker-section padding-bottom padding-top">
        <div class="container">
            <div class="section-header-3">
                <span class="cate">speakers</span>
                <h2 class="title">event speakers</h2>
                <p>It is a long established fact that a reader will be
                    distracted by the readable content of a page when
                    looking at its layout.</p>
            </div>
            <div class="speaker--slider">
                <div class="speaker-slider owl-carousel owl-theme">
                    @php
                        $printedMatches = []; // Mảng để theo dõi các trận đấu đã được in
                    @endphp
                    @foreach ($matches as $data)
                        @php
                            $image = $data->competitions->emblem;
                        @endphp
                        @if ($data->date_time > $new_time && !in_array($data->match_id, $printedMatches))
                            @php
                                $printedMatches[] = $data->match_id;
                            @endphp
                            <div class="speaker-item">
                                <div class="speaker-thumb">
                                    <a href="{{ route('matches.seat', ['id' => $data->match_id]) }}">
                                        <img src="{{ $image }}" alt="speaker">
                                    </a>
                                </div>
                                <div class="speaker-content">
                                    <h5 class="title">
                                        <a href="{{ route('matches.seat', ['id' => $data->match_id]) }}">
                                            {{ $data->home_team }} - {{ $data->away_team }}
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
                <div class="speaker-prev">
                    <i class="fal fa-long-arrow-alt-right"></i>
                </div>
                <div class="speaker-next">
                    <i class="fal fa-long-arrow-alt-right"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="event-details padding-bottom">
        <div class="container">
            <div class="section-header-3">
                <span class="cate">SPONSORS</span>
                <h2 class="title">Partners & Sponsors</h2>
                <p>It is a long established fact that a reader will be
                    distracted by the readable content of a page when
                    looking at its layout</p>
            </div>
            {{-- <div class="tabTwo sponsor-tab">
                <div class="owl-theme owl-carousel sponsor-slider">
                    <div class="sponsor-thumb">
                        <a href="{{ url('#') }}">
                            <img src="{{ asset('/img/sponsor/01.png') }}" alt="sponsor">
                        </a>
                    </div>
                    <div class="sponsor-thumb">
                        <a href="{{ url('#') }}">
                            <img src="{{ asset('/img/sponsor/02.png') }}" alt="sponsor">
                        </a>
                    </div>
                    <div class="sponsor-thumb">
                        <a href="{{ url('#') }}">
                            <img src="{{ asset('/img/sponsor/03.png') }}" alt="sponsor">
                        </a>
                    </div>
                    <div class="sponsor-thumb">
                        <a href="{{ url('#') }}">
                            <img src="{{ asset('/img/sponsor/04.png') }}" alt="sponsor">
                        </a>
                    </div>
                    <div class="sponsor-thumb">
                        <a href="{{ url('#') }}">
                            <img src="{{ asset('/img/sponsor/05.png') }}" alt="sponsor">
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
    @if ($match->date_time > $new_time)
        <script>
            var now = new Date().getTime();
            // Chuyển đổi chuỗi thời gian thành đối tượng JavaScript Date
            var countDownDate = new Date("{{ $match->date_time }}").getTime() - 30 * 60 * 1000;

            // Cập nhật thời gian mỗi 1 giây
            var x = setInterval(function() {
                // Lấy thời gian hiện tại
                var current = new Date().getTime();

                // Tính thời gian còn lại
                var distance = countDownDate - current;

                // Tính toán các giá trị ngày, giờ, phút, giây
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Hiển thị giá trị lên giao diện người dùng
                document.getElementById("days").innerHTML = days;
                document.getElementById("hours").innerHTML = hours;
                document.getElementById("minutes").innerHTML = minutes;
                document.getElementById("seconds").innerHTML = seconds;

                // Kiểm tra xem thời gian đã hết hay chưa
                if (distance < 0) {
                    clearInterval(x);
                    // Hiển thị thông báo khi thời gian kết thúc
                    document.getElementById("countdown").innerHTML = "EXPIRED";
                }
            }, 1000);
        </script>
    @endif
@endsection
