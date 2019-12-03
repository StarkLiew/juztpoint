import { Bar, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
    extends: Bar,
    mixins: [reactiveProp],
    props: ['options'],
    mounted() {
        this.renderChart(this.chartdata, this.options)
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
