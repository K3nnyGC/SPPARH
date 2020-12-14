<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8"><!-- Content -->
            <div class="card">
                <div class="card-header bg-greenv">
                    <p class="card-text text-white">Crear certificado</p>
                </div>
                <div class="card-body">
                 <form @submit.prevent>       
                    <div class="mb-2">
                        <label for="certificatename" class="form-label h6">Nombre del certificado*</label>
                        <input type="text" class="form-control form-control-sm" :class="{'is-invalid': $v.certificateName.$error}" v-model.trim="$v.certificateName.$model" disabled>
                    </div>

                    <div class="mb-2">
                        <label for="institutename" class="form-label h6">Nombre de la institución educativa*</label>
                        <!-- <input type="text" class="form-control form-control-sm"  v-model="institutename"> -->
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="text" placeholder="Buscar" class="form-control" v-model="comercialName">
                            </li>
                            <li v-for="(institute, index) in searchInstitute" :key="index+'institutes'" class="list-group-item">
                                {{institute.comercial_name}}
                            </li>
                        </ul>
                    </div>

                    <div class="row mb-2">
                        <div class="col">
                            <label for="certificatedate" class="form-label h6">Fecha de emisión*</label>
                            <input type="date"  class="form-control form-control-sm" :class="{'is-invalid': $v.certificateDate.$error}" v-model.trim="$v.certificateDate.$model" disabled>
                        </div>

                        <div class="col">
                            <label for="certificatefile" class="form-label h6">Cargar certificado*</label>
                            <div class="form-file form-file-sm">
                                <input type="file" @change="onFileSelected"  accept=".pdf" class="form-file-input" id="customFileSm" disabled>
                                <label class="form-file-label" for="customFile">
                                    <span class="form-file-text">{{selectedCertiFile.name}}</span>
                                    <span class="form-file-button">Examinar</span>
                                </label>
                            </div>                                   
                            <div id="passwordHelpBlock" class="form-text">
                                <small class="text-muted">Formato pdf.</small>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row mb-2" style="float:right;">
                        <!-- <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary">Cancelar</button>
                        </div> -->
                        <div class="col-6">
                            <!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Guardar</button> -->
                            <button type="button" @click="sendCerticate()" class="btn btn-secondary">
                                <!-- <font-awesome-icon :icon="isOk ? 'cog': 'edit'" :spin="isOk"/> -->
                                <div v-if="isOk" class="spinner-border spinner-border-sm"></div>
                                <!-- <span v-if="isOk" >Guardando...</span> -->
                                <span v-else>Enviar</span>
                            </button>
                        </div>
                    </div>
                    </form>
                </div><!--End CardBody-->
            </div><!--End Card-->
        </div><!--col-md-8-->
    </div>
</div>

</template>

<script>

import { required } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
import Sidebar from '@/components/Sidebar.vue'
import axios from 'axios'


export default {
    name: 'create',
    components: { 
        Sidebar
    },


data: function () {
    return { 
        institutes: [],
        certificateName:'',
        comercialName:'',
        certificateDate:'',
        selectedCertiFile:[],
        sbOptions:[],
        isOk:false  
        }
  }, 

validations: {
    certificateName: {
         required
        },
  
    certificateDate:{
        required
    }

  },

  mixins: [mixinMethods],

  computed: {
    ...mapState(['api','tkn','activeUser']), 
    
    searchInstitute: function () {
        return this.institutes.filter((institute) => institute.comercial_name.includes(this.comercialName))
    }



  },

  methods:{
    ...mapMutations(['setToken', 'setUserOnStore']),
    
    getInstitutes () {      
        this.request(this.api + 'users/institutes', 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {                 
            this.institutes = respond.institutes
            console.log(respond.institutes)
        },()=>{},(err)=>{
            UIkit.notification(err.message)
        })    
      },

    onFileSelected(event){
        this.selectedCertiFile = event.target.files[0]
    },

    async uploadCertificate(form, apiHeader){  
        this.isOk = true
        this.$forceUpdate();
            axios({
                method: 'POST',
                url: this.api + apiHeader,
                headers: {
                    'Content-Type': 'multipart/form-data',
                    "Accept": "application/json, text/plain, */*",
                    authorization: "Basic " + this.tkn
                },
                data:form
            }).then(response => {
                console.log(response.data.document)
                console.log(response.data.ok)
                
                UIkit.notification("<span uk-icon='icon: check'></span> Certificado registrado exitosamente.") 
                this.clearImputs()                 
            }).catch(err => {
                UIkit.notification(err.response.data.message)  
                UIkit.notification(err.response.data.parameter) 
            }).finally( () => {
                this.isOk = false
                this.$forceUpdate();
            });
    },
    
    clearImputs(){
        this.certificateName = ''
        this.certificateDate = ''
        this.selectedCertiFile = []
    },

    sendCerticate(){       
        
        let now = moment()
        let fecha = moment()

        fecha = moment(this.certificateDate)

        if (fecha.isAfter(now)) {
            UIkit.notification('fecha mayor a lo permitido')
            return;
        }

        if (this.selectedCertiFile.type != 'application/pdf') {
            UIkit.notification('El formato del certificado debe ser pdf')
            return;                      
        }
        
            let form = new FormData()
            form.append("file", this.selectedCertiFile)

            form.append("name", this.certificateName)
            form.append("emited_date", this.certificateDate)
            //Enviar instituto
            //Gonzales creará un endpoint nuevo
            this.uploadCertificate(form,'documents')
       
    },

 },

created(){

    if (this.activeUser.id_role == 1 || this.activeUser.id_role ==3 ) { 
        this.setToken('')
        this.setUserOnStore({})
        this.goto('/users/login')    
    } 

    this.getInstitutes()  
    console.log(this.institutes)
  
},

mounted(){
    
    document.title = 'Solicitar validación de certificado';

    this.sbOptions = [
        {
            name:"Solicitar validación", 
            icon: ['fas','file-medical'],
            clic: () =>{
            }
        },
        {
            name:"Cerrar sesión", 
            icon: ['fas','power-off'],
            clic: ()=>{
                this.setToken('')
                this.setUserOnStore({})
                this.goto('/users/login')
            }
        }
    ]



       
  }
}

</script>


