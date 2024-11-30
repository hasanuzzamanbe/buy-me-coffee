<template>
  <div v-loading="loading" class="buymecoffee_supporter_main_container">
    <h3 class="buymecoffee_title">
      <router-link class="text-blue-500" :to="{name: 'Supporters'}">Supporters / </router-link># {{$route.params.id}}
    </h3>
      <div class="max-w-7xl mx-auto p-6 flex flex-wrap">
        <!-- Left Sidebar -->
        <div class="w-1/3 pr-6 mb-4">
          <div class="rounded-lg shadow-md p-4" style="background: rgb(255 254 246);">
            <div class="flex items-center">
              <img :alt="supporter?.supporters_name" class="w-20 h-20 rounded-full" height="100" :src="supporter?.supporters_image" width="100"/>
              <div class="ml-4">
                <h2 class="text-xl font-semibold">
                  {{supporter?.supporters_name}}
                </h2>
                <a v-if="supporter?.supporters_email" :href="'mailto:' + supporter?.supporters_email" class="text-gray-500">
                  {{supporter?.supporters_email}}
                </a>
                <p>
                  {{supporter?.created_at}}
                </p>
              </div>
            </div>
            <div class="mt-4 flex space-x-2">
              <a v-if="supporter?.supporters_email" :href="'mailto:' + supporter?.supporters_email" class="bg-gray-200 text-black px-4 py-2 flex align-center rounded-md" style="align-items: center">
                <el-icon class="mr-2"><Message/></el-icon> Send email
              </a>
              <a target="_blank" v-if="supporter?.transaction?.transaction_url" :href="supporter?.transaction?.transaction_url" class="bg-blue-300 text-black px-4 py-2 flex align-center rounded-md cursor-pointer" style="align-items: center">
                <el-icon class="mr-2"><Link/></el-icon> View on {{supporter?.payment_method}}
              </a>
            </div>
            <div class="mt-4">
              <h3 class="text-lg font-semibold">
                Message
              </h3>
              <p class="text-gray-500">
                {{supporter?.supporters_message ?? 'No message'}}
              </p>
            </div>
            <div class="mt-4">

              <div class="mt-4">
                <h3 class="text-lg font-semibold">
                  Transaction <span :class="'buymecoffee_status buymecoffee_status_' + supporter.payment_status">
                    {{supporter.payment_status}} <span class="cursor-pointer" @click="statusModal = !statusModal"><el-icon><EditPen /></el-icon></span>
                  </span>
                </h3>
                <div class="">
                  <p class="text-md font-semibold">
                    Donated
                  </p>
                  <div class="wpm_supporter_items">
                    <div>
                      <Coffee />
                      <span>{{parseInt(supporter?.coffee_count)}}</span>
                    </div>
                    <div>
                      <Money />
                      <span>{{getFormatedAmount(supporter?.payment_total)}} {{supporter?.currency}}</span>
                    </div>
                  </div>
                </div>
                <div class="text-white rounded-lg p-6 relative" style="background-image: linear-gradient(22deg, rgb(196 144 255), rgb(30, 71, 65))">
                  <div class="flex justify-between items-center mb-4">
                    <div class="text-lg font-semibold">Card</div>
                    <div class="absolute top-3 right-4 bg-gradient-to-r from-green-300 to-blue-300 rounded-full p-2">
                      <span class="text-black font-semibold">{{supporter?.transaction?.card_brand}}</span>
                    </div>
                  </div>
                  <div class="text-sm mb-4">**** **** **** {{supporter?.transaction?.card_last_4}}</div>
                  <div class="flex justify-between items-center text-sm">
                    <div>{{supporter?.supporters_name ?? 'Anonymous'}}</div>
                    <div>{{supporter?.payment_method ?? '-'}}</div>
                  </div>
                </div>
                <p class="text-[12px] border rounded-md bg-amber-200 p-1 mt-2" v-if="supporter.payment_status === 'paid-initially'">
                  <el-icon><Warning/></el-icon>  Please verify this transaction, before mark paid!
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- Right Content -->
        <div class="w-2/3">
          <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-4 rounded-lg flex-1 text-center shadow-md">

              <div class="flex items-center justify-center">
                <span><Coffee style="width:20px"  class="mr-2"/></span>  <h3 class="text-2xl font-semibold" v-html="supporter?.all_time_total_coffee">
              </h3>
              </div>
              <p class="text-gray-500">
                All time total coffee
              </p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg flex-1 text-center ml-2 shadow-md">
              <h3 class="text-2xl font-semibold text-green-600" v-html="supporter?.all_time_total_paid">
              </h3>
              <p class="text-gray-500">
                All time total paid
              </p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg flex-1 text-center ml-2 shadow-md">
              <h3 class="text-2xl font-semibold text-yellow-600" v-html="supporter?.all_time_total_pending">
              </h3>
              <p class="text-gray-500">
                All time total pending
              </p>
            </div>
          </div>

          <el-table class="w-full bg-white rounded-lg shadow-md" :data="supporter?.other_donations">
            <el-table-column label="ID" width="100">
                <template #default="scope">
                  <a class="text-blue-700 cursor-pointer" @click="handleGet(scope.row.id)">{{ scope.row.id }}</a>
                </template>
            </el-table-column>
            <el-table-column label="Name">
              <template #default="scope">
                {{scope?.row?.supporters_name}} <br/><span class="text-[10px]">{{scope?.row?.created_at}}</span>
              </template>
            </el-table-column>
            <el-table-column label="Donated For" prop="reference">
            </el-table-column>
            <el-table-column label="Amount">
              <template #default="scope">
                {{getFormatedAmount(scope?.row?.payment_total)}} {{scope?.row?.currency}}
              </template>

            </el-table-column>
            <el-table-column label="Payment Status" prop="payment_status">
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>

  <el-dialog v-model="statusModal" width="400px">
    Change Payment Status
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
  </el-dialog>
</template>
<script>
import {Coffee, User, Money, EditPen, Warning, Message, Link} from '@element-plus/icons-vue';
import { ElMessage, ElMessageBox } from 'element-plus'
export default {
    name: 'Supporter',
    data () {
        return {
            supporter: {},
            loading: false,
            paymentStatus: '',
            statusModal: false,
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
      Message,
      Warning,
      ElMessageBox,
      Coffee,
      User,
      Money,
      EditPen,
      Link
    },
  methods: {
      handleGet(id) {
        this.$router.push({ name: 'Supporter', params: { id: id } }).then(()=>{
          this.getSupporter()
        })
      },
      getFormatedAmount(amount) {
        return parseInt(amount / 100);
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
                  action: 'buymecoffee_admin_ajax',
                  route: 'update_payment_status',
                  data: {
                    id: this.$route.params.id,
                    status: this.paymentStatus,
                  },
                  buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
                }
            ).then((response) => {
              this.statusModal = false;
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
          action: 'buymecoffee_admin_ajax',
          route: 'get_supporter',
          data: {
            id: this.$route.params.id,
          },
          buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
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
  color: #2ad8ff;
  font-size:16px;
}
.wpm_supporter_payment_actions input:focus, .wpm_supporter_payment_actions input:active {
  border: none !important;
  box-shadow: none !important;
}

.wpm_supporter_payment_actions .el-input__wrapper {
  //border: 1px solid #14a3b7;
  width: 133px;
}
.buymecoffee_supporter_main_container {
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
    border: 1px dotted #055b28;
    padding: 0px 20px;
    border-radius: 6px;
    margin-right: 6px;
    margin-bottom: 12px;
    display: flex;
    color: #055b28;
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