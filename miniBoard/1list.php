<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/1config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 6; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

try {
    // DB Connect
    $conn = my_db_conn(); // connection 함수 호출

    // 파라미터에서 page 획득
    $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;
    
    // 게시글 수 조회
    $result_board_cnt = db_select_boards_cnt($conn);
    
    // 페이지 관련 설정 셋팅
    $max_page_num = ceil($result_board_cnt / $list_cnt); // 최대 페이지 수
    $offset = $list_cnt * ($page_num - 1); // 오프셋
    
    
    // 페이지네이션
    $start_page = (int)(ceil($page_num / $list_cnt) * $list_cnt - ($list_cnt - 1));
    $end_page = $start_page + ($list_cnt - 1);
    $end_page = $end_page > $max_page_num ? $max_page_num : $end_page;


    $prev_page_num = ($page_num -1) < 1 ? 1 : ($page_num - 1) ; // 이전 버튼 페이지 번호
    $next_page_num = ($page_num +1) > $max_page_num  ? $max_page_num : ($page_num + 1); // 다음 버튼 페이지 번호




    // 게시글 리스트 조회
    $arr_param =[
        "list_cnt"  => $list_cnt
        ,"offset"   => $offset
    ];
    $result = db_select_boards_paging($conn, $arr_param);

    // var_dump($result);
    // exit;

} catch (\Throwable $err) {
    echo $err->getMessage();
    exit;
} finally {
    // PDO 파기
    if(!empty($conn)) {
        $conn = null;
    }
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리스트 페이지</title>
    <link rel="stylesheet" href="./1css/1common.css">
</head>
<body>
    <?php require_once(FILE_HEADER) ?>
    <div class="list_container">
        <div class="moon">
            <div class="moon1"></div>
            <div class="moon2"></div>
        </div>
        <div class="li_header">
            <div class="li_header_back">
                <a class="li_a" href="./1main.php">home</a>
            </div>
        </div>

        <div class="li_main">
            <div class="li_main_header">
                <div class="li_main_name">LIST PAGE</div>
                <div class="li_create_btn">
                    <a class="a-button" href="./1insert.php">글 작성</a>
                </div>
                <div class="main-bottom">
                    <a href="./1list.php?page=<?php echo $prev_page_num ?>" class="small-button">이전</a>
                    <?php 
                      for($num = $start_page; $num <= $end_page; $num++) {
                    ?>
                    <a href="./1list.php?page=<?php echo $num ?>" class="small-button <?php echo ($num === (int)$page_num) ? "color-button" : ""; ?>"><?php echo $num ?></a>
                    <?php 
                     }  
                    ?>
                    <a href="./1list.php?page=<?php echo $next_page_num ?>" class="small-button">다음</a>
                </div>
            </div>
            <div class="li_main_item2">
                <?php
                foreach($result as $item) {
                ?>   
                <div class="li_main_card">
                    <a class="li-button" href="./1detail.php?no=<?php echo $item["no"]?>&page=<?php echo $page_num ?>">
                        <div class="li_card_item">
                            <div class="li_card_no">no</div>
                            <div class="li_card_no2"><?php echo $item["no"] ?> </div>
                        </div>
                        <div class="li_card_item">
                            <div class="li_card_name">title</div>
                            <div class="item_card_name"><?php echo $item["title"] ?></div>
                        </div>
                        <div class="li_card_item">
                            <div class="li_card_created_at">created</div>
                            <div class="li_card_created_at2"><?php echo $item["created_at"] ?></div>
                        </div>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>