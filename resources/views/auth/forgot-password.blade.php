@extends('client.layout.app')
@section('content')
        <div class="main-page-header speaker-banner" style="background: url('/img/banner/banner-2.jpg');">
            <div class="container">
                <div class="speaker-banner-content">
                    <h2 class="title">forgot password</h2>
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('home')}}">
                                Home
                            </a>
                        </li>
                        <li>
                            forgot password
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="account-section">
            <div class="container">
                <div class="padding-top padding-bottom">
                    <div class="account-area">
                        <div class="section-header-3">
                            <span class="cate">hello !</span>
                            <h2 class="title">don't worry</h2>
                        </div>
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <x-validation-errors class="mb-4" />

                        <form method="POST" action="{{ route('password.email') }}" class="account-form">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email<span>*</span></label>
                                <input type="text" placeholder="Email" name="email" :value="old('email')" required autofocus autocomplete="username">
                            </div>

                            <div class="form-group text-center">
                                <input type="submit" value="reset link">
                            </div>
                        </form>
                        <div class="option" style="color: white">
                            We send a password reset link to your email.
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection