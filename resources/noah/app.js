import 'babel-polyfill'
import Vue from 'vue'

import router from '~~~~//router/index'
import store from '~~~~//store/index'
import App from '$noah/App'
import '~~~~//plugins/index'
import vuetify from '~~~~//plugins/vuetify'
import VueLodash from 'vue-lodash'

const options = { name: 'lodash' } // customize the way you want to call it

Vue.use(VueLodash, options) // options is optional


export const app = new Vue({
    router,
    store,
    vuetify,
    render: h => h(App),

}).$mount('#app')
