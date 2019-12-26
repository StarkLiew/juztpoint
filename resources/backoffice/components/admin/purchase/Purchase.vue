<template>
    <v-container>
        <viewer title="Purchase Orders" :headers="selected.headers" :items.sync='items' sort-by="reference" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="selected.exportFields" :groups="[]" @closed="selected = null" :hasSummary="false" :hideBack="true" :showAdd="true" :default-item="defaultItem" :beforeAppendNew="appendNewItem" :saveMethod="saveItem" :removeMethod="removeItem">
            <template v-slot:dialog="{ valid, editedItem }">
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="4" lg="4">
                            <v-autocomplete v-model="editedItem.account" :items="vendors" :rules="[v => !!v || 'Account is required',]" required :loading="loading" item-text="name" label="Supplier" placeholder="Choose" prepend-icon="mdi-truck" return-object></v-autocomplete>
                            <v-menu ref="menu" v-model="showDatePicker" :close-on-content-click="false" transition="scale-transition" offset-y min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-text-field class="" :value="$moment(editedItem.date).format('YYYY-MM-DD')" label="Date" prepend-icon="mdi-calendar" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                </template>
                                <v-date-picker @input="pickedDate(editedItem, ...arguments)" :value="$moment(editedItem.date).format('YYYY-MM-DD')"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4">
                            <v-text-field label="PO #" v-model="editedItem.reference" :rules="[v => !!v || 'PO number is required',]" required :disabled="!!editedItem.id"></v-text-field>
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
                                        <v-spacer></v-spacer>
                                        <v-toolbar-title>Total</v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-toolbar-title>{{ editedItem.charge | currency }}</v-toolbar-title>
                                    </v-toolbar>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.receive="{item, header}">
                <v-dialog v-model="updateDialog" fullscreen persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" fab small dark color="deep-orange darken-1">
                            <v-icon>mdi-truck-check</v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title primary-title>
                            <v-toolbar dark color="primary">
                                <v-btn icon @click="receiveDialogClose" :disabled="saving">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <v-toolbar-title>
                                    {{ item.account.name }}
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-title>
                                    {{ item.reference }}
                                </v-toolbar-title>
                                <v-toolbar-title>
                                    ~ {{ item.date | moment('DD/MM/YYYY') }}
                                </v-toolbar-title>
                            </v-toolbar>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-form ref="receiveForm" v-model="validReceive">
                 
                                    <v-expansion-panels accordion>
                                        <v-expansion-panel v-for="(line,index) in item.items" :key="index">
                                            <v-expansion-panel-header @click="receivedItemReset()">
                                                <v-toolbar flat tile>
                                                    <v-toolbar-title>{{ line.item.name }}</v-toolbar-title>
                                                    <v-spacer></v-spacer>
                                                    <v-toolbar-title>{{ line.qty }}</v-toolbar-title>
                                                    <v-toolbar-title v-if="line.qty === line.refund_qty">
                                                        <v-icon color="green darken-1">mdi-equal</v-icon>
                                                    </v-toolbar-title>
                                                    <v-toolbar-title v-if="line.qty !== line.refund_qty">
                                                        <v-icon color="orange darken-1">mdi-not-equal-variant</v-icon>
                                                    </v-toolbar-title>
                                                    <v-toolbar-title>{{ line.refund_qty }}</v-toolbar-title>
                                                </v-toolbar>
                                            </v-expansion-panel-header>
                                            <v-expansion-panel-content>
                                           <v-row>
                                            <v-col cols="2" lg="2" md="2" sm="12">
                                                <v-menu ref="menu" :close-on-content-click="true" transition="scale-transition" offset-y min-width="290px">
                                                    <template v-slot:activator="{ on }">
                                                        <v-text-field dense class="" :value="$moment(receivedItem.properties.date).format('YYYY-MM-DD')" label="Date" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                                    </template>
                                                    <v-date-picker @input="pickedReceivedDate(receivedItem, ...arguments)" :value="$moment(receivedItem.properties.date).format('YYYY-MM-DD')"></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="2" lg="2" md="2" sm="12">
                                                <v-text-field v-model="receivedItem.properties.do" :rules="[v => !!v || 'D/O # is required',]" required dense label="D/O"></v-text-field>
                                            </v-col>
                                            <v-col cols="3" lg="3" md="3" sm="12">
                                                <v-autocomplete dense v-model="receivedItem.store" :items="stores" :rules="[v => !!v || 'Store is required',]" required :loading="loading" item-text="name" label="Store" placeholder="Choose" return-object></v-autocomplete>
                                            </v-col>
                                            <v-col cols="3" lg="3" md="3" sm="12">
                                                <v-autocomplete dense v-model="receivedItem.user" :items="users" :rules="[v => !!v || 'Received person is required',]" required :loading="loading" item-text="name" label="Received by" return-object placeholder="Choose"></v-autocomplete>
                                            </v-col>
                                            <v-col cols="1" lg="1" md="1" sm="12">
                                                <v-text-field dense v-model="receivedItem.qty" :rules="[v => !!v.match(/^[0-9]+(\.[0-9]{1,2})?$/g) || 'Quantity is required',]" required label="Quantity"></v-text-field>
                                            </v-col>
                                            <v-col cols="1" lg="1" md="1" sm="12">
                                                <v-btn :loading="saving" :disabled="!validReceive" icon small color="primary" @click="addReceiveItem(line)">
                                                    <v-icon>mdi-plus-circle</v-icon>
                                                </v-btn>
                                            </v-col>
                                        </v-row>
                                                <v-row v-for="(received, rIndex) in line.receives" :key="rIndex">
                                                    <v-col cols="2" lg="2" md="2" sm="12">
                                                        {{ received.properties.date | moment('YYYY-MM-DD') }}
                                                    </v-col>
                                                    <v-col cols="2" lg="2" md="2" sm="12">
                                                        {{ received.properties.do }}
                                                    </v-col>
                                                    <v-col cols="3" lg="3" md="3" sm="12">
                                                        {{ received.store.name }}
                                                    </v-col>
                                                    <v-col cols="3" lg="3" md="3" sm="12">
                                                        {{ received.user.name }}
                                                    </v-col>
                                                    <v-col cols="1" lg="1" md="1" sm="12">
                                                        {{ received.qty }}
                                                    </v-col>
                                                    <v-col cols="1" lg="1" md="1" sm="12">
                                                        <v-btn icon small color="red darken-1" @click="removeReceiveItem(line, rIndex)" :disabled="saving">
                                                            <v-icon>mdi-close</v-icon>
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </v-form>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </template>
            <template v-slot:item.date="{item, header}">
                <span>{{ item[header.value] + 'Z' | moment('DD/MM/YYYY') }}</span>
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
            showReceivedDatePicker: false,
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
                },
                receives: [],

            },
            receivedItem: {
                item: null,
                store: null,
                store_id: -1,
                item_id: -1,
                line: -1,
                type: 'receive',
                trxn_id: -1,
                user_id: -1,
                note: '',
                qty: 0,
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
                    date: new Date(),
                    do: '',
                    price: 0,
                }
            },
            selected: {
                title: 'Receipts',
                name: 'DataReceipts',
                fields: `id, status, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, account{id, name}, terminal{id, name}, store{id, name}, transact_by, teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, received, change, properties{so},
                         note,refund, items{id, line, item_id, item{id, name, sku}, user_id, note, name, discount{type, rate, amount}, discount_amount, tax{id, name, properties{rate, code}}, tax_id, tax_amount, qty, refund_qty, receives{id, store_id, store{id, name}, user{id, name}, qty, properties{do, date}}, 
                         refund_amount, total_amount, amount, saleBy{id, name}, shareWith{id, name}, composites{id, name, performBy{id, name}}, properties{price}}, payments{id, name, line, item_id, total_amount},properties{name}, note`,
                headers: [

                    { text: 'Reference', value: 'reference', sortable: true },
                    { text: 'Date', value: 'date', sortable: true, custom: true },
                    { text: 'Supplier', value: 'account.name', sortable: true },
                    { text: 'Amount', value: 'charge', sortable: true, align: 'end', currency: true },
                    { text: 'Issued by', value: 'teller.name', sortable: true },
                    { text: 'Status', value: 'status', sortable: true },
                    { text: 'Receive', value: 'receive', sortable: false, custom: true },
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
            saving: false,
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
            updateDialog: false,
            validReceive: false,
            maskMoney: {
                decimal: '.',
                thousands: ',',
                prefix: '',
                suffix: '',
                precision: 2,
                masked: false
            },
            maskQty: {
                decimal: '.',
                thousands: ',',
                prefix: '',
                suffix: '',
                precision: 0,
                masked: false
            },
            showReceiveItemDialog: false,


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
        stores: 'setting/items',
        users: 'user/users',
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

            await this.$store.dispatch('user/fetch', { type: 'user', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            await this.$store.dispatch('setting/fetch', { type: 'store', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            await this.$store.dispatch('account/fetch', { type: 'vendor', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            const results = await this.$store.dispatch('receipt/fetch', { name: 'DataPurchase', fields: this.selected.fields, filter, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false


            if (noCommit) return results
        },
        applyFilter(filter) {

        },
        appendNewItem(editedItem, items) {
d
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
        async removeItem(item) {
            this.loading = true
            await this.$store.dispatch('document/trash', item)
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
            this.recalc(editedItem)
            this.editItemDialog = false
        },
        recalc(editedItem) {
            let totalAmount = 0
            let totalTax = 0
            for (const item of editedItem.items) {
                totalAmount += item.total_amount
                totalTax += item.tax_amount
            }
            editedItem.charge = totalAmount
            editedItem.tax_amount = totalTax
        },
        editLine(line) {
            this.selectedLine = line
            this.editItemDialog = true
        },
        removeLine(line, editedItem) {
            const index = editedItem.items.findIndex(v => v.line === line.line)
            confirm('Are you sure you want to delete this item?') && editedItem.items.splice(index, 1)
            this.recalc(editedItem)
        },
        closeDialogLine() {
            this.selectedLine = null
            this.editItemDialog = false
        },
        pickedDate(editedItem, val) {
            editedItem.date = val
            this.showDatePicker = false
        },
        pickedReceivedDate(editedItem, val) {
            editedItem.properties.date = val
            this.showReceivedDatePicker = false
        },
        receivedItemReset() {

            this.$refs.receiveForm.reset()
            this.$refs.receiveForm.resetValidation()
            this.validReceive = false
            this.receivedItem = {
                item: null,
                store: null,
                store_id: -1,
                item_id: -1,
                line: -1,
                type: 'po',
                trxn_id: -1,
                user_id: -1,
                note: '',
                qty: 0,
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
                    date: new Date(),
                    do: '',
                    price: 0,
                }
            }
        },
        async addReceiveItem(line) {
            this.saving = true

            line.refund_qty = this.calcReceivedQty(line.receives) + parseFloat(this.receivedItem.qty)
            const { receivedItem } = this
            const result = await this.$store.dispatch('document/receive', { line, receivedItem })

            if (result) {

                this.receivedItem.id = result.id
                line.receives.push({ ...receivedItem })


            } else {
                this.$toast.error('Fail to save received item!')
            }
            this.receivedItemReset()
            this.saving = false
        },
        async removeReceiveItem(line, rIndex) {
            this.saving = true
            this.receivedItemReset()
            if (confirm('Are you sure you want to delete this item?')) {
                const receivedItem = { ...line.receives[rIndex] }
                line.receives.splice(rIndex, 1)
                line.refund_qty = this.calcReceivedQty(line.receives)
                const doc = await this.$store.dispatch('document/undo', { line, receivedItem })


                if (doc) {

                    // this.$store.dispatch('report/updateDocumentStatus', { id: doc.id, status: doc.status })

                } else {
                    line.receives.push(rIndex, receivedItem)
                    this.$toast.error('Fail to remove received item.')
                }

            }
            this.saving = false
        },
        calcReceivedQty(receives) {
            let total = 0
            for (const receive of receives) {
                total += parseFloat(receive.qty)
            }
            return total
        },
        receiveDialogClose() {
            this.receivedItemReset()
            this.updateDialog = false
        },
        receiveDialogSave() {
            this.receivedItemReset()
            this.updateDialog = false
        },


    }

}

</script>
