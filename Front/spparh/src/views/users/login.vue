<template>
<div class="container-xl">
    
    <div class="row m-0 justify-content-center align-items-center vh-100">             
        <div class="col-md-5  bg-white box-login rounded">
            <p class="text-center box-signup-text h2">spparh <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
            </p>
            <h5 class="text-center mt-4 mb-4 h6">Inicia sesión en tu cuenta</h5>              
            <form @submit.prevent>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label h6">Correo electrónico</label>
                    <input type="email" class="form-control" :class="{ 'is-invalid': $v.email.$error }" v-model.trim="$v.email.$model" id="exampleFormControlInput1">
                        
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label h6">Contraseña</label>
                    <input type="password" class="form-control" :class="{ 'is-invalid': $v.password.$error }" v-model.trim="$v.password.$model" id="exampleInputPassword1">
                </div>
                  
                <button type="submit" class="btn btn-primary btn-lg btn-block" @click="authenticate()" :disabled="$v.$invalid">Enviar</button>
                     
                <div class="text-center mt-3">
                    <small class="text-muted">No tienes una cuenta. <a href="#">¡Resgistrate!</a></small>
                </div>                   
            </form>
        </div>      
    </div>
</div>
  
</template>

<script>

import { required, minLength, between, email } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'

export default {
    name: 'login',
    components: { 
    },

data: function () {
    return { 
        email:'',
        password:''     
    }
}, 

validations: {
    email: {
        required,
        email
    },
    password: {
        required
    }
  },
  
  mixins: [mixinMethods],

  computed: {
      ...mapState(['api','tkn', 'activeUser'])
  },

  methods:{
    ...mapMutations(['setToken', 'setUserOnStore']),
      

    authenticate: function () {
        this.request(this.api + 'users/login', 'POST', {},{
            email: this.email,
            password: this.password
        },
        (respond) => {                 
            this.setToken(respond.tkn)
            this.setUserOnStore(respond.user)
                if (respond.user.id_role == 1) {
                    UIkit.notification("¡Bienvenido!")
                    this.goto('/certificates/create')
                }
                else {
                    if (respond.user.id_role == 3) {
                        UIkit.notification("¡Bienvenido!")
                        this.goto('/users/validation')
                    }else{
                        UIkit.notification("La cuenta tipo estudiante estará habiliatda para el Sprint 3 :)")
                    }
                }
            },()=>{},(err)=>{
                UIkit.notification(err.response.data.message)
            })   
      }
  },

  mounted (){
    document.title = 'Login'
  }


}
</script>

