@extends('client.layout.app')
@section('content')
    <section class="details-banner" style="background:url('{{ $competition->emblem }}')">
        <div class="container">
            <div class="details-banner-wrapper">
                <div class="details-banner-thumb">
                    <img src="{{ $competition->emblem }}" alt="movie" style="width: 14em">
                    <a href="https://www.youtube.com/watch?v=uyNh0RPiLyI" class="video-button video-popup">
                        <i class="fal fa-play"></i>
                    </a>
                </div>
                <div class="details-banner-content offset-lg-4">
                    <h3 class="title">{{ $competition->name_of_competition }}</h3>
                    <div class="social-and-duration">
                        <div class="duration-area">
                            <div class="item">
                                <i class="fal fa-calendar-alt"></i><span>{{ \Carbon\Carbon::parse($competition->start_date)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="item">
                                <i class="fal fa-calendar-alt"></i><span>{{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        {{-- <ul class="social-share">
                            <li><a href="{{ url('#') }}"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="{{ url('#') }}"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="{{ url('#') }}"><i class="fab fa-pinterest-p"></i></a></li>
                            <li><a href="{{ url('#') }}"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="{{ url('#') }}"><i class="fab fa-google-plus-g"></i></a></li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $footballMatchSeat = 0;
        if ($competition->footballMatch) {
            // Kiểm tra xem có trận đấu nào trong giải đấu hay không
            foreach ($competition->footballMatch as $footballMatch) {
                $footballMatchSeat += $footballMatch->seat;
            }
        }
    @endphp

    <section class="book-section">
        <div class="container">
            <div class="book-wrapper offset-lg-4">
                <div class="left-side">
                    <div class="item">
                        <div class="item-header">
                            <div class="thumb">
                                <i class="fal fa-shopping-cart"></i>
                            </div>
                            <div class="counter-area">

                                <span class="counter-item odometer" data-odometer-final="{{ $footballMatchSeat }}">0</span>

                                <span>k+</span>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </section>

    <section class="movie-details-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center flex-wrap-reverse mb--50">
                <div class="col-lg-3 col-sm-10 col-md-6 mb-50">
                    <div class="widget-1 widget-offer">
                        <h3 class="title">TODAY OFFER</h3>
                        <div class="offer-body">
                            <div class="offer-item">
                                @foreach ($competition->footballMatch as $footballMatch)
                                    @if ($footballMatch)
                                        <div class="content">
                                            <h6>
                                                <a href="{{ route('matches.seat', ['id' => $footballMatch->match_id]) }}">{{ $footballMatch->home_team }}
                                                    -
                                                    {{ $footballMatch->away_team }}</a>
                                            </h6>
                                            <p>{{ $footballMatch->date }}</p>
                                        </div>
                                    @else
                                        <div class="content">
                                            <h6>
                                                <p>Update</p>
                                            </h6>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-9 mb-50">
                    <div class="movie-details">
                        <h3 class="title">photos</h3>
                        <div class="details-photos owl-carousel">

                            @foreach ($competitions as $data)
                                <div class="thumb">
                                    <a href="{{ route('competition.detail', ['id' => $data->id]) }}">
                                        <img src="{{ $data->emblem }}" alt="movie">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="tab summery-review">
                            <ul class="tab-menu">
                                <li class="active">
                                    description
                                </li>
                                {{-- <li>
                                    review <span>10</span>
                                </li> --}}
                            </ul>
                            <div class="tab-area">
                                <div class="tab-item active">
                                    <div class="item">
                                        <h5 class="sub-title">There are many
                                            variations of passages</h5>
                                        <p>There are many variations of
                                            passages of Lorem Ipsum
                                            available, but the majority have
                                            suffered alteration in some
                                            form, by injected humour, or
                                            randomised words which don't
                                            look even slightly believable.
                                            If you are going to use a
                                            passage of Lorem Ipsum, you need
                                            to be sure there isn't anything
                                            embarrassing hidden in the
                                            middle of text. </p>
                                        <p>There are many variations of
                                            passages of Lorem Ipsum
                                            available, but the majority have
                                            suffered alteration in some
                                            form, by injected humour, or
                                            randomised words which don't
                                            look even slightly believable.
                                            If you are going to use a
                                            passage of Lorem Ipsum, you need
                                            to be sure there isn't anything
                                            embarrassing hidden in the
                                            middle of text. </p>
                                        {{-- <div class="widget-tags mt-5">
                                            <p>Tags : </p>
                                            <ul>
                                                <li>
                                                    <a href="{{ url('#') }}">2D</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('#') }}">3D</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('#') }}">MOVIE</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('#') }}">2023</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> --}}
                                        <div class="item">
                                            <div class="header">
                                                <h5 class="sub-title">movie
                                                    cast</h5>
                                                <div class="navigation">
                                                    <div class="cast-prev"><i
                                                            class="flaticon-double-right-arrows-angles"></i>
                                                    </div>
                                                    <div class="cast-next"><i
                                                            class="flaticon-double-right-arrows-angles"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="casting-slider owl-carousel">
                                                @foreach ($competition->clubs as $club)
                                                    <div class="cast-item">
                                                        <div class="cast-thumb">
                                                            <a href="{{ url('#') }}">
                                                                <img src="{{ $club->crest }}" alt="cast">
                                                            </a>
                                                        </div>
                                                        <div class="cast-content">
                                                            <h6 class="cast-title"><a
                                                                    href="{{ url('#') }}">{{ $club->name }}</a>
                                                            </h6>
                                                            <span class="cate">{{ $club->tla }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="header">
                                                <h5 class="sub-title">movie
                                                    crew</h5>
                                                <div class="navigation">
                                                    <div class="cast-prev-2"><i
                                                            class="flaticon-double-right-arrows-angles"></i></div>
                                                    <div class="cast-next-2"><i
                                                            class="flaticon-double-right-arrows-angles"></i></div>
                                                </div>
                                            </div>
                                            <div class="casting-slider-two owl-carousel">
                                                @foreach ($competitions as $competition)
                                                    <div class="cast-item">
                                                        <div class="cast-thumb">
                                                            <a
                                                                href="{{ route('competition.detail', ['id' => $competition->id]) }}">
                                                                <img src="{{ $competition->emblem }}" alt="cast">
                                                            </a>
                                                        </div>
                                                        <div class="cast-content">
                                                            <h6 class="cast-title"><a
                                                                    href="{{ route('competition.detail', ['id' => $competition->id]) }}">{{ $competition->name_of_competition }}</a>
                                                            </h6>
                                                            <span class="cate">{{ $competition->short_name }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-item">
                                        <div class="movie-review-item">
                                            <div class="author">
                                                <div class="thumb">
                                                    <a href="{{ url('#') }}">
                                                        <img src="{{ asset('/img/cast/cast-2.jpg') }}" alt="cast">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="movie-review-content">
                                                <div class="movie-review-info">
                                                    <h6 class="subtitle">
                                                        <a href="{{ url('#') }}">Thomas E
                                                            Criswell</a>
                                                    </h6>
                                                    <span class="reply-date"><i class="fal fa-clock"></i>
                                                        1 hour ago </span>
                                                    <div class="review">
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                    </div>
                                                </div>
                                                <p>It is a long established fact
                                                    that a reader will be
                                                    distracted by the readable
                                                    content of a page when
                                                    looking at its layout. </p>
                                                <div class="review-meta">
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-thumbs-up"></i><span>10</span>
                                                    </a>
                                                    <a href="{{ url('#') }}" class="dislike">
                                                        <i class="fal fa-thumbs-down"></i><span>02</span>
                                                    </a>
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-flag"></i>
                                                        <span>Report
                                                            Review</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="movie-review-item">
                                            <div class="author">
                                                <div class="thumb">
                                                    <a href="{{ url('#') }}">
                                                        <img src="{{ asset('/img/cast/cast-1.jpg') }}" alt="cast">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="movie-review-content">
                                                <div class="movie-review-info">
                                                    <h6 class="subtitle">
                                                        <a href="{{ url('#') }}">Thomas E
                                                            Criswell</a>
                                                    </h6>
                                                    <span class="reply-date"><i class="fal fa-clock"></i>
                                                        1 hour ago </span>
                                                    <div class="review">
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                    </div>
                                                </div>
                                                <p>It is a long established fact
                                                    that a reader will be
                                                    distracted by the readable
                                                    content of a page when
                                                    looking at its layout. </p>
                                                <div class="review-meta">
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-thumbs-up"></i><span>10</span>
                                                    </a>
                                                    <a href="{{ url('#') }}" class="dislike">
                                                        <i class="fal fa-thumbs-down"></i><span>02</span>
                                                    </a>
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-flag"></i>
                                                        <span>Report
                                                            Review</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="movie-review-item">
                                            <div class="author">
                                                <div class="thumb">
                                                    <a href="{{ url('#') }}">
                                                        <img src="{{ asset('/img/cast/cast-2.jpg') }}" alt="cast">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="movie-review-content">
                                                <div class="movie-review-info">
                                                    <h6 class="subtitle">
                                                        <a href="{{ url('#') }}">Thomas E
                                                            Criswell</a>
                                                    </h6>
                                                    <span class="reply-date"><i class="fal fa-clock"></i>
                                                        1 hour ago </span>
                                                    <div class="review">
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                    </div>
                                                </div>
                                                <p>It is a long established fact
                                                    that a reader will be
                                                    distracted by the readable
                                                    content of a page when
                                                    looking at its layout. </p>
                                                <div class="review-meta">
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-thumbs-up"></i><span>10</span>
                                                    </a>
                                                    <a href="{{ url('#') }}" class="dislike">
                                                        <i class="fal fa-thumbs-down"></i><span>02</span>
                                                    </a>
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-flag"></i>
                                                        <span>Report
                                                            Review</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="movie-review-item">
                                            <div class="author">
                                                <div class="thumb">
                                                    <a href="{{ url('#') }}">
                                                        <img src="{{ asset('/img/cast/cast-3.jpg') }}" alt="cast">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="movie-review-content">
                                                <div class="movie-review-info">
                                                    <h6 class="subtitle">
                                                        <a href="{{ url('#') }}">Thomas E
                                                            Criswell</a>
                                                    </h6>
                                                    <span class="reply-date"><i class="fal fa-clock"></i>
                                                        1 hour ago </span>
                                                    <div class="review">
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                    </div>
                                                </div>
                                                <p>It is a long established fact
                                                    that a reader will be
                                                    distracted by the readable
                                                    content of a page when
                                                    looking at its layout. </p>
                                                <div class="review-meta">
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-thumbs-up"></i><span>10</span>
                                                    </a>
                                                    <a href="{{ url('#') }}" class="dislike">
                                                        <i class="fal fa-thumbs-down"></i><span>02</span>
                                                    </a>
                                                    <a href="{{ url('#') }}">
                                                        <i class="fal fa-flag"></i>
                                                        <span>Report
                                                            Review</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="load-more text-center">
                                            <a href="{{ url('#') }}" class="custom-button transparent">load
                                                more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
