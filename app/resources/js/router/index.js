
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
                    middleware: "guest",
                    title: `Login`
                }
            },
            {
                name: "register",
                path: "/register",
                component:  ()=>import('@/components/User/Auth/Register.vue'),
                meta: {
                    middleware: "guest",
                    title: `Register`
                }
            },
        ]
    
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})
export default router