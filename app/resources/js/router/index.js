
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
                // redirect: '/admin-dashboard',
                component: () => import('@/layout/full/MainContainer.vue'),
                // children: [
                //   {
                //     path: '/admin-dashboard',
                //     name: 'admin-dashboard',
                //     component: () => import('@/views/admin/Dashboard.vue'),
                //     meta: {
                //       rule: 'admin'
                //     }
                //   },
                //   {
                //     path: '/admin-user',
                //     name: 'admin-user',
                //     component: () => import('@/views/admin/ManagerUser.vue'),
                //     meta: {
                //       rule: 'user'
                //     }
                //   },
                // ]
                },
                {
                path: '',
            component: () => import('@/layout/full/MainContainer.vue'),
            // ======================
            // Theme routes / pages
            // ======================

            children: [
                {
                    path: '/',
                    redirect: '/starterkit'
                },
                {
                    path: '/starterkit',
                    name: 'Starterkit',
                    index: 1,
                    component: () => import('@/views/StarterPage.vue')
                },
                {
                    path: '/alert',
                    name: 'Alert',
                    index: 2,
                    component: () => import('@/views/components/vuesax/alert/alert.vue')
                },
                {
                    path: '/avatar',
                    name: 'Avatar',
                    index: 3,
                    component: () => import('@/views/components/vuesax/avatar/avatar.vue')
                },
                {
                    path: '/breadcrumb',
                    name: 'Breadcrumb',
                    index: 4,
                    component: () => import('@/views/components/vuesax/breadcrumb/breadcrumb.vue')
                },
                {
                    path: '/buttons',
                    name: 'Buttons',
                    index: 5,
                    component: () => import('@/views/components/vuesax/buttons/buttons.vue')
                },
                {
                    path: '/cards',
                    name: 'Cards',
                    index: 6,
                    component: () => import('@/views/components/vuesax/cards/cards.vue')
                },
                {
                    path: '/checkbox',
                    name: 'Checkbox',
                    index: 7,
                    component: () => import('@/views/components/vuesax/checkbox/checkbox.vue')
                },{
                    path: '/collapse',
                    name: 'Collapse',
                    index: 9,
                    component: () => import('@/views/components/vuesax/collapse/collapse.vue')
                },
                {
                    path: '/dialog',
                    name: 'Dialog',
                    index: 10,
                    component: () => import('@/views/components/vuesax/dialog/dialog.vue')
                },
                {
                    path: '/divider',
                    name: 'Divider',
                    index: 11,
                    component: () => import('@/views/components/vuesax/divider/divider.vue')
                },
                {
                    path: '/dropdown',
                    name: 'Dropdown',
                    index: 12,
                    component: () => import('@/views/components/vuesax/dropdown/dropdown.vue')
                },
                {
                    path: '/input',
                    name: 'Input',
                    index: 13,
                    component: () => import('@/views/components/vuesax/input/input.vue')
                },
                {
                    path: '/list',
                    name: 'List',
                    index: 14,
                    component: () => import('@/views/components/vuesax/list/list.vue')
                },
                {
                    path: '/loading',
                    name: 'Loading',
                    index: 15,
                    component: () => import('@/views/components/vuesax/loading/loading.vue')
                },{
                    path: '/navbar',
                    name: 'Navbar',
                    index: 16,
                    component: () => import('@/views/components/vuesax/navbar/navbar.vue')
                },
                {
                    path: '/notification',
                    name: 'Notification',
                    index: 17,
                    component: () => import('@/views/components/vuesax/notification/notification.vue')
                },{
                    path: '/number-input',
                    name: 'Number input',
                    index: 18,
                    component: () => import('@/views/components/vuesax/number-input/number-input.vue')
                },
                {
                    path: '/pagination',
                    name: 'Pagination',
                    index: 19,
                    component: () => import('@/views/components/vuesax/pagination/pagination.vue')
                },{
                    path: '/popup',
                    name: 'Popup',
                    index: 20,
                    component: () => import('@/views/components/vuesax/popup/popup.vue')
                },
                {
                    path: '/progress',
                    name: 'Progress',
                    index: 21,
                    component: () => import('@/views/components/vuesax/progress/progress.vue')
                },
                {
                    path: '/radio',
                    name: 'Radio',
                    index: 22,
                    component: () => import('@/views/components/vuesax/radio/radio.vue')
                },
                {
                    path: '/switch',
                    name: 'Switch',
                    index: 26,
                    component: () => import('@/views/components/vuesax/switch/switch.vue')
                },
                {
                    path: '/tabs',
                    name: 'Tabs',
                    index: 28,
                    component: () => import('@/views/components/vuesax/tabs/tabs.vue')
                },{
                    path: '/textarea',
                    name: 'Textarea',
                    index: 29,
                    component: () => import('@/views/components/vuesax/textarea/textarea.vue')
                }

            ]
		},
    // Redirect to starterkit page, if no match found
        // {
        //     path: '*',
        //     redirect: '/starterkit'
        // }
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