<template>
  <div class="buymecoffee_main_container">
    <div class="buymecoffee_notifications">
      <el-row>
        <el-col :span="12">
          <h1 class="buymecoffee_menu_title">Notifications</h1>
        </el-col>
        <el-col :span="12">
        </el-col>
      </el-row>
      <el-tabs :tab-position="'top'" style="height: 200px">

        hello
        <router-view/>
        end

        <el-tab-pane label="Emails"><Emails/></el-tab-pane>
        <el-tab-pane label="Webhook"><Webhook/></el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>
<script>
import Emails from "./Email/Emails.vue";
import Webhook from "./Webhook.vue";
export default {
  name: "Notifications",
  data() {
    return {
      current: 0,
      total: 0,
      posts_per_page: 10,
      notifications: []
    }
  },
  components: {
    Webhook,
    Emails
  },
  methods: {
    getNotifications () {
      this.fetching = true;
      this.$get({
        action: 'buymecoffee_admin_ajax',
        route: 'get_notifications',
        data: {
          limit: this.limit,
          page: this.current,
          posts_per_page: this.posts_per_page,
        },
        buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
      })
          .then((response) => {
            this.notifications = response?.data?.notifications;
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
    this.getNotifications();
  }
}
</script>

<style scoped lang="scss">
.buymecoffee_notifications {
  box-shadow: rgb(17 12 46 / 15%) 0px 8px 100px 0px;
  background: #f6fffc;
  padding: 24px;
  border-radius: 6px;
  min-height: 500px;
}
</style>