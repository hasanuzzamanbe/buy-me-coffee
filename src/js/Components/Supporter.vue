<template>
  <div v-loading="loading" class="wpm_bmc_supporter_main_container">
    <h3 class="wpm_bmc_title">
      <router-link style="text-decoration: none;" :to="{name: 'Dashboard'}">Supporters / </router-link>{{$route.params.id}}
    </h3>
    <div class="wpm_supporter_profile_wrapper">
        <div class="wpm_supporter_profile_section">
            <img v-if="supporter?.supporters_image" :src="supporter?.supporters_image" alt="Supporter's Image" class="wpm_supporter_profile_image">
        </div>
        <div>
          <h3 v-if="supporter?.supporters_name" class="wpm_supporter_profile_name">{{supporter?.supporters_name}}</h3>
          <h3 v-else class="wpm_supporter_profile_name">Anonymous</h3>

          <p v-if="supporter?.supporters_email" class="wpm_supporter_profile_email"><a :href="'mailto:' + supporter?.supporters_email" id="supporters_email">{{supporter?.supporters_email}}</a></p>
          <p v-else class="wpm_supporter_profile_email"><p id="supporters_email">No Email</p></p>

          <p class="wpm_supporter_message">{{supporter?.supporters_message}}</p>
        </div>
    </div>
    <div class="wpm_supporter_payment_line"></div>
    <div class="wpm_supporter_payment_wrapper">
      <div>
        <div class="wpm_supporter_items">
          <div>
            <Coffee />
            <span>{{parseInt(supporter?.coffee_count)}}</span>
          </div>
          <div>
            <Money />
            <span>{{parseInt(supporter?.payment_total / 100)}} {{supporter?.currency}}</span>
          </div>
        </div>
        <table class="wpm_supporter_payments">
          <thead>
          </thead>
          <tbody>
          <tr>
            <td>Payment Method</td>
            <td>
              <img v-if="supporter.payment_method === 'paypal'" width="64" :src="getImage('PayPal.svg')" />
              <span v-else>{{supporter.payment_method}}</span>
            </td>
          </tr>
          <tr>
            <td>Payment Status</td><td><span :class="'wpm_bmc_status wpm_bmc_status_' + supporter.payment_status">
            {{supporter.payment_status}}
            <a :href="getTransactionUrl()" v-if="supporter.payment_status === 'paid-initially'">Needs to verify</a>
            </span></td>
          </tr>
          <tr v-if="supporter.payment_mode">
            <td>Payment Mode</td><td>{{supporter.payment_mode}}</td>
          </tr>
          <tr>
            <td style="text-transform:capitalize;">Coffee For</td><td>{{supporter.reference}}</td>
          </tr>
          <tr v-if="supporter.supporters_message">
            <td style="text-transform:capitalize;">Message</td><td>{{supporter.supporters_message}}</td>
          </tr>
          <tr>
            <td>Submission Hash:</td>
            <td style="font-family:monospace;">{{supporter.entry_hash}}</td>
          </tr>
          <tr>
            <td>Transaction URL:</td>
            <td style="font-family:monospace;">
              <a target="_blank" :href="getTransactionUrl()">{{supporter?.transaction?.charge_id}}</a>
            </td>
          </tr>
          <tr>
            <td style="font-family:monospace;">{{supporter.created_at}}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div>
        <div class="wpm_supporter_payment_actions">
          <el-select v-model="paymentStatus" @change="updateStatus">
            <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </div>
      </div>
    </div>
    </div>
</template>
<script>
import {Coffee, User, Money} from '@element-plus/icons-vue';
import { ElMessage, ElMessageBox } from 'element-plus'
export default {
    name: 'Supporter',
    data () {
        return {
            supporter: {},
            loading: false,
            paymentStatus: '',
            val: 'Hello from Supports',
            options: [
              { value: 'pending', label: 'Pending' },
              { value: 'paid', label: 'Paid' },
              { value: 'refunded', label: 'Refunded' },
              { value: 'cancelled', label: 'Cancelled'},
              { value: 'failed', label: 'Failed'},
              { value: 'paid-initially', label: 'Paid Initially'}
            ]
        }
    },
    components: {
      ElMessageBox,
      Coffee,
      User,
      Money
    },
  methods: {
      getTransactionUrl() {
        if (this.supporter.payment_method === 'paypal') {
          if (this.supporter.payment_mode !== 'live') {
            return 'https://www.sandbox.paypal.com/activity/payment/' + this.supporter?.transaction?.charge_id;
          }
          return 'https://www.paypal.com/activity/payment/' + this.supporter?.transaction?.charge_id;
        }
        return 'https://dashboard.stripe.com/test/payments/' + this.supporter?.transaction?.charge_id;
      },
    getImage(path) {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + path;
    },
    updateStatus() {
      ElMessageBox.confirm(
          'Are you sure to change payment status?',
          'Warning',
          {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
          }
      )
          .then(() => {
            this.$post(
                {
                  action: 'wpm_bmc_admin_ajax',
                  route: 'update_payment_status',
                  id: this.$route.params.id,
                  status: this.paymentStatus,
                  nonce: window.BuyMeCoffeeAdmin.nonce
                }
            ).then((response) => {
              this.getSupporter();
              this.$handleSuccess('Updated Successfully');
            })
          })
          .catch(() => {
            ElMessage({
              type: 'info',
              message: 'Update canceled',
            })
          })
    },
      getSupporter() {
        this.loading = true
        this.$get({
          action: 'wpm_bmc_admin_ajax',
          route: 'get_supporter',
          id: this.$route.params.id,
          nonce: window.BuyMeCoffeeAdmin.nonce
        }).then((response) => {
          this.supporter = response.data
          this.paymentStatus = response.data.payment_status
          this.loading = false
        }).catch((e) => {
          this.loading = false
          this.$handleError(e)
        })
      }
  },
  mounted() {
        this.getSupporter()
    }
}

</script>
<style lang="scss">
.wpm_supporter_payment_wrapper {
  display: flex;
  justify-content: center;
}

.wpm_supporter_payment_actions input {
  background: #eefaf9;
  border: none !important;
  height: 38px;
  color: #ff932a;
  font-size:16px;
}
.wpm_supporter_payment_actions input:focus, .wpm_supporter_payment_actions input:active {
  border: none !important;
  box-shadow: none !important;
}

.wpm_supporter_payment_actions .el-input__wrapper {
  border: 1px solid #ff932a;
  width: 133px;
}
.wpm_bmc_supporter_main_container {
  background: #ebfffea3;
  box-shadow: rgb(17 12 46 / 15%) 0px 48px 100px 0px;
  padding: 32px;
  border-radius: 6px;
  .wpm_supporter_items svg {
    width: 20px;
    margin-right: 10px;
  }

  .wpm_supporter_profile_section img, .wpm_supporter_profile_section svg{
    width: 124px;
    height: 124px;
    border: 3px solid #b5b5b5;
    border-radius: 50%;
    background: #fff;
  }
  .wpm_supporter_items {
    display: flex;
    flex-wrap: wrap;
  }

  .wpm_supporter_items div {
    font-size: 20px;
    border: 1px solid #0c993a;
    padding: 9px 16px;
    border-radius: 6px;
    margin-right: 6px;
    margin-bottom: 12px;
    display: flex;
    color: #0c993a;
  }
  .wpm_supporter_profile_wrapper {
    max-width: 350px;
    margin: 0 auto;
  }
  .wpm_supporter_payment_line {
    border-top: 2px dotted #c8c8c8;
    padding-top: 32px;
    max-width: 800px;
    margin:0 auto;
  }

}
</style>