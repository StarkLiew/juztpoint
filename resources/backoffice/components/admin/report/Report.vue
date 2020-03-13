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
                    items: [{
                            title: 'Account Summary',
                            name: 'ReportAccountSummary',
                            fields: 'item_name, total_amount, refund_amount, net, count',
                            headers: [
                                { text: 'Customer', value: 'item_name', sortable: false },
                                { text: 'Transactions', value: 'count', sortable: false, align: 'end', },
                                { text: 'Gross', value: 'total_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Refund', value: 'refund_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Net', value: 'net', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'customer': 'item_name',
                                'transactions': 'count',
                                'gross': 'total_amount',
                                'refund': 'refund_amount',
                                'net': 'net',
                            },
                            disabled: false
                        },

                        {
                            title: 'Payments Summary',
                            name: 'ReportPaymentSummary',
                            fields: 'item_name, customer_name, total_amount, refund_amount, net, count',
                            headers: [
                                { text: 'Payment', value: 'item_name', sortable: false },
                                { text: 'Customer', value: 'customer_name', sortable: false },
                                { text: 'Transactions', value: 'count', sortable: false, align: 'end', },
                                { text: 'Gross', value: 'total_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Refund', value: 'refund_amount', sortable: false, align: 'end', currency: true },
                                { text: 'Net', value: 'net', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'payment': 'item_name',
                                'customer': 'customer_name',
                                'transactions': 'count',
                                'gross': 'total_amount',
                                'refund': 'refund_amount',
                                'net': 'net',
                            },
                            disabled: false
                        },

                        {
                            title: 'Payment Log',
                            name: 'ReportPaymentLog',
                            fields: 'date, store_name, terminal_name, reference, customer_name, user_name, payment_name, total_amount',
                            headers: [
                                { text: 'Payment Date', value: 'date', sortable: true },
                                { text: 'Store', value: 'store_name', sortable: true },
                                { text: 'Terminal', value: 'terminal_name', sortable: true },
                                { text: 'Receipt', value: 'reference', sortable: true },
                                { text: 'Customer', value: 'customer_name', sortable: true },
                                { text: 'Cashier', value: 'user_name', sortable: true },
                                { text: 'Method', value: 'payment_name', sortable: true },
                                { text: 'Amount', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'date': 'date',
                                'store': 'store_name',
                                'terminal': 'terminal_name',
                                'receipt': 'reference',
                                'customer': 'customer_name',
                                'cashier': 'user_name',
                                'method': 'payment_name',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },

                        {
                            title: 'Taxes Summary',
                            name: 'ReportTaxesSummary',
                            fields: 'item_name, store_name, customer_name, count, total_amount',
                            headers: [
                                { text: 'Tax', value: 'item_name', sortable: true },
                                { text: 'Store', value: 'store_name', sortable: true },
                                { text: 'Customer', value: 'customer_name', sortable: true },
                                { text: 'Transactions', value: 'count', sortable: false, align: 'end', },
                                { text: 'Amount', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'tax': 'item_name',
                                'store': 'store_name',
                                'customer': 'customer_name',
                                'transactions': 'count',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },

                        {
                            title: 'Discount Summary',
                            name: 'ReportDiscountSummary',
                            fields: 'item_name, store_name, customer_name, count, total_amount',
                            headers: [
                                { text: 'Discount', value: 'item_name', sortable: true },
                                { text: 'Store', value: 'store_name', sortable: true },
                                { text: 'Customer', value: 'customer_name', sortable: true },
                                { text: 'Transactions', value: 'count', sortable: false, align: 'end', },
                                { text: 'Amount', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'discount': 'item_name',
                                'store': 'store_name',
                                'customer': 'customer_name',
                                'transactions': 'count',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },

                    ]
                },
                {

                    title: 'Inventory',
                    describe: 'Keep track on product stock level and etc',
                    items: [

                        {
                            title: 'Stock On Hand',
                            name: 'ReportStockOnHand',
                            fields: 'item_name, onhand, cost, total_amount',
                            headers: [
                                { text: 'Product', value: 'item_name', sortable: true },
                                { text: 'On Hand', value: 'onhand', sortable: false, align: 'end', },
                                { text: 'Average Cost', value: 'cost', sortable: false, align: 'end', currency: true },
                                { text: 'Amount', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'product': 'item_name',
                                'on hand': 'onhand',
                                'cost': 'cost',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Product Sales Performance',
                            name: 'ReportProductSalesPerformance',
                            fields: 'item_name, cat_name, qty, total_amount',
                            headers: [
                                { text: 'Product', value: 'item_name', sortable: true },
                                { text: 'Category', value: 'cat_name', sortable: false, align: 'end', },
                                { text: 'Quantity', value: 'qty', sortable: false, align: 'end', currency: true },
                                { text: 'Amount', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'product': 'item_name',
                                'category': 'cat_name',
                                'quantity': 'qty',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Stock Movement Log',
                            name: 'ReportStockMovementLog',
                            fields: 'date, item_name, user_name, method, price, qty',
                            headers: [
                                { text: 'Date & Time', value: 'date', sortable: true },
                                { text: 'Product', value: 'item_name', sortable: true },
                                { text: 'Staff', value: 'user_name', sortable: true },
                                { text: 'Action', value: 'method', sortable: false, align: 'end', },
                                { text: 'Price', value: 'price', sortable: false, align: 'end', currency: true },
                                { text: 'Quantity', value: 'qty', sortable: false, align: 'end' },
                            ],
                            exports: {
                                'date & time': 'date',
                                'product': 'item_name',
                                'staff': 'user_name',
                                'action': 'action',
                                'cost': 'price',
                                'quantity': 'qty',
                            },
                            disabled: false
                        },
                        {
                            title: 'Stock Movement Summary',
                            name: 'ReportStockMovementSummary',
                            fields: 'item_name, opening, received, sold, return, balance, total_amount',
                            headers: [
                                { text: 'Product', value: 'item_name', sortable: true },
                                { text: 'Opening', value: 'opening', sortable: false, align: 'end', },
                                { text: 'Received', value: 'received', sortable: false, align: 'end', },
                                { text: 'Sold', value: 'sold', sortable: false, align: 'end', },
                                { text: 'Return', value: 'return', sortable: false, align: 'end', },
                                { text: 'Balance', value: 'balance', sortable: false, align: 'end', },
                                { text: 'Value', value: 'total_amount', sortable: false, align: 'end', currency: true },
                            ],
                            exports: {
                                'product': 'item_name',
                                'opening': 'opening',
                                'sold': 'sold',
                                'cost': 'price',
                                'return': 'return',
                                'balance': 'balance',
                                'value': 'total_amount',
                            },
                            disabled: false
                        },
                        { title: 'Product Own Consumption', disabled: true },
                    ]
                },
                {
                    title: 'Employee',
                    describe: 'View on team performance and earnings',
                    items: [{
                            title: 'Staff Commission Summary By Day',
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
                        {
                            title: 'Staff Commission Summary',
                            name: 'ReportCommissionSummary',
                            fields: 'item_name, total_amount',
                            headers: [
                                { text: 'Name', value: 'item_name', sortable: true },
                                { text: 'Earn', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'name': 'item_name',
                                'earn': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Staff Commission Detailed',
                            name: 'ReportCommissionDetailed',
                            fields: 'item_date, user_name, item_name, total_amount',
                            headers: [
                                { text: 'Date', value: 'item_date', sortable: true },
                                { text: 'Staff', value: 'user_name', sortable: true },
                                { text: 'Name', value: 'item_name', sortable: true },
                                { text: 'Earn', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'date': 'item_date',
                                'staff': 'user_name',
                                'product': 'item_name',
                                'earn': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Service Charge Summary',
                            name: 'ReportServiceChargeSummary',
                            fields: 'item_date, total_amount',
                            headers: [
                                { text: 'Date', value: 'item_date', sortable: true },
                                { text: 'Amount', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'date': 'item_date',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        { title: 'Staff Shift Summary', disabled: true },
                        { title: 'Staff Shift Detailed', disabled: true },

                    ]
                },
                {
                    title: 'Sales',
                    describe: 'Intel about all sales related performance and activities',
                    items: [{
                            title: 'Sales by Product',
                            name: 'ReportSalesByProduct',
                            fields: 'item_name, product_type, category_name, qty, total_amount',
                            headers: [
                                { text: 'Name', value: 'item_name', sortable: true },
                                { text: 'Type', value: 'product_type', sortable: true },
                                { text: 'Category', value: 'category_name', sortable: true },
                                { text: 'Sold', value: 'qty', sortable: true, align: 'end' },
                                { text: 'Amount', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'name': 'item_name',
                                'type': 'product_type',
                                'category': 'category_name',
                                'sold': 'qty',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Service',
                            name: 'ReportSalesByService',
                            fields: 'item_name, product_type, category_name, qty, total_amount',
                            headers: [
                                { text: 'Name', value: 'item_name', sortable: true },
                                { text: 'Type', value: 'product_type', sortable: true },
                                { text: 'Category', value: 'category_name', sortable: true },
                                { text: 'Sold', value: 'qty', sortable: true, align: 'end' },
                                { text: 'Amount', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'name': 'item_name',
                                'type': 'product_type',
                                'category': 'category_name',
                                'sold': 'qty',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Product Category',
                            name: 'ReportSalesByProductCategory',
                            fields: 'category_name, qty, total_amount',
                            headers: [
                                { text: 'Category', value: 'category_name', sortable: true },
                                { text: 'Sold', value: 'qty', sortable: true, align: 'end' },
                                { text: 'Amount', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'category': 'category_name',
                                'sold': 'qty',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Service Category',
                            name: 'ReportSalesByServiceCategory',
                            fields: 'item_name, product_type, category_name, qty, total_amount',
                            headers: [
                                { text: 'Category', value: 'category_name', sortable: true },
                                { text: 'Sold', value: 'qty', sortable: true, align: 'end' },
                                { text: 'Amount', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'category': 'category_name',
                                'sold': 'qty',
                                'amount': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Store',
                            name: 'ReportSalesByStore',
                            fields: 'item_name, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Store', value: 'item_name', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'store': 'item_name',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Terminal',
                            name: 'ReportSalesByTerminal',
                            fields: 'item_name, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Store', value: 'item_name', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'store': 'item_name',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Customer',
                            name: 'ReportSalesByCustomer',
                            fields: 'item_name, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Customer', value: 'item_name', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'customer': 'item_name',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },
                        {
                            title: 'Sales by Staff',
                            name: 'ReportSalesByStaff',
                            fields: 'item_name, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Staff', value: 'item_name', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'staff': 'item_name',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },

                        { title: 'Sales by Staff Breakdown', disabled: true },
                    {
                            title: 'Sales by Day',
                            name: 'ReportSalesByDay',
                            fields: 'item_date, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Date', value: 'item_date', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'date': 'item_date',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },
                                        {
                            title: 'Sales by Month',
                            name: 'ReportSalesByMonth',
                            fields: 'item_date, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Month', value: 'item_date', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'month': 'item_date',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },
                                        {
                            title: 'Sales by Year',
                            name: 'ReportSalesByYear',
                            fields: 'item_date, product_amount, service_amount, total_amount',
                            headers: [
                                { text: 'Year', value: 'item_date', sortable: true },
                                { text: 'Product', value: 'product_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Service', value: 'service_amount', sortable: true, align: 'end', currency: true },
                                { text: 'Total', value: 'total_amount', sortable: true, align: 'end', currency: true },
                            ],
                            exports: {
                                'year': 'item_date',
                                'product': 'product_amount',
                                'service': 'service_amount',
                                'total': 'total_amount',
                            },
                            disabled: false
                        },


                        { title: 'Sales Log', disabled: true },
                    ]
                },

            ]
            this.loading = false
        }

    }
}

</script>
