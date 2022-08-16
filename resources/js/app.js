import { createApp } from 'vue'
import App from './components/App/AppComponent.vue'
const app = createApp(App)

import router from './router'
app.use(router)

app.mount('#app')

import 'tw-elements';
require('./bootstrap')