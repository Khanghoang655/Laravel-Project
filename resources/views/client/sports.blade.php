@extends('admin.layouts.app')

@section('content')
    <section class="banner-section">
        <div class="banner-bg bg-fixed" style="background:url('img/banner/banner-event.jpg')"></div>
        <div class="container">
            <div class="banner-content">
                <h1 class="title bold">buy <span class="color-theme">sport</span> tickets</h1>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when
                    looking at its layout. </p>
            </div>
        </div>
    </section>
    <section class="search-ticket-section padding-top pt-lg-0">
        <div class="container">
            <div class="search-tab">
                <div class="row align-items-center mb--20">
                    <div class="col-lg-6 mb-20">
                        <div class="search-ticket-header">
                            <h6 class="category">search tickets </h6>
                            <h3 class="title">find your tickets now</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-20">
                        <ul class="tab-menu ticket-tab-menu">
                            <li>
                                <div class="tab-thumb">
                                    <img src="{{ asset('img/ticket/movie.png') }}" alt="ticket">
                                </div>
                                <span>movie</span>
                            </li>
                            <li>
                                <div class="tab-thumb">
                                    <img src="{{ asset('img/ticket/event.png') }}" alt="ticket">
                                </div>
                                <span>events</span>
                            </li>
                            <li class="active">
                                <div class="tab-thumb">
                                    <img src="{{ asset('img/ticket/sport.png') }}" alt="ticket">
                                </div>
                                <span>sports</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-area">

                    <div class="tab-item">
                        <form class="ticket-search-form">
                            <div class="form-group large">
                                <input type="text" placeholder="Search for Movies">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/city.png') }}" alt="ticket">
                                </div>
                                <span class="type">city</span>
                                <select id="citySelect" class="select-bar">
                                    <?php $uniqueCities = []; ?>
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $uniqueCities))
                                            <option value="{{ $areaName }}">{{ $areaName }}</option>
                                            <?php
                                            // Thêm thành phố vào danh sách các thành phố duy nhất
                                            $uniqueCities[] = $areaName;
                                            ?>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/date.png') }}" alt="ticket">
                                </div>
                                <span class="type">date</span>
                                <select id="dateTimeSelect" class="select-bar">
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        $dateTime = $match->date_time;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $selectedCities))
                                            <option value="{{ $dateTime }}" data-city="{{ $areaName }}">
                                                {{ $dateTime }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/cinema.png') }}" alt="ticket">
                                </div>
                                <span class="type">movie</span>
                                <select class="select-bar">
                                    <option value="Avatar">Avatar</option>
                                    <option value="Inception">Inception</option>
                                    <option value="Parasite">Parasite</option>
                                    <option value="Joker">Joker</option>
                                    <option value="Searching">Searching</option>
                                    <option value="Coco">Coco</option>
                                    <option value="Lion">Lion</option>
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <div class="thumb">
                                    <button type="submit" class="filter-btn"><i class="fa-solid fa-magnifying-glass"
                                            style="color: #ffffff;"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-item active">
                        <form class="ticket-search-form">
                            <div class="form-group large">
                                <input type="text" placeholder="Search for Events">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/city.png') }}" alt="ticket">
                                </div>
                                <span class="type">city</span>
                                <select id="citySelect" class="select-bar">
                                    <?php $uniqueCities = []; ?>
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $uniqueCities))
                                            <option value="{{ $areaName }}">{{ $areaName }}</option>
                                            <?php
                                            // Thêm thành phố vào danh sách các thành phố duy nhất
                                            $uniqueCities[] = $areaName;
                                            ?>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/date.png') }}" alt="ticket">
                                </div>
                                <span class="type">date</span>
                                <select id="dateTimeSelect" class="select-bar">
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        $dateTime = $match->date_time;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $selectedCities))
                                            <option value="{{ $dateTime }}" data-city="{{ $areaName }}">
                                                {{ $dateTime }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/event-2.png') }}" alt="ticket">
                                </div>
                                <span class="type">event</span>
                                <select class="select-bar">
                                    <option value="Design">Design</option>
                                    <option value="Development">Development</option>
                                    <option value="Software">Software</option>
                                    <option value="Digital">Digital</option>
                                    <option value="Festival">Festival</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Seo">Seo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <button type="submit" class="filter-btn"><i class="fa-regular fa-magnifying-glass"
                                            style="color: #ffffff;"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-item">
                        <form class="ticket-search-form">
                            <div class="form-group large">
                                <input type="text" placeholder="Search fo Sports">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/city.png') }}" alt="ticket">
                                </div>
                                <span class="type">city</span>
                                <select id="citySelect" class="select-bar">
                                    <?php $uniqueCities = []; ?>
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $uniqueCities))
                                            <option value="{{ $areaName }}">{{ $areaName }}</option>
                                            <?php
                                            // Thêm thành phố vào danh sách các thành phố duy nhất
                                            $uniqueCities[] = $areaName;
                                            ?>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/date.png') }}" alt="ticket">
                                </div>
                                <span class="type">date</span>
                                <select id="dateTimeSelect" class="select-bar">
                                    @foreach ($matches as $match)
                                        <?php
                                        $areaName = $match->area_name;
                                        $dateTime = $match->date_time;
                                        ?>
                                        {{-- Kiểm tra xem thành phố đã xuất hiện chưa --}}
                                        @if (!in_array($areaName, $selectedCities))
                                            <option value="{{ $dateTime }}" data-city="{{ $areaName }}">
                                                {{ $dateTime }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <img src="{{ asset('img/ticket/sport-2.png') }}" alt="ticket">
                                </div>
                                <span class="type">sport</span>
                                <select class="select-bar">
                                    <option value="Cricket">Cricket</option>
                                    <option value="Football">Football</option>
                                    <option value="Basketball">Basketball</option>
                                    <option value="Baseball">Baseball</option>
                                    <option value="Golf">Golf</option>
                                    <option value="Running">Running</option>
                                    <option value="Badminton">Badminton</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="thumb">
                                    <button type="submit" class="filter-btn"><i class="fa-regular fa-magnifying-glass"
                                            style="color: #fafafa;"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="event-section padding-top padding-bottom">
        <div class="container">
            <div class="row flex-wrap-reverse justify-content-center">
                <div class="col-sm-10 col-md-8 col-lg-3">
                    <div class="widget-1 widget-check">
                        <div class="widget-1-body">
                            <h6 class="subtitle">categories</h6>
                            <div class="check-area">
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat1"><label
                                        for="cat1">all</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat2"><label
                                        for="cat2">cricket</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat3"><label
                                        for="cat3">football</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat4"><label
                                        for="cat4">baseball</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat5"><label
                                        for="cat5">badminton</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat6"><label for="cat6">running
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat8"><label for="cat8">golf
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat9"><label
                                        for="cat9">hocky</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="lang" id="cat10"><label for="cat10">table
                                        tenis</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget-1 widget-banner">
                        <div class="widget-1-body">
                            <a href="{{ url('#') }}">
                                <img src="{{ asset('img/sidebar/banner/banner-2.jpg') }}" alt="banner">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 mb-50 mb-lg-0">
                    <div class="filter-tab">
                        <div class="filter-area">
                            <div class="filter-main">
                                <div class="left w-100 justify-content-between">
                                    <div class="item">
                                        <span class="show">Show :</span>
                                        <select class="select-bar">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="60">60</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <span class="show">Sort By :</span>
                                        <select class="select-bar">
                                            <option value="latest">latest showing</option>
                                            <option value="exclusive">exclusive</option>
                                            <option value="upcoming">upcoming</option>
                                            <option value="trending">trending</option>
                                            <option value="popular">popular</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10 justify-content-center">
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-1.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">World Cricket League 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-2.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">Football League 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-3.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">Badminton League 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-4.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">International Golf 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-5.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">Baseball League 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="event-grid">
                                    <div class="movie-thumb c-thumb">
                                        <a href="{{ url('#') }}">
                                            <img src="{{ asset('img/sports/sport-6.jpg') }}" alt="sports">
                                        </a>
                                        <div class="event-date">
                                            <h6 class="date-title">11</h6>
                                            <span>Feb</span>
                                        </div>
                                    </div>
                                    <div class="movie-content">
                                        <h5 class="title m-0">
                                            <a href="{{ url('#') }}">Freedom Music Fest 2023</a>
                                        </h5>
                                        <div class="movie-rating-percent">
                                            <div>
                                                <i class="fa-solid fa-person"></i>
                                                <span>64,000 available seat</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>
                                                <span>10k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination-area text-center">
                            <a href="{{ url('#') }}"><i class="fal fa-long-arrow-alt-left"></i><span>Prev</span></a>
                            <a href="{{ url('#') }}">1</a>
                            <a href="{{ url('#') }}" class="active">2</a>
                            <a href="{{ url('#') }}">3</a>
                            <a href="{{ url('#') }}"><span>Next</span><i
                                    class="fal fa-long-arrow-alt-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
@section('js-custom')
<script>
  $(document).ready(function () {
    // Hide all date options initially
    $('#dateTimeSelect option').hide();

    // Event when a city is selected
    $('#citySelect').change(function () {
        var selectedCity = $(this).val();

        // Hide all date options and then show only those that match the selected city
        $('#dateTimeSelect option').hide().filter('[data-city="' + selectedCity + '"]').show();
    });
});


</script>

@endsection
