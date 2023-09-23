<template>
    <div class="wpm_bmc_main_container">
        <el-row>
            <el-col :span="12">
                <h1 class="wpm_bmc_menu_title">Payment Gateways</h1>
            </el-col>
            <el-col :span="12">
            </el-col>
        </el-row>
        <el-row class="wpm_bmc_gateways">
            <div v-for="(gateway, index) in gateways" :key="index" class="wpm_bmc_gateway_item" @click="() => this.$router.push({ name: gateway.route })">
                  <div>
                    <img :src="gateway.image"
                         style="max-width: 96px;"
                         class="image" />
                  </div>

                    <div style="display: flex;">
                        <h3>{{ gateway.title }}</h3>
<!--                        <div class="bottom">-->
<!--                            <el-button size="small" class="link" @click="() => this.$router.push({ name: gateway.route })" icon="View">settings </el-button>-->
<!--                        </div>-->
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
                {
                    title: 'Stripe',
                    description: 'Stripe is a suite of payment APIs that powers commerce for online businesses of all sizes, including fraud prevention, and subscription management. Use Stripe’s payment platform to accept and process payments online for easy-to-use commerce solutions.',
                    image: 'credit-card.png',
                    route: 'stripe'
                },
                {
                    title: 'PayPal',
                    description: 'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
                    image: 'paypal.png',
                    route: 'paypal'
                }
            ]
        }
    },
    methods: {
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