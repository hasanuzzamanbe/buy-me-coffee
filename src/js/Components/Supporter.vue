<template>
  <div class="wpm_bmc_main_container">
    <h3 class="wpm_bmc_title">
      <router-link style="text-decoration: none;" :to="{name: 'Supporters'}">Supporters / </router-link>Supporter:
    </h3>
      <div class="customer-info">
        <p><span class="label">Created At:</span> <span id="created_at">{{supporter?.created_at}}</span></p>
        <p><span class="label">Supporter's Email:</span> <a :href="'mailto:' + supporter?.supporters_email" id="supporters_email">{{supporter?.supporters_email}}</a></p>
        <p><span class="label">Supporter's Message:</span> <span id="supporters_message">{{supporter?.supporters_message}}</span></p>
        <p><span class="label">Supporter's Name:</span> <span id="supporters_name">{{supporter?.supporters_name}}</span></p>
<!--        <p><span class="label">Currency:</span> <span id="currency">{{supporter?.currency}}</span></p>-->
        <p><span class="label">Entry Hash:</span> <code id="entry_hash">{{supporter?.entry_hash}}</code></p>
        <p><span class="label">Payment Method:</span> <span id="payment_method">{{supporter?.payment_method}}</span></p>
        <p><span class="label">Payment Status:</span> <span id="payment_status">{{supporter?.payment_status}}</span></p>
        <p><span class="label">Coffee Count:</span> <span id="coffee_count">{{parseInt(supporter?.coffee_count)}}</span></p>
        <p><span class="label">Supported:</span> <span id="payment_total">{{parseInt(supporter?.payment_total / 100)}} {{supporter?.currency}}</span></p>
      </div>
    </div>
</template>
<script>

export default {
    name: 'Supporter',
    data () {
        return {
            supporter: {},
            val: 'Hello from Supports'
        }
    },
  methods: {
      getSupporter() {
        this.$get({
          action: 'wpm_bmc_admin_ajax',
          route: 'get_supporter',
          id: this.$route.params.id
        }).then((response) => {
          console.log(response.data)
          this.supporter = response.data
        }).catch((e) => {
          this.$handleError(e)
        })
      }
  },
  mounted() {
        this.getSupporter()
    }
}

</script>
<style scoped>
.customer-info {
  border: 1px solid #ccc;
  padding: 20px;
  margin: 20px;
  max-width: 400px;
  border-radius: 8px;
}
.label {
  font-weight: bold;
}
span#payment_total {
  color: #008000;
  border: 1px solid #ccc;
  padding: 2px 16px;
  border-radius: 8px;
}
</style>