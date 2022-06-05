<template>
    <div>
        <!-- example content  start -->
        <el-container>
            <el-header>
                <el-row>
                    <el-col :span="12">
                        <h1>Buy Me <i class="el-icon-coffee" ></i>- Button templates</h1>
                    </el-col>
                    <el-col :span="12" style="padding: 12px;">
                        <div>
                            <el-tooltip effect="dark" style="float:right;"
                                content="Click to copy shortcode"
                                title="Click to copy shortcode"
                                placement="top">
                                <code class="copy"
                                        data-clipboard-text='[buymecoffee type="button"]'>
                                    <i class="el-icon-document"></i> [buymecoffee type="button"]
                                </code>
                            </el-tooltip>
                        </div>
                    </el-col>
                </el-row>
            </el-header>
            <el-main>
                <el-row class="wpm-template" v-loading="saving">
                    <div class="wpm-template-inner">
                        <div class="wpm-bmc-editor">
                            <el-row :gutter="20">
                                <el-col :span="12">
                                    <el-form label-position="left" label-width="140px">
                                        <el-form-item label="Button text">
                                            <el-input size="small" type="text" v-model="template.buttonText"></el-input>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableName">Collect name of donor</el-checkbox>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableEmail">Collect email of donor</el-checkbox>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-checkbox true-label="yes" false-label="no" v-model="template.enableMessage">Enable message option when donate</el-checkbox>
                                        </el-form-item>
                                        <el-form-item label="">
                                             <p>Enable pay method</p>
                                              <el-checkbox-group v-model="template.methods">
                                                <el-checkbox v-for="method in methods" :key="method.value" :label="method.name"></el-checkbox>
                                            </el-checkbox-group>
                                        </el-form-item>
                                        <el-collapse v-model="template.advanced.enable">
                                            <el-collapse-item title="Advanced style:" name="yes">
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
                                            </el-collapse-item>
                                        </el-collapse>
                                        <div>
                                            <el-button style="float:right;margin-top:12px;" @click="saveTemplates" type="warning" size="mini">
                                                Save Settings
                                            </el-button>
                                        </div>
                                    </el-form>
                                </el-col>
                                <el-col :span="12" class="wpm-btm-preview">
                                    <h3 style="margin-bottom: 23px">Preview Button style:</h3>
                                    <div>
                                        <button
                                            style="cursor: pointer;"
                                            :style="{'background-color': template.advanced.bgColor,
                                                    'color': template.advanced.color,
                                                    'border-radius': template.advanced.radius + 'px',
                                                    'padding': '8px 20px',
                                                    'border' : 'none',
                                                    'font-size': template.advanced.fontSize + 'px',
                                                }"
                                            size="medium"
                                            @click="previewButton"
                                            >
                                            {{template.buttonText}}
                                        </button>
                                    </div>
                                </el-col>
                            </el-row>
                        </div>
                    </div>
                </el-row>
            </el-main>
        </el-container>
        <!-- example content end -->
    </div>
</template>
<script>
import Clipboard from 'clipboard';
export default {
    name: 'Dashboard',
    data(){
        return {
            saving: false,
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
            }
        }
    },
    methods: {
        getSettings() {
            this.$get({
                    action: 'wpm_bmc_admin_ajax',
                    route: 'get_settings'
                }).then(res => {
                    this.template = res.data;

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
                        this.$message.success(response.data.message);
                        this.saving = false;
                    })
                    .fail(error => {
                        this.$message.error(error.responseJSON.data.message);
                    })
                    .always(() => {
                        this.saving = false;
                    });

        },
        previewButton(){
            window.open(window.buyMeCoffeeAdmin.preview_url);
        },
    },
    mounted() {
        this.getSettings();
        if(!window.wpm_clip_inited) {
            var clipboard = new Clipboard('.copy');
            clipboard.on('success', (e) => {
                this.$message({
                    message: 'Copied to Clipboard!',
                    type: 'success',
                    offset: 32
                });
            });
            window.wpm_clip_inited = true;
        }
    }
}
</script>

