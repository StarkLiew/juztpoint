<template>
    <v-container>
        <v-row v-if="!selected">
            <v-col col="6" lg="6" md="6" sm="12" v-for="(report, index) in reports" :key="index">
                <v-card min-width="300" class="mx-auto mx-2" color="grey lighten-4" max-width="600">
                    <v-card-title>
                        {{ report.title }}
                    </v-card-title>
                    <v-list>
                        <v-list-item-group color="primary">
                            <v-list-item v-for="(item, i) in report.items" :key="i" @click="select(item)">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
        <viewer v-if="!!selected" :title="selected.title" :headers="headers" :items.sync='items' sort-by="name" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="exportFields" :groups="[]" @closed="selected = null">
        </viewer>
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import Viewer from '../shared/Viewer'


export default {
    components: {
        Viewer
    },
    data() {
        return {
            reports: [],
            selected: null,
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            title: '',
            exportFields: {},
            headers: [],
            loading: false,

        }
    },
    computed: mapGetters({
        items: 'report/items',
        summary: 'report/summary',
        count: 'report/count',
    }),
    async mounted() {
        this.initialise()
    },
    methods: {
        async retrieve(filter, options, noCommit = false) {
           
            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options

            const results = await this.$store.dispatch('report/fetch', { name: this.selected.name, fields: this.selected.fields, filter, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })



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
        async initialise() {

            this.loading = true


            this.reports = [{
                    title: 'Accounts',
                    describe: 'Keep track on all cash flow, payments, taxes, and etc',
                    items: [
                        { title: 'Account Summary', fields: '"date", "item_name", "total_amount"', headers: [], exports: {}, },
                        {
                            title: 'Payments Summary',
                            name: 'payment_summary',
                            fields: 'item_name, total_amount, refund_amount, net, count',
                            headers: [
                                { text: 'Payment', value: 'item_name', sortable: false },
                                { text: 'Transactions', value: 'count', sortable: false, align: 'end', },
                                { text: 'Gross', value: 'total_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Refund', value: 'refund_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Net', value: 'net', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'payment': 'item_name',
                                'transactions': 'count',
                                'gross': 'total_amount',
                                'refund': 'refund_amount',
                                'net': 'net',
                            },
                        },
                        { title: 'Payments Log', to: '' },
                        { title: 'Taxes Summary', to: '' },
                        { title: 'Discount Summary', to: '' },
                        { title: 'Outstanding Payments', to: '' },
                    ]
                },
                {

                    title: 'Inventory',
                    describe: 'Keep track on product stock level and etc',
                    items: [
                        { title: 'Stock on Hand', to: '' },
                        { title: 'Product Sales Performance', to: '' },
                        { title: 'Stock Movement Log', to: '' },
                        { title: 'Stock Movement Summary', to: '' },
                        { title: 'Product Own Consumption', to: '' },
                    ]
                },
                {
                    title: 'Employee',
                    describe: 'View on team performance and earnings',
                    items: [
                    {
                            title: 'Daily Commission Summary',
                            name: 'commission_daily_summary',
                            fields: 'item_date, item_name, total_amount',
                            headers: [
                                { text: 'Date', value: 'item_date', sortable: true },
                                { text: 'Name', value: 'item_name', sortable: true },
                                { text: 'Earn', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'date': 'item_date',
                                'name': 'item_name',
                                'earn': 'total_amount',
                            },
                        },
                        { title: 'Staff Shift Summary', to: '' },
                        { title: 'Staff Shift Detailed', to: '' },
                        { title: 'Staff Commission Summary', to: '' },
                        { title: 'Staff Commission Detailed', to: '' },
                    ]
                },
                {
                    title: 'Sales',
                    describe: 'Intel about all sales related performance and activities',
                    items: [
                        { title: 'Sales by Item', to: '' },
                        { title: 'Sales by Type', to: '' },
                        { title: 'Sales by Service', to: '' },
                        { title: 'Sales by Product', to: '' },
                        { title: 'Sales by Store', to: '' },
                        { title: 'Sales by Terminal', to: '' },
                        { title: 'Sales by Customer', to: '' },
                        { title: 'Sales by Staff', to: '' },
                        { title: 'Sales by Staff Breakdown', to: '' },
                        { title: 'Sales by Hour', to: '' },
                        { title: 'Sales by Month', to: '' },
                        { title: 'Sales by Year', to: '' },
                        { title: 'Sales Log', to: '' },
                    ]
                },

            ]
            this.loading = false
        }

    }
}

</script>
