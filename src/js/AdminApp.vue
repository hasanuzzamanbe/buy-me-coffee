<template>
    <div id="buy-me-coffee_app">
        <div class="buy-me-coffee_main_nav">
            <div class="topnav">
                    <router-link v-for="menuItem in topMenus"
                        :key="menuItem.route"
                        active-class="ninja-tab-active"
                        exact
                        :class="['ninja-tab']"
                        :to="{ name: menuItem.route }">
                       <i :class="menuItem.icon"></i> {{ menuItem.title }}
                    </router-link>
                    <img width="140px" :src="fullPath('coffee.png')" alt="">
            </div>

        </div>
        <div class="main-content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
  export default {
      name: 'AdminApp',
      data() {
          return {
              topMenus: []
          }
      },
      methods: {
          fullPath(path) {
              return window.buyMeCoffeeAdmin.assets_url + 'images/' + path;
          },
          setTopmenu() {
              this.topMenus = this.applyFilters('buy-me-coffee_top_level_menu', [
                  {
                    route: 'dashboard',
                    title: 'Templates',
                    icon: 'el-icon-document'
                  },
                  {
                    route: 'settings',
                    title: 'Payment',
                    icon: 'el-icon-bank-card'
                  },
                  {
                    route: 'supporters',
                    title: 'Supporters',
                    icon: 'el-icon-templates'
                  }
              ])
          }
      },
      mounted() {
          this.setTopmenu();
      }
  }
</script>
<style scoped lang="scss">
#buy-me-coffee_app {
    display: flex;
    padding-top: 23px;
}
</style>
