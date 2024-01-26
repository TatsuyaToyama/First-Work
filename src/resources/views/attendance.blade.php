@extends('layouts.apphome')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/attendance.css') }}" />
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

<!-- 確認用 -->
<!-- @dump(old('selectedDate', \Carbon\Carbon::now()->format('Y-m-d'))) -->
<!-- @dump($search_date) -->


<script>
    function redirectToURL(input) {
        var selectedDate = input.value;
        var redirectTo = '/attendance';
        window.location.href = redirectTo;
    }
</script>


<div class="contents">
    <div class="contents_title">
        <form class="contents_title-inner" action="/attendance/submit" method="post">
            @csrf
            <input class="contents_title-input" type="date" name="selectedDate" value="{{ old('selectedDate', \Carbon\Carbon::now()->format('Y-m-d')) }}">
            <button class="contents_title-search">検索</button>
        </form>
        <form class="contents_title-search" action="/attendance" method="post">
            @csrf
            <button class="contents_title-back">本日に戻す</button>
        </form>
    </div>

    <div class="table">
        <table class="table_contents">
            <tr class="table_contents-row">
                <th class="table_contents-name">　名前　</th>
                <th class="table_contents-workstart">勤務開始</th>
                <th class="table_contents-workend">勤務終了</th>
                <th class="table_contents-breaktime">休憩時間</th>
                <th class="table_contents-worktime">勤務時間</th>
            </tr>

            @foreach($search_date as $result)
                <tr class="table_contents-row">
                    <td class="table_item">{{$result['name']}}</td>
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
                {{ $search_date->appends(session('_old_input'))->links('vendor.pagination.tailwind') }}                         
        </div>
        

    </div>


    


</div>


@endsection