<template>
  <div class="container-fluid">
    <div
      class="row xproduct-list xgrid_full xgrid_sidebar xgrid-uniform xcontainer-fluid"
    >
      <vitrin-pagination-component v-if="!goodsLoading" :inf="goodsData.meta" />
      <div class="col-4 text-center" v-if="goodsLoading">
        <h2>.. загрузка предложений ..</h2>
        <br />
        <br />
        <!-- <img src="/storage/site/img/loader.gif" alt="" style="width:120px;" /> -->

        <div class="flex justify-center items-center space-x-2">
          <div
            class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-600"
            role="status"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div v-else>
        <div
          class="grid grid-cols-2 gap-2 md:grid-cols-4 lg:grid-cols-6 lg:gap-4"
        >
          <div
            v-for="i in goodsData.data"
            :key="i.key"
            xclass="col-xs-6 col-sm-4 col-md-3 col-lg-2"
          >
            <good-component :i="i" />
            <br />
            <br />
          </div>
        </div>

        <template
          v-if="
            !goodsLoading &&
            goodsData.meta &&
            goodsData.meta.links &&
            goodsData.meta.links.length > 3
          "
        >
          <br clear="all" />
          <vitrin-pagination-component
            v-if="!goodsLoading"
            :inf="goodsData.meta"
          />
        </template>
        <template v-else>
          <br />
          <br />
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import VitrinPaginationComponent from './PaginationComponent.vue'

import { watchEffect, ref } from 'vue'
import { useRoute } from 'vue-router'

import catalogs from './../../use/catalogs.js'
import goods from './../../use/goods.js'

import GoodComponent from './GoodComponent.vue'

const route = useRoute()

const { loading } = catalogs()

const { goodsLoading, goodsData, loadGoods } = goods()

const stopWatch2 = watchEffect(() => {
  // console.log(1)
  if (loading.value == false) {
    // console.log(2 , loading.value)
    if (route.params.cat_id && route.params.cat_id.length) {
      const { catNow } = catalogs()
      catNow.value = route.params.cat_id
      loadGoods(route.params.cat_id, route.params.page)
    }
  }
})
</script>

<style scoped></style>
