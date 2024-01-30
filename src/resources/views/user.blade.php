@extends('layouts.apphome')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/user.css') }}" />
@endsection

@section('header')
    <div class="header_list">
        <a class="header_home" href="/">ホーム</a>
        <a class="header_date" href="/attendance">日付一覧</a>
        <a class="header_user" href="/user">ユーザー一覧</a>
        <a class="header_logout" href="/logout">ログアウト</a>
    </div>
@endsection

@section('content')
    <div class="contents_title">
        <p class="contents_title-content">ユーザー一覧</p>
    </div>
    <div class="table">
        <table class="table_contents">
            <tr class="table_contents-row">
                <th class="table_contents-name">名前</th>
            </tr>
            @foreach($search_user as $result)
                <tr class="table_contents-row">
                    <td class="table_item">
                        <form class="user_result" action="/user/result" method="post">
                        <input type="hidden" name="name" value="{{$result['name']}}">
                        @csrf
                        <button class="user_result-submit">{{$result['name']}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="result_pagenumber">
        <div class="result_pagenumber-paginate"> 
                {{ $search_user->links('vendor.pagination.tailwind') }}                         
        </div>
    </div>
@endsection


