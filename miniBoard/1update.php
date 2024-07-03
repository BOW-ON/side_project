<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/1config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리


try {
    // DB Connect
    $conn = my_db_conn(); // PDO 인스턴스 생성

    if(REQUEST_METHOD === "GET"){
        // 파라미터 획득
        $no = isset($_GET["no"]) ? $_GET["no"] : ""; // no 획득
        $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득

        // 파라미터 예외처리
        $arr_err_param = [];
        if( $no === "") {
            $arr_err_param[] = "no";
        }
        if($page === "") {
            $arr_err_param[] = "page";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
        }

        // 게시글 정보 획득
        $arr_param = [
            "no" => $no
        ];
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result) !==1 ) {
            throw new Exception("Select Boards no count");
        }

        // 아이템 셋팅
        $item = $result[0];

    } else if(REQUEST_METHOD === "POST") {
        // 파라미터 획득
        $no = isset($_POST["no"]) ? $_POST["no"] : ""; // no 획득
        $page = isset($_POST["page"]) ? $_POST["page"] : ""; // page 획득
        $title = isset($_POST["title"]) ? $_POST["title"] : ""; // title 획득
        $content = isset($_POST["content"]) ? $_POST["content"] : ""; // content 획득

        // 파라미터 예외처리
        $arr_err_param = [];
        if( $no === "") {
            $arr_err_param[] = "no";
        }
        if($page === "") {
            $arr_err_param[] = "page";
        }
        if($title === "") {
            $arr_err_param[] = "title";
        }
        if($content === "") {
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
        }

        // Transaction 시작
        $conn->beginTransaction();
        
        // 게시글 수정 처리
        $arr_param = [
            "no" => $no
            ,"title" => $title
            ,"content" => $content
        ];
        $result = db_update_boards_no($conn, $arr_param);

        // 수정 예외 처리
        if($result !== 1) {
            throw new Exception("Update Boards no count");
        }

        $conn->commit();

        // 상세 페이지로 이동
        header("Location: 1detail.php?no=".$no."&page=".$page);
        exit;

    }

} catch (\Throwable $err) {
    if (!empty($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
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
    <title>수정 페이지</title>
    <link rel="stylesheet" href="./1css/1common.css">
</head>
<body>
<?php require_once(FILE_HEADER) ?>
    <form action="./1update.php" method="post">
        <input type="hidden" name="no" value="<?php echo $item["no"];?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <div class="list_container">
            <div class="moon">
                <div class="moon1"></div>
                <div class="moon2"></div>
            </div>
            <div class="li_header">
                <div class="li_header_back">
                    <a href="./1detail.php?no=<?php echo $no ?>&page=<?php echo $page?>" class ="li_a">back</a>
                </div>
            </div>
            <div class="li_main">
                <div class="li_main_header">
                    <div class="li_main_name">수정 PAGE</div>
                    <div class="b-tutton">
                        <button type="submit" class="a-button small-button">완료</button>
                    </div>
                </div>
                <div class="li_main_item">
                    <div class="li_main_card">
                        <div class="li_card_item">
                            <div class="li_card_no">no</div>
                            <div class="li_card_no2"><?php echo $item["no"] ?> </div>
                        </div>
                        <div class="li_card_item">
                            <label for="title" class="li_card_name">
                                <h2>title</h2>
                            </label>
                            <div class="li_card_name2">
                                <input type="text" name="title" id="title" value="<?php echo $item["title"]; ?>">
                            </div>
                        </div>   
                        <div class="li_card_item">
                            <label for="content" class="li_card_name">
                                <h2>content</h2>
                            </label>
                            <div class="li_card_name2">             
                                <textarea name="content" id="content" cols="auto" rows="8"><?php echo $item["content"]; ?></textarea>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>