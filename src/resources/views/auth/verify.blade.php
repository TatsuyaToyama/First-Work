@extends('layouts.apphome')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/verify.css') }}" />
@endsection

@section('content')

<div class="contents_confirm-sentence">
    @if (session('resent'))
        <div class="contents_confirm-alert" role="alert">
            {{ __('ご登録いただいたメールアドレスに確認用のリンクをお送りしました。')}}
        </div>
    @endif
    <div class="contents_confirm">{{ __('メールアドレスをご確認ください') }}</div>
    <div class="contents_confirm-sent">{{ __('もし確認用メールが送信されていない場合は、下記をクリックしてください。') }}</div>

    <form class="contents_confirm-resent" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button class="contents_confirm-resent-submit" type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('確認メールを再送信する') }}</button>
    </form>
    <a class="contents_confirm-done" href="/">メールを確認済みの方はこちら</a>
</div>

@endsection
