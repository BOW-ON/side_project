require('./bootstrap');

// 기본 설정 import 하기
import { createApp } from 'vue';
import AppComponent from '../components/AppComponent.vue'; // 가장 처음 불러올 파일
import store from './store';
import router from './router';

createApp({
    components: {
        AppComponent,
    }
})
.use(store)
.use(router)
.mount('#app'); // id가 app인 곳에 삽입됨