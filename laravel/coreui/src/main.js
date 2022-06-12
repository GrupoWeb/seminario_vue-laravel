import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from './assets/icons/icons.js'
import store from './store'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2';
import axios from 'axios';
import VueCurrencyFilter from 'vue-currency-filter'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'sweetalert2/dist/sweetalert2.min.css';

import Mixin from "./mixins/index"

// Vue.prototype.$apiAdress = 'http://127.0.0.1:81'
Vue.prototype.$apiAdress = 'https://sgc.mineco.gob.gt'
Vue.config.performance = true
Vue.use(CoreuiVue)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueSweetalert2);
Vue.use(VueCurrencyFilter, {
    symbol: 'Q', // El símbolo, por ejemplo €
    thousandsSeparator: ',', // Separador de miles
    fractionCount: 2, // ¿Cuántos decimales mostrar?
    fractionSeparator: '.', // Separador de decimales
    symbolPosition: 'front', // Posición del símbolo. Puede ser al inicio ('front') o al final ('') es decir, si queremos que sea al final, en lugar de front ponemos una cadena vacía ''
    symbolSpacing: true // Indica si debe poner un espacio entre el símbolo y la cantidad
})


new Vue({
  el: '#app',
  mixins: [Mixin],
  router,
  store,  
  icons,
  template: '<App/>',
  components: {
    App
  },
  created(){
    window.axios = axios;
    window.axios.defaults.headers.common['Content-Type'] = 'application/json';
    window.axios.defaults.headers.common['Accept'] = 'application/json';

    axios.interceptors.request.use((config) => {
        store.state.isVisible = true
        return config
    }, (error) => {
        store.state.isVisible = false  
        return Promise.reject(error)
    });

    axios.interceptors.response.use((response) => {
        store.state.isVisible = false    
        return response
    }, function(error) {
        store.state.isVisible = false
        console.log(error)
        return Promise.reject(error)
    });
  }
  
})
