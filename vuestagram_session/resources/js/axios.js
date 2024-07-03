import axios from "axios";

// 세션으로 로그인 하기 위해 기본설정
//  >> welcome.blade.php의 헤더에 csrf를 가져와서 모든 axios 처리 할때 csrf를 적용함
const axiosInstance = axios.create({
    // 기본 헤더 설정
    headers: {
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    // axios로 API요청 할때, 세션쿠키가 포함되도록 하는 설정
    withCredentials: true,
});