@extends('client.layout.app')
@section('content')
        <section class="main-page-header speaker-banner" style="background: url('/img/banner/banner-2.jpg');">
            <div class="container">
                <div class="speaker-banner-content">
                    <h2 class="title">login</h2>
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('home')}}">
                                Home
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </li>
                        <li>
                            login
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="account-section">
            <div class="container">
                <div class="padding-top padding-bottom">
                    <div class="account-area">
                        <div class="section-header-3">
                            <span class="cate">hello !</span>
                            <h2 class="title">welcome back</h2>
                        </div>

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{session('msg')}}
                            {{ session('error') }}
                        </div>
                    @endif
                        <form class="account-form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email<span>*</span></label>
                                <input type="text" placeholder="Email" id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            </div>

                            <div class="form-group">
                                <label for="password">Password<span>*</span></label>
                                <input type="password" placeholder="Password" id="password" type="password" name="password" required autocomplete="current-password" />
                            </div>

                            <div class="form-group checkgroup">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forget-pass">Forgot Password?</a>
                                @endif
                            </div>

                            <div class="form-group text-center">
                                <input type="submit" value="log in">
                            </div>
                        </form>

                        <div class="option">
                            Don't have an account? <a href="{{ route('register') }}">register now</a>.
                        </div>

                        <div class="or"><span>Or</span></div>

                        
                    </div>
                </div>
            </div>
        </section>
@endsection
