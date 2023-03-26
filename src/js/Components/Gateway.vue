<template>
    <div class="wpm_bmc_main_container">
        <el-row>
            <el-col :span="12">
                <h1 class="wpm_bmc_menu_title">Payment Gateways</h1>
            </el-col>
            <el-col :span="12">
            </el-col>
        </el-row>
        <el-row>
            <div v-for="(gateway, index) in gateways" :key="index" :offset="index > 0 ? 2 : 0">
                <el-card :body-style="{ padding: '10px' }" style="width: 250px;margin-right:12px;">
                    <img :src="getImage(gateway.image)"
                        class="image" />
                    <div style="padding: 14px">
                        <h3>{{ gateway.title }}</h3>
                        <div class="bottom">
                            <el-button size="small" class="link" @click="() => this.$router.push({ name: gateway.route })" icon="View">settings </el-button>
                        </div>
                    </div>
                </el-card>
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
        getImage(image) {
            return window.buyMeCoffeeAdmin.assets_url + '/images/' + image;
        }
    },
    computed: {
        // All computed properties go here
    },
    watch: {
        // All watchers go here
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