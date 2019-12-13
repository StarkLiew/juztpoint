<template>
    <v-container>
        <v-row>
            <v-col col="6" lg="6" md="6" sm="12" v-for="(item, i) in items" :key="i">
                <v-card min-width="300" class="mx-auto mx-2" color="white" max-width="600">
                    <v-card-title class="caption text-uppercase">
                        {{ item.title }}
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="retrieve(item)" :loading="item.loading">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-sheet color="white" height="350">
                        <v-chart  :options="polar" />
                        
                    </v-sheet>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import ECharts from 'vue-echarts'
import 'echarts/lib/chart/line'
import 'echarts/lib/component/polar'

export default {
    components: {
        'v-chart': ECharts,
    },
    data() {



        let data = []

        for (let i = 0; i <= 360; i++) {
            let t = i / 180 * Math.PI
            let r = Math.sin(2 * t) * Math.cos(2 * t)
            data.push([r, i])
        }

        return {
            polar: {
                title: {
                    text: 'Polar'
                },
                legend: {
                    data: ['line']
                },
                polar: {
                    center: ['50%', '54%']
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross'
                    }
                },
                angleAxis: {
                    type: 'value',
                    startAngle: 0
                },
                radiusAxis: {
                    min: 0
                },
                series: [{
                    coordinateSystem: 'polar',
                    name: 'line',
                    type: 'line',
                    showSymbol: false,
                    data: data
                }],
                animationDuration: 2000
            },
            items: [],
        }




    },
    computed: {

        chartStyles() {
            return {
                height: '350px',
                position: 'relative',
            }
        },

    },
    async mounted() {

        this.initialize()
        for (const item of this.items) {
            await this.retrieve(item)
        }
    },
    methods: {
        initialize() {
            this.items = [
                { title: 'Recent Sales', chart: 'bar', loading: false, datacollection: null },
                { title: 'Top Services', chart: '', loading: false, datacollection: null },
                { title: 'Top Products', chart: '', loading: false, datacollection: null },
                { title: 'Top Employee', chart: '', loading: false, datacollection: null },
            ]
        },
        async retrieve(item) {

            // this.loading = true
            // const { sortBy, sortDesc, page, itemsPerPage } = options
            item.loading = true
            try {
                const results = await this.$store.dispatch('report/fetch', { name: 'sales_six', fields: `md, mth, total_amount`, filter: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })
                item.datacollection = {
                    labels: results.data.data.map(r => r.mth),
                    datasets: [{
                        label: 'Sales',
                        backgroundColor: '#136ACD',
                        data: results.data.data.map(r => r.total_amount)
                    }]
                }


            } catch (e) {
                item.loading = false
            }
            item.loading = false
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
<style>
/**
 * The default size is 600px×400px, for responsive charts
 * you may need to set percentage values as follows (also
 * don't forget to provide a size for the container).
 */
.echarts {
  width: 100%;
  height: 100%;
}
</style>