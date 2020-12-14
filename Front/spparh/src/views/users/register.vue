<template>
<div class="container mt-4">

    <div class="row m-0 justify-content-center align-items-center">
        <div class="col-md-5 box-signup-text">
            <h1 class="text-center">spparh <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
            </h1>
            <span class="text-align-center text-secondary font-weight-ligh">Sistema de validación y preservación de la autenticidad de certificados utilizando Blockchain para las instituciones de educación superior</span>
        </div>
        <div class="col-md-5 sm-6 bg-white box-login rounded">
            <h5 class="text-center mb-4">Registrar Cuenta</h5> 
            <form @submit.prevent>
                <div class="mb-2">
                    <label for="email" class="form-label h6">Correo electrónico</label>
                    <input type="email" class="form-control form-control-sm" :class="{ 'is-invalid': $v.email.$error }" v-model.trim="$v.email.$model">            
                </div>
                <div class="mb-2">
                    <label for="inputPassword5" class="form-label h6">Contraseña</label>
                    <input type="password" id="inputPassword5" class="form-control form-control-sm" :class="{ 'is-invalid': $v.password.$error }" v-model.trim="$v.password.$model" aria-describedby="passwordHelpBlock">
                    <div id="passwordHelpBlock" class="form-text">
                        Mínimo 8 carcateres. Sólo letras y números.
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="name" class="form-label h6">Nombres</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.name.$error }" v-model.trim="$v.name.$model" aria-label="First name">
                    </div>
                    <div class="col">
                        <label for="lastname" class="form-label h6">Apellidos</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.lastname.$error }" v-model.trim="$v.lastname.$model" aria-label="Last name">
                    </div>
                </div>   
                <div class="row mb-2">
                    <div class="col">
                        <label for="dni" class="form-label h6" >DNI</label>
                        <input class="form-control form-control-sm" :class="{ 'is-invalid': $v.dni.$error }" v-model.trim="$v.dni.$model">
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label h6">Teléfono</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.phone.$error }" v-model.trim="$v.phone.$model">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label h6">Tipo de cuenta</label>
                        <select class="form-select form-select-sm" v-model="usertype" aria-label=".form-select-sm">
                            <option v-for="(option,index) in usertypes" :value="option.value" :key="index+'usertypeselect'">{{option.text}}</option>
                        </select>
                    </div>
                </div>
                <!-- <transition name="custom-classes-transition" enter-active-class="animated fadeInDownBig" mode="out-in" leave-active-class="animated fadeOutRightBig"> -->
                <div id="student"  v-if="usertype == 2">
                    <div class="mb-2">
                        <label class="form-label h6">Fecha de nacimiento</label>
                        <input type="date" class="form-control form-control-sm" :class="{ 'is-invalid': $v.birthdate.$error }" v-model.trim="$v.birthdate.$model" id="exampleFormControlInput1">            
                    </div>
                </div>
                <div id="institute" v-else>                 
                    <div class="row mb-2">
                        <div class="col">
                            <label for="legalname" class="form-label h6">Razón social</label>
                            <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.legalname.$error }" v-model.trim="$v.legalname.$model">
                        </div>
                        <div class="col">
                            <label for="comercialname" class="form-label h6">Nombre comercial</label>
                            <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.comercialname.$error }" v-model.trim="$v.comercialname.$model">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="ruc" class="form-label h6">RUC</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.ruc.$error }" v-model.trim="$v.ruc.$model">            
                    </div>
                    <div class="mb-2">
                        <label for="address" class="form-label h6">Dirección</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.address.$error }" v-model.trim="$v.address.$model">            
                    </div>
                </div>
                <!-- </transition> -->
                <button type="submit"  class="btn btn-primary btn-sm btn-block" @click="send()" :disabled="$v.validationGroupStudent.$invalid && $v.validationGroupInstitute.$invalid">Registrar</button>
                <div class="text-center mt-3">
                    <small class="text-muted">Al hacer clic en «Registrar», aceptas las <a href="#">Condiciones de uso</a>, la <a href="#">Política de privacidad</a> y la <a href="#">Política de cookies</a> de SPPARH.</small>
                </div> 
                </form>        
        </div> 
    </div>
</div>
  
</template>

<script>

import { required, minLength, maxLength, email, alphaNum, numeric } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
export default {
  name: 'register',
  components: { 
  },


  data: function () {
    return { 
        email:'',
        password:'' ,
        name:'',
        lastname:'',
        dni:'',
        phone:'',
        birthdate:'',
        ruc:'',
        legalname:'',
        comercialname:'',
        address:'',
        usertype: 1,
        usertypes: [
                {
                 value: 1, text:'Intitución'
                },
                {
                value: 2, text:'Estudiante'
                }
            ],
        isActive: true
        }
  }, 

  validations: {
      email:{
          required,
          email
      },
      password:{
          required,
          minLength: minLength(8),
          alphaNum
      },
      name:{
          required
      },
      lastname:{
          required
      },
      dni:{
          required,
          minLength: minLength(8),
          maxLength: maxLength(8),
          numeric
      },
      phone:{
          required,
          numeric,
          minLength: minLength(7),
          maxLength: maxLength(14),
      },
      birthdate:{
          required,
      },
      ruc:{
          required,
          minLength: minLength(11),
          maxLength: maxLength(11),
          numeric
      },
      legalname:{
          required
      },
      comercialname:{
          required
      },
      address: {
          required
      },
    //   validationGroup: ['email', 'password', 'name', 'lastname', 'dni', 'phone'],
      validationGroupStudent: ['email', 'password', 'name', 'lastname', 'dni', 'phone','birthdate'],
      validationGroupInstitute: ['email', 'password', 'name', 'lastname', 'dni', 'phone','ruc', 'legalname','comercialname', 'address']
  },

  mixins: [mixinMethods],

  computed: {
    ...mapState(['api','tkn'])
    
  },

  methods:{
      ...mapMutations(['setToken', 'setUserOnStore']),

      send: function (){
          if (this.usertype == 1) {
              this.request(this.api + 'users', 'POST', {},{
                  email: this.email,
                  password: this.password,
                  name: this.name,
                  lastname: this.lastname,
                  dni: this.dni,
                  phone: this.phone,
                  RUC: this.ruc,
                  legal_name: this.legalname,
                  comercial_name: this.comercialname,
                  address: this.address,
                  type: this.usertype
              },
              (respond) => {                 
                    UIkit.notification('Bienvenido')
                    this.setToken(respond.tkn)
                    this.setUserOnStore(respond.user)
                    this.goto('/institute/certificates/create')
              },()=>{},(err)=>{
                    UIkit.notification(err.response.data.message)
                    UIkit.notification(err.response.data.parameter)
              })
          }
          else
          {
              this.request(this.api + 'users', 'POST', {},{
                    name: this.name,
                    lastname: this.lastname,
                    dni: this.dni,
                    phone: this.phone,
                    email: this.email,
                    password: this.password,
                    type: this.usertype,
                    birthdate: this.birthdate
              },
              (respond) => {
                    UIkit.notification('¡Bienvenido!')
                    this.setToken(respond.tkn)
                    this.setUserOnStore(respond.user)
                    this.goto('/student/certificates/create')
              },()=>{},(err)=>{
                    UIkit.notification(err.response.data.message)
                    UIkit.notification(err.response.data.parameter)
                    
                     
              })
          }

      }                          
  },

  mounted(){
        document.title = 'Registro';

        // this.email='galarza.ross@gmail.com';
        // this.password='12345upc';
        // this.name='Ross';
        // this.lastname='Galarza Contreras';
        // this.dni='44855369';
        // this.phone='999854712';
        // this.ruc='20429649655';
        // this.legalname='Programming Extreme IT S.A.C.';
        // this.comercialname='Programming Extreme IT';
        // this.address='Jr. Mariscal Castilla 789. Lince'

        
        // this.email='correo2@mail.com';
        // this.password='12345upc';
        // this.name='Diego';
        // this.lastname='Torres Lara';
        // this.dni='44765409';
        // this.phone='999854700';
        // this.ruc='20429649123';
        // this.legalname='Instituto Tecnológico Data Log S.A.C.';
        // this.comercialname='Instituto Tecnológico Data Log';
        // this.address='Calle Las Flores 245. San Isidro'
       
  }
}

</script>
