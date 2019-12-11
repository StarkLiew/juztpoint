<template>
    <v-container>
        <viewer title="Purchase Orders" :headers="selected.headers" :items.sync='items' sort-by="reference" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="selected.exportFields" :groups="[]" @closed="selected = null" :hasSummary="false" :hideBack="true" :showAdd="true" :default-item="defaultItem" :beforeAppendNew="appendNewItem" :saveMethod="saveItem">
            <template v-slot:dialog="{ valid, editedItem }">
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="4" lg="4">
                            <v-autocomplete v-model="editedItem.account" :items="vendors" :rules="[v => !!v || 'Account is required',]" required :loading="loading" item-text="name" label="Supplier" placeholder="Choose" prepend-icon="mdi-truck" return-object></v-autocomplete>
                            <v-menu ref="menu" v-model="showDatePicker" :close-on-content-click="false" transition="scale-transition" offset-y min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-text-field class="" v-model="editedItem.date" label="Date" prepend-icon="mdi-calendar" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                </template>
                                <v-date-picker @input="showDatePicker = false" v-model="editedItem.date"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4">
                            <v-text-field label="PO #" v-model="editedItem.reference" :rules="[v => !!v || 'PO number is required',]" required></v-text-field>
                            <v-text-field label="SO #" v-model="editedItem.properties.so"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4">
                            <v-textarea solo name="input-7-4" v-model="editedItem.note" label="Note"></v-textarea>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12" lg="12">
                            <v-data-table hide-default-footer disable-pagination :headers="itemHeaders" :items="editedItem.items" class="elevation-1">
                                <template v-slot:item.action="{item}">
                                    <v-icon class="mr-2" color="blue darken-1" @click="editLine(item)">
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon class="mr-2" color="red darken-1" @click="removeLine(item, editedItem)">
                                        mdi-close
                                    </v-icon>
                                </template>
                                <template v-slot:footer="{pagination}">
                                    <v-toolbar>
                                        <v-dialog v-model="editItemDialog" fullscreen persistent max-width="600px">
                                            <template v-slot:activator="{ on }">
                                                <v-btn text v-on="on" small color="primary">
                                                    <v-icon>mdi-plus-circle-outline</v-icon>
                                                    Add a new item
                                                </v-btn>
                                            </template>
                                            <purchase-line :line="selectedLine" :collection="editedItem.items" @close="closeDialogLine" @save="updateItems(editedItem, ...arguments)"></purchase-line>
                                        </v-dialog>
                                        <v-spacer></v-spacer>
                                        <v-toolbar-title>Total</v-toolbar-title>
                                        <v-toolbar-title>{{ 0 | currency }}</v-toolbar-title>
                                    </v-toolbar>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.date="{item, header}">
                <span>{{ item[header.value] + 'Z' | moment('DD/MM/YYYY hh:mmA') }}</span>
            </template>
        </viewer>
        <vue-easy-print v-if="selectedItem" tableShow style="display: none" ref="receipt">
            <template slot-scope="func">
                <receipt v-model="selectedItem" :header="{company, store: selectedItem.store}"></receipt>
            </template>
        </vue-easy-print>
    </v-container>
</template>
<script>
import Vue from 'vue'
import { mapGetters } from 'vuex'
import Viewer from '../shared/Viewer'
import vueEasyPrint from 'vue-easy-print'
import receipt from "../../../../pos/components/sales/shared/ReceiptTemplate"
import PurchaseLine from './PurchaseLine'
import Form from '~~/mixins/form'
import axios from 'axios'
import { api } from '~~/config'
import { bus } from '$receipt/bus'

export default {
    mixins: [Form],
    components: {
        PurchaseLine,
        Viewer,
        vueEasyPrint,
        receipt,
    },

    /*    discount{type,rate,amount}, discount_amount,  tax_total, 
                         service_charge, charge, rounding, note,refund, 
                         items{id, line,  item_id, discount{type,rate,amount}, discount_amount, tax_id, tax_amount, qty, refund_qty, refund_amount, total_amount}, 
                          payments{id, line, item_id, total_amount} 
                         properties{name}, note` */
    data() {
        return {
            reports: [],
            showDatePicker: false,
            defaultItem: {
                account_id: '',
                account: '',
                transact_by: -1,
                note: '',
                reference: '',
                properties: {
                    so: '',
                },
                items: [],
                charge: 0,
                tax_amount: 0,
                formData: null,
            },
            editedLine: {
                item: null,
                item_id: -1,
                line: -1,
                type: 'po',
                trxn_id: -1,
                user_id: -1,
                note: '',
                qty: null,
                discount: {
                    rate: 0,
                    type: 'percent',
                    amount: 0,
                },
                discount_amount: 0,
                tax_id: -1,
                tax: null,
                tax_amount: 0,
                total_amount: 0,
                properties: {
                    price: 0,
                }
            },
            selected: {
                title: 'Receipts',
                name: 'receipts',
                fields: `id, status, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, account{id, name}, terminal{id, name}, store{id, name}, teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, received, change, properties{so},
                         note,refund, items{id, line, item_id, item{id, name, sku}, note, name, discount{type, rate, amount}, discount_amount, tax{id, name, properties{rate, code}}, tax_id, tax_amount, qty, refund_qty, 
                         refund_amount, total_amount, amount, saleBy{id, name}, shareWith{id, name}, composites{id, name, performBy{id, name}}, properties{price}}, payments{id, name, line, item_id, total_amount},properties{name}, note`,
                headers: [

                    { text: 'Reference', value: 'reference', sortable: true },
                    { text: 'Date', value: 'date', sortable: true, custom: true },
                    { text: 'Supplier', value: 'customer.name', sortable: true },
                    { text: 'Amount', value: 'charge', sortable: true, align: 'end', currency: true },
                    { text: 'Issued by', value: 'teller.name', sortable: true },
                    { text: 'Status', value: 'status', sortable: true },
                    { text: 'Actions', value: 'action', sortable: false, custom: true },
                ],
                exports: {
                    'reference': 'reference',
                    'date': 'date',
                    'customer': 'customer.name',
                    'amount': 'charge',
                },
                disabled: false
            },
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 25,
            },
            title: '',
            exportFields: {},
            headers: [],
            loading: false,
            company: null,
            sendDialog: false,
            refundDialog: false,
            voidDialog: false,
            store: null,
            selectedItem: null,
            passwordHidden: true,
            form: {
                email: '',
                name: '',
                ref: '',
            },

            selectedLine: null,
            supplierItems: [],
            itemHeaders: [
                { text: 'Item', value: 'item.sku' },
                { text: 'Description value', value: 'note' },
                { text: 'Quantity', value: 'qty', align: 'end' },
                { text: 'Price', value: 'properties.price', align: 'end', currency: true },
                { text: 'Discount', value: 'discount.rate', align: 'end', currency: true },
                { text: 'Tax', value: 'tax.name' },
                { text: 'Amount', value: 'total_amount', align: 'end', currency: true },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            editItemDialog: false,



        }
    },
    watch: {

    },
    computed: mapGetters({
        auth: 'auth/user',
        items: 'receipt/items',
        summary: 'receipt/summary',
        count: 'receipt/count',
        vendors: 'account/items',
    }),
    async created() {
        await this.$store.dispatch('receipt/reset')
    },
    methods: {
        async print(item) {
            this.$emit('overlay', true)
            this.selectedItem = item
            setTimeout(() => {

                this.$refs.receipt.print()
                this.$emit('overlay', false)
                this.selectedItem = null
            }, 3000)



        },
        async retrieve(filter, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            await this.$store.dispatch('account/fetch', { type: 'vendor', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            const results = await this.$store.dispatch('receipt/fetch', { name: 'purchase', fields: this.selected.fields, filter, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false


            if (noCommit) return results
        },
        applyFilter(filter) {

        },
        appendNewItem(editedItem, items) {

            if (items.length > 0) {
                // const last = items[items.length - 1]
                const last = items[0]
                const numStr = last.reference.replace(/\D/g, '')
                const flag = last.reference.match(/^\D+/g, '')


                const parsedNum = (parseInt(numStr) + 1 || 0) + ''
                let padded = ''
                if (numStr.length > 0) {
                    padded = new Array((parsedNum.length - 1) + (/\./.test(parsedNum) ? 2 : 1)).join('0') + parsedNum
                }

                editedItem.reference = flag + padded

            } else {
                editedItem.reference = ''
            }



        },
        async saveItem(item) {

            this.loading = true

            if (!item.id) {

                item.transact_by = this.auth.id
                item.type = 'po'

                await this.$store.dispatch('document/add', item)
            } else {

                await this.$store.dispatch('document/update', item)
            }


            this.loading = false
        },
        select(item) {
            this.selected = item
            this.headers = item.headers
            this.exportFields = item.exports

        },
        async selectItem(item) {
            this.selectedItem = item
            const company = this.company

        },
        closeDialog() {
            this.sendDialog = false
            this.voidDialog = false
            this.refundDialog = false
            this.selectedItem = null
            this.reset()
        },
        reset() {
            this.form.name = ''
            this.form.email = ''
            this.form.ref = ''
        },
        async voidReceipt(item) {
            if (item.reference === this.form.ref) {
                await this.$store.dispatch('receipt/voidReceipt', item)
                this.$toast.success(item.reference + ' receipt has been voided.')
                this.closeDialog()
            } else {
                this.$toast.error('Wrong receipt reference.')
            }
            this.reset()
        },
        async sendEmail(item) {
            if (this.$refs.form.validate()) {
                this.loading = true
                const form = {
                    id: item.id,
                    to: this.form.email,
                    name: this.form.name,
                    data: { item, company: this.company }
                }

                await axios.post(api.path('receipt'), form)
                    .then(res => {
                        this.$toast.success('You have been successfully registered!')
                        this.$emit('success')
                    })
                    .catch(err => {
                        this.handleErrors(err.response.data.errors)
                    })
                    .then(() => {
                        this.loading = false
                    })
            }
        },
        updateItems(editedItem, line) {

            if (!this.selectedLine) {
                if (editedItem.items.length < 1) line.line = 1
                else {
                    const last = editedItem.items[editedItem.items.length - 1]
                    line.line = last.line + 1
                }
                editedItem.items.push(JSON.parse(JSON.stringify(line)))
            } else {
                const index = editedItem.items.findIndex(v => v.line === line.line)
                Vue.set(editedItem.items, index, JSON.parse(JSON.stringify(line)))
                this.selectedLine = null

            }
            this.editItemDialog = false
        },
        editLine(line) {
            this.selectedLine = line
            this.editItemDialog = true
        },
        removeLine(line, editedItem) {
            const index = editedItem.items.findIndex(v => v.line === line.line)
            editedItem.items.splice(index, 1)

        },
        closeDialogLine() {
            this.selectedLine = null
            this.editItemDialog = false
        },
        pickedDate() {
            this.showDatePicker = false
        },


    }

}

</script>
