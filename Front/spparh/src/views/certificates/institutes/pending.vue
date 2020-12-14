<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8"><!-- Content -->
            <div class="card">
                <div class="card-header bg-greenv">
                    <p class="card-text text-white">Certificados pendientes</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Certificado</th>
                            <th scope="col">Fecha de Emisión</th>
                            <th scope="col">Firmar</th>
                            </tr>
                        </thead>
                        <!-- <tbody id="contenido"> -->
                        <paginate name="certificates" :list="getPendingCertificates" :per="10" tag="tbody">
                            <tr v-for="(certificate, index) in paginated('certificates')" :key="index+'certificates'">
                            <!-- <tr v-for="(certificate, index) in getPendingCertificates" :key="index+'certificates'"> -->
                            <td class="text-center">{{certificate.id_document}}</td>
                            <td class="formatText"> {{certificate.name}}</td>
                            <td>{{certificate.created_date | formatDate}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="setTemporalCert(certificate)" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
                                    <!-- <font-awesome-icon :icon="['fas','edit']" /> -->
                                    <!-- <font-awesome-icon :icon="isLoading && certificate.id_document == tempCert.id_document ? 'cog': 'edit'" :spin="isLoading && certificate.id_document == tempCert.id_document"/> -->
                                    <font-awesome-icon :icon="certificate.isEditing ? 'cog': 'edit'" :spin="certificate.isEditing"/>
                                </button>  
                            </td>
                            </tr>
                        </paginate>
                        <!-- </tbody> -->
                        </table>
                    </div>
                <paginate-links for="certificates" :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"></paginate-links>
                </div>
            </div><!--End Card-->
        </div><!-- End Content -->  
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Código del certificado: {{tempCert.id_document}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent>
                                    <div class="mb-3">
                                        <label for="message-text" class="col-form-label">Adjuntar firma:</label>
                                        <div class="form-file">
                                            <input type="file" @change="onFileSelected" accept=".pdf" class="form-file-input" id="customFile">
                                            <label class="form-file-label" for="customFile">
                                                <span class="form-file-text">{{selectedSignFile.name}}</span>
                                                <span class="form-file-button">Examinar</span>
                                            </label>
                                        </div>   
                                        <div id="passwordHelpBlock" class="form-text">
                                            <small class="text-muted">Public Key.</small>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-secondary" @click="sendSign()" data-dismiss="modal">Guardar</button>
                                </div>
                            </div>
                        </div>
    </div><!--End Modal-->

</div>

</template>

<script>

import { required, minLength, maxLength, email, alphaNum, numeric } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
import Sidebar from '@/components/Sidebar.vue'
import axios from 'axios'


export default {
    name: 'pending',
    components: { 
        Sidebar
    },

data: function () {
    return { 
        certificates:[],
        tempCert:{
            id_document:''
        },
        users:[],
        selectedSignFile:[],
        //form: new FormData,
        sbOptions:[],
        isLoading:false,
        paginate: ['certificates']
    }
  }, 

  validations: {
     
  },

  mixins: [mixinMethods],

  computed: {
    ...mapState(['api','tkn','activeUser']),

    getPendingCertificates: function(){
        let arreglo = [];
        
        this.certificates.forEach(element => {
            if (element.status == 0) {
                element.isEditing = false
                arreglo.push(element)
            }
        });     
        return arreglo
    }
    
  },

  methods:{
    ...mapMutations(['setToken','setUserOnStore']),
      
    getCertificates () {      
        this.request(this.api + 'documents/pending', 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {                 
            this.certificates = respond.documents
        },()=>{},(err)=>{
            console.log(err)
            UIkit.notification(err.message)
        })    
      },

    setTemporalCert(certificate){
        this.tempCert = certificate;    
    },

    onFileSelected(event){
        this.selectedSignFile = event.target.files[0]
        console.log(this.selectedSignFile)
    },

    async uploadSign(form){        
        //this.isLoading = true   
        this.tempCert.isEditing = true
        this.$forceUpdate();
            axios({
                method: 'POST',
                url: this.api + 'certificates/update',
                headers: {
                    'Content-Type': 'multipart/form-data',
                    "Accept": "application/json, text/plain, */*"
                },
                data:form
            }).then(response => {
                 UIkit.notification("<span uk-icon='icon: check'></span> Certificado firmado exitosamente.")  
                 this.tempCert.status = 1
            }).catch(error => {
                UIkit.notification("Revisar los datos ingresados")  
            }).finally( () => {
                //this.isLoading = false;
                this.tempCert.isEditing = false
                this.$forceUpdate();
            });
        },


    sendSign(){

        if (this.selectedSignFile == '') {
            UIkit.notification('Adjuntar firma')
             return;
        }    

        let form = new FormData()
        form.append("sign", this.selectedSignFile)
        form.append("id_document", this.tempCert.id_document)
        this.uploadSign(form)
            
       
    }


  },

created(){
    if (this.activeUser.id_role == 1  ) { 
        this.getCertificates()  
         
    } else {
        this.setToken('')
        this.setUserOnStore({})
        this.goto('/users/login')           
    }
},

  mounted(){
    document.title = 'Certificados pendientes';

    this.sbOptions = [
        {
            name:"Crear certificado", 
            icon: ['fas','file-medical'],
            clic: () =>{
                this.goto('/institute/certificates/create')
                }
        },
        {
            name:"Certificados recibidos", 
            icon: ['fas','file-import'],
            clic: () =>{
                this.goto('/institute/certificates/recieved')
            }
        },
        {
            name:"Certificados pendientes", 
            icon: ['fas','file-alt'],
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

<style scoped>
</style>

