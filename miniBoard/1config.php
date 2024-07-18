<?php
// ■ MariaDB 관련	
	define("MARIADB_HOST", "localhost");    // DB HOST		//상수 지정 다른 방법 : const MARIADB_HOST = "localhost";
	define("MARIADB_USER", "root");         // DB 유저
	define("MARIADB_PASSWORD", "php505");   // DB 비밀번호
	define("MARIADB_NAME", "test_board");   // DB 명
	define("MARIADB_CHARSET", "utf8mb4");   // DB 유니코드
	define("MARIADB_DSN", "mysql:host=".MARIADB_HOST.";dbname=".MARIADB_NAME.";charset=".MARIADB_CHARSET);
	
// ■ PHP Path 관련	
	define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/"); // 웹서버 root 패스
	define("FILE_HEADER", ROOT."1header.php"); // 헤더 파일 패스
	define("FILE_LIB_DB", ROOT."1lib/1lib_db.php"); // DB 파일 패스

// 유저 정보 요청
	define("REQUEST_METHOD", strtoupper($_SERVER["REQUEST_METHOD"]));