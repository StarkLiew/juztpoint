<template>
    <v-container>
        <v-row>
            <v-col col="6" lg="6" md="6" sm="12" v-for="(item, i) in items" :key="i">
                <v-card min-width="300" class="mx-auto mx-2" color="grey lighten-4" max-width="600">
                    <v-card-title class="caption text-uppercase">
                        {{ item.title }}
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="retrieve()">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-sheet color="transparent">
                        <bar-chart :chart-data="datacollection" v-if="item.chart === 'bar' && !loading"></bar-chart>
                        <line-chart :chart-data="datacollection" v-if="item.chart === 'line' && !loading"></line-chart>
                    </v-sheet>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import { Bar } from 'vue-chartjs'
import LineChart from './LineChart.js'
import BarChart from './BarChart.js'

export default {
    components: {
        LineChart,
        BarChart
    },
    data() {
        return {
            datacollection: null,
            checking: false,
            loading: true,
            heartbeats: [],
            items: [],
        }
    },
    computed: {

    },
    async mounted() {
        this.retrieve()
        this.initialize()
    },
    methods: {
        initialize() {
            this.items = [
                { title: 'Recent Sales', chart: 'bar' },
                { title: 'Top Services', chart: '' },
                { title: 'Top Products', chart: '' },
                { title: 'Top Employee', chart: '' },
            ]
        },
        async retrieve(filter, options, noCommit = false) {

            // this.loading = true
            // const { sortBy, sortDesc, page, itemsPerPage } = options
            this.loading = true
            const results = await this.$store.dispatch('report/fetch', { name: 'sales_six', fields: `year, month, total_amount`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })



            this.datacollection = {
                labels: results.data.data.map(r => r.month),
                datasets: [{
                    label: 'Sales',
                    backgroundColor: '#f87979',
                    data: results.data.data.map(r => r.total_amount)
                }]
            }
    
            this.loading = false

            //const mapped = results.reports.data.data.map(data => total_amount)
            //console.log(mapped)
            // return mapped
            //this.loading = false


            // if (noCommit) return results
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
        }

    }
}

</script>
