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
                <el-row class="wpm-template">
                    <div class="wpm-template-inner">
                        <div class="wpm-bmc-editor">
                            <el-row :gutter="20">
                                <el-col :span="12">
                                    <el-form>
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
                                            <el-collapse-item title="Advanced" name="yes">

                                            </el-collapse-item>
                                        </el-collapse>
                                    </el-form>
                                </el-col>
                                <el-col :span="12" class="wpm-btm-preview">
                                    <button size="medium">{{template.buttonText}}</button>
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
            vueJs: 'https://vuejs.org/',
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
            template: {
                buttonText: 'Buy Me Coffee',
                enableMessage: 'yes',
                enableName: 'yes',
                enableEmail: 'yes',
                methods: [],
                advanced: {
                    enable: 'yes',
                    minWidth: '180px',
                    textAlign: 'center',
                    minHeight: '43px',
                    fontSize: '19px'
                }

            }
        }
    },
    methods: {
        preview(id){
            console.log(id);
        },
        addNew(){
            console.log('add new');
        }
    },
    mounted() {
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

