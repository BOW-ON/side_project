import { createStore } from 'vuex';
import router from './router';

const store = createStore({
    state() {
        return {

            // 로그인 상태를 체크하기 위해 변수 설정
            // indexOf() : 값이 있으면 0, 없으면 -1을 리턴함
            authFlg: document.cookie.indexOf('auth=') >= 0 ? true : false ,


            // localStorage에 userInfo가 저장되어 있으면 그 값을 가져와서(getItem) 사용하기 편하게 다시 객체 형태로 바꿈(parse 메소드)
            userInfo: localStorage.getItem('userInfo') ? JSON.parse(localStorage.getItem('userInfo')) : null,

            // 게시글 데이터를 담을 변수 설정
            boardData: [],

            // 더보기 버튼을 조절할 flg을 담아둘 변수 설정
            moreBoardFlg : true,
        }
    },
    mutations: {
        // 인증 플레그 저장
        setAuthFlg(state, flg) {
            state.authFlg = flg
        },

        // 유저 정보 저장
        setUserInfo(state, userInfo) {
            state.userInfo = userInfo;
        },

        // ** 게시글 삽입(게시글 불러오기) **
        // 게시글 초기 삽입
        setBoardData(state, data) {
            state.boardData = data;
        },
        // 보드리스트의 가장 앞에 작성한 글정보 추가
        setUnshiftBoardData(state, data) {
            state.boardData.unshift(data);
        },
        // 유저의 작성글 수 1 증가
        setUserBoardCount(state) {
            state.userInfo.boards_count++;
        },
        
        // ** 게시글 삭제 **
        // 게시글 목록에서 빼기
        setUserBoardData(state, index) {
            console.log(state.boardData) // TODO
            state.boardData.splice(index, 1);
            console.log(state.boardData) // TODO
        },
        // 유저의 작성글 수 1 감소
        setUserBoardsCountSub(state) {
            state.userInfo.boards_count--;
        },

        
        // ** 더보기 버튼 **
        // 더보기 버튼 플래그 저장
        setMoreBoardFlg(state, flg) {
            state.moreBoardFlg = flg;
        },
        // 더보기 게시글 추가
        setMoreBoardData(state, data) {
            // 기존 습득했던 데이터와 더보기로 습득한 데이터를 합치기 (... 이용)
            state.boardData = [...state.boardData, ...data];
        },
        


    },
    actions: {
        
        // --------------------------
        // ** 접속 관련 **
        // --------------------------

        /**
         * 로그인 처리
         * 
         * @param {store} context
         */
        login(context) {
            const url = '/api/login';
            const form = document.querySelector('#loginForm');
            const data = new FormData(form);
            
            axios.post(url, data)
            .then(response => {
                console.log(response.data); // TODO
                // 객체형태의 데이터를 json형태로 변환하여(stringify 메소드) localStorage에 저장(setItem)
                //  >> localStorage는 보통 문자로 저장되므로 객체로 받은 데이터를 저장할 수가 없음
                // localStorage.setItem 와 context.commit으로 두번 저장하는 이유 : 새로고침을 할 시 데이터를 없어지기 때문에
                localStorage.setItem('userInfo', JSON.stringify(response.data.data));
                
                // 뮤테이션스 호출 (context.commit)
                context.commit('setUserInfo', response.data.data);

                // 로그인 시 setAuthFlg 에 true로 변환
                context.commit('setAuthFlg', true);

                // 라우터를 이용하여 로그인 처리 후 board로 이동
                router.replace('/board');

            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('로그인에 실패했습니다. (' + error.response.data.code + ')')
            });
        },

        /**
         * 로그아웃 처리
         * 
         * @param {store} context
         */
        logout(context) {
            const url = '/api/logout';

            axios.post(url)
            .then(response => {
                console.log(response.data); // TODO
            })
            .catch(error => {
                console.log(error.response) // TODO
                alert('문제가 발생하여 강제 로그아웃 됩니다. (' +  error.response.data.code + ')')
            })
            .finally( () => {
                // 쿠키도 지워야됨(UserController.php 에서 처리)

                // localStorage, setAuthFlg, setUserInfo 비우기
                localStorage.clear(); 
                context.commit('setAuthFlg', false);
                context.commit('setUserInfo', null); 

                // 보드 초기화
                context.commit('setBoardData', []);

                // 라우터를 이용하여 로그아웃 처리 후 login 페이지로 이동
                router.replace('/login');
            });
        },
        
        /**
         * 회원가입 처리
         * 
         * @param {*} context
         */
        registration(context) {
            const url = '/api/registration';
            const data = new FormData(document.querySelector('#registrationForm'));

            axios.post(url, data)
            .then(response => {
                console.log(response.data); // TODO

                router.replace('/login');
            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('회원가입에 실패했습니다. (' + error.response.data.code + ')')
            });
        },


        

        // --------------------------
        // ** 게시글 관련 **
        // --------------------------

        /**
         * 최초 게시글 획득
         * 
         * @param {*} context
         */
        getBoardData(context) {
            const url = '/api/board';

            axios.get(url)
            .then(response => {
                console.log(response.data); // TODO

                // 보내온 데이터를 뮤테이션의 setBoardData 를 통해 state에 삽입
                context.commit('setBoardData', response.data.data);
            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('게시글 습득에 실패 하였습니다. (' + error.response.data.code + ')')
            });
        },

        /**
         * 추가 게시글 획득
         * 
         * @param {*} context
         */
        getMoreBoardData(context) {
            // 마지막 게시글 번호 획득
            const lastItem = context.state.boardData[context.state.boardData.length - 1];

            // 마지막 게시글 번호의 url을 서버에 보냄
            const url = '/api/board/' + lastItem.id;

            axios.get(url)
            .then(response => {
                console.log(response.data); // TODO

                // 받은 response.data.data를 뮤테이션의 setMoreBoardData를 이용하여 데이터를 합침
                context.commit('setMoreBoardData', response.data.data);

                // 더보기 버튼 플래그 갱신 (더보기 버튼을 사라지게 만들기 위해)
                if(response.data.data.length < 20) {
                    context.commit('setMoreBoardFlg', false);
                }
                

            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('추가 게시글 획득에 실패했습니다. (' + error.response.data.code + ')')
            });
            
        },

        /**
         * 유저 아이디를 입력했을때 해당 게시글 획득
         * 
         * @param {*} context
         */
        accountIndex(context, account) {
            const url = '/api/accountIndex/' + account;

            axios.get(url)
            .then(response => {
                console.log(response.data); // TODO

                // 보내온 데이터를 뮤테이션의 setBoardData 를 통해 state에 삽입
                context.commit('setBoardData', response.data.data);

            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('게시글 획득에 실패했습니다. ( ' + error.response.data.code + ' )')
            });
        },

        
        /**
         * 좋아요 버튼
         * 
         * @param {*} context
         */
        likeBtn(context, boardID) {
            const url = '/api/board/like/' + boardID;

            axios.post(url)
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('좋아요 클릭에 실패했습니다. ( ' + error.response.data.code + ' )')
            });
        },



        /**
         * 글작성 처리
         * 
         * @param {*} context
         */
        boardCreate(context) {
            const url = '/api/create';
            const data = new FormData(document.querySelector('#boardCreateForm'));

            axios.post(url, data)
            .then(response => {
                console.log(response.data); // TODO

                if(context.state.boardData.length > 1) {
                    // 보드데이터의 가장 앞에 작성한 글정보 추가
                    context.commit('setUnshiftBoardData', response.data.data);
                }

                // 유저의 작성글 수 1 증가
                context.commit('setUserBoardCount');
                localStorage.setItem('userInfo', JSON.stringify(context.state.userInfo));

                router.replace('/board');
            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('글 작성에 실패했습니다. (' + error.response.data.code + ')')
            });
        },

        /**
         * 글삭제 처리
         * 
         * @param {*} context
         */
        boardDelete(context, id) {
            const url = '/api/delete/' + id;

            axios.delete(url)
            .then(response => {
                console.log(response.data.data) // TODO
                console.log(typeof(response.data.data)) // TODO

                // 보드 데이터의 삭제한 글 정보 빼기
                context.state.boardData.forEach((item, key) => {
                    if(item.id == response.data.data) {
                        context.commit('setUserBoardData', key)
                        return false;
                    }
                });

                // 유저의 작성글 수 1 하락
                context.commit('setUserBoardsCountSub');
                localStorage.setItem('userInfo', JSON.stringify(context.state.userInfo));

                router.replace('/board')
            })
            .catch(error => {
                console.log(error.response); // TODO
                alert('글 삭제에 실패했습니다. ( ' + error.response.data.code + ' )');
            });
        },

    }
});



export default store;
