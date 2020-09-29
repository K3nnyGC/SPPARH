import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/users/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "login" */ '../views/users/login.vue')
  },
  {
    path: '/users/register',
    name: 'Register',
    component: () => import(/* webpackChunkName: "register" */ '../views/users/register.vue')
  },
  {
    path: '/users/validation',
    name: 'Validation',
    component: () => import(/* webpackChunkName: "validation" */ '../views/users/validation.vue')
  },
  {
    path: '/certificates/pending',
    name: 'Pending',
    component: () => import(/* webpackChunkName: "pending" */ '../views/certificates/pending.vue')
  },
  {
    path: '/certificates/create',
    name: 'Create',
    component: () => import(/* webpackChunkName: "create" */ '../views/certificates/create.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
