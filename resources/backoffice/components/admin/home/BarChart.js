import { Bar, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
    extends: Bar,
    mixins: [reactiveProp],
    data: () => ({
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                labels: {
                    fontColor: 'white'
                }
            }
        }
    }),
    watch: {
        chartData(val) {

            this.chartData = val
            this.renderChart(val, this.options)
        }
    },
    mounted() {

        this.renderChart(this.chartData, this.options)
    }
}
/* data: () => ({
  chartdata: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label: 'Data One',
        backgroundColor: '#f87979',
        data: [40, 100, 50, 30]
      },
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
}), */
