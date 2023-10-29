<script>
import {Coffee, User, Money} from '@element-plus/icons-vue';
export default {
  name: 'Report',
  components: {
    Coffee,
    User,
    Money
  },
  data () {
    return {
      val: 'Hello from Report'
    }
  },
  props: {
    reportData: {
      type: Object,
      required: true
    }
  },
  methods: {
    getImage(path) {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + path;
    }
  }
}

</script>

<template>
  <div class="wpm_bmc_report_wrapper">
    <div class="wpm_bmc_report_inner">
      <div class="wpm_bmc_report_count" style="text-align:center;">
        <img :src="getImage('money.png')" alt="Money"/>
        <ul v-if="reportData.currency_total.length > 0" style="max-height: 120px;overflow-y: hidden;">
          Total Received
          <p v-if="reportData.currency_total.length === 1">{{reportData?.currency_total[0].currency}} : {{reportData?.currency_total[0].total_amount ? reportData?.currency_total[0].total_amount / 100 : 0}} </p>
          <li v-else v-for="group in reportData.currency_total">{{group.currency}} : {{group.total_amount ? group.total_amount / 100 : 0}}</li>
        </ul>
        <span v-else><br/>No amount received yet!</span>
      </div>
<!--      <div class="wpm_bmc_report_header">-->
<!--        <p>Amount Received</p>-->
<!--      </div>-->
    </div>
      <div class="wpm_bmc_report_inner">
        <div class="wpm_bmc_report_count">
          <img :src="getImage('supporters.png')" alt="supporters"/>
          <ul>
            <li>
              <span>Supporters</span>
            </li>
            <li>
              <p style="margin:0">{{ reportData.total_supporters }} </p>
            </li>
          </ul>
        </div>
<!--        <div class="wpm_bmc_report_header">-->
<!--          <p>Supporters</p>-->
<!--        </div>-->
      </div>
      <div class="wpm_bmc_report_inner">
        <div class="wpm_bmc_report_count">
          <img :src="getImage('coffee-cup.png')" alt="coffee-cup"/>
          <ul>
            <li>
              <span>Total Coffee</span>
            </li>
            <li>
              <p>{{reportData.total_coffee || 0}} </p>
            </li>
          </ul>
        </div>
<!--        <div class="wpm_bmc_report_header">-->
<!--          <p>Total Coffee</p>-->
<!--        </div>-->
      </div>
  </div>
</template>

<style scoped lang="scss">
.wpm_bmc_report_wrapper {
  background: linear-gradient(84deg, #f5fffe54, #affbf054);
  justify-content: space-between;
  flex-wrap: wrap;
  box-shadow: rgb(17 12 46 / 15%) 0px 8px 100px 0px;
  margin-bottom: 24px;
  border-radius: 7px;
  margin-top: 23px;
  min-height: 190px;
  display: flex;
  align-items: center;
}

.wpm_bmc_report_inner {
  width: 23%;
  min-width: 250px;
  &:last-child {
    border: none;
  }
}

.wpm_bmc_report_header {
  font-size: 16px;
  font-family: monospace;
  text-align: center;
}

.wpm_bmc_report_count p {
  font-size: 28px;
  text-align: center;
}
.wpm_bmc_report_count i {
  margin-right: 20px;
  color: #2a9e6f;
}
@media only screen and (min-width: 840px) {
  .wpm_bmc_report_inner {
    border-right: 1px solid #ccc;
  }
}
</style>