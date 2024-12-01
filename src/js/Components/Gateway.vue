<template>
    <div class="buymecoffee_main_container input_green_border">
        <el-row class="buymecoffee_gateways">
            <h1 class="buymecoffee_menu_title">Payment Gateways</h1>
           <div class="buymecoffee_gateway_menu">
             <div v-for="(gateway, index) in gateways" :key="index" class="buymecoffee_gateway_item" @click="() => this.$router.push({ name: gateway.route })">
               <div :class="'buymecoffee_gateway_' + gateway.route + (gateway.route === current_route ? ' active' : '')">
                 <img :src="gateway.image"
                      style="width:70px; max-width: 70px;"
                      class="image" />
               </div>
             </div>
         </div>
          <router-view/>
        </el-row>

    </div>
</template>

<script>
 export default {
    name: 'Gateway',
    data() {
        return {
            gateways: [
            ],
            current_route: this.$route.name
        }
    },
    methods: {
      getImage(image){
        return window.BuyMeCoffeeAdmin.assets_url + '/images/' + image;
      },
        // All methods go here
        goto() {
            this.$router.push({ name: 'stripe' })
        },
        getAllMethods() {
            this.$get({
                action: 'buymecoffee_admin_ajax',
                route: 'gateways',
                buymecoffee_nonce: window.BuyMeCoffeeAdmin.buymecoffee_nonce
            })
                .then((response) => {
                    this.gateways = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    computed: {
        // All computed properties go here
    },
    watch: {
        // All watchers go here
    }
    ,
    mounted() {
        this.getAllMethods();
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