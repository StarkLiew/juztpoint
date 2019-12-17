import Vue from 'vue'
import Vuetify, { VSnackbar, VBtn, VIcon } from 'vuetify/lib'
import VuetifyToast from 'vuetify-toast-snackbar'
import VueCurrencyFilter from 'vue-currency-filter'
import VueMoment from 'vue-moment'
import VueTheMask from 'vue-the-mask'
import money from 'v-money'
import moment from 'moment-timezone'





Vue.use(VueCurrencyFilter, {
    symbol: '',
    thousandsSeparator: ',',
    fractionCount: 2,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: true
})

Vue.use(money, {precision: 4})

Vue.use(VueTheMask)

Vue.use(VueMoment, { moment })

Vue.use(Vuetify, {
    components: {
        VSnackbar,
        VBtn,
        VIcon
    }
})
Vue.use(VuetifyToast)


export default new Vuetify()
