<script>
import {DArrowLeft, DArrowRight, Share, InfoFilled, DocumentCopy} from '@element-plus/icons-vue';
import MediaButton from './Parts/MediaButton.vue';
import PayPal from './PayPal.vue';
import ClipboardJS from "clipboard";
export default {
  name: 'Onboarding',
  components: {
    DArrowLeft,
    DArrowRight,
    MediaButton,
    Share,
    InfoFilled,
    DocumentCopy,
    PayPal

  },
  data() {
    return {
      active: 1,
      saving: false,
      previewUrl: window.BuyMeCoffeeAdmin.preview_url,
      template: {
        advanced: {

        }
      },
      currencies: [],
      fetching: false
    }
  },
  methods: {
    getSettings() {
      this.$get({
        action: 'wpm_bmc_admin_ajax',
        route: 'get_settings',
        wpm_bmc_nonce: window.BuyMeCoffeeAdmin.wpm_bmc_nonce
      }).then(res => {
        this.template = res.data.template;
        this.currencies = res.data.currencies;
        this.fetching = false;
      });
    },
    onMediaSelected ($selected) {
      if ($selected.length) {
        this.template.advanced.image = $selected[0].url
      }
    },
    gotoPage() {
      window.open(this.previewUrl, '_blank');
    },
    fullPath(path) {
      return window.BuyMeCoffeeAdmin.assets_url + 'images/' + path;
    },
    prev() {
      if (this.active > 0) this.active = this.active - 1;
    },
    next() {
      if (this.active == 1) {
        this.saving = true;
        this.$post({
          action: 'wpm_bmc_admin_ajax',
          route: 'save_settings',
          data: {
            settings: this.template,
          },
          wpm_bmc_nonce: window.BuyMeCoffeeAdmin.wpm_bmc_nonce
        })
            .then(response => {
              this.saving = false;
            })
            .fail(error => {
              console.log(error)
            })
            .always(() => {
              this.saving = false;
            });
      }
      if (this.active < 3) this.active = this.active+1;
    }
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

<template>
  <div>
      <div class="wpm_bmc_onboard_wrapper">
        <div class="wpm_bmc_onboard_content">
          <div>
              <div v-if="active == 1" class="profile_section">
                  <div class="profile_image">
                    <img width="120" height="120"
                         v-if="template.advanced.image"
                         :src="template.advanced.image"
                    />
                    <img v-else width="120" height="120"
                         :src="fullPath('profile.png')"
                    />
                    <MediaButton class="quick_media" @onMediaSelected="onMediaSelected"/> <br/>
                  </div>
                  <div class="profile_name" style="margin-top:32px;">
                    <label>Collect donation for</label>
                    <el-input size="large" v-model="template.yourName"></el-input>
                  </div>
              </div>
              <div v-else-if="active == 2" class="quick_payment_section">
                <PayPal/>
              </div>
              <div v-else-if="active == 3" class="quick_done_section">
                <h1 style="margin-bottom: 32px;">
                  Congratulations 🎉 Everything done!
                </h1>
                <div>
                  <div style="display:inline;">
                    <h3 style="margin-bottom: 32px;"><el-icon><Share /></el-icon>
                    Share your page  <a target="_blank" :href="previewUrl">{{previewUrl}}</a><br/>
                    </h3>
                  </div>
                  <el-icon><InfoFilled /></el-icon> You can build your own page using block editor "BuyMeCoffee" or use available shortcodes
                  <br/>
                  <br>
                  <el-tooltip effect="light"
                              content="Click to copy shortcode"
                              title="Click to copy shortcode"
                              :show-after="1000"
                              placement="right">
                    <p class="copy"
                          data-clipboard-action="copy"
                          data-clipboard-text='[buymecoffee_basic]'>
                      <i class="el-icon-document"></i> [buymecoffee_basic]
                    </p>
                  </el-tooltip>
                  <el-tooltip effect="light"
                              :show-after="1000"
                              content="Click to copy shortcode"
                              title="Click to copy shortcode"
                              placement="right">
                    <p class="copy"
                          data-clipboard-action="copy"
                          data-clipboard-text='[buymecoffee_form]'>
                      <i class="el-icon-document"></i> [buymecoffee_form]
                    </p>
                  </el-tooltip>
                  <el-tooltip effect="light"
                              :show-after="1000"
                              content="Click to copy shortcode"
                              title="Click to copy shortcode"
                              placement="right">
                    <p class="copy"
                          data-clipboard-action="copy"
                          data-clipboard-text='[buymecoffee_button]'>
                      <i class="el-icon-document"></i> [buymecoffee_button]
                    </p>
                  </el-tooltip>
                </div>
              </div>
          </div>
          <div style="text-align: center;">
            <el-button :disabled="active < 2" @click="prev"><el-icon><DArrowLeft /></el-icon> &nbsp  Prev </el-button>
            <el-button v-if="active < 3" :disabled="active > 2" @click="next">Next  &nbsp <el-icon><DArrowRight /></el-icon></el-button>
            <el-button type="success" plain v-if="active == 3" @click="gotoPage()" >View Page</el-button>
          </div>
        </div>
        <div class="wpm_bmc_onboard_stepper">
          <h3>Quick setup</h3>
          <el-steps direction="vertical" finish-status="success" style="margin-top:23px" :active="active">
            <el-step title="Profile" />
            <el-step title="Payment" />
            <el-step title="Done" />
          </el-steps>
          <el-button class="el-button el-button--text" style="margin-top:12px;" @click="$router.push('/')">Skip Setup for later</el-button>
        </div>
      </div>
  </div>

</template>

<style scoped lang="scss">
.wpm_bmc_onboard
{
  &_wrapper {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    min-height: 50vh;
    background: #e0fffb45;
    margin-top: 32px;
    border-radius: 10px;
    box-shadow: rgb(99 99 99 / 20%) 0px 2px 8px 0px;
  }
  &_content {
    padding: 32px 10px;
    width: 70%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 12px;
    min-height: 450px;

    .profile_section {
      max-width: 300px;
      margin: 100px auto;
    }
    .profile_image {
      display: flex;
      align-items: center;
      justify-content: center;
      img{
        width: 120px;
        border-radius: 50%;
      }
    }
    .quick_media {
      width: 74px;
      color: #3a59c2;
      border: none;
      background: #0000;
      cursor: pointer;
    }
    .quick_done_section {
      display: grid;
      align-items: center;
      justify-items: center;
      .copy {
        color: #4f4f4f;
        font-size: 18px;
        background: #eaebec;
        text-align: center;
        padding: 4px;
        border-radius: 10px;
        cursor: pointer;
        margin: 4px;
      }
    }
  }
  &_stepper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    background: #eefffe;
    padding: 20px;
  }

}

</style>