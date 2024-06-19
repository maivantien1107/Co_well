/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import '../sass/app.scss'
import './assets/css/main.css'
import './assets/scss/main.scss'
// import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
 import Vue from 'vue'
 import router from '@/router';
 import Vuesax from 'vuesax';
 import 'boxicons/css/boxicons.min.css'
 import 'material-icons/iconfont/material-icons.css';
 import 'vuesax/dist/vuesax.css';
 import App from './App.vue';
 import store from '@/store'

 Vue.config.productionTip = false;
 Vue.use(Vuesax, {});
 new Vue({  
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
  // import '@/assets/scss/style.scss'
