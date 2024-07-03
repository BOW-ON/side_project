<template>
  <form class="form-box" id="boardCreateForm">
    <h3 class="form-title">글 작성</h3>
    <img :src="preview">
    <textarea name="content" placeholder="내용을 적어주세요." max="200"></textarea>
    <label for="btn-img" class="btn btn-file">파일 선택</label>
    <input @change="setFile" type="file" name="img" id="btn-img" accept="image/*">
    <button @click="$store.dispatch('boardCreate')" type="button" class="btn btn-submit btn-bg-black">작성</button>
    <button @click="$router.back()" type="button" class="btn btn-submit">취소</button>
  </form>
</template>
<script setup>
import { reactive, ref } from 'vue';


const boardInfo = reactive({
    img: null,
});

const preview = ref('');

function setFile(e) {
    boardInfo.img = e.target.files[0];
    preview.value = URL.createObjectURL(boardInfo.img);
}

</script>
<style>
</style>
