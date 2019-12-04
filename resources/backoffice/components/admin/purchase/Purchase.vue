<template>
    <v-container>
        <viewer title="Purchase Orders" :headers="selected.headers" :items.sync='items' sort-by="reference" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="selected.exportFields" :groups="[]" @closed="selected = null" :hasSummary="false" :hideBack="true" :showAdd="true">
            <template v-slot:dialog="{ valid, editedItem }">
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="6" lg="6">
             
                        </v-col>
                        <v-col cols="12" sm="12" md="6" lg="6">

                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.action="{item}">
                <v-icon class="mr-2" @click="print(item)" color="blue darken-1">
                    mdi-printer
                </v-icon>
                <v-dialog v-model="sendDialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn icon dark v-on="on">
                            <v-icon @click="" color="green darken-1">
                                mdi-email
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">
                                <v-icon color="green darken-1">mdi-email</v-icon>Send Receipt
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn fab light small top right @click="closeDialog">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-form ref="form" @submit.prevent="sendEmail(item)" lazy-validation v-model="valid">
                                    <v-row>
                                        <v-text-field label="Recipient name" v-model="form.name" type="name" :error-messages="errors.name" :rules="[rules.required('name')]" :disabled="loading"></v-text-field>
                                    </v-row>
                                    <v-row>
                                        <v-text-field label="Recipient email" v-model="form.email" type="email" :error-messages="errors.email" :rules="[rules.required('email')]" :disabled="loading"></v-text-field>
                                    </v-row>
                                    <v-layout row class="mt-4 mx-0">
                                        <v-spacer></v-spacer>
                                        <v-btn type="submit" :loading="loading" :disabled="loading || !valid" color="primary" class="ml-4">
                                            Send
                                        </v-btn>
                                    </v-layout>
                                </v-form>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="refundDialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn icon dark v-on="on" :disabled="item.status === 'void'" style="display:none">
                            <v-icon @click="" color="orange darken-1">
                                mdi-currency-usd-off
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">
                                <v-icon color="orange darken-1">mdi-currency-usd-off</v-icon>Refund Receipt
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn fab light small top right @click="closeDialog">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <p>
                                    To refund customer, please use the Terminal that transact this receipt.
                                </p>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="voidDialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn icon dark v-on="on" @click="selectItem(item)" :disabled="item.status === 'void'">
                            <v-icon @click="" color="red darken-1">
                                mdi-cancel
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">
                                <v-icon color="red darken-1">mdi-cancel</v-icon>Void Receipt {{ selectedItem ? selectedItem.reference : '' }}
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn fab light small top right @click="closeDialog">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-text-field label="Enter receipt reference to confirm." v-model="form.ref" :disabled="loading"></v-text-field>
                                </v-row>
                                <v-layout row class="mt-4 mx-0">
                                    <v-spacer></v-spacer>
                                    <v-btn @click="voidReceipt(selectedItem)" :loading="loading" :disabled="loading" color="primary" class="ml-4">
                                        Confirm
                                    </v-btn>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
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
import { mapGetters } from 'vuex'
import Viewer from '../shared/Viewer'
import vueEasyPrint from 'vue-easy-print'
import receipt from "../../../../pos/components/sales/shared/ReceiptTemplate"
import Form from '~~/mixins/form'
import axios from 'axios'
import { api } from '~~/config'
import { bus } from '$receipt/bus'

export default {
    mixins: [Form],
    components: {
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
            selected: {
                title: 'Receipts',
                name: 'receipts',
                fields: `id, status, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, customer{name}, terminal{id, name}, store{id, name}, teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, received, change,
                         note,refund, items{id, line, item_id, name, discount{type, rate, amount}, discount_amount, tax{id, name, properties{rate, code}}, tax_id, tax_amount, qty, refund_qty, 
                         refund_amount, total_amount, amount, saleBy{id, name}, shareWith{id, name}, composites{id, name, performBy{id, name}}, properties{price}}, payments{id, name, line, item_id, total_amount},properties{name}, note`,
                headers: [
                    { text: 'Store', value: 'store.name', sortable: true },
                    { text: 'Terminal', value: 'terminal.name', sortable: true },
                    { text: 'Teller', value: 'teller.name', sortable: true },
                    { text: 'Reference', value: 'reference', sortable: true },
                    { text: 'Date', value: 'date', sortable: true, custom: true },
                    { text: 'Supplier', value: 'customer.name', sortable: true },
                    { text: 'Amount', value: 'charge', sortable: true, align: 'end', currency: true },
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
            }

        }
    },
    computed: mapGetters({
        items: 'receipt/items',
        summary: 'receipt/summary',
        count: 'receipt/count',
    }),
    async mounted() {

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

            const com = await this.$store.dispatch('setting/fetch', { type: 'company', search: '', limit: 0, page: 1, sort: 'name', desc: '', noCommit: true })
            this.company = com[0]

            const results = await this.$store.dispatch('receipt/fetch', { name: this.selected.name, fields: this.selected.fields, filter, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false


            if (noCommit) return results
        },
        applyFilter(filter) {

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



    }
}

</script>
