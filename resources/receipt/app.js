import Vue from 'vue'
import App from '$receipt/components/App'
import VueCurrencyFilter from 'vue-currency-filter'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

Vue.use(VueCurrencyFilter, {
    symbol: '',
    thousandsSeparator: ',',
    fractionCount: 2,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: true
})

Vue.use(VueMoment, { moment })


export default new Vue({

    render: h => h(App),

})
