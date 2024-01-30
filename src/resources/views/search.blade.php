@extends('layouts.apphome')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/search.css') }}" />
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
    <div class="contents_greeting">
        <p class="contents_greeting-content">{{$search_user[0]['name']}}さんの出勤一覧</p>
    </div>

    <div class="table">
        <table class="table_contents">
            <tr class="table_contents-row">
                <th class="table_contents-name">　日付　</th>
                <th class="table_contents-workstart">勤務開始</th>
                <th class="table_contents-workend">勤務終了</th>
                <th class="table_contents-breaktime">休憩時間</th>
                <th class="table_contents-worktime">勤務時間</th>
            </tr>
            @foreach($search_user as $result)
                <tr class="table_contents-row">
                    <td class="table_item">{{$result['date']}}</td>
                    <td class="table_item">{{$result['work_start']}}</td>
                    <td class="table_item">{{$result['work_end']}}</td>
                    <td class="table_item">{{$result['break_total']}}</td>
                    <td class="table_item">{{$result['work_time']}}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="result_pagenumber">
        <div class="result_pagenumber-paginate"> 
                {{ $search_user->appends(session('_old_input'))->links('vendor.pagination.tailwind') }}                         
        </div>
    </div>
@endsection
