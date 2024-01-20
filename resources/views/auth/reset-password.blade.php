@extends('client.layout.app')
@section('content')
<x-guest-layout>
    <div class="container">
        <div class="padding-top padding-bottom">
            <div class="account-area">
                {{-- <div class="section-header-3">
                    <span class="cate">{{ __('hello !') }}</span>
                    <h2 class="title">{{ __("don't worry") }}</h2>
                </div> --}}

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                    <div class="form-group">
                        <label for="email" class="text-white">{{ __('Email') }}<span>*</span></label>
                        <input id="email" class="block mt-1 w-full " type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    </div>
                
                    <div class="form-group mt-4">
                        <label for="password" class="text-white">{{ __('Password') }}<span>*</span></label>
                        <input id="password" class="block mt-1 w-full " type="password" name="password" required autocomplete="new-password">
                    </div>
                
                    <div class="form-group mt-4">
                        <label for="password_confirmation" class="text-white">{{ __('Confirm Password') }}<span>*</span></label>
                        <input id="password_confirmation" class="block mt-1 w-full " type="password" name="password_confirmation" required autocomplete="new-password">
                    </div>
                
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="custom-button">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
                

                <div class="option" style="color: white">
                    {{ __('We send a password reset link to your email.') }}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
@endsection