<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-greenv">
                    <!-- <h5 class="card-title text-white text-white">Lista de instituciones pendientes</h5> -->
                    <p class="card-text text-white">Lista de instituciones pendientes</p>
                </div>
                <div class="card-body">
                    <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">RUC</th>
                        <th scope="col">Razon social</th>
                        <th scope="col">Persona de contacto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Actualizar</th>
                        </tr>
                    </thead>
                    <paginate name="users" :list="listaFiltrada" :per="3" tag="tbody">
                    <!-- <tbody id="contenido"> -->
                        <tr v-for="(user, index) in paginated('users')" :key="index+'users'">
                        <!-- <tr v-for="(user, index) in listaFiltrada" :key="index+'users'"> -->
                        <td>{{user.RUC}}</td>
                        <td>{{user.legal_name}}</td>
                        <td>{{user.name}} {{user.lastname}}</td>
                        <td>{{user.created_date | formatDate}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="setTemporalUser(user)" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10zm4.854-7.85a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            </button>                 
                        </td>
                        </tr>
                    </paginate>
                    <!-- </tbody> -->
                    </table>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cuenta: {{tempUser.legal_name}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                    <div class="mb-3">
                                        <label for="recipient-name" class="col-form-label">Actualizar estado:</label>
                                        <select class="form-select form-select-sm" v-model="tempUser.status" aria-label=".form-select-sm">
                                        <!-- <select class="form-select form-select-sm" v-model="statusType" aria-label=".form-select-sm"> -->
                                            <option v-for="(option,index) in status" :value="option.value" :key="index+'statusaccount'">{{option.text}}</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message-text" class="col-form-label">Comentarios:</label>
                                        <textarea class="form-control" id="message-text"></textarea>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" @click="getInstitutes ()" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-secondary" @click="updateValidation()" data-dismiss="modal">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div><!--End Modal-->
                </div>
            </div><!--End Card-->
        </div>
    </div>
</div>

</template>

<script>

import { required } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
import Sidebar from '@/components/Sidebar.vue'


export default {
  name: 'validation',
  components: { 
      Sidebar
  },


data: function () {
    return { 
        users:[],
        tempUser:{
            dni:''
        },
        //statusType:1,
        status:[
            {
                value: 0, text:'Pendiente'
            },
            {
                value: 1, text:'Aprobado'
            },
            {
                value: 2, text:'Rechazado'
            }
        ],
        sbOptions:[],
        paginate: ['users']
    }
  }, 

  validations: { 
  },

  mixins: [mixinMethods],

  computed: {
    ...mapState(['api','tkn','activeUser']),

    listaFiltrada: function(){
        let arreglo = [];
        this.users.forEach(element => {
            if (element.status == 0) {
                arreglo.push(element)
            }
        });
        return arreglo
    }   
 },

methods:{
    ...mapMutations(['setToken', 'setUserOnStore']),
      
    getInstitutes () {      
        this.request(this.api + 'users/institutes/pendings', 'GET', {},{},
        (respond) => {                 
            this.users = respond.institutes
        },()=>{},(err)=>{
            console.log(err)
             UIkit.notification(err.message)
        })    
      },

    setTemporalUser(user){
        this.tempUser = user;    
    },

    updateValidation () {

        if (this.tempUser.status == 1) {
            this.request(this.api + 'users/institutes/approve/' + this.tempUser.id_user, 'PUT', {authorization: "Basic " + this.tkn},{
                email: this.tempUser.email,
                name: this.tempUser.name,
                lastname: this.tempUser.lastname,
                dni: this.tempUser.dni,
                phone: this.tempUser.phone,
                RUC: this.tempUser.RUC,
                legal_name: this.tempUser.legal_name,
                comercial_name: this.tempUser.comercial_name,
                address: this.tempUser.address,
                type: 1,
                status: this.tempUser.status            
            },
            (respond) => {             
                UIkit.notification("<span uk-icon='icon: check'></span> Se actualizó cuenta.")             
            },()=>{},(err)=>{
                UIkit.notification(err.message)
            })        
        } else {
            this.request(this.api + 'users/' + this.tempUser.id_user, 'PUT', {authorization: "Basic " + this.tkn},{
                email: this.tempUser.email,
                name: this.tempUser.name,
                lastname: this.tempUser.lastname,
                dni: this.tempUser.dni,
                phone: this.tempUser.phone,
                RUC: this.tempUser.RUC,
                legal_name: this.tempUser.legal_name,
                comercial_name: this.tempUser.comercial_name,
                address: this.tempUser.address,
                type: 1,
                status: this.tempUser.status            
            },
            (respond) => {             
                UIkit.notification("<span uk-icon='icon: check'></span> Se actualizó cuenta.")             
            },()=>{},(err)=>{
                UIkit.notification(err.message)
            })        
        }

      }
  },

created(){
    if (this.activeUser.id_role != 3) {      
        this.setToken('')
        this.setUserOnStore({})
        this.goto('/users/login')
    } else {
        this.getInstitutes();
    }
},

mounted(){
    
    document.title = 'Validación de Cuenta';

    this.sbOptions = [
        {
            name:"Instituciones",
            icon:['fas', 'building'],
            clic: () => {
            }
        },
        {
            name:"Cerrar sesión",
            icon:['fas','power-off'],
            clic: () => {
                this.setToken('')
                this.setUserOnStore({})
                this.goto('/users/login')
            }
        }
    ]      
  }
}

</script>

