<template>
  <div>
    <el-table
        class="customers_table"
        :data="supporters"
        >
      <el-table-column v-if="!hide_columns.includes('id')"
          width="80"
          label="Id">
        <template #default="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('date')"
          width="180"
          label="Date">
        <template #default="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('name')"
          prop="supporters_name"
          width="200"
          label="Name">
        <template #default="scope">
          <a style="cursor:pointer;" @click="handleGet(scope.row.id)">{{ scope.row.supporters_name }}</a>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('amount')"
          label="Amount">
        <template #default="scope">
          <span class="wpm_supporters_amount" v-html="scope.row.amount_formatted"></span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('status')"
          prop="payment_status"
          label="Status">
        <template #default="scope">
          <span :class="'wpm_bmc_status wpm_bmc_status_' + scope.row.payment_status" v-html="scope.row.payment_status"></span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('method')"
          label="Method">
        <template #default="scope">
          <img width="48" class="wpm_bmc_paid_by_image" v-if="maybeGetMethodImage(scope.row.payment_method)" :src="maybeGetMethodImage(scope.row.payment_method)">
          <span v-else :class="'wpm_bmc_payment_type wpm_bmc_payment_type_' + scope.row.payment_method" style="margin-left: 10px">{{ scope.row.payment_method ? scope.row.payment_method : '-' }}</span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('mode')"
          label="Mode">
        <template #default="scope">
          <span :class="'wpm_bmc_payment_mode wpm_bmc_payment_mode_' + scope.row.payment_mode" style="margin-left: 10px">{{ scope.row.payment_mode ? scope.row.payment_mode : '-' }}</span>
        </template>
      </el-table-column>
      <el-table-column
          v-if="!hide_columns.includes('operations')"
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
    <el-pagination
        v-if="hide_pagination !== 'yes'"
        @current-change="handleSizeChange"
        :page-size="posts_per_page"
        background="background"
        layout="size, prev, pager, next, total"
        :page-count="Math.ceil(total / posts_per_page)"
        :total="total"
    />
  </div>
</template>
<script>
export default {
  name: "Supporters",
  data() {
    return  {
      currentPage : this.current
    }
  },
  props: {
    supporters: {
      type: Array,
      required: true
    },
    posts_per_page: {
      type: Number,
    },
    total: {
      type: Number || String,
    },
    current: {
      type: Number || String,
    },
    hide_pagination: {
      type: String,
      default: 'no'
    },
    hide_columns: {
      type: Array,
      default: []
    }
  },
  methods: {
    handleSizeChange(val) {
      this.currentPage = val-1;
      this.$emit('pageChanged', this.currentPage);
    },
    handleGet(id) {
      this.$router.push({ name: 'Supporter', params: { id: id } })
    },
    handleDelete(id) {
      this.$post({
        action: 'wpm_bmc_admin_ajax',
        route: 'delete_supporter',
        data: {
          id: id,
        },
        nonce: window.BuyMeCoffeeAdmin.nonce
      }).then(() => {
        this.$handleSuccess('This record has been deleted.')
        this.$emit('fetchSupporters');
      }).catch((e) => {
        this.$handleError(e)
      })
    },
    maybeGetMethodImage(method) {
      if (method === 'paypal') {
        return window.BuyMeCoffeeAdmin.assets_url + 'images/' + 'PayPal.svg';
      } else if (method === 'stripe') {
        return window.BuyMeCoffeeAdmin.assets_url + 'images/' + 'stripe.svg';
      } else {
        return false;
      }
    },
  }
}
</script>

<style scoped lang="scss">

</style>