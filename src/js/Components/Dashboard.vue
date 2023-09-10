<template>
  <div class="wpm_bmc_main_container">
    <Report :reportData='reportData'/>
    <div class="bmc_coffee_preview">
      <a :href="previewUrl" target="_blank"><el-icon style="margin-right:4px;">
        <View/></el-icon> Preview Your Page</a>
    </div>
    <div class="wpm_bmc_supporters">
      <h1 class="wpm_bmc_menu_title">Supporters:</h1>
      <el-table
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
            <span>{{ scope.row.supporters_name }}</span>
          </template>
        </el-table-column>
        <el-table-column
            label="Amount">
          <template #default="scope">
            <span v-html="scope.row.amount_formatted"></span>
          </template>
        </el-table-column>
        <el-table-column
            prop="payment_status"
            label="Status">
        </el-table-column>
        <el-table-column
            prop="payment_method"
            label="Payment By">
        </el-table-column>
        <el-table-column
            label="Mode">
          <template #default="scope">
            <span style="margin-left: 10px">{{ scope.row.payment_mode ? scope.row.payment_mode : '-' }}</span>
          </template>
        </el-table-column>
        <el-table-column
            label="Operations">
          <template #default="scope">
            <el-button-group>
              <el-button
                  size="small"
                  icon="View"
                  @click="handleGet(scope.row.id)"></el-button>
              <el-popconfirm @confirm="handleDelete(scope.row.id)" title="Are you sure to delete this?">
                <template #reference>
                  <el-button
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
      <el-pagination
          @current-change="handleSizeChange"
          :page-size="posts_per_page"
          background
          layout="prev, pager, next"
          :total="total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
import Report from "./Report";
import {View} from "@element-plus/icons-vue";
export default {
  name: 'Dashboard',
  components: {
    Report,
    View
  },
  data() {
    return {
      limit: 20,
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
  methods: {
    handleSizeChange(val) {
      this.current = val-1;
      this.getSupporters();
    },
    getSupporters () {
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_supporters',
        limit: this.limit,
        page: this.current,
        posts_per_page: this.posts_per_page,
      })
          .then((response) => {
            this.supporters = response.data.supporters;
            this.total = response.data.total;
            this.reportData = response.data.reports;
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
        id: id
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
  }
}
</script>
<style scoped lang="scss">
.wpm_bmc_supporters {
  background: #ebfffea3;
  padding: 24px;
  //margin-top: 24px;
  border-radius: 6px;
    tr, th {
      background: #ebfffea3 !important;
    }
}
.bmc_coffee_preview {
  z-index: 999;
  position: fixed;
  bottom: 0;
  right: 0;
  padding: 10px 32px;
  background: white;
  font-size: 16px;
  font-family: monospace;
  box-shadow: -6px -3px 9px 4px #ccc;
  border-top-left-radius: 8px;
  border-right: 6px solid #4f94d4;
}

.bmc_coffee_preview a {
  text-decoration: none;
  color: #72aee6;
}

.bmc_coffee_preview a:hover{
  text-decoration: none;
  color: #ff8b46;
}
</style>

