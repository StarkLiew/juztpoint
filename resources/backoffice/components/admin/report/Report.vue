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
                            <v-list-item :disabled="item.disabled" v-for="(item, i) in report.items" :key="i" @click="select(item)">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
        <viewer :hasSummary="true" v-if="!!selected" :title="selected.title" :headers="headers" :items.sync='items' sort-by="name" :refresh="retrieve" :summary="summary" :options.sync="options" :server-items-length="count" :loading="loading" loading-text="Loading..." @apply-filter="applyFilter" :export-fields="exportFields" :groups="[]" @closed="selected = null">
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
                        { title: 'Account Summary', fields: '"date", "item_name", "total_amount"', headers: [], exports: {}, disabled: false },
                        {
                            title: 'Payments Summary',
                            name: 'ReportPaymentSummary',
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
                            }, disabled: false
                        },
                        { title: 'Payments Log', disabled: true },
                        { title: 'Taxes Summary', disabled: true },
                        { title: 'Discount Summary',  disabled: true },
                        { title: 'Outstanding Payments', disabled: true },
                    ]
                },
                {

                    title: 'Inventory',
                    describe: 'Keep track on product stock level and etc',
                    items: [
                        { title: 'Stock on Hand',  disabled: true  },
                        { title: 'Product Sales Performance', disabled: true  },
                        { title: 'Stock Movement Log',  disabled: true },
                        { title: 'Stock Movement Summary',  disabled: true  },
                        { title: 'Product Own Consumption',  disabled: true  },
                    ]
                },
                {
                    title: 'Employee',
                    describe: 'View on team performance and earnings',
                    items: [
                    {
                            title: 'Daily Commission Summary',
                            name: 'ReportCommissionDailySummary',
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
                             disabled: false
                        },
                        { title: 'Staff Shift Summary',  disabled: true  },
                        { title: 'Staff Shift Detailed', disabled: true },
                        { title: 'Staff Commission Summary',  disabled: true  },
                        { title: 'Staff Commission Detailed',  disabled: true },
                    ]
                },
                {
                    title: 'Sales',
                    describe: 'Intel about all sales related performance and activities',
                    items: [
                        { title: 'Sales by Item',  disabled: true  },
                        { title: 'Sales by Type',  disabled: true  },
                        { title: 'Sales by Service',  disabled: true  },
                        { title: 'Sales by Product',  disabled: true  },
                        { title: 'Sales by Store',  disabled: true  },
                        { title: 'Sales by Terminal',  disabled: true  },
                        { title: 'Sales by Customer',  disabled: true },
                        { title: 'Sales by Staff',  disabled: true },
                        { title: 'Sales by Staff Breakdown',  disabled: true },
                        { title: 'Sales by Hour',  disabled: true  },
                        { title: 'Sales by Month',  disabled: true  },
                        { title: 'Sales by Year', disabled: true  },
                        { title: 'Sales Log',  disabled: true },
                    ]
                },

            ]
            this.loading = false
        }

    }
}

</script>
