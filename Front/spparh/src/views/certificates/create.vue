<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8"><!-- Content -->
            <div class="card">
                <div class="card-header bg-greenv">
                    <!-- <h5 class="card-title text-white text-white">Lista de instituciones pendientes</h5> -->
                    <p class="card-text text-white">Crear certificado</p>
                </div>
                <div class="card-body">
                 <form @submit.prevent>       
                    <div class="mb-2">
                        <label for="certificatename" class="form-label h6">Nombre del certificado*</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.certificateName.$error }" v-model.trim="$v.certificateName.$model">
                    </div>

                    <div class="row mb-2">
                        <!-- <div class="col">
                            <label for="studentname" class="form-label h6">Notas</label>
                            <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.studentName.$error }" v-model.trim="$v.studentName.$model">
                        </div> -->
                        <div class="col">
                            <label for="certificatedate" class="form-label h6">Fecha de emisión*</label>
                            <input type="date"  class="form-control form-control-sm" :class="{ 'is-invalid': $v.certificateDate.$error}" v-model.trim="$v.certificateDate.$model">
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

                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="addsignselect" class="form-label h6">¿Desear agregar firma?</label>
                            <!-- <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.legalname.$error }" v-model.trim="$v.legalname.$model"> -->
                            <select class="form-select form-select-sm" v-model="signCertificate" aria-label=".form-select-sm">
                                <option v-for="(option,index) in signCertificates" :value="option.value" :key="index+'signcertificateselect'">{{option.text}}</option>
                            </select>
                        </div>
                        <div class="col-6" v-if="signCertificate == 1">
                            <label for="addsign" class="form-label h6">Agregar firma</label>
                            <div class="form-file form-file-sm">
                                <input type="file" @change="onSignSelected"  class="form-file-input" id="customFileSm">
                                <label class="form-file-label" for="customFile">
                                    <span class="form-file-text">{{selectedSignFile.name}}</span>
                                    <span class="form-file-button">Examinar</span>
                                </label>
                            </div>                                   
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div v-for="(field, index) in newFields" :key="index + 'newfields'" class="col-6 mb-2">
                            <input v-model="field.key" type="text" class="form-control form-control-sm mb-2">
                            <input v-model="field.value" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <div class="col-3">
                                <font-awesome-icon :icon="['fas','plus-circle']" @click="addField()"/>
                                campos
                            </div>
                            <div class="col-3" v-show="newFields.length>0">
                                <font-awesome-icon :icon="['fas','minus-circle']"  @click="deleteField()"/> campos
                            </div>
                            <!-- <font-awesome-icon :icon="['fas','minus-circle']"/>
                            <font-awesome-icon :icon="['fas','plus-circle']"/> -->
                            <!-- <button @click="addField()" type="button" class="btn btn-primary mr-2">Agregar campos</button> -->
                            <!-- <button v-show="newFields.length>0" @click="deleteField()" type="button" class="btn btn-primary">Eliminar campos</button> -->
                            
                            
                            
                        </div>
                    </div>  


                    
                    <div class="row mb-2">  
                    </div>
                    
                    <div class="row mb-2" style="float:right;">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary">Cancelar</button>
                        </div>
                        <div class="col-6">
                            <!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Guardar</button> -->
                            <button type="button" @click="sendCerticate()" class="btn btn-secondary">Guardar</button>
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

import { required, minLength, maxLength, alphaNum, numeric, between } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
import Sidebar from '@/components/Sidebar.vue'
import axios from 'axios'


export default {
    name: 'validation',
    components: { 
        Sidebar
    },


data: function () {
    return { 
        signCertificates:[
            {value:0, text:'No'},
            {value:1, text:'Si'}
        ],
        signCertificate:0,
        certificateName:'',
        studentName:'',
        certificateDate:'',
        selectedCertiFile:[],
        selectedSignFile:[],
        newFields:[],
        sbOptions:[]    
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

    getPendingCertificates: function(){
        let arreglo = [];

        this.certificates.forEach(element => {
            if (element.status == 0) {
                arreglo.push(element)
            }
        });    
        return arreglo
    }
    
  },

  methods:{
    ...mapMutations(['setToken', 'setUserOnStore']),
      
    getCertificates () {      
        this.request(this.api + 'documents/', 'GET', {},{},
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
        this.selectedCertiFile = event.target.files[0]
        console.log(this.selectedCertiFile)
    },

     onSignSelected(event){
        this.selectedSignFile = event.target.files[0]
        console.log(this.selectedSignFile.to)
    },

    async uploadCertificate(form, apiHeader){        
        this.$forceUpdate();
            axios({
                method: 'POST',
                url: this.api + apiHeader,
                headers: {
                    'Content-Type': 'multipart/form-data',
                    "Accept": "application/json, text/plain, */*"
                },
                data:form
            }).then(response => {
                 UIkit.notification("<span uk-icon='icon: check'></span> Certificado registrado exitosamente.") 
                 this.clearImputs()                 
            }).catch(err => {
                UIkit.notification(err.response.data.message)  
                UIkit.notification(err.response.data.parameter) 
            }).finally( () => {
            });
    },
    
    clearImputs(){

        this.certificateName = ''
        this.certificateDate = ''
        this.selectedCertiFile = []
        this.selectedSignFile = []
        this.newFields = []

    },

    sendCerticate(){       
        
        let now = moment()
        let fecha = moment()

        fecha = moment(this.certificateDate)

        if (fecha.isAfter(now)) {
                alert('actualzar fecha del certificado')
                console.log(fecha)
                console.log(now)
                return;
        }

        if (this.selectedCertiFile.type != 'application/pdf') {         
            UIkit.notification('Formato de archivo adjunto incorrecto')
            this.selectedSignFile = ''
            return;
                        
        }
        
        
            let form = new FormData()
            form.append("file", this.selectedCertiFile)
            form.append("sign", this.selectedSignFile)
            form.append("notes", JSON.stringify(this.newFields))
            form.append("name", this.certificateName)
            form.append("emited_date", this.certificateDate)
            form.append("id_institute", this.activeUser.id_user)
            this.uploadCertificate(form, this.signCertificate ? 'certificates/create' : 'documents')
            // UIkit.notification(err.response.data.message)  
            // UIkit.notification(err.response.data.parameter) 
       
    },

    addField(){
        this.newFields.push({key:'', value:''})
    },

    deleteField(){
        this.newFields.pop()
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
    
    document.title = 'Crear certificado';

    this.sbOptions = [
        {
            name:"Crear certificado", 
            icon: ['fas','file-medical'],
            clic: () =>{
            }
        },
        {
            name:"Certificado pendiente", 
            icon: ['fas','file-alt'],
            clic: () =>{
                this.goto('/certificates/pending')
            }
        },
        // {
        //     name:"Mis certificados", 
        //     icon: ['fas','file-signature'],
        //     clic: () =>{
        //         this.goto('/certificates/list')
        //     }
        // },
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


