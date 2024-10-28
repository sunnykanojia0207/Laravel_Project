<template>
  <div>
  </div>
  <div>
    <highcharts :options="chartOptions"></highcharts>
  </div>
</template>

<script>
import { Chart } from 'highcharts-vue';
export default {
  components: {
    highcharts: Chart
  },
  data() {
    return {
      chartOptions: {
        // series: [{
        //   data: [1, 2, 3] // sample data
        // }]

        chart: {
          type: 'pie'
        },
        title: {
          text: 'Users Joining Date'
        },
        series: [{
          data: [{
            name: '16/04/24',
            y: 5
          }, {
            name: '15/04/24',
            y: 10
          }, {
            name: '14/04/24',
            y: 20
          }]
        }]



      }
    }
  }
}
</script>