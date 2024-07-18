<?php
require_once("2test_liphp.php");


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
                    <a href="./2test_li.php?page=<?php echo $prev_page_num ?>" class="small-button">이전</a>
                    <?php 
                      for($num = $start_page; $num <= $end_page; $num++) {
                    ?>
                    <a href="./2test_li.php?page=<?php echo $num ?>" class="small-button <?php echo ($num === (int)$page_num) ? "color-button" : ""; ?>"><?php echo $num ?></a>
                    <?php 
                     }  
                    ?>
                    <a href="./2test_li.php?page=<?php echo $next_page_num ?>" class="small-button">다음</a>
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