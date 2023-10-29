<template>
  <div class="wpm_bmc_main_container">
    <report :reportData='reportData'/>
    <div class="bmc_coffee_preview">
        <el-tooltip
            effect="light"
            content="Quick guided setup"
            placement="top"
        >
          <a style="cursor:pointer;" @click="$router.push('quick-setup')">
            <el-icon><Help /></el-icon>Setup |
          </a>
        </el-tooltip>
      <a :href="previewUrl" target="_blank"><el-icon style="margin-right:4px;">
        <View/></el-icon> Preview</a>
    </div>
    <div class="quick_setup_tour" v-if="!supporters.length && !guidedTour && !fetching">
      <p @click="setStore" style="float:right">x close </p>
      <div>
        <el-icon><Setting /></el-icon>
      </div>
      <div @click="$router.push('quick-setup')">
        Start collecting your donations with Buy me coffee!
        <br/>
        Start a Quick setup tour.
      </div>

    </div>
    <div class="wpm_bmc_dashboard_2nd_row">
      <div class="wpm_bmc_supporters">
        <h1 class="wpm_bmc_menu_graph_title">Supporters Leaderboard</h1>
        <supporters-table
            @pageChanged="(val)=>{current = val; getSupporters()}"
            :supporters="supporters"
            hide_pagination="yes"
            :hide_columns="['operations', 'id', 'mode', 'date']"
        />
      </div>
      <div class="wpm_bmc_supporters_map">
        <h1 class="wpm_bmc_menu_graph_title" style="padding:23px;">Recent Revenue graph in {{top_paid_currency}}
          <span style="color:#ff9800; font-weight: 400;" v-if="dummyChart">(Dummy chart)</span></h1>
        <div style="height: 100%">
          <div v-if="dummyChart" style="text-align: center; color:#e38110">
            NB: No actual data found! Once you receive some donations, this chart will be updated.
          </div>
          <ChartRenderer v-if="renderChart" :chartProps="totalRevenue" :chartOptions="overviewOptions"></ChartRenderer>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import Report from "./Report.vue";
import {View,Setting, Help} from "@element-plus/icons-vue";
import SupportersTable from "./SupportersTable.vue";
import ChartRenderer from "./Parts/ChartRenderer.vue";

export default {
  name: 'Dashboard',
  components: {
    Report,
    View,
    Help,
    Setting,
    SupportersTable,
    ChartRenderer
  },
  data() {
    return {
      limit: 20,
      guidedTour: true,
      fetching: false,
      posts_per_page: 10,
      current: 0,
      total: 0,
      supporters: [],
      renderChart: false,
      dummyChart: true,
      top_paid_currency: 'USD',
      previewUrl : window.BuyMeCoffeeAdmin.preview_url,
      reportData: {
        total_supporters: this.total,
        total_coffee: 0,
        currency_total: {}
      },
      totalRevenue: {
        id: 'revenue_chart',
        type: 'line',
        height: '460',
        title: 'Total Revenue',
        color: 'rgba(111,194,255,0.51)',
        backgroundColor: 'rgba(24,220,244,0.32)',
        data: [
            20,
            18,
            20,
            20,
            25
        ],
        label: [
            'January',
            'February',
            'March',
            'April',
            'May'
        ]
      },
      overviewOptions: {
        elements: {
          line: {
            tension: 0.3
          },
          point:{
            radius: 5
          },
        },
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: (context) => {
                return context.formattedValue +' '+ this.top_paid_currency;
              }
            }
          }
        }
      },
    }
  },
  computed: {

  },
  methods: {
    setStore() {
         this.guidedTour = true;
        if (window.localStorage) {
          localStorage.setItem("wpm_bmc_guided_tour", false);
        }
    },
    getSupporters () {
      this.fetching = true;
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_supporters',
        filter_top: 'yes',
        limit: this.limit,
        page: this.current,
        posts_per_page: this.posts_per_page,
        nonce: window.BuyMeCoffeeAdmin.nonce
      })
          .then((response) => {
            this.supporters = response.data.supporters;
            this.total = response.data.total;
            this.reportData = response.data.reports;
            this.fetching = false;
          })
          .fail(error => {
            this.$message.error(error.responseJSON.data.message);
          })
          .always(() => {
            this.fetching = false;
          });

    },
    getWeeklyRevenue() {
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_weekly_revenue',
        nonce: window.BuyMeCoffeeAdmin.nonce
      }).then((response) => {
        this.top_paid_currency = response?.data?.top_paid_currency || 'USD';
        let graphData = response?.data?.chartData[this.top_paid_currency];
        if (graphData) {
          this.totalRevenue.data = graphData.data;
          this.totalRevenue.label = graphData.label;
          this.dummyChart = false;
        }
        this.renderChart = true;
      }).catch((e) => {
        this.$handleError(e)
      })
    }
  },
  mounted() {
    this.getSupporters();
    this.getWeeklyRevenue();
    if (window.localStorage) {
      this.guidedTour = !!window.localStorage.getItem('wpm_bmc_guided_tour');
    }
  }
}
</script>
<style scoped lang="scss">
.wpm_bmc_supporters {
  overflow: auto;
  background: #f6fffc;
  padding: 24px;
  width: 40%;
  box-sizing: border-box;
  border-radius: 6px;
    tr, th {
      background: #ebfffea3 !important;
    }
}
.wpm_bmc_supporters_map {
  box-sizing: border-box;
  border-radius: 6px;
  background: #f6fffc;
  width: 60%;
}

.bmc_coffee_preview {
  display: flex;
  z-index: 999;
  position: fixed;
  bottom: 0;
  right: 0;
  padding: 4px 15px;
  background: white;
  font-size: 16px;
  font-family: monospace;
  box-shadow: -6px -3px 9px 4px #ccc;
  border-top-left-radius: 5px;
  border-right: 6px solid #4f94d4;
}

.bmc_coffee_preview a {
  display: flex;
  svg{
    width: 20px;
  }
  text-decoration: none;
  color: #72aee6;
}

.bmc_coffee_preview a:hover{
  text-decoration: none;
  color: #07958b;
}
.wpm_bmc_paid_by_image {
  opacity: 0.8;
}
.wpm_supporters_amount{
  color: #009697;
  font-family: monospace;
  font-weight: bold;
}
.wpm_bmc_dashboard_2nd_row {
  box-sizing: border-box;
  display: flex;
  gap: 20px;
  justify-content: flex-start;
}
@media (max-width: 1200px) {
  .wpm_bmc_dashboard_2nd_row {
    flex-direction: column;
  }
  .wpm_bmc_supporters {
    width: 100%;
    box-sizing: border-box;
  }
  .wpm_bmc_supporters_map {
    width: 100%;
    box-sizing: border-box;

  }
}
</style>

