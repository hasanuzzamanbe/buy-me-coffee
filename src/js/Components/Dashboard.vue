<template>
    <div class="wpm_bmc_main_container">
        <el-row>
            <el-col :span="12">
                <h1 class="wpm_bmc_menu_title">Buy Me <CoffeeCup style="width:23px;"/>- Global settings</h1>
            </el-col>
            <el-col :span="12">
            </el-col>
        </el-row>
        <el-row class="wpm-template" v-loading="saving">
            <div class="wpm-template-inner">
                <div class="wpm-bmc-editor">
                    <el-row :gutter="20">
                        <el-col :md="24" :lg="12">
                            <el-form label-position="left" label-width="140px" v-if="!fetching">
                                <el-tabs>
                                    <el-tab-pane label="General">
                                      <el-form-item label="You Name">
                                        <el-input size="small" type="text" v-model="template.yourName"></el-input>
                                      </el-form-item>
                                        <el-form-item label="Button text">
                                            <el-input size="small" type="text" v-model="template.buttonText"></el-input>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableName">Collect name of supporter</el-checkbox>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableEmail">Collect email of supporter</el-checkbox>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableMessage">Enable message option when donate</el-checkbox>
                                        </el-form-item>
                                        <el-form-item label="Per coffee price">
                                            <el-input type="number" v-model="template.defaultAmount"></el-input>
                                        </el-form-item>
<!--                                        <el-form-item label="Enable pay method">-->
<!--                                                <el-checkbox-group v-model="template.methods">-->
<!--                                                <el-checkbox v-for="method in methods" :key="method.value" :label="method.name"></el-checkbox>-->
<!--                                            </el-checkbox-group>-->
<!--                                        </el-form-item>-->
                                        <el-form-item label="Currency">
                                          <el-select filterable v-model="template.currency" placeholder="Select Currency">
                                            <el-option
                                                v-for="(currencyName, currenyKey) in currencies"
                                                :key="currenyKey"
                                                :label="currencyName"
                                                :value="currenyKey">
                                            </el-option>
                                          </el-select>
                                        </el-form-item>
                                    </el-tab-pane>
                                    <el-tab-pane label="Styles">
                                                <el-form-item label="Button color">
                                                    <el-color-picker
                                                        size="small"
                                                        v-model="template.advanced.bgColor"
                                                        show-alpha
                                                        :predefine="predefineColors">
                                                    </el-color-picker>
                                                </el-form-item>
                                                    <el-form-item label="Button Text color">
                                                    <el-color-picker
                                                        size="small"
                                                        v-model="template.advanced.color"
                                                        show-alpha
                                                        :predefine="predefineColors">
                                                    </el-color-picker>
                                                </el-form-item>
                                                    <el-form-item label="Button Radius(px)">
                                                    <el-input
                                                        style="width:50%"
                                                        type="number"
                                                        size="small"
                                                        v-model="template.advanced.radius"
                                                        >
                                                    </el-input>
                                                </el-form-item>
                                                <el-form-item label="Button click action">
                                                  <el-radio-group v-model="template.openMode">
                                                    <el-radio label="modal">Modal (recommended)</el-radio>
                                                    <el-radio label="page">Open in Page</el-radio>
                                                  </el-radio-group>
                                                </el-form-item>
                                    </el-tab-pane>
                                    <div>
                                      <el-popconfirm @confirm="resetDefault" title="Are you sure to reset to default settings?">
                                        <template #reference>
                                          <el-button style="margin-top:12px;" type="danger" size="default">
                                            Reset Default
                                          </el-button>
                                        </template>
                                      </el-popconfirm>
                                        <el-button style="margin-top:12px;" @click="saveTemplates" type="primary" size="default">
                                            Save Settings
                                        </el-button>
                                    </div>
                                </el-tabs>
                            </el-form>
                            <div v-else>
                              <el-skeleton :rows="5" />
                            </div>
                        </el-col>
                        <el-col :md="24" :lg="12" class="wpm-btm-preview">
                            <div>
                                <h3>Preview Button style</h3>
                                <button
                                    style="cursor: pointer;"
                                    :style="{'background-color': template.advanced.bgColor,
                                            'color': template.advanced.color,
                                            'border-radius': template.advanced.radius + 'px',
                                            'padding': '8px 20px',
                                            'border' : 'none',
                                            'height' : '50px',
                                            'font-size': template.advanced.fontSize + 'px',
                                        }"
                                    size="default"
                                    @click="previewButton"
                                    >
                                    {{template.buttonText}}
                                </button>
                            </div>
                            <div class="wpm-btm-render-options">
                                <br/>
                                <br/>
                              <h3>Available ShortCodes:</h3>
                              <p>
                                <i class="el-icon-info"></i>
                                You can embed the shortcode on your posts/pages if you want to use the button above.
                                Or use the URL bellow to get collect payments from your supporters.
                              </p>
                              <h4>Button:</h4>
                                <div>
                                    <el-tooltip effect="dark"
                                        content="Click to copy shortcode"
                                        title="Click to copy shortcode"
                                        placement="top">
                                        <code class="copy"
                                                data-clipboard-action="copy"
                                                data-clipboard-text='[buymecoffee_button]'>
                                            <i class="el-icon-document"></i> [buymecoffee_button]
                                        </code>
                                    </el-tooltip>
                                </div>
                                <br/>
                              <div>
                                <h4>Form:</h4>
                                <el-tooltip effect="dark"
                                            content="Click to copy shortcode"
                                            title="Click to copy shortcode"
                                            placement="top">
                                  <code class="copy"
                                        data-clipboard-action="copy"
                                        data-clipboard-text='[buymecoffee_form]'>
                                    <i class="el-icon-document"></i> [buymecoffee_form]
                                  </code>
                                </el-tooltip>
                              </div>
                                <div>
                                    <h4>Page with designed template(recomended):</h4>
                                    <el-tooltip effect="dark"
                                        content="Click to copy shortcode"
                                        title="Click to copy shortcode"
                                        placement="top">
                                        <code class="copy"
                                                :data-clipboard-text='previewUrl'>
                                            <i class="el-icon-document"></i> {{previewUrl}}
                                        </code>
                                    </el-tooltip>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </el-row>
        <!-- example content end -->
    </div>
</template>
<script>
import ClipboardJS from 'clipboard';
export default {
    name: 'Dashboard',
    data(){
        return {
            saving: false,
            currencies: {},
            fetching: true,
            previewUrl: window.BuyMeCoffeeAdmin.preview_url,
            methods: [
                {
                    name: 'PayPal',
                    value: 'paypal'
                },
                {
                    name: 'Stripe',
                    value: 'stripe'
                }
            ],
            predefineColors: [
                '#ff4500',
                '#ff8c00',
                '#ffd700',
                '#90ee90',
                '#00ced1',
                '#1e90ff',
                '#c71585',
                'rgba(255, 69, 0, 0.68)',
                'rgb(255, 120, 0)',
                'hsv(51, 100, 98)',
                'hsva(120, 40, 94, 0.5)',
                'hsl(181, 100%, 37%)',
                'hsla(209, 100%, 56%, 0.73)',
                '#c7158577'
            ],
            template: {
                advanced: {

                }
            }
        }
    },
    methods: {
        getSettings() {
            this.$get({
                    action: 'wpm_bmc_admin_ajax',
                    route: 'get_settings'
                }).then(res => {
                    this.template = res.data.template;
                    this.currencies = res.data.currencies;
                    this.fetching = false;
            });

        },
        resetDefault() {
            this.saving = true;
                this.$post({
                    action: 'wpm_bmc_admin_ajax',
                    route: 'reset_template_settings'
                })
                    .then(response => {
                        this.$handleSuccess(response.data.message);
                        this.template = response.data.settings;
                        this.saving = false;
                    })
                    .fail(error => {
                        this.$message.error(error.responseJSON.data.message);
                    })
                    .always(() => {
                        this.saving = false;
                    });
        },
        saveTemplates() {
            this.saving = true;
                this.$post({
                    action: 'wpm_bmc_admin_ajax',
                    settings: this.template,
                    route: 'save_settings'
                })
                    .then(response => {
                        this.$handleSuccess(response.data.message);
                        this.saving = false;
                    })
                    .fail(error => {
                        console.log(error)
                        // this.$message.error(error.responseJSON.data.message);
                    })
                    .always(() => {
                        this.saving = false;
                    });
        },
        previewButton(){
            window.open(window.BuyMeCoffeeAdmin.preview_url);
        },
    },
    mounted() {
        this.getSettings();
        jQuery(document).ready(function($) {
            var clipboard = new ClipboardJS('.copy');
            clipboard.on('success', function(e) {
                $(e.trigger).text("Copied!");
                e.clearSelection();
                //todo: add timeout to revert text
                setTimeout(function() {
                    $(e.trigger).text(e.text);
                }, 1000);
            });
        });
    }
}
</script>

