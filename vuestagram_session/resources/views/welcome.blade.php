<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- csrf 토큰 : 공격 방어하기 위해서--}}
        {{-- meta 데이터 안에 작성할 수 있고, 폼안에 @태그로 작성할 수도 있음(@csrf) --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- asset은 public 부분 --}}
        <script src="{{ asset('js/app.js') }}" defer></script>

        <title>vuestagram</title>

    </head>
    <body>
        {{-- #app 만들어서 연결하기 --}}
        <div id="app">
            {{-- 최초 접근할 파일 : AppComponet.vue --}}
            <App-Component></App-Component>
        </div>
    </body>
</html>
