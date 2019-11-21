import 'babel-polyfill'
import Vue from 'vue'
import Receipt from '$backoffice/Receipt'

export const app = new Vue({
    render: h => h(Receipt),
}).$mount('#app')

