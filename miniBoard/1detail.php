<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/1config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // PDO 인스턴스 생성
    
    // 게시글 데이터 조회
    // 파라미터 획득
    $no = isset($_GET["no"]) ? $_GET["no"] : ""; // no 획득
    $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득

    // 파라미터 예외처리
    $arr_err_param = [];
    if( $no === "") {
        $arr_err_param[] = "no";
    }
    if( $page === "") {
        $arr_err_param[] = "page";
    }
    if(count($arr_err_param) > 0) {
        throw new Exception("Parameter Error : ".implode( ",", $arr_err_param));
    }

    // 게시글 정보 획득
    $arr_param = [
        "no" => $no
    ];
    $result = db_select_boards_no($conn, $arr_param);
    if(count($result) !==1 ) {
        throw new Exception("Select Boards no count");
    }

    // 상세 페이지에 이전 버튼, 다음 버튼 만들기
    $max_board_no = max_no_sql($conn);
    $min_board_no = min_no_sql($conn);
    $prev_btn_result = prev_btn($conn, $arr_param);
    $next_btn_result = next_btn($conn, $arr_param);



    // 아이템 셋팅
    $item = $result[0];

} catch (\Throwable $err) {
    echo $err->getMessage();
    exit;
} finally {
    if(!empty($conn)) {
        // PDO 파기
        $conn = null;
    }
}


?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상세 페이지</title>
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
                <a class="li_a" href="./1list.php">back</a>
            </div>
        </div>
        <div class="li_main">
            <div class="li_main_header">
                <div class="li_main_name">상세 PAGE</div>
                <div class="b-button">


                    <a href="./1detail.php?no=<?php if($prev_btn_result !== null){ echo $prev_btn_result; } if($no == $min_board_no){ echo $min_board_no; }?>&page=<?php echo $page ?>" class="a-button small-button">이전글</a>

                    
                    
                    <a href="./1update.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="a-button small-button">수정</a>
                    <a href="./2test_de.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="a-button small-button">삭제</a>



                    <a href="./1detail.php?no=<?php if($next_btn_result !== null){ echo $next_btn_result; } if($no == $max_board_no){ echo $max_board_no; } ?>&page=<?php echo $page ?>" class="a-button small-button">다음글</a>
                
                
                </div>
            </div>
            <div class="li_main_item">
                <div class="li_main_card">
                    <div class="li_card_item">
                        <div class="li_card_no">no</div>
                        <div class="li_card_no2"><?php echo $item["no"] ?></div>
                    </div>
                    <div class="li_card_item">
                        <div class="li_card_name">title</div>
                        <div class="li_card_name2"><?php echo $item["title"] ?></div>
                    </div>   
                    <div class="li_card_item">
                        <div class="li_card_no">content</div>
                        <div class="li_card_no2"><?php echo $item["content"] ?></div>
                    </div>
                    <div class="li_card_item">
                        <div class="li_card_name">created</div>
                        <div class="li_card_name2"><?php echo $item["created_at"] ?></div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</body>
</html>