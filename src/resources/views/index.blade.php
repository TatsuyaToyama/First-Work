@extends('layouts.apphome')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
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
    <script>
        var searchItemDate = @json($search_item['date']);

        function setSubmissionTime(InptId1,InptId2) {
            var options = { timeZone: 'Asia/Tokyo',  locale: 'ja-JP' };
            var currentDate = new Date(searchItemDate).toLocaleDateString(undefined, options);
            var currentTime = new Date().toLocaleTimeString(undefined, options,{ hour12: false });

            document.getElementById(InptId1).value = currentTime;
            document.getElementById(InptId2).value = currentDate;
            document.getElementById('timeForm').submit();
        }
    </script>


    <div class="contents">
        <div class="contents_greeting">
            <p class="contents_greeting-content">{{$search_item['name']}}さんお疲れ様です！</p>
        </div>

        <div class="contents_button">
            <form class="contents_form" action="/record" method="post" id="timeForm">
                @csrf
                <table class="contents_button-table">
                    <tr class="button-row">
                        <td class="button-each">
                            <input type="hidden" name="name" value="{{$search_item['name']}}">
                            <input type="hidden" name="date" id="date">
                            <input type="hidden" name="work_start" id="work_start">
                            <button class="button-each_submit" type="submit" onclick="setSubmissionTime('work_start','date')" {{ $search_item['work_start'] ? 'disabled' : '' }}>勤務開始</button>
                        </td>
                        <td class="button-each">
                            <input type="hidden" name="work_end" id="work_end">
                            <button class="button-each_submit" type="submit" onclick="setSubmissionTime('work_end','date')"{{ !$search_item['work_start']||$search_item['work_end']||$search_item['break_start'] ? 'disabled' : '' }}>勤務終了</button>
                        </td>
                    </tr>
                    <tr class="button-row">
                        <td class="button-each">
                            <input type="hidden" name="break_start" id="break_start">
                            <button class="button-each_submit" type="submit" onclick="setSubmissionTime('break_start','date')"{{ $search_item['break_start']||$search_item['work_end']||!$search_item['work_start'] ? 'disabled' : '' }}>休憩開始</button>
                        </td>
                        <td class="button-each">
                            <input type="hidden" name="break_end" id="break_end">
                            <button class="button-each_submit" type="submit" onclick="setSubmissionTime('break_end','date')"{{ $search_item['break_start'] ? '' : 'disabled' }}>休憩終了</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection
