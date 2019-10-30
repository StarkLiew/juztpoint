import 'babel-polyfill'
import Vue from 'vue'

import router from '~/router/index'
import store from '~/store/index'
import App from '$pos/App'
import '~/plugins/index'
import vuetify from '~/plugins/vuetify'
import IdleVue from 'idle-vue'
import VueLodash from 'vue-lodash'

const eventsHub = new Vue()
Vue.use(IdleVue, {
    eventEmitter: eventsHub,
    idleTime: 60000
})

const options = { name: 'lodash' } // customize the way you want to call it

Vue.use(VueLodash, options) // options is optional

Vue.mixin({

    methods: {
        castVariantString(item) {
            let values = []

            for (const variant of item.variants) {
                values.push(item.properties.variant[variant.name])
            }

            return values.join('-')
        },
        async sync($store) {

            /* Upload all offline transaction to server */

            const offline_receipts = $store.getters['receipt/receipts'].filter(r => r.status === 'offline')

            for (const r of offline_receipts) {
                await $store.dispatch('receipt/addReceipt', r)
            }

            /*  const offline_appointments = this.$store.getters['receipt/appointments'].filter(a => a.status === 'offline')

              for(const a of offline_appointments) {
                   await this.$store.dispatch('receipt/addReceipt', a)
              } */


            const offline_customers = $store.getters['account/customers'].filter(c => c.status === 'offline')

            for (const c of offline_customers) {
                await $store.dispatch('account/addCustomer', c)
            }


            const offline_voids = $store.getters['receipt/receipts'].filter(r => r.status === 'void offline')

            for (const v of offline_voids) {
                await $store.dispatch('receipt/voidReceipt', v)
            }


            const offline_refunds = $store.getters['receipt/receipts'].filter(r => r.status === 'update offline')

            for (const r of offline_refunds) {
                await $store.dispatch('receipt/refundReceipt', r)
            }


            const offline_shifts = $store.getters['system/shifts'].filter(s => s.synced === false)

            for (const s of offline_shifts) {
                await $store.dispatch('system/syncShift', s)
            }
            /* Fetch latest data */
            await $store.dispatch('user/fetchUsers')
            await $store.dispatch('product/fetchProducts')
            await $store.dispatch('service/fetchServices')
            await $store.dispatch('account/fetchCustomers')
            await $store.dispatch('system/fetchSystem')

        },

        sumAmount(item) {

            if (item.properties && !item.properties.price) {
                item.price = item.properties.price = 0.00
            }

            item.amount = item.qty * item.properties.price

            if (item.discount) {

                if (item.discount.type === 'fix') {
                    item.amount = item.amount - item.discount.rate
                    item.discount.amount = item.discount.rate
                } else {
                    item.discount.amount = item.amount * item.discount.rate / 100
                    item.amount = item.amount - item.discount.amount
                }

            } else {
                item.discount = { type: 'percent', rate: 0, amount: 0 }
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
                item.tax_amount = item.amount * item.tax.properties.rate / 100
                taxTotal += item.tax_amount
                if (item.refund) {
                    refundAmount += item.refund.amount
                }
            })
            if (receipt.discount) {
                if (receipt.discount.type == "percent") {
                    receipt.discount.amount = total * receipt.discount.rate / 100
                }
                if (receipt.discount.type == "fix") {
                    receipt.discount.amount = receipt.discount.rate
                }
            }

            receipt.refund = refundAmount
            receipt.charge = total

            if (receipt.tax) {
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
