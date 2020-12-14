<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8"><!-- Content -->
            <div class="card">
                <div class="card-header bg-greenv">
                    <p class="card-text text-white">Solicitar validación de certificado</p>
                </div>
                <div class="card-body">
                 <form @submit.prevent>       
                    <div class="mb-2">
                        <div id="passwordHelpBlock" class="form-text">
                            <small class="text-muted">Paso 1: Encuentra el nombre de la institución donde obtuviste el certificado</small>
                        </div>
                        <label for="certificatename" class="form-label h6">Nombre de la institución*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="instituteToSearch" @keyup.enter="searchInstitute()" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <!-- <button class="btn btn-outline-secondary" @click="searchInstitute()" type="button" id="button-addon2">Buscar</button> -->
                        </div>   
                        <div class="input-group mb-3">    <!-- v-model="instituteSelected"  -->
                            <select class="form-select form-select-sm" v-model="instituteSelected" aria-label=".form-select-sm">
                                <option v-for="(institute,index) in instituteToSearchResult" :value="institute" :key="index+'instituteToSearchResult'">{{institute.comercial_name}}</option>
                            </select>
                            
                        </div>                    
                 
                    </div>

                    <!-- <div class="mb-2">
                        <label for="institutename" class="form-label h6">Nombre de la institución educativa*</label>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="text" placeholder="Buscar" class="form-control" v-model="comercialName">
                            </li>
                            <li v-for="(institute, index) in searchInstitute" :key="index+'institutes'" class="list-group-item">
                                {{institute.comercial_name}}
                            </li>
                        </ul>
                    </div>
 -->
                    <div v-if="instituteToSearchResult.length">
                        <div class="row mb-2">
                            <div id="passwordHelpBlock" class="form-text">
                                <small class="text-muted">Paso 2: Completa los siguientes campos</small>
                            </div>
                            <div class="col">
                                <label for="certificatename" class="form-label h6">Nombre del certificado*</label>
                                <input type="text" class="form-control form-control-sm" :class="{'is-invalid': $v.certificateName.$error}" v-model.trim="$v.certificateName.$model">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="certificatedate" class="form-label h6">Fecha de emisión*</label>
                                <input type="date"  class="form-control form-control-sm" :class="{'is-invalid': $v.certificateDate.$error}" v-model.trim="$v.certificateDate.$model">
                            </div>
                            <div class="col">
                                <label for="certificatefile" class="form-label h6">Cargar certificado*</label>
                                <div class="form-file form-file-sm">
                                    <input type="file" @change="onFileSelected"  accept=".pdf" class="form-file-input" id="customFileSm">
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
                            <div class="col-6">
                                <button type="button" @click="sendCerticate()" class="btn btn-secondary">
                                    <div v-if="isOk" class="spinner-border spinner-border-sm"></div>
                                    <span v-else>Enviar</span>
                                </button>
                            </div>
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
        instituteToSearch:'',
        instituteToSearchResult:[],
        instituteSelected: null,
        certificateName:'',     
        comercialName:'',
        certificateDate:'',
        selectedCertiFile:[],
        sbOptions:[],
        isOk:false ,
        setTimeoutSearch:'' 
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
    
    // searchInstitute: function () {
    //     return this.institutes.filter((institute) => institute.comercial_name.includes(this.comercialName))
    // }



  },

  methods:{
    ...mapMutations(['setToken', 'setUserOnStore']),
    
    getInstitutes () {      
        this.request(this.api + 'users/institutes?seed=' + this.instituteToSearch, 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {                 
            this.institutes = respond.institutes
            console.log(respond.institutes)
        },()=>{},(err)=>{
            UIkit.notification(err.message)
        })    
      },

    searchInstitute () {      

        if (this.instituteToSearch.trim().length < 3) {
            UIkit.notification('Ingrese al menos 3 letras')
            return
        } 

        this.request(this.api + 'users/institutes?seed=' + this.instituteToSearch, 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {                 
            this.instituteToSearchResult = respond.institutes
            if (this.instituteToSearchResult.length > 0) {
                this.instituteSelected = this.instituteToSearchResult[0]
            }else
            {
                this.instituteSelected = null
                UIkit.notification('No se encontraron resultados')
            }

            console.log(respond.institutes)
        },()=>{},(err)=>{
            UIkit.notification(err.message)
        })    
      },

    // searchInstitute () {

    //     clearTimeout(this.setTimeoutSearch)
    //     this.setTimeoutSearch = setTimeout(this.getInstitutes, timeout:360)

    // },

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
            form.append("id_institute", this.instituteSelected.id_user)
            form.append("name", this.certificateName)
            form.append("emited_date", this.certificateDate)
            form.append("notes",'[]')
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


