<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/1config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리


?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link rel="stylesheet" href="./1css/1common.css">
</head>
<body>
    <?php require_once(FILE_HEADER) ?>
    
    <div class="mc_container">
        <div class="main_container1 mc_flex">
            <div class="mc_building1"></div>
            <div class="mc_building11">ㅁㅁㅁㅁㅁ</div>
        </div>
        <div class="main_container2 mc_flex">
            <div class="mc_building2"></div>
            <div class="mc_building22">ㅁㅁㅁㅁㅁㅁㅁ</div>
        </div>
        <div class="main_container3 mc_flex">
            <div class="mc_building3"></div>
            <div class="mc_building33">ㅁㅁㅁ</div>
        </div>
        <div class="main_container4 mc_flex">
            <div class="mc_building4"></div>
            <div class="mc_building44">ㅁㅁㅁㅁㅁ</div>
        </div>
        <div class="main_container5 mc_flex">
            <div class="mc_building5"></div>
            <div class="mc_building55">ㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁ<br>ㅁ</div>
        </div>
        <div class="main_container6 mc_flex">
            <div class="mc_building6"></div>
            <div class="mc_building66">ㅁㅁㅁㅁㅁ</div>
        </div>
        <div class="main_container7 mc_flex">
            <div class="mc_moon">
                <div class="mc_moon1"></div>
                <div class="mc_moon2"></div>
            </div>
            <div class="mc_title">
                <div class="mc_title1"></div>
                <div class="mc_title2">To Do List</div>
                <div class="mc_title3"></div>
            </div>
            <div class="mc_list">
                <div class="mc_list1"><a class="mc_a" href="./1insert.php">작성하기</a></div>
                <div class="mc_list2"><a class="mc_a" href="./1list.php">리스트보기</a></div>
            </div>
        </div>
    </div>
</body>
</html>