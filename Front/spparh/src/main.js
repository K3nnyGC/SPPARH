import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import '@/assets/main.css'
import Vuelidate from 'vuelidate'
import 'animate.css/animate.min.css'
import VuePaginate from 'vue-paginate'
Vue.use(VuePaginate)

Vue.use(Vuelidate)

import { library } from '@fortawesome/fontawesome-svg-core'
import { faBars,  faFileMedical, faFileAlt, faFileSignature, faPowerOff, faBuilding, faPlusSquare, faCheckSquare, faEdit, faCog, faPlusCircle, faMinusCircle } from '@fortawesome/free-solid-svg-icons'
import { faFile } from '@fortawesome/free-regular-svg-icons'
import { faFacebookSquare } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faFile, faBars, faFileMedical, faFileAlt, faFileSignature, faPowerOff, faBuilding, faPlusSquare, faCheckSquare, faEdit, faCog, faPlusCircle, faMinusCircle)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

Vue.filter('untilNow', function (value) {
  return moment(''+value).fromNow();
});

Vue.filter('formatDate', function (value) {
  return moment(value).format('DD/MM/YYYY');
});

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
