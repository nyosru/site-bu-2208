<template>
  <header-tw-component></header-tw-component>

  <div class="content1">
    <router-view name="BreadcrumbsComponent" />
    <router-view name="adver" />
    <router-view name="adverList" />
    <router-view name="starter" />
    <router-view name="page" />
    <router-view name="cart" />
    <router-view name="vitrin" />
  </div>

  <footer-tw-component></footer-tw-component>
</template>

<script setup>
// import HeaderComponent from './HeaderComponent.vue'
// import FooterComponent from './FooterComponent.vue'

import HeaderTwComponent from './HeaderTwComponent.vue'
import FooterTwComponent from './FooterTwComponent.vue'

// import cart from './../../use/cart.js'

// import { onMounted } from 'vue'

// const { cartCashRead } = cart()

// onMounted(() => {
//   cartCashRead()
// })
</script>

<style scoped>
.content1 {
  display: block;
  min-height: 60vh;
}
</style>
