import { createApp } from 'vue'
import './style.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'
import 'bootstrap-icons/font/bootstrap-icons.min.css'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'

const pinia = createPinia()

createApp(App)
    .use(router)
    .use(pinia)
    .use(Toast)
    .mount('#app')
