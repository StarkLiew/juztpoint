import 'babel-polyfill'
import Vue from 'vue'

import router from '~/router/index'
import store from '~/store/index'
import App from '$comp/App'
import '~/plugins/index'
import vuetify from '~/plugins/vuetify'
import IdleVue from 'idle-vue'
import VueLodash from 'vue-lodash'

const eventsHub = new Vue()
Vue.use(IdleVue, {
     eventEmitter: eventsHub,
     idleTime: 30000
})

const options = { name: 'lodash' } // customize the way you want to call it

Vue.use(VueLodash, options) // options is optional

Vue.mixin({

  methods: {

    sumAmount(item) {
          
        if(item.properties && !item.properties.price) {
           item.price = item.properties.price = 0.00
        }

        item.amount = item.qty * item.properties.price

        if (item.discount) {
     
          if(item.discount.type === 'fix') {
             item.amount =  item.amount - item.discount.rate
             item.discount.amount = item.discount.rate
          } else {
             item.discount.amount = item.amount * item.discount.rate / 100
             item.amount =  item.amount - item.discount.amount
          }

        } else {
            item.discount =  {type: 'percent', rate: 0, amount: 0}
        }
           
        return item
    },
    sumTotal(receipt) {
        let total = 0
        let taxTotal = 0
        let discountAmount = 0
        let refundAmount = 0
       
         
        receipt.items.forEach((item) => {
            total += item.amount
            item.tax_amount =  item.amount * item.tax.properties.rate / 100
            taxTotal += item.tax_amount
            if(item.refund) {
                   refundAmount += item.refund.amount 
            }
        })
        if(receipt.discount) {
            if(receipt.discount.type == "percent") {
                receipt.discount.amount = total * receipt.discount.rate /100
            }
            if(receipt.discount.type == "fix") {
                receipt.discount.amount = receipt.discount.rate
            }
         }

          receipt.refund = refundAmount        

         if(receipt.tax) {
            receipt.tax = taxTotal        
         }
    },
  }
}) 

export const app = new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
  async onIdle() {
      await this.$store.dispatch('auth/logout')
      this.$toast.info('You are logged out.')
      this.$router.push({ name: 'login' })
  },
  onActive() {

  }


}).$mount('#app')
