@extends('layouts.apphome')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}" />
@endsection

@section('content')



<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <div class="register_title">
            <p class="register_title-content">会員登録</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="register">
                <x-jet-input id="name" class="register_content" placeholder="名前" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="register">
                <x-jet-input id="email" class="register_content" placeholder="メールアドレス" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="register">
                <x-jet-input id="password" class="register_content" placeholder="パスワード" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="register">
                <x-jet-input id="password_confirmation" class="register_content" placeholder="確認用パスワード" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="register_comfirm">
                <x-jet-button class="register_submit">
                    {{ __('会員登録') }}
                </x-jet-button>

                <div class="register_already-inner">
                    <a class="register_already" href="{{ route('login') }}">{{ __('既に登録済みですか？') }}</a>
                </div>

            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

@endsection