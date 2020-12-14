<template>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4"><!-- Sidebar -->
            <Sidebar :options="sbOptions"/>  
        </div><!-- End Sidebar -->

        <div class="col-md-8"><!-- Content -->
            <div class="card">
                <div class="card-header bg-greenv">
                    <p class="card-text text-white">Certificados recibidos</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Alumno</th>
                            <th scope="col">Certificado</th>
                            <th scope="col">Visualizar</th>
                            <th scope="col">Descargar</th>
                            <th scope="col">Aprobar</th>
                            </tr>
                        </thead>
                        <!-- <tbody id="contenido"> -->
                        <paginate name="certificates" :list="getPendingToApproveCertificates" :per="10" tag="tbody">
                            <tr v-for="(certificate, index) in paginated('certificates')" :key="index+'certificates'">
                            <!-- <tr v-for="(certificate, index) in getPendingToApproveCertificates" :key="index+'certificates'"> -->
                            <td class="text-center">{{certificate.id_document}}</td>
                            <td class="formatText"> {{certificate.name}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="viewCertificate(certificate)">
                                    <font-awesome-icon icon="eye"/>
                                </button>  
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="downloadCertificate(certificate)">
                                    <font-awesome-icon icon="download"/>
                                </button>  
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="setTemporalCert(certificate)" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
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

    <div class="row" id="viewerPDF">


    </div>


    <!--Begin Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{tempCert.name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Actualizar estado:</label>
                            <select class="form-select form-select-sm" v-model="statusType" aria-label=".form-select-sm">
                                <option v-for="(option,index) in status" :value="option.value" :key="index+'statusaccount'">{{option.text}}</option>
                            </select>
                        </div>
                        <div class="mb-3" v-if="statusType == 1">
                            <label for="message-text" class="col-form-label">Adjuntar firma:</label>
                            <div class="form-file">
                                <input type="file" @change="onFileSelected" class="form-file-input" id="customFile">
                                <label class="form-file-label" for="customFile">
                                    <span class="form-file-text">{{selectedSignFile.name}}</span>
                                    <span class="form-file-button">Examinar</span>
                                </label>
                            </div>   
                            <div id="passwordHelpBlock" class="form-text">
                                <small class="text-muted">Public Key.</small>
                            </div>
                        </div>
                        <div class="mb-3" v-else>
                            <label for="exampleFormControlTextarea1" class="form-label">Motivo:</label>
                            <textarea class="form-control" v-model="notesReason" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" @click="sendApproval()" data-dismiss="modal">Guardar</button>
                </div>
            </div>
        </div>
    </div><!--End Modal-->

</div>

</template>

<script>

import { required } from 'vuelidate/lib/validators'
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'
import Sidebar from '@/components/Sidebar.vue'
import axios from 'axios'


export default {
    name: 'Recieved',
    components: { 
        Sidebar
    },

data: function () {
    return { 
        certificates:[],
        tempCert:{
            id_document:''
        },
        selectedSignFile:[],
        sbOptions:[],
        paginate: ['certificates'],
        statusType:1,
        status:[
            {
                value: 1, text:'Aprobado'
            },
            {
                value: 2, text:'Rechazado'
            }
        ],
        notesReason:''

    }
  }, 

  validations: {
     
  },

  mixins: [mixinMethods],

  computed: {
    ...mapState(['api','tkn','activeUser']),

//Refresca los certificados pendientes de aprobación en estado = 4
    getPendingToApproveCertificates: function(){
        let arreglo = [];
        
        this.certificates.forEach(element => {
            if (element.status == 4) {
                element.isEditing = false
                arreglo.push(element)
            }
        });     
        return arreglo
    }
    
  },

  methods:{
    ...mapMutations(['setToken','setUserOnStore']),
    
    //Lista de certificados asignados para validación
    getCertificates () {      
        this.request(this.api + 'documents/assigned', 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {                 
            this.certificates = respond.documents
        },()=>{},(err)=>{
            console.log(err)
            UIkit.notification(err.message)
        })    
      },

    viewCertificate(certificate){

        this.request(this.api + 'documents/getfile/' + certificate.id_document, 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {     
            const a1 = document.createElement('a')
            a1.href = URL.createObjectURL( this.change(respond.blob,'application/pdf'))
            a1.target='_Blank'
            a1.click()

        },()=>{},(err)=>{
            UIkit.notification(err.message)
        })    

    },


    downloadCertificate(certificate){
        UIkit.notification(certificate.id_document)

        this.request(this.api + 'documents/getfile/' + certificate.id_document, 'GET', {authorization: "Basic " + this.tkn},{},
        (respond) => {     
            let blob =  this.change(respond.blob)
            let namePdf = certificate.name
            namePdf = namePdf.split(' ').join('') + '.pdf'
            saveAs(blob,namePdf)
        },()=>{},(err)=>{
            UIkit.notification(err.message)
        })    

    },

    //Seteo variable tempCert para que sea utilizada en el modal
    setTemporalCert(certificate){
        this.tempCert = certificate;    
    },

    onFileSelected(event){
        this.selectedSignFile = event.target.files[0]
        console.log(this.selectedSignFile)
    },

    async uploadSign(form){        
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
                this.tempCert.isEditing = false
                this.$forceUpdate();
            });
        },


    clearImputs(){

        this.notesReason = ''
    },


    sendApproval(){

        if (this.statusType == 2) {
            
            let newNotes = JSON.parse(this.tempCert.notes)
            newNotes.push({key:"reason", value:this.notesReason})
            
            this.tempCert.isEditing = true

            this.request(this.api + 'documents/' + this.tempCert.id_document, 'PUT',  {authorization: "Basic " + this.tkn}, {       
                id_institute: this.tempCert.idInstitute,
                status: this.statusType,
                name: this.tempCert.name,
                notes: JSON.stringify(newNotes),
                emited_date: this.tempCert.emited_date
            },
            (respond) => {             
                UIkit.notification("<span uk-icon='icon: check'></span> Se actualizó estado del certificado.")          
                this.tempCert.status = 2   
                this.clearImputs()                 
            },()=>{},(err)=>{
                this.tempCert.isEditing = false
                UIkit.notification(err.message)
            })        
            return;
        }    

        if (this.statusType == 1  &&  this.selectedSignFile == '') {
            UIkit.notification("Adjuntar firma")
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
    document.title = 'Certificados recibidos';

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
            }
        },
        {
            name:"Certificados pendientes", 
            icon: ['fas','file-alt'],
            clic: () =>{
                this.goto('/institute/certificates/pending')
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

