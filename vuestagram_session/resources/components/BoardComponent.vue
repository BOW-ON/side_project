<template>
  <!-- 상세 모달 -->
  <div v-if="detailFlg" class="board-detail-box">
    <div class="item">
      <img :src="detailItem.img">
      <div class="etc-box">
        <span>좋아요 : {{ detailItem.like }}</span>
        <button @click="likeBtn(detailItem), $store.dispatch('likeBtn', detailItem.id)" class="btn btn-bg-blue btn-close">좋아요</button>
      </div>
      <hr>
      <p>{{ detailItem.content }}</p>
      <hr>
      <div class="etc-box">
        <span>작성자 : {{ detailItem.name }}</span>
        <div class="etc-box-btn">
          <button v-if="$store.state.userInfo.id === detailItem.user_id" @click="$store.dispatch('boardDelete', detailItem.id), closeDetail()" class="btn btn-bg-black btn-delete btn-margin">삭제</button>
          <button @click="closeDetail()" class="btn btn-bg-red btn-close btn-margin">닫기</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 리스트 -->
  <div class="board-list-box">
    <div @click="openDetail(item)" v-for="(item, key) in $store.state.boardData" :key="key" class="item">
      <img :src="item.img">   
    </div>
  </div>
  <!-- v-if="$store.state.moreBoardFlg" : 습득할 데이터가 없으면 더보기 버튼 없애기 위해 작성 -->
  <button
    v-if="$store.state.moreBoardFlg"
    @click="$store.dispatch('getMoreBoardData')"
    class="btn btn-bg-black btn-more"
    type="button">
      더보기
  </button>
  <a href="#" class="btn btn-bg-white btn-fixed">TOP</a>

</template>
<script setup>
import { onBeforeMount, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

// Vuex에서  useStore 사용
const store = useStore();

const router = useRoute();


// 상세 모달 관련
//  >> 계속 저장되는 변수 이므로 let으로 선언
let detailItem = reactive({});
let detailFlg = ref(false);

function openDetail(data) {
  detailItem = data; // 받아온 데이터 detailItem에 추가
  detailFlg.value = true;
}

function closeDetail() {
  detailItem = {}; // detailItem 초기화
  detailFlg.value = false; // 상세 플래그 false로 변환
}

function likeBtn(data) {
  if(data.like_chk == 1) {
    data.like_chk = 0;
    data.like--;
  } else {
    data.like_chk = 1;
    data.like++;
  }
}


// 게시글 습득 관련
onBeforeMount(() => {
  // 다른페이지 갔다가 다시 돌아오면 서버에 게시글 획득 재요청을 방지하기 위해 if로 특정 상황일때만 요청하기
  // router.path == '/board' : board 경로가 있을 경우
  if(store.state.boardData.length < 1 && router.path == '/board' ) {
    store.dispatch('getBoardData');
  }
})

</script>
<style>
@import url('../css/boardList.css');
</style>
