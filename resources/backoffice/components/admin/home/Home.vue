<template>
    <v-container>
        <h1 class="display-1">Dashboard</h1>
        <v-row>
            <v-col cols="12" lg="4" md="4" sm="12">
                <v-card class="mx-auto mb-3" max-width="400">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title class="Title">THIS WEEK</v-list-item-title>
                            <v-list-item-subtitle>{{ week.start | moment('MMM D') }} ~ {{ week.end | moment('MMM D') }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-card-text>
                        <v-row align="center">
                            <v-col class="text-center" cols="12">
                                <v-progress-circular v-if="loading" indeterminate color="green"></v-progress-circular>
                                <span v-if="!loading" class="display-3 font-weight-black black--text">{{ appointments.summary.count }}</span><br />
                                <span class="title">Appointments</span>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn text :to="{ name: 'appointments'}">View Schedule</v-btn>
                    </v-card-actions>
                </v-card>
                <v-card class="mx-auto" max-width="400">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title class="title">Things to do</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list flat>
                        <v-list-item-group color="primary">
                            <v-list-item :to=" { name: 'products' }">
                                <v-list-item-content>
                                    <v-list-item-title>Add a Product</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item :to="{ name: 'services.standard'}">
                                <v-list-item-content>
                                    <v-list-item-title>Add a Service</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item :to=" { name: 'customers' }">
                                <v-list-item-content>
                                    <v-list-item-title>Add a Customer</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item :to=" { name: 'vendors' }">
                                <v-list-item-title>Add a Supplier</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item :to=" { name: 'reports' }">
                                <v-list-item-content>
                                    <v-list-item-title>View Report</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
            <v-col cols="12" lg="8" md="8" sm="12">
                <v-card v-for="(item, i) in items" :key="i" class="mx-auto mb-3" color="white" max-width="600">
                    <v-card-title class="title">
                        {{ item.title }}
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="item.refresh(item)" :loading="item.loading">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-sheet color="white">
                        <apexchart v-if="item.chart === 'bar' && item.data" width="549" height="344" type="bar" :options="item.data.options" :series="item.data.series"></apexchart>
                        <apexchart v-if="item.chart === 'line' && item.data" width="549" height="344" type="line" :options="item.data.options" :series="item.data.series"></apexchart>
                    </v-sheet>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import VueApexCharts from 'vue-apexcharts'

export default {
    components: {
        'apexchart': VueApexCharts
    },
    data() {

        return {
            loading: false,
            appointments: {
                summary: {
                    count: 0
                }
            },
            week: { start: '', end: '' },
            items: [],
        }

    },

    async created() {

        this.initialize()
        for (const item of this.items) {
            if (item.refresh) await item.refresh(item)
        }
    },
    methods: {
        initialize() {
            this.items = [
                { title: 'Recent Sales Performance', chart: 'bar', loading: false, refresh: this.chartSalesSix, data: [] },
                /* { title: 'Top Products', chart: 'bar', loading: false, refresh: this.topProduct, data: [] },
                 { title: 'Top Services', chart: 'bar', loading: false, refresh: this.topService, data: [] },
                 { title: 'Top Employee', chart: 'bar', loading: false, refresh: this.topEmployee, data: [] }, */
            ]

            const today = this.$moment()
            this.week.start = today.startOf('week').toString()
            this.week.end = today.endOf('week').toString()


        },
        async retrieve(item) {

            this.loading = true
            // const { sortBy, sortDesc, page, itemsPerPage } = options
            item.loading = true
            item.datacollection = null
            try {



                this.appointments = await this.$store.dispatch('report/fetch', {
                    name: 'WeeklyAppointmentCount',
                    fields: `total_amount`,
                    filter: [this.week.start, this.week.end],
                    limit: 0,
                    page: 1,
                    sort: [],
                    desc: [],
                    noCommit: true
                })



            } catch (e) {
                item.loading = false
            }
            item.loading = false
            this.loading = false
        },
        fillData() {
            this.datacollection = {
                labels: [this.getRandomInt(), this.getRandomInt()],
                datasets: [{
                    label: 'Data One',
                    backgroundColor: '#f87979',
                    data: [this.getRandomInt(), this.getRandomInt()]
                }, {
                    label: 'Data One',
                    backgroundColor: '#f87979',
                    data: [this.getRandomInt(), this.getRandomInt()]
                }]
            }
        },
        getRandomInt() {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        },


        async chartSalesSix(item) {

            item.loading = true
            try {
                const results = await this.$store.dispatch('report/fetch', { name: 'ChartSalesSix', fields: `md, mth, total_amount`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })
                const fmt = 'MMM'

                item.data = {
                    options: {
                        dataLabels: {
                            enabled: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                        chart: {
                            id: 'vuechart-example'
                        },
                        xaxis: {
                            categories: results.data.data.map(r => this.$moment(r.md).format(fmt)),
                            labels: {
                                trim: false,
                            },

                        }
                    },

                    series: [{
                        name: 'Sale',
                        data: results.data.data.map(r => r.total_amount)
                    }],
                }
            } catch (e) {
                item.loading = false
            }
            item.loading = false

        },
        async topProduct(item) {

            item.loading = true
            try {
                const results = await this.$store.dispatch('report/fetch', { name: 'ChartTopProduct', fields: `product{id, name}, qty`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })
                const fmt = 'MMM'

                item.data = {
                    options: {
                        dataLabels: {
                            enabled: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                        chart: {
                            id: 'vuechart-example'
                        },
                        xaxis: {
                            categories: results.data.data.map(r => r.product.name),
                            labels: {
                                trim: false,
                            },

                        }
                    },
                    series: [{
                        name: 'Quantity',
                        data: results.data.data.map(r => r.qty)
                    }],
                }
            } catch (e) {
                item.loading = false
            }
            item.loading = false
        },
        async topService(item) {

            item.loading = true
            try {
                const results = await this.$store.dispatch('report/fetch', { name: 'ChartTopService', fields: `product{id, name}, qty`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })
                const fmt = 'MMM'

                item.data = {
                    options: {
                        dataLabels: {
                            enabled: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                        chart: {
                            id: 'vuechart-example'
                        },
                        xaxis: {
                            categories: results.data.data.map(r => r.product.name),
                            labels: {
                                trim: false,
                            },
                        }
                    },

                    series: [{
                        name: 'Quantity',
                        data: results.data.data.map(r => r.qty)
                    }],
                }
            } catch (e) {
                item.loading = false
            }
            item.loading = false
        },
        async topEmployee(item) {

            item.loading = true
            try {
                const results = await this.$store.dispatch('report/fetch', { name: 'ChartTopEmployee', fields: `md, item_name, total_amount`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })
                const fmt = 'MMM'

                item.data = {
                    options: {
                        dataLabels: {
                            enabled: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                        chart: {
                            id: 'vuechart-example'
                        },
                        xaxis: {
                            categories: results.data.data.map(r => r.md),
                            labels: {
                                trim: false,
                            },

                        }
                    },

                    series: [{
                        name: results.data.data[0].name,
                        data: results.data.data.map(r => r.total_amount)
                    }],
                }
            } catch (e) {
                item.loading = false
            }
            item.loading = false
        },
    },



}

</script>
<style>
/**
 * The default size is 600px×400px, for responsive charts
 * you may need to set percentage values as follows (also
 * don't forget to provide a size for the container).
 */

.wrapXLabel {
    word-break: break-all;
    word-wrap: break-word;
}

</style>
