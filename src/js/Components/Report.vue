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
  }
}

</script>

<template>
  <div class="wpm_bmc_report_wrapper">
    <div class="wpm_bmc_report_inner">
      <div class="wpm_bmc_report_header">
        <p>Received Amount Based on currency</p>
      </div>
      <div class="wpm_bmc_report_count" style="text-align:center;">
        <el-icon style="font-size: 35px;"><Money /></el-icon>
        <ul v-if="reportData.currency_total.length > 0" style="max-height: 120px;overflow-y: auto;"
            v-for="group in reportData.currency_total">
          <li>{{group.currency}} : {{group.total_amount ? group.total_amount / 100 : 0}}</li>
        </ul>
        <span v-else><br/>No amount received yet!</span>
      </div>
    </div>
      <div class="wpm_bmc_report_inner">
        <div class="wpm_bmc_report_header">
          <p>Supporters</p>
        </div>
        <div class="wpm_bmc_report_count">
          <p><el-icon><User /></el-icon>{{ reportData.total_supporters }} </p>
        </div>
      </div>
      <div class="wpm_bmc_report_inner">
        <div class="wpm_bmc_report_header">
          <p>Total Coffee</p>
        </div>
        <div class="wpm_bmc_report_count">
          <p><el-icon><Coffee /></el-icon>{{reportData.total_coffee}} </p>
        </div>
      </div>
  </div>
</template>

<style scoped lang="scss">
.wpm_bmc_report_wrapper {
  background: #fffcf2;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  box-shadow: rgb(17 12 46 / 15%) 0px 48px 100px 0px;
  margin-bottom: 24px;
  border-radius: 7px;
  margin-top: 23px;
}

.wpm_bmc_report_inner {
  width: 23%;
  min-width: 250px;
  border-right: 1px solid #ccc;
}

.wpm_bmc_report_header {
  font-size: 16px;
  font-family: monospace;
  text-align: center;
}

.wpm_bmc_report_count p {
  font-size: 35px;
  text-align: center;
}
.wpm_bmc_report_count i {
  margin-right: 20px;
  color: #e4c710;
}
</style>