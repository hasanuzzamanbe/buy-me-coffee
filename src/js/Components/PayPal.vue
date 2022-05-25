<template>
    <div v-loading="fetching">
        <div class="wpm_bmc_wrapper">
            <div class="wpm_bmc_header">
                <h3 class="wpm_bmc_title">
                    <!-- {{ $t('PayPal Gateway Settings') }} -->
                    PayPal Gateway Settings:
                </h3>
            </div>
            <div style="margin-bottom: 23px;">
                <label>Enable PayPal Payment
                    <el-switch active-value="yes" inactive-value="no" active-text="Enable PayPal" v-model="settings.enable"></el-switch>
                </label>
            </div>
            <div class="wpm_bmc_section_body" :class="settings.enable !== 'yes' ? 'payment-inactive' : ''">
                <el-form :label-position="labelPosition" rel="paypal_settings" :model="settings" label-width="220px">
                    <el-form-item label="PayPal Payment Mode">
                        <el-radio-group v-model="settings.payment_mode">
                            <el-radio label="test">Sandbox Mode</el-radio>
                            <el-radio label="live">Live Mode</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="Paypal Email">
                        <el-input type="text" size="small" v-model="settings.paypal_email"
                                  placeholder="Paypal Email Address"/>
                    </el-form-item>
                    <el-form-item label="Disable PayPal IPN Verification">
                        <el-switch active-value="yes" inactive-value="no" v-model="settings.disable_ipn_verification"/>
                        <p>If you are unable to use Payment Data Transfer and payments are not getting marked as
                            complete, then check this box. This forces the site to use a slightly less secure method of
                            verifying purchases.</p>
                    </el-form-item>

                    <div class="action_right">
                        <el-button @click="saveSettings()" type="primary" size="small">Save PayPal Settings</el-button>
                    </div>
                </el-form>
            </div>
        </div>
    </div>
</template>
<script type="text/babel">
    export default {
        name: 'paypal_settings',
        data() {
            return {
                settings: {},
                saving: false,
                fetching: false,
                labelPosition: 'right'
            }
        },
        methods: {
            getSettings() {
                this.fetching = true;
                this.$get({
                    action: 'wpm_bmc_payment_setting',
                    route: 'get_paypal_Settings'
                })
                    .then((response) => {
                        this.settings = response.data.settings;
                        this.fetching = false;
                    })
                    .fail(error => {
                        this.$message.error(error.responseJSON.data.message);
                    })
                    .always(() => {
                        this.fetching = false;
                    });
            },
            saveSettings() {
                this.saving = true;
                this.$post({
                    action: 'wpm_bmc_payment_setting',
                    route: 'save_payment_settings',
                    method: 'paypal',
                    settings: this.settings,
                })
                    .then(response => {
                        this.$message.success(response.data.message);
                    })
                    .fail(error => {
                        this.$message.error(error.responseJSON.data.message);
                    })
                    .always(() => {
                        this.saving = false;
                    });
            }
        },
        mounted() {
            this.getSettings();
        }
    }
</script>

<style lang="scss">

</style>