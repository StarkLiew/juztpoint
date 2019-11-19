<template>
    <v-container>
        <viewer :title="selected.title" :headers="selected.headers" :items.sync='items' sort-by="reference" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="selected.exportFields" :groups="[]" @closed="selected = null" :hasSummary="false">
            <template v-slot:item.action="{item}">
                <v-icon small class="mr-2" @click="print(item)">
                    print
                </v-icon>
                <v-icon small @click="">
                    money_off
                </v-icon>
                <v-icon small @click="">
                    cancel
                </v-icon>
            </template>
            <template v-slot:item.date="{item, header}">
                    <span>{{ item[header.value] + 'Z' | moment('DD/MM/YYYY hh:mmA') }}</span>
            </template>
        </viewer>
        <vue-easy-print v-if="selectedItem" tableShow style="display: block" ref="receipt">
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

export default {
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
                fields: `id, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, customer{name},
                         teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, 
                         note,refund, items{id, line, item_id, discount{type, rate, amount}, discount_amount, tax_id, tax_amount, qty, refund_qty, 
                         refund_amount, total_amount, saleBy{id, name}, properties{price}}, payments{id, line, item_id, total_amount},properties{name}, note`,
                headers: [
                    { text: 'Reference', value: 'reference', sortable: true },
                    { text: 'Date', value: 'date', sortable: true, custom: true },
                    { text: 'Customer', value: 'customer.name', sortable: true },
                    { text: 'Amount', value: 'charge', sortable: true, align: 'end', currency: true },
                    { text: 'Refund', value: 'refund', sortable: true, align: 'end', currency: true },
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
            store: null,
            selectedItem: null,

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

            this.selectedItem = item
            console.log(item)
            // this.$refs.receipt.print()
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


    }
}

</script>
