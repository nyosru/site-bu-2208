<template>
  <header-tw-component></header-tw-component>

  <!-- route: {{ $route }} -->
111
  <div class="content1">
    <router-view name="BreadcrumbsComponent" />
    <router-view name="MenuComponent" />

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
import HeaderTwComponent from './HeaderTwComponent.vue'
import FooterTwComponent from './FooterTwComponent.vue'
</script>

<style scoped>
.content1 {
  display: block;
  min-height: 60vh;
}
</style>
