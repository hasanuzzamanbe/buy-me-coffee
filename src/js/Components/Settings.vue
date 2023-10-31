<template>
  <div class="wpm_bmc_main_container">
    <el-row class="wpm-template" v-loading="saving">
      <div class="wpm-template-inner">
        <div class="wpm_bmc-editor">
          <el-row>
            <el-col :md="24" :lg="12" style="background: linear-gradient(122deg, #ffffff87, #c4fff654);border-radius:6px; padding: 24px;">
              <h1 class="wpm_bmc_menu_title">Buy Me <CoffeeCup style="width:23px;"/>- Global settings</h1>
              <el-form label-position="left" label-width="140px" v-if="!fetching">
                <el-tabs>
                  <el-tab-pane label="General">
                    <el-form-item label="You Name">
                      <el-input size="small" type="text" v-model="template.yourName"></el-input>
                      <span style="font-style: italic; font-size: 13px; color: #929292; line-height: 1.6em;">
                                          Also you can use data params Ex: https://page-link&<code>for=John</code></span>
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
                      <el-select class="wpm_currency_select" filterable v-model="template.currency" placeholder="Select Currency">
                        <el-option
                            v-for="(currencyName, currenyKey) in currencies"
                            :key="currenyKey"
                            :label="currencyName"
                            :value="currenyKey">
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </el-tab-pane>
                  <el-tab-pane label="Template Settings">
                    <el-form-item label="Button color">
                      <el-color-picker
                          size="small"
                          @active-change="changeBgColor"
                          v-model="template.advanced.bgColor"
                          show-alpha
                          :predefine="predefineColors">
                      </el-color-picker>
                    </el-form-item>
                    <el-form-item label="Button Text color">
                      <el-color-picker
                          size="small"
                          @active-change="changeFontColor"
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
                    <el-form-item label="Your Quotes">
                      <el-input
                          type="textarea"
                          size="small"
                          v-model="template.advanced.quote"
                      >
                      </el-input>
                    </el-form-item>
                    <el-form-item label="">
                      <div class="wpm_bmc_settings_image">
                        <div>
                          <MediaButton @onMediaSelected="onMediaSelected" />
                        </div>
                        <img width="120" height="120"
                            v-if="template.advanced.image"
                            :src="template.advanced.image"
                        />
                        <img v-else width="120" height="120"
                             :src="fullPath('profile.png')"
                        />
                      </div>
                    </el-form-item>
                    <!--                                      will add modal-->
                    <!--                                                <el-form-item label="Button click action">-->
                    <!--                                                  <el-radio-group v-model="template.openMode">-->
                    <!--                                                    <el-radio label="modal">Modal (recommended)</el-radio>-->
                    <!--                                                    <el-radio label="page">Open in Page</el-radio>-->
                    <!--                                                  </el-radio-group>-->
                    <!--                                                </el-form-item>-->
                  </el-tab-pane>
                  <div>
                    <el-popconfirm @confirm="resetDefault" title="Are you sure to reset to default settings?">
                      <template #reference>
                        <el-button plain style="margin-top:12px;" type="warning" size="default">
                          Reset Default
                        </el-button>
                      </template>
                    </el-popconfirm>
                    <el-button plain style="margin-top:12px;" @click="saveTemplates" type="success" size="default">
                      Save Settings
                    </el-button>
                  </div>
                </el-tabs>
              </el-form>
              <div v-else>
                <el-skeleton :rows="5" />
              </div>
            </el-col >
            <el-col :md="24" :lg="12" class="wpm-btm-preview"
                    style="padding: 24px;
                    background: white;
                    border-top-right-radius: 6px;
                    border-bottom-right-radius: 6px;">
              <h3>Preview Button Style</h3>
              <div style="display: flex;">
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
                <h3>Embed:</h3>
                <i class="el-icon-info"></i>
                <p>Use Block editor or embed the shortcode on your posts/pages if you want to use the button above.
                  Or use the URL bellow to collect payments from your supporters</p>
                <br/>
                <img :src="fullPath('blocks.jpeg')" alt="Block editor" style="width: 80%;opacity: 0.3;"/>
                <h4>Or Use ShortCodes:</h4>
                <div style="display:flex; align-items: center;">
                  <p>Button ShortCode:</p>
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
                </div>
                <div style="display:flex; align-items: center;">
                  <p>Form ShortCode:</p>
                  <div>
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
                </div>
                <div style="display:flex; align-items: center;">
                  <p>Form With Template: </p>
                  <div>
                    <el-tooltip effect="dark"
                                content="Click to copy shortcode"
                                title="Click to copy shortcode"
                                placement="top">
                      <code class="copy"
                            data-clipboard-action="copy"
                            data-clipboard-text='[buymecoffee_basic]'>
                        <i class="el-icon-document"></i> [buymecoffee_basic]
                      </code>
                    </el-tooltip>
                  </div>
                  <a style="margin-left:12px; color: #e88b0d;text-decoration: none;" :href="previewUrl" target="_blank">Preview</a>
                </div>
                <br/>
                <p>Also you can use custom amount template by adding <code>&custom</code> &nbsp param with your page link like this:</p>
                <p>{{ previewUrl }}&custom=10 <a style="color: #e88b0d;text-decoration: none;" target="_blank" :href="previewUrl + '&custom=10'">Preview</a></p>
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
import {View} from "@element-plus/icons-vue";
import MediaButton from "./Parts/MediaButton.vue";
export default {
  name: 'Settings',
  computed: {
    View() {
      return View
    }
  },
  components: {
    MediaButton
  },
  data(){
    return {
      saving: false,
      currencies: {},
      fetching: true,
      previewUrl: window.BuyMeCoffeeAdmin.preview_url,
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
        '#c7158577',
        '#FFF',
        '#000000',
      ],
      template: {
        advanced: {

        }
      }
    }
  },
  methods: {
    onMediaSelected ($selected) {
      if ($selected.length) {
        this.template.advanced.image = $selected[0].url
      }
    },
    changeBgColor(value) {
      this.template.advanced.bgColor = value;
    },
    changeFontColor(value) {
      this.template.advanced.color = value;
    },
    getSettings() {
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_settings',
        nonce: window.BuyMeCoffeeAdmin.nonce
      }).then(res => {
        this.template = res.data.template;
        this.currencies = res.data.currencies;
        this.fetching = false;
      });

    },
    fullPath(path) {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + path;
    },
    resetDefault() {
      this.saving = true;
      this.$post({
        action: 'wpm_bmc_admin_ajax',
        route: 'reset_template_settings',
        nonce: window.BuyMeCoffeeAdmin.nonce
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
        route: 'save_settings',
        nonce: window.BuyMeCoffeeAdmin.nonce
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
      window.open(this.previewUrl);
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
