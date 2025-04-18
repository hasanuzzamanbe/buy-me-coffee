<template>
  <div class="buymecoffee_main_container">
    <report :reportData='reportData'/>
    <div class="bmc_coffee_preview">
        <el-tooltip
            effect="light"
            content="Quick guided setup"
            placement="top"
        >
          <a class="cursor-pointer" @click="$router.push('quick-setup')">
            <el-icon><Help /></el-icon><p class="ml-1">Setup</p> <p class="text-gray-400 ml-2 mr-2">|</p>
          </a>
        </el-tooltip>
        <a :href="previewUrl" target="_blank"><el-icon>
        <View/></el-icon><p class="ml-1">Preview</p></a>
    </div>
    <div class="quick_setup_tour" v-if="!supporters.length && !guidedTour && !fetching">
      <p class="float-right" @click="setStore">x close </p>
      <div>
        <el-icon><Setting /></el-icon>
      </div>
      <div @click="$router.push('quick-setup')">
        Start collecting your donations with Buy me coffee!
        <br/>
        Start a Quick setup tour.
      </div>

    </div>
    <div class="buymecoffee_dashboard_2nd_row">
      <div class="buymecoffee_supporters">
        <h1 class="buymecoffee_menu_graph_title">Supporters Leaderboard</h1>
        <supporters-table
            @pageChanged="(val)=>{current = val; getSupporters()}"
            :supporters="supporters"
            hide_pagination="yes"
            :hide_columns="['operations', 'id', 'mode', 'date']"
        />
      </div>
      <div class="buymecoffee_supporters_map" style="padding:23px;">
        <h1 class="buymecoffee_menu_graph_title">Recent Revenue graph in {{top_paid_currency}}
          <span style="color:#ff9800; font-weight: 400;" v-if="dummyChart">(Dummy chart)</span></h1>
        <div style="height: 100%;">
          <div v-if="dummyChart" style="text-align: center; color:#e38110">
            NB: No actual data found! Once you receive some donations, this chart will be updated.
          </div>
          <ChartRenderer v-if="renderChart" :chartProps="totalRevenue" :chartOptions="overviewOptions" height="400"></ChartRenderer>
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
      fetching: true,
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
        currency_total: [],
        currency_total_pending: [],
      },
      totalRevenue: {
        id: 'revenue_chart',
        type: 'line',
        height: '200',
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
    getImage(path) {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + path;
    },
    setStore() {
         this.guidedTour = true;
        if (window.localStorage) {
          localStorage.setItem("buymecoffee_guided_tour", false);
        }
    },
    getSupporters () {
      this.fetching = true;
      this.$get({
        action: 'buymecoffee_admin_ajax',
        route: 'get_supporters',
        data: {
          filter_top: 'yes',
          limit: this.limit,
          page: this.current,
          posts_per_page: this.posts_per_page,
        },
        buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
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
      this.fetching = true;
      this.$get({
        action: 'buymecoffee_admin_ajax',
        route: 'get_weekly_revenue',
        buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
      }).then((response) => {
        this.top_paid_currency = response?.data?.top_paid_currency || 'USD';
        let graphData = response?.data?.chartData[this.top_paid_currency];
        if (graphData) {
          this.totalRevenue.data = graphData.data;
          this.totalRevenue.label = graphData.label;
          this.dummyChart = false;
        }
        this.renderChart = true;
        this.fetching = false;
      }).catch((e) => {
        this.fetching = false;
        this.$handleError(e)
      })
    }
  },
  mounted() {
    this.getSupporters();
    this.getWeeklyRevenue();
    if (window.localStorage) {
      this.guidedTour = !!window.localStorage.getItem('buymecoffee_guided_tour');
    }
  }
}
</script>
<style scoped lang="scss">
.buymecoffee_supporters {
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
.buymecoffee_supporters_map {
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
  align-items: center;
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
.buymecoffee_paid_by_image {
  opacity: 0.8;
}
.wpm_supporters_amount{
  color: #009697;
  font-family: monospace;
  font-weight: bold;
}
.buymecoffee_dashboard_2nd_row {
  box-sizing: border-box;
  display: flex;
  gap: 20px;
  justify-content: flex-start;
}
@media (max-width: 1200px) {
  .buymecoffee_dashboard_2nd_row {
    flex-direction: column;
  }
  .buymecoffee_supporters {
    width: 100%;
    box-sizing: border-box;
  }
  .buymecoffee_supporters_map {
    width: 100%;
    box-sizing: border-box;

  }
}
</style>

