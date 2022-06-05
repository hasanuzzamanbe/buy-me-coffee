<template>
    <div class="wpm_bmc_admin_ajax">
        <el-tabs v-model="activeName">
            <el-tab-pane label="Stripe" name="stripe">
                <Stripe/>
            </el-tab-pane>
            <el-tab-pane label="PayPal" name="paypal">
                <PayPal/>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script type="text/babel">
import Stripe from './Stripe.vue';
import PayPal from './PayPal.vue';

    export default {
        name: 'settings',
        components: {
            Stripe,
            PayPal
        },
        data() {
            return {
                activeName: 'stripe'
            }
        },
    }
</script>

<style lang="scss">
</style>
