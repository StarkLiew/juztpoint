import 'babel-polyfill'
import Vue from 'vue'

import router from '~back/router/index'
import store from '~back/store/index'
import App from '$backoffice/App'
import '~back/plugins/index'
import vuetify from '~back/plugins/vuetify'
import VueLodash from 'vue-lodash'

const eventsHub = new Vue()

const options = { name: 'lodash' } // customize the way you want to call it

Vue.use(VueLodash, options) // options is optional

Vue.mixin({
    methods: {}
})

export const app = new Vue({
    router,
    store,
    vuetify,
    render: h => h(App),
}).$mount('#app')
