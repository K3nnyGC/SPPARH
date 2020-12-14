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
    path: '/institute/certificates/recieved',
    name: 'Recieved',
    component: () => import(/* webpackChunkName: "recieved" */ '../views/certificates/institutes/recieved.vue')
  },
  {
    path: '/institute/certificates/pending',
    name: 'Pending',
    component: () => import(/* webpackChunkName: "pending" */ '../views/certificates/institutes/pending.vue')
  },
  {
    path: '/institute/certificates/create',
    name: 'CreateByInstitute',
    component: () => import(/* webpackChunkName: "createbyinstitute" */ '../views/certificates/institutes/create.vue')
  },
  {
    path: '/student/certificates/create',
    name: 'CreateByStudent',
    component: () => import(/* webpackChunkName: "createbystudent" */ '../views/certificates/students/create.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
