<template>
  <div>
    <canvas :id="chartId" :width="chartWidth" :height="chartHeight"></canvas>
  </div>
</template>


<script>
import Chart from 'chart.js/auto';
export default {
  name: 'CardChart',
  props: {
    chartProps: {
      type: Object,
      required: true
    },
    chartOptions: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      chartId: 'wpm_bmc_overview_chart_' + this.chartProps.id,
      chartHeight: this.chartProps.height ? this.chartProps.height : 'auto',
      chartWidth: this.chartProps.width ? this.chartProps.width : 'auto',
    }
  },
  methods: {
    generateChart() {
      let options = {
        type: this.chartProps.type ? this.chartProps.type : 'line',
        data: {
          labels: this.chartProps.label,
          datasets: [
            {
              bezierCurve: false,
              label: '',
              data: this.chartProps.data,
              borderWidth: 1,
              borderColor: this.chartProps.color,
              backgroundColor:this.chartProps.backgroundColor,
              fill: true,
            }
          ]
        },
        options: this.chartOptions
      }

      let ctx = document.getElementById(this.chartId).getContext('2d');
      window[this.chartProps.id] = new Chart(ctx, options);
    }
  },
  mounted() {
    this.generateChart();
  }
}
</script>
