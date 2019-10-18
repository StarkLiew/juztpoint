<template>
    <v-row dense>
       <v-col col="6"  v-for="(report, index) in items" :key="index">
        <v-card class="mx-auto mx-2" color="grey lighten-4" max-width="600">
            <v-card-title>
                {{ report.title }}
            </v-card-title>
            <v-list>
                <v-list-item-group color="primary">
                    <v-list-item v-for="(item, i) in report.items" :key="i">
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-card>
    </v-col>
    </v-row>
</template>
<script>
import { mapGetters } from 'vuex'
const exhale = ms =>
    new Promise(resolve => setTimeout(resolve, ms))

export default {
    components: {

    },
    data() {
        return {
            items: [],

        }
    },
    computed: {},
    async mounted() {
        this.initialise()
    },
    methods: {
        initialise() {
            this.items = [{
                    title: 'Accounts',
                    describe: 'Keep track on all cash flow, payments, taxes, and etc',
                    items: [
                        { title: 'Account Summary', to: '' },
                        { title: 'Payments Summary', to: '' },
                        { title: 'Payments Log', to: '' },
                        { title: 'Taxes Summary', to: '' },
                        { title: 'Discount Summary', to: '' },
                        { title: 'Outstanding Payments', to: '' },
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
                        { title: 'Staff Commission Summary', to: '' },
                        { title: 'Staff Commission Detailed', to: '' },
                    ]
                },
            ]
        }

    }
}

</script>
