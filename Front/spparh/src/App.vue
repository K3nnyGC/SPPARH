<template>
  <div id="app">

    <nav class="navbar bg-bluev text-white">
        <div class="container-fluid pt-2 pb-2">
            <div class="row">
                <div class="col-sm-8">
                    <a style="font-family: 'Open Sans Condensed', sans-serif;" class="navbar-brand h3 text-white" href="#">spparh 
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                        </svg>
                    </a>
                </div>
                <div class="col-sm-4">
                    <ActiveUser/>
                </div>
            </div>
        </div>
    </nav>
    <!-- <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link> |
      <router-link to="/users/login">Login</router-link> |
      <router-link to="/users/register">Registro</router-link>
    </div> -->
    <transition
            name="custom-classes-transition"
            enter-active-class="animate__animated animate__fadeIn"
            leave-active-class="animate__animated animate__fadeOut"
            mode="out-in">
        <router-view/>
    </transition>
  </div>
</template>

<script>
// import Register from '@/views/users/register.vue'
import {mapMutations} from 'vuex'
import ActiveUser from '@/components/ActiveUser.vue'

export default {
  name: 'App',

  components: {
    ActiveUser
  },
  
  methods: {
    ...mapMutations(['setToken','setUserOnStore'])
  },

  created (){
     console.log('Created del app.vue')
    if (localStorage.getItem('localToken')) {
      //this.setToken(JSON.parse(localStorage.getItem('localToken'))) CUANDO ES UN OBJ SE PARSEA
      this.setToken(localStorage.getItem('localToken'))
    }
    if (localStorage.getItem('localUser')) {
      this.setUserOnStore(JSON.parse(localStorage.getItem('localUser')))    
    }
  }

}
</script>


<style>
/* #app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
} */
</style>
