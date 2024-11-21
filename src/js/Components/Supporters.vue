<template>
  <div class="buymecoffee_main_container">
    <div class="buymecoffee_supporters">
      <el-row>
        <el-col :span="12">
          <h1 class="buymecoffee_menu_title">Supporters</h1>
        </el-col>
        <el-col :span="12">
        </el-col>
      </el-row>
    <SupportersTable
        @pageChanged="(val)=>{current = val; getSupporters()}"
        @sizeChanged="(val) => {posts_per_page = val; getSupporters()}"
        @fetchSupporters="()=>getSupporters()"
        :supporters="supporters"
        :posts_per_page="posts_per_page"
        :current="current"
        :total="total"/>
    </div>
  </div>
</template>
<script>
import SupportersTable from "./SupportersTable.vue";
export default {
  name: "Supporters",
  data() {
    return {
      current: 0,
      total: 0,
      posts_per_page: 10,
      supporters: []
    }
  },
  components: {
    SupportersTable
  },
  methods: {
    getSupporters () {
      this.fetching = true;
      this.$get({
        action: 'buymecoffee_admin_ajax',
        route: 'get_supporters',
        data: {
          limit: this.limit,
          page: this.current,
          posts_per_page: this.posts_per_page,
        },
        buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
      })
          .then((response) => {
            this.supporters = response?.data?.supporters;
            this.total = response?.data?.total;
            this.reportData = response?.data?.reports;
            this.fetching = false;
          })
          .fail(error => {
            this.$message.error(error?.responseJSON?.data?.message);
          })
          .always(() => {
            this.fetching = false;
          });

    },
  },
  mounted() {
    this.getSupporters();
  }
}
</script>

<style scoped lang="scss">
.buymecoffee_supporters {
  box-shadow: rgb(17 12 46 / 15%) 0px 8px 100px 0px;
  background: #f6fffc;
  padding: 24px;
  border-radius: 6px;
  min-height: 500px;
}
</style>