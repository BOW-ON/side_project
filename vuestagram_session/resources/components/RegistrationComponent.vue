<template>
  <form class="form-box" id="registrationForm">
    <h3 class="form-title">회원가입</h3>
    <input type="text" name="account" placeholder="아이디">
    <input type="password" name="password" placeholder="비밀번호" autoComplete="off">
    <input type="password" name="password_chk" placeholder="비밀번호 확인" autoComplete="off">
    <input type="text" name="name" placeholder="이름" autoComplete="off">
    <div class="radio-box">
      <div>
        <label for="male">남자</label>
        <input type="radio" name="gender" id="male" value="0">
      </div>
      <div>
        <label for="female">여자</label>
        <input type="radio" name="gender" id="female" value="1">
      </div>
    </div>
    <label for="btn-img" class="btn btn-file">프로필 파일 선택</label>
    <input @change="setFile" type="file" name="profile" id="btn-img" accept="image/*">
    <span v-if="userRegist.img">{{ userRegist.img.name }}</span>
    <img :src="preview">
    <hr>
    <button @click="$store.dispatch('registration')" type="button" class="btn btn-submit btn-bg-black">가입 완료</button>
    <button @click="$router.back()" type="button" class="btn btn-submit">취소</button>
  </form>
</template>

<script setup>
import { reactive, ref } from 'vue';


const userRegist = reactive({
  img: null,
});
const preview = ref('');

function setFile(e) {
  const file = e.target.files[0];
  if (file) {
    userRegist.img = file;
    preview.value = URL.createObjectURL(userRegist.img);
  }
}

</script>

<style>
</style>
