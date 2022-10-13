
import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes : [
        
            {
                name: "login",
                path: "/login",
                component: ()=>import('@/components/User/Auth/Login.vue'),
                meta: {
                    rule: 'user',
                    title: `Login`
                }
            },
            {
                name: "register",
                path: "/register",
                component:  ()=>import('@/components/User/Auth/Register.vue'),
                meta: {
                    rule: 'user',
                    title: `Register`
                }
            },
            {
                path: '/admin',
                name: 'admin',
                redirect: '/admin-dashboard',
                component: () => import('@/components/layouts/MainAdmin.vue'),
                children: [
                  {
                    path: '/admin-dashboard',
                    name: 'admin-dashboard',
                    component: () => import('@/views/admin/Dashboard.vue'),
                    meta: {
                      rule: 'admin'
                    }
                  },
                  {
                    path: '/admin-user',
                    name: 'admin-user',
                    component: () => import('@/views/admin/ManagerUser.vue'),
                    meta: {
                      rule: 'user'
                    }
                  },
                ]
                }
        ]
    
})

router.beforeEach(async (to, from, next) => {
    if (!to.meta || !to.meta.rule || to.meta.rule == 'user') {
      return next()
    }
    if (to.meta && to.meta.rule && to.meta.rule !== 'user' && !store.state.auth.profile.type) {
      await store.dispatch('auth/getProfile')
    }
    if (to.meta && to.meta.rule && to.meta.rule == 'admin' && store.state.auth.profile.type !== 1) {
      store.dispatch('app/setErrorNotification', 'Bạn không có quyền truy cập trang này !')
      if (from.path.search('admin') != -1) {
        store.dispatch('auth/setToken')
        return {
          path: '/admin-login'
        }
      }
      store.dispatch('clientAuth/setToken')
      return {
        path: '/login'
      }
    } else if (to.meta && to.meta.rule && to.meta.rule == 'editor' && store.state.auth.profile.type === 3) {
      store.dispatch('app/setErrorNotification', 'Bạn không có quyền truy cập trang này !')
      if (from.path.search('admin') != -1) {
        return {
          path: '/admin'
        }
      }
      return {
        path: '/home'
      }
    } else {
      next()
    }
  })
  
export default router