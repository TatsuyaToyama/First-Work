@extends('layouts.apphome')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')

<x-guest-layout>
    <x-jet-authentication-card>
        <!-- <x-slot name="logo">

        </x-slot>

        <x-jet-validation-errors class="mb-4" /> -->

        <!-- @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif -->


        @if($errors->any())
            <div class="mb-4 font-medium text-sm text-red-600">
                {{ $errors->first('error') }}
            </div>
        @endif

        <div class="logintitle">
            <p class="logintitle_content">ログイン</p>
        </div>

        <form class="input" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input_inner">
                <x-jet-input  class="input_inner-content" id="email" placeholder="メールアドレス" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="input_inner">
                <x-jet-input class="input_inner-content" id="password" placeholder="パスワード" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="input_inner">
                <x-jet-button class="input_submit">
                    {{ __('ログイン') }}
                </x-jet-button>
            </div>
        </form>
        <div class="input_new">
            <p class="input_new-sentence">アカウントをお持ちでない方はこちら</p>
            <a class="input_new-register" href="/register">会員登録</a>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
@endsection