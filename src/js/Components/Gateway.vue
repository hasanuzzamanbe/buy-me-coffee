<template>
    <div class="wpm_bmc_main_container">
        <el-row class="wpm_bmc_gateways">
          <h1 class="wpm_bmc_menu_title">Payment Gateways</h1>
          <div v-for="(gateway, index) in gateways" :key="index" class="wpm_bmc_gateway_item" @click="() => this.$router.push({ name: gateway.route })">
                  <div>
                    <img :src="gateway.image"
                         style="max-width: 180px;"
                         class="image" />
                  </div>

                    <div>
                      <p style="font-size: 16px;font-weight: bold;">{{ gateway.title }}</p>
                      <p>{{gateway.description}}</p>
                    </div>
            </div>
        </el-row>

    </div>
</template>

<script>
 export default {
    name: 'Gateway',
    data() {
        return {
            gateways: [
            ]
        }
    },
    methods: {
      getImage(image){
        return window.BuyMeCoffeeAdmin.assets_url + '/images/' + image;
      },
        // All methods go here
        goto() {
            this.$router.push({ name: 'stripe' })
        },
        getAllMethods() {
            this.$get({
                action: 'wpm_bmc_admin_ajax',
                route: 'gateways',
                nonce: window.BuyMeCoffeeAdmin.nonce
            })
                .then((response) => {
                    this.gateways = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    computed: {
        // All computed properties go here
    },
    watch: {
        // All watchers go here
    }
    ,
    mounted() {
        this.getAllMethods();
    }
 }
</script>
<style scoped>
.bottom {
  margin-top: 13px;
  line-height: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.button {
  padding: 0;
  min-height: auto;
}

.image {
  width: 100%;
  display: block;
}
</style>