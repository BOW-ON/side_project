<?php

namespace App\Http\Controllers;

use App\Exceptions\MyAuthException;
use App\Exceptions\MyValidateException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // ** 로그인 **
    public function login(Request $request) {

        // 유효성 검사
        $validator = Validator::make(
            // 검사할 항목
            $request->only('account','password')
            // 검사할 조건
            ,[
                'account' => ['required', 'min:4', 'max:20', 'regex:/^[a-zA-Z0-9]+$/']
                ,'password' => ['required', 'min:2', 'max:20', 'regex:/^[a-zA-Z0-9]+$/']
            ]
        );
        // 유효성 검사 실패시 처리
        // fails() 메소드 : 실패시 true
        if($validator->fails()) {
            // Log를 사용할때 2번째 파라미터는 배열형태로 넣어야됨(toArray()을 사용하여 변환하기)
            Log::debug('유효성 검사 실패', $validator->errors()->toArray());
            throw new MyValidateException('E01');
        }

        // 유저정보 획득 (DB에서 검색 후 select)
        $userInfo = User::select('users.*')
                            // 서브 쿼리 작성하는 법
                            ->selectsub(function($query) {
                                // DB::raw : 라라벨에서 데이터베이스라는 것을 인식하기 위해 사용
                                $query->select(DB::raw('count(*)'))
                                        ->from('boards')
                                        ->whereColumn('users.id', 'boards.user_id');
                            }, 'boards_count')
                            ->where('account', $request->account)
                            ->first();

        // 유저 정보 없음 확인
        if(!isset($userInfo)) {
            // 유저 없음
            throw new MyAuthException('E20');
        } else if(!(Hash::check($request->password, $userInfo->password))) {
            // $request->password : 입력한 평문 패스워드
            // $userInfo->password : DB에 있는 변환된 패스워드

            // 비밀번호 오류
            throw new MyAuthException('E21');
        }

        // 로그인 처리
        Auth::login($userInfo);

        // 레스폰스 데이터 생성
        $responseData = [
            'code' => '0'
            ,'msg' => '로그인 성공'
            ,'data' => $userInfo
        ];
        return response()->json($responseData, 200)->cookie('auth', '1', 120, null, null, false, false);
        // cookie(키, 값, 유지 시간, 패스(어떤경로에서 쓰일건가), 도메인, 시큐리티, HTTP: 프론트에서 읽을 수 있냐 없냐 차이 - false면 읽을 수 있음, true는 못 읽음 )
    }


    // ** 로그아웃 **
    public function logout() {
        // 로그 아웃 처리
        Auth::logout(Auth::user());

        // 세션 지우기 
        Session::invalidate(); // 기본 세션 파기하고 새로운 세션 생성
        Session::regenerateToken(); // CSRF 토큰 재발급

        // 레스폰스 데이터 생성
        $responseData = [
            'code' => '0'
            ,'msg' => '로그 아웃 완료'
        ];
        return response()->json($responseData, 200)->cookie('auth', '1', -1, null, null, false, false);
        // 쿠키는 유저 브라우저에 있으므로 제거를 직접적으로 할 수 없음 > 유지시간을 -1초로 변경하여 삭제 처리
    }

    // ** 회원 가입 **
    public function registration(Request $request) {
        // 리퀘스트 데이터 획득
        $requestData = $request->all();

        // 유효성 검사
        $validator = Validator::make(
            $requestData
            ,[
                'account' => ['unique:users', 'required' ,'min:4', 'max:20', 'regex:/^[a-zA-z0-9]+$/']
                    // 'unique:users' : users 테이블에서 중복 확인
                ,'password' => ['required' ,'min:2', 'max:20', 'regex:/^[a-zA-z0-9!@#$%^&*]+$/']
                ,'password_chk' => ['same:password']
                    // 'same:password' : password 와 동일한지 파악
                ,'name' => ['required' ,'min:2', 'max:20', 'regex:/^[가-힣]+$/u']
                    // 'regex:/^[가-힣]+$/u' : 컴퓨터가 글자를 숫자로 변환해서 인식하는데 각각 다른 숫자로 변환하기때문에 u(유니코드)로 통일
                ,'gender' => ['required', 'regex:/^[0-1]{1}$/']
                ,'profile' => ['required', 'image'] 
                    // image : 이미지를 저장할 수 있음 (특정 이미지 확장자만 사용가능함)
            ]
        );

        // 유효성 검사 실패 체크
        if($validator->fails()) {
            Log::debug('유효성 검사 실패', $validator->errors()->toArray());
            throw new MyValidateException('E01');
        }

        // 작성 데이터 생성
        $insertData = $request->all();

        // 파일 저장
        //  >> 기본 저장이 store에 저장됨 so) config/filesystems.php 에서 경로 수정
        $insertData['profile'] = '/'.$request->file('profile')->store('profile');
        // 파일 명을 문자열로 보내옴

        // 비밀번호 설정
        $insertData['password'] = Hash::make($request->password);

        // 인서트 처리
        $userInfo = User::create($insertData);

        // 레스폰스 데이터 생성
        $responseData = [
            'code' => '0'
            ,'msg' => '회원 가입 완료'
            ,'data' => $userInfo
        ];

        return response()->json($responseData, 200);
    }

}
