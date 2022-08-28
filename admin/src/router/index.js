import axios from '../utils'
import { createRouter, createWebHistory } from 'vue-router'
import store from '../store'
import HomeView from '../views/HomeView.vue'
import AddPairView from '../views/AddPairView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes:
    [
      {
        path: '/login',
        name: 'login',
        component: () => import('../views/Login.vue')
      },
      {
        path: '/',
        name: 'home',
        component: HomeView,
        meta: { requiresAuth: true }
      },
      {
        path: '/addpair',
        name: 'addpair',
        component: AddPairView,
        meta: { requiresAuth: true }
      }
    ]
})

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const currentUser = store.state.user.token
  if (requiresAuth && !currentUser) {
    next('/login')
  } else if (to.path === '/login' && currentUser) {
    next('/')
  }
  else {
    next()
  }
})

export default router
