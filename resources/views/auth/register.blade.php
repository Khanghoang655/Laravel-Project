@extends('client.layout.app')
@section('content')
    <section class="main-page-header speaker-banner" style="background: url('/img/banner/banner-2.jpg');">
        <div class="container">
            <div class="speaker-banner-content">
                <h2 class="title">register</h2>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('home') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        register
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section class="account-section bg_img" data-background="/images/account/account-bg.html">
        <div class="container">
            <div class="padding-top padding-bottom">
                <div class="account-area">
                    <div class="section-header-3">
                        <span class="cate">welcome !</span>
                        <h2 class="title">Create Account</h2>
                    </div>
                    <div id="emailVerificationForm">
                        <form id="verificationForm" action="{{ route('send-verification-email') }}" method="POST"
                            onsubmit="return sendVerificationEmail()">
                            @csrf
                            <div class="mt-4 row">
                                <div class="col-12" id="emailVerificationField">
                                    <x-label for="email" value="{{ __('Email') }}" />
                                    <x-input class="form-control" type="email" name="email" id="verificationEmail"
                                        required />
                                </div>
                                <div class="col-12">
                                    <x-button id="sendVerificationButton" class="btn btn-sm btn-primary">
                                        Gửi mã xác nhận
                                    </x-button>
                                </div>
                                <div class="col-12" id="cooldownMessage"
                                    style="display: none; color: red; margin-top: 8px;">
                                    <p> Vui lòng đợi <span id="countdownDisplay">60</span> giây </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    <x-validation-errors class="mb-4" />
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="mt-4">
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required />
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="verification_code" value="{{ __('Verification Code') }}" />
                            <x-input id="verification_code" class="block mt-1 w-full" type="text"
                                name="verification_code" :value="old('verification_code')" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </a>
                            <x-button class="ms-4 btn btn-danger" @click.prevent="submitFormAndSendVerificationEmail()">
                                {{ __('Register') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function startCountdown(seconds = 60) {
            const countdownDisplay = document.getElementById('countdownDisplay');
            const sendVerificationButton = document.getElementById('sendVerificationButton');
            const cooldownMessage = document.getElementById('cooldownMessage');

            countdownDisplay.textContent = seconds;
            sendVerificationButton.disabled = true;
            cooldownMessage.style.display = 'block';

            const intervalId = setInterval(() => {
                seconds--;
                countdownDisplay.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(intervalId);
                    localStorage.removeItem('verificationCodeCooldown');
                    sendVerificationButton.disabled = false;
                    cooldownMessage.style.display = 'none';
                }
            }, 1000);
        }

        function sendVerificationEmail() {
            var form = document.getElementById('emailVerificationForm');
            var userEmail = form.querySelector('input[name="email"]').value;

            if (!userEmail) {
                alert('Vui lòng nhập email.');
                return false;
            }
            document.getElementById('emailVerificationField').style.display = 'block';

            // Đặt giá trị vào trường email
            document.getElementById('verificationEmail').value = userEmail;
            var cooldown = localStorage.getItem('verificationCodeCooldown');
            if (cooldown) {
                const remainingSeconds = parseInt(cooldown) - Math.floor(Date.now() / 1000);
                if (remainingSeconds > 0) {
                    startCountdown(remainingSeconds);
                    return false;
                } else {
                    localStorage.removeItem('verificationCodeCooldown');
                }
            }

            axios.post('/send-verification-email', {
                    email: userEmail
                })
                .then(response => {

                    // Lưu timestamp hiện tại vào localStorage
                    var currentTimestamp = Math.floor(Date.now() / 1000);
                    localStorage.setItem('verificationCodeCooldown', currentTimestamp + 30);
                })
                .catch(error => {
                    console.error('Error sending verification email:', error);
                });
            return false;
        }
    </script>
@endsection
