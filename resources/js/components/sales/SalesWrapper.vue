<template>
    <div class="fill-height">
        <div v-if="shift && shift.status === 'open'">
            <carts @cart-toggle="cartToggle" @customer-toggle="showCustomerDialog = true" @customer-remove="customer = null" @item-added="itemAdded" @edit-item="editProductToggle" @payment="goPayment" @reset-done="resetDone" :reset="reset" :is-product-entry="panel === 'product'" :show="showCart" :customer="customer" :product.sync="product" :calmode="setAppointment"> </carts>
            <top-menu @overlay="overlayShow" @search="search" @cart-toggle="cartToggle" @reset="newTrxn"></top-menu>
            <v-content style="margin-top: 5px">
                <v-sheet id="scrolling-techniques-7" class="overflow-y-auto" max-height="calc(100vh - 48px)" color="transparent">
                    <products-list :search.sync="searchText" @calendar="goAppointment()" @selected="selectedProduct" v-if="panel === 'product'"></products-list>
                    <item-add @close="showEdit = false" v-if="item" :item="item" :show="showEdit" @done="addedProduct"></item-add>
                    <customers-list @close="showCustomerDialog = false" @selected="selectedCustomer" :show="showCustomerDialog"></customers-list>
                    <payment :trxn="trxn" @done="newTrxn" @back="cancelPayment" v-if="panel === 'payment'"></payment>
                </v-sheet>
                <v-overlay :value="overlay">
                    <v-progress-circular indeterminate size="64"></v-progress-circular>
                </v-overlay>
            </v-content>
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
import TopMenu from './shared/TopMenu'
import Carts from './shared/Carts'
import ProductsList from './shared/ProductsList'
import ItemAdd from './shared/ItemAdd'
import CustomersList from './shared/CustomersList'
import Payment from './shared/Payment'


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
        TopMenu,
        Carts,
        ProductsList,
        ItemAdd,
        CustomersList,
        Payment,
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
