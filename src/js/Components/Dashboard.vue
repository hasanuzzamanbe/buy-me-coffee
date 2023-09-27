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
    <div class="wpm_bmc_supporters">
      <h1 class="wpm_bmc_menu_title">Supporters:</h1>
      <el-table
          class="customers_table"
          :data="supporters"
          style="width: 100%">
        <el-table-column
            width="180"
            label="Date">
          <template #default="scope">
            <span>{{ scope.row.created_at }}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="supporters_name"
            width="180"
            label="Name">
          <template #default="scope">
            <a style="cursor:pointer;" @click="handleGet(scope.row.id)">{{ scope.row.supporters_name }}</a>
          </template>
        </el-table-column>
        <el-table-column
            label="Amount">
          <template #default="scope">
            <span class="wpm_supporters_amount" v-html="scope.row.amount_formatted"></span>
          </template>
        </el-table-column>
        <el-table-column
            prop="payment_status"
            label="Status">
          <template #default="scope">
            <span :class="'wpm_bmc_status wpm_bmc_status_' + scope.row.payment_status" v-html="scope.row.payment_status"></span>
          </template>
        </el-table-column>
        <el-table-column
            label="Method">
          <template #default="scope">
            <img width="48" class="wpm_bmc_paid_by_image" v-if="scope.row.payment_method" :src="PayPalImage">
            <span v-else :class="'wpm_bmc_payment_type wpm_bmc_payment_type_' + scope.row.payment_method" style="margin-left: 10px">{{ scope.row.payment_method ? scope.row.payment_method : '-' }}</span>
          </template>
        </el-table-column>
        <el-table-column
            label="Mode">
          <template #default="scope">
            <span :class="'wpm_bmc_payment_mode wpm_bmc_payment_mode_' + scope.row.payment_mode" style="margin-left: 10px">{{ scope.row.payment_mode ? scope.row.payment_mode : '-' }}</span>
          </template>
        </el-table-column>
        <el-table-column
            label="Operations">
          <template #default="scope">
            <el-button-group>
              <el-button
                  round
                  size="small"
                  icon="View"
                  @click="handleGet(scope.row.id)"></el-button>
              <el-popconfirm @confirm="handleDelete(scope.row.id)" title="Are you sure to delete this?">
                <template #reference>
                  <el-button
                      round
                      size="small"
                      type="danger"
                      icon="Delete">
                  </el-button>
                </template>
              </el-popconfirm>
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
      <br/>
<!--      <el-pagination-->
<!--          @current-change="handleSizeChange"-->
<!--          :page-size="posts_per_page"-->
<!--          background="background"-->
<!--          layout="size, prev, pager, next, total"-->
<!--          :total="total">-->
<!--      </el-pagination>-->
      <el-pagination
          @current-change="handleSizeChange"
          :page-size="posts_per_page"
          background="background"
          layout="size, prev, pager, next, total"
          :page-count="1"
          :total="total"
      />
    </div>
  </div>
</template>
<script>
import Report from "./Report.vue";
import {View,Setting, Help} from "@element-plus/icons-vue";
import Onboarding from './Onboarding.vue';

export default {
  name: 'Dashboard',
  components: {
    Report,
    View,
    Help,
    Setting
  },
  data() {
    return {
      limit: 20,
      guidedTour: true,
      fetching: false,
      posts_per_page: 10,
      current: 0,
      total: null,
      supporters: [],
      previewUrl : window.BuyMeCoffeeAdmin.preview_url,
      reportData: {
        total_supporters: this.total,
        total_coffee: 0,
        // total_amount: 0,
        currency_total: {}
      }
    }
  },
  computed: {
    PayPalImage() {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + 'PayPal.svg';
    }
  },
  methods: {
    setStore() {
         this.guidedTour = true;
        if (window.localStorage) {
          localStorage.setItem("wpm_bmc_guided_tour", false);
        }
    },
    handleSizeChange(val) {
      this.current = val-1;
      this.getSupporters();
    },
    getSupporters () {
      this.fetching = true;
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_supporters',
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
    handleGet(id) {
      this.$router.push({ name: 'Supporter', params: { id: id } })
    },
    handleDelete(id) {
      this.$post({
        action: 'wpm_bmc_admin_ajax',
        route: 'delete_supporter',
        id: id,
        nonce: window.BuyMeCoffeeAdmin.nonce
      }).then(() => {
        this.$handleSuccess('This record has been deleted.')
        this.getSupporters();
      }).catch((e) => {
        this.$handleError(e)
      })
    }
  },
  mounted() {
    this.getSupporters();
    if (window.localStorage) {
      this.guidedTour = !!window.localStorage.getItem('wpm_bmc_guided_tour');
    }
  }
}
</script>
<style scoped lang="scss">
.wpm_bmc_supporters {
  background: #f6fffc;
  padding: 24px;
  //margin-top: 24px;
  border-radius: 6px;
    tr, th {
      background: #ebfffea3 !important;
    }
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
</style>

