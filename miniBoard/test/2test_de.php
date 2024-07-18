<?php
require_once("2test_dephp.php")
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>삭제 페이지</title>
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
                <a href="./1detail.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="li_a">back</a>
            </div>
        </div>
        <div class="li_main">
            <div class="li_main_header">
                <div class="li_main_name">삭제 PAGE</div>
                <p>
                    삭제하면 영구적으로 복구 할 수 없습니다.
                <br>
                    정말로 삭제 하시겠습니까?
                </p>
                <br><br>
                <form action="./2test_dephp.php" method="post">
                    <div class="main-bottom">
                        <input type="hidden" name="no" value="<?php echo $no; ?>">
                        <button type="submit" class="a-button small-button">동의</button>
                    </div>
                </form>
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
                        <div class="li_card_no">created</div>
                        <div class="li_card_no2"><?php echo $item["content"] ?></div>
                    </div>
                    <div class="li_card_item">
                        <div class="li_card_name">content</div>
                        <div class="li_card_name2"><?php echo $item["created_at"] ?></div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</body>
</html>