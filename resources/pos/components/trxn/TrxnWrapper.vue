<template>
    <div class="fill-height" >
        <div v-if="shift && shift.status === 'open'">
            <carts @cart-toggle="cartToggle" @customer-toggle="showCustomerDialog = true" @customer-remove="customer = null" @item-added="itemAdded" @edit-item="editProductToggle" @payment="goPayment" @reset-done="resetDone" :reset="reset" :is-product-entry="panel === 'product'" :show="showCart" :customer="customer" :product.sync="product" :calmode="setAppointment"> </carts>

            <panel></panel>
        </div>
        <v-banner v-if="!shift" single-line>
            <v-icon slot="icon" color="warning" size="36">
                mdi-wifi-strength-alert-outline
            </v-icon>
            Shift is closed. Seek Manager assistance to open new shift.
            <template v-slot:actions>
                <v-btn color="primary" text to="{name: 'pos'}">
                    Exit
                </v-btn>
            </template>
        </v-banner>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Carts from './shared/Carts'
import Panel from './shared/Panel'


export default {
    data: () => ({
        panel: 'product',
        customer: null,
        product: null,
        showEdit: false,
        reset: false,
        showCart: false,
        item: null,
        setAppointment: false,
        overlay: false,
        showCustomerDialog: false,
        trxn: null,
        searchText: '',
    }),
    components: {

        Carts,
        Panel,

    },
    computed: mapGetters({
        shift: 'system/shift',
    }),

    methods: {
        cartToggle() {
            this.showCart = !this.showCart
        },
        customerToggle() {
            this.panel = 'customer'
        },
        productToggle() {
            this.panel = 'product'
        },
        overlayShow(show) {
            this.overlay = show
        },
        editProductToggle(product) {
            this.item = product

            this.panel = 'edit-product'
        },
        goAppointment(status) {
            this.setAppointment = status
        },
        itemAdded() {
            this.product = null
        },
        editProductToggle() {
            this.showCart = !this.showCart
        },
        selectedCustomer(customer) {
            this.customer = customer
            this.showCustomerDialog = false
        },
        selectedProduct(item) {
            this.showEdit = true
            this.item = item
        },
        addedProduct(item) {
            this.showEdit = false
            this.product = item
        },
        cancelProductEdit() {
            this.productToggle()
        },
        doneProductEdit(item) {

            this.productToggle()
        },
        cancelPayment() {
            this.trxn = null
            this.panel = "product"
        },
        newTrxn() {
            this.customer = null
            this.trxn = null
            this.panel = "product"
            this.reset = true
            this.searchText = ''
            // window.location.reload()
        },
        search(text) {
            this.searchText = text
        },
        resetDone() {
            this.reset = false
        },
        goPayment(trxn) {
            this.trxn = trxn
            this.panel = 'payment'
        },

    }
}

</script>
