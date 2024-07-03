<?php

function my_db_conn() {
    // 설정 정보
    $option = [
        PDO::ATTR_EMULATE_PREPARES      => FALSE
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
    ];

    // 리턴
    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

function db_select_boards_cnt($conn) {
    // sql 작성
    $sql = 
        " SELECT " 
        ."	COUNT(no) as cnt " 
        ." FROM " 
        ."	boards1 " 
        ." WHERE " 
        ."	deleted_at IS NULL "
        ;

    // Query 실행
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();

    // 리턴
    return (int)$result[0]["cnt"];



}

function db_select_boards_paging(&$conn, &$array_param) {
    // sql 작성
    $sql =
        " SELECT " 
        ."  no " 
        ."  ,title " 
        ."  ,created_at " 
        ." FROM " 
        ."  boards1 " 
        ." WHERE " 
        ."  deleted_at IS NULL " 
        ." ORDER BY  " 
        ."  no DESC " 
        ." LIMIT :list_cnt OFFSET :offset "
    ;
    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    // 리턴
    return $result;

}


// ■ Insert row to boards 게시판 테이블 레코드 작성처리
function dba_insert_boards(&$conn, &$array_param) {
    $sql =
        " INSERT INTO boards1( "
        ." title "
        ." ,content "
        ." ) "
        ." VALUES( "
        ." :title " 
        ." ,:content "
        ." ) "
    ;
    
    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    
    // 리턴
    return $stmt->rowCount();
}

// pk로 게시글 정보 조회
function db_select_boards_no(&$conn, &$array_param) {
    // SQL
    $sql =
        " SELECT " 
        ."	no 	" 
        ."	,title " 
        ."	,content " 
        ."	,created_at " 
        ." FROM 		" 
        ."	boards1 " 
        ." WHERE " 
        ."	no = :no "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    // 리턴
    return $result; 
}

// pk로 특정 게시글 삭제 처리
function db_delete_boards_no(&$conn, &$array_param) {
    // SQL
    $sql =
        " UPDATE boards1 "
        ." SET "
        ." deleted_at = NOW() "
        ." WHERE "
        ." no = :no "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    // 리턴
    return $stmt->rowCount(); 
}


// pk로 특정 레코드 수정
function db_update_boards_no(&$conn, &$array_param) {
    // SQL
    $sql =
        " UPDATE boards1 "
        ." SET "
        ."  title = :title "
        ."  ,content = :content "
        ." ,updated_at = NOW() "
        ." WHERE "
        ."  no = :no "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    // 리턴
    return $stmt->rowCount(); 
}





// 상세페이지에서 이전 버튼, 다음 버튼
// 함수
function next_btn(&$conn, &$array_param){
    $sql =
        " SELECT "
        ." no "
        ." FROM "
        ." boards1 "
        ." WHERE "
        ." no > :no "
        ." AND deleted_at IS NULL "
        ." ORDER BY no ASC "
        ." LIMIT 1 "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchColumn();

    return $result ? $result : null;
}
function prev_btn(&$conn, &$array_param){
    $sql=
        " SELECT "
        ." no" 
        ." FROM "
        ." boards1 " 
        ." WHERE "
        ." no < :no " 
        ." AND "
        ." deleted_at IS NULL " 
        ." ORDER BY no DESC " 
        ." LIMIT 1 "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchColumn();

    return $result ? $result : null;
}

function max_no_sql(&$conn){
    $sql =
        " SELECT "
        ." MAX(no) no "
        ." FROM boards1 "
        ." WHERE "
        ." deleted_at IS NULL "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return $result[0]["no"];
}

function min_no_sql(&$conn){
    $sql =
        " SELECT "
        ." MIN(no) no "
        ." FROM "
        ." boards1 "
        ." WHERE "
        ." deleted_at IS NULL "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return $result[0]["no"];
}