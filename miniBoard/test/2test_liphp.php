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
