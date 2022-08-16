<template>
  <div class="container-fluid">
    <!-- <div class="row">
      <div class="col-xs-12 py-4">
        <h3>vitrin mitrin</h3>
      </div>
    </div> -->

    <div class="row" v-if="false">
      <div class="col-12 text-center">
        <template
          v-if="
            !goodsLoading &&
            goodsData.meta &&
            goodsData.meta.links &&
            goodsData.meta.links.length > 3
          "
        ></template>
        <template v-else>
          <!-- <br /> -->
          <!-- <br /> -->
        </template>
      </div>
    </div>
    <div
      class="row xproduct-list xgrid_full xgrid_sidebar xgrid-uniform xcontainer-fluid"
    >
      <vitrin-menu-component-vue></vitrin-menu-component-vue>
    </div>
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
        <!-- xclass="grid grid-rows-2 md:grid-rows-6" -->
        <!-- xxclass="flex flex-wrap" -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          <!-- goodsData: {{ goodsData }} -->
          <!-- <br /> -->
          <!-- goodsLoading: {{ goodsLoading }} -->
          <!-- <vitrin-pagination-component /> -->
          <div
            v-for="i in goodsData.data"
            :key="i.key"
            xclass="col-xs-6 col-sm-4 col-md-3 col-lg-2"
          >
            <vitrin-goods-list-item :i="i" />
            <!-- i: {{ i }} -->
            <!-- {{ i.name }} -->
            <br />
            <br />
          </div>
        </div>

        <!-- <vitrin-pagination-component :inf="goodsData.meta" /> -->

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
// import VitrinGoodsListComponentVue from './VitrinGoodsListComponent.vue'
import VitrinMenuComponentVue from './MenuComponent.vue'
import VitrinPaginationComponent from './PaginationComponent.vue'

// import ff from VitrinMenuComponentVue

import { watchEffect, ref } from 'vue'
import { useRoute } from 'vue-router'

import catalogs from './../../use/catalogs.js'
import goods from './../../use/goods.js'

import VitrinGoodsListItem from './GoodsItemComponent.vue'

const route = useRoute()

// const GoodsLoading = ref(true)

// catsLevelLower
const { loading } = catalogs()

const { goodsLoading, goodsData, loadGoods } = goods()

const stopWatch2 = watchEffect(() => {
  // console.log(1)
  if (loading.value == false) {
    // console.log(2 , loading.value)
    if (route.params.cat_id && route.params.cat_id.length) {
      // console.log(3, route.params.cat_id)
      // const toLevelCatalogs = catsLevelLower(route.params.cat_id)
      // console.log(31, route.params.cat_idtoLevelCatalogs )

      const { catNow } = catalogs()
      catNow.value = route.params.cat_id

      loadGoods(route.params.cat_id, route.params.page)

      // GoodsLoading.value = false
    }
  }
})
</script>

<style scoped></style>
