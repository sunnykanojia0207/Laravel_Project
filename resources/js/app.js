import { createApp } from 'vue'
import router from './router.js'
import App from './App.vue'
import "bootstrap"
import 'bootstrap/dist/css/bootstrap.min.css'
import "bootstrap-icons/font/bootstrap-icons.css"



createApp(App).use(router).mount('#app');
// const app = createApp(App);
// app.use(router).mount('#app');
