<template>
  <!-- header -->
  <header>
    <div class="header-container">
      <div class="header-content">
        <div class="title">
          <router-link to="/board"><h1>Vuestagram</h1></router-link>
        </div>
        <img class="img-logo" alt="logo" src="/logo.png">
        <div class="btn-group">

          <div v-if="!$store.state.authFlg">
            <router-link v-if="route.path === '/registration'" to="/login">
              <button @click="$router.push('/login')" class="btn btn-header btn-bg-black">로그인</button>
            </router-link>
            <router-link v-else-if="route.path === '/login'" to="/registration">
              <button @click="$router.push('/registration')" class="btn btn-header btn-bg-white">회원가입</button>
            </router-link>
          </div>

          <div v-else>
            <button @click="$store.dispatch('logout')" class="btn btn-header btn-bg-black">로그아웃</button>
          </div>
        </div>
      </div>
    </div>
  </header>
  <hr>

  <!-- Main -->
  <main>
    <!-- userInfo의 정보가 주어지면 UserInfoComponent 실행 -->
    <UserInfoComponent v-if="$store.state.userInfo"/>
    <div class="container">
      <router-view></router-view>
    </div>
  </main>

  <!-- footer -->
  <footer>
    <p>ⓒ 2024. Meerkat All rights reserved.</p>
  </footer>
</template>

<script setup>
import { useRoute } from 'vue-router';
import UserInfoComponent from './UserInfoComponent.vue';

const route = useRoute()

</script>

<style>
@import url('../css/common.css');
</style>
