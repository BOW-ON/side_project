<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/1config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

// POST 처리
if (REQUEST_METHOD === "POST" ) {
    try {
        // 파라미터 획득
        $title = isset($_POST["title"]) ? trim($_POST["title"]) : ""; // title 획득
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : ""; // content 획득
        
        // 파라미터 에러 체크
        $arr_err_param =[];
        if ($title === "") {
            $arr_err_param[] = "title";
        }
        if($content === "") {
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0) {
            // 예외 처리
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        
        //DB connect
        $conn = my_db_conn(); // PDO 인스턴스

        //Transaction 시작
        $conn->beginTransaction();

        // 게시글 작성 처리
        $arr_err_param = [
            "title" => $title
            ,"content" => $content
        ];
        $result = dba_insert_boards($conn, $arr_err_param);

        // 글 작성 예외처리
        if($result !== 1) {
            throw new Exception("Insert Boards count");
        }

        // 커밋
        $conn->commit();

        // 리스트 페이지로 이동
        header("Location: 1list.php");
        exit;
    } catch (\Throwable $err) {
        if(!empty($conn)) {
            $conn->rollBack();
        }
        echo $err->getMessage();
        exit;
    } finally {
        if(!empty($conn)) {
            $conn = null;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>작성 페이지</title>
    <link rel="stylesheet" href="./1css/1common.css">
</head>
<body>
    <?php require_once(FILE_HEADER) ?>
    <form action="./1insert.php" method="post">
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
                    <div class="li_main_name">작성 PAGE</div>
                    <div class="b-tutton">
                        <button type="submit" class="a-button">작성완료</button>
                    </div>
                </div>
                <div class="li_main_item">
                    <div class="li_main_card">
                        <div class="li_card_item">
                            <label for="title" class="li_card_name"><h2>title</h2></label>
                            <div class="li_card_name2">
                                <input type="text" name="title" id="title">
                            </div>
                        </div>   
                        <div class="li_card_item">
                            <label for="content" class="li_card_name"><h2>content</h2></label>
                            <div class="li_card_name2">
                                <textarea name="content" id="content" cols="auto" rows="8"></textarea>                    
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>