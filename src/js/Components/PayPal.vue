<template>
    <div class="wpm_bmc_main_container" v-loading="fetching">
        <div class="wpm_bmc_wrapper wpm_bmc_payment_settings">
            <div class="wpm_bmc_header">
                <h3 class="wpm_bmc_title">
                    <router-link style="text-decoration: none;" :to="{name: 'Gateway'}">Gateways / </router-link>PayPal Gateway Settings:
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
                  <el-tabs v-model="settings.payment_type" class="demo-tabs" @tab-click="handleClick">
                    <el-tab-pane label="PayPal pro" name="pro">
                      <div v-if="settings.payment_mode === 'test'">
                        <el-form-item label="Test Public key">
                          <el-input type="text" size="small" v-model="settings.test_public_key"
                                    placeholder="Public key from paypal dashboard"/>
                        </el-form-item>
                        <el-form-item label="Test Secret key">
                          <el-input type="password" size="small" v-model="settings.test_secret_key"
                                    placeholder="Secret key from paypal dashboard"/>
                        </el-form-item>
                      </div>
                      <div v-else>
                        <el-form-item label="Live Public key">
                          <el-input type="text" size="small" v-model="settings.live_public_key"
                                    placeholder="Public key from paypal dashboard"/>
                        </el-form-item>
                        <el-form-item label="live Secret key">
                          <el-input type="password" size="small" v-model="settings.live_secret_key"
                                    placeholder="Secret key from paypal dashboard"/>
                        </el-form-item>
                      </div>
                        <div class="wpm_bmc_settings_section">
                          <p>Please use IPN url to get marked paid on you site.</p>
                          <p><b>IPN URL: </b><code>{{webhook_url}}</code></p>
                      </div>

                    </el-tab-pane>
                    <el-tab-pane label="Paypal Standard" name="standard">
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
                        <div class="wpm_bmc_settings_section">
                          <p>Please use IPN url to get marked paid on you site.</p>
                          <p><b>IPN URL: </b><code>{{webhook_url}}</code></p>
                        </div>
                    </el-tab-pane>
                  </el-tabs>

                    <div class="action_right">
                        <el-button @click="saveSettings()" type="primary" size="default">Save PayPal Settings</el-button>
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
                labelPosition: 'right',
                webhook_url: ''
            }
        },
        methods: {
            getSettings() {
                this.fetching = true;
                this.$get({
                    action: 'wpm_bmc_admin_ajax',
                    route: 'get_data',
                    method: this.$route.name,
                    nonce: window.BuyMeCoffeeAdmin.nonce
                })
                    .then((response) => {
                        this.settings = response.data.settings;
                        this.webhook_url = response.data.webhook_url;
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
                    action: 'wpm_bmc_admin_ajax',
                    route: 'save_payment_settings',
                    method: this.$route.name,
                    settings: this.settings,
                    nonce: window.BuyMeCoffeeAdmin.nonce
                })
                    .then(response => {
                        this.$handleSuccess(response.data.message);
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