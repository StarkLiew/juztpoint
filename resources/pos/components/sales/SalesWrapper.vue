<template>
    <div>
        <div v-if="shift && shift.status === 'open'">
            <carts @cart-toggle="cartToggleUpdate" @customer-toggle="showCustomerDialog = true" @customer-remove="customer = null" @item-added="itemAdded" @edit-item="editProductToggle" @payment="goPayment" @reset-done="resetDone" :reset="reset" :is-product-entry="panel === 'product'" :show-cart.sync="showCart" :customer="customer" :product.sync="product" :calmode="setAppointment"> </carts>
            <top-menu @scanned="onScanned" @overlay="overlayShow" @search="search" @cart-toggle="cartToggle" @reset="newTrxn"></top-menu>
            <v-content style="margin-top: 5px">
                <v-sheet id="scrolling-techniques-7" class="overflow-y-auto" max-height="calc(100vh - 48px)" color="transparent">
                    <products-list @scanned="onScanned" :search.sync="searchText" @calendar="goAppointment()" @selected="selectedProduct" v-if="panel === 'product'"></products-list>
                    <item-add @close="showEdit = false" v-if="item" :product="item" :show="showEdit" @done="addedProduct"></item-add>
                    <customers-list @close="closeCustomerDialog" @selected="selectedCustomer" :show="showCustomerDialog"></customers-list>
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
        products: 'product/collection',
        services: 'service/collection',
    }),
    methods: {
        onScanned(value) {
            let item = this.products.find(p => p.sku === value)
            if (!item) this.services.find(p => p.sku === value)
            if (!item) {
                this.$toast.error('No product found')
                return
            }

            const defaultItem = {
                qty: 1,
                price: 0.00,
                discount: 0.0,
                saleBy: null,
            }

            item = JSON.parse(JSON.stringify(item))
            item = { ...defaultItem, ...item }

            this.selectedProduct(item)

        },
        cartToggle() {
            this.showCart = !this.showCart
        },
        cartToggleUpdate(val) {
            this.showCart = val
        },
        customerToggle() {
            this.panel = 'customer'

        },
        closeCustomerDialog() {
            this.showCustomerDialog = false
            setTimeout(() => {
                if (this.$vuetify.breakpoint.xs || this.$vuetify.breakpoint.sm) {
                    if (!this.showCart) this.cartToggle()
                }
            }, 10)


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

            this.closeCustomerDialog()
        },
        selectedProduct(item) {
            this.showEdit = true
            this.item = item
        },
        addedProduct(item) {
            this.showEdit = false
            this.product = JSON.parse(JSON.stringify(item))

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
            if (this.$vuetify.breakpoint.xs || this.$vuetify.breakpoint.sm) {
                this.cartToggle()
            }
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
