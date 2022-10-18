/** @format */

import Vue from 'vue'
import Vuex from 'vuex'
import auth from '@/store/auth.js'
import app from '@/store/common/app.js'
import clientAuth from '@/store/user/auth.js'
import user from '@/store/admin/user.js'
import notification from '@/store/common/notification'
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    // todo: moduleTodo
    auth,
    clientAuth,
    app,
    user,
    notification
  }
})
