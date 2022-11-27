<template>
  <div class="container mx-auto">
    <div
      style="z-index: -1;"
      class="relative w-full pl-5 py-3 bg-gray-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg navbar navbar-expand-lg navbar-light"
    >
      <ul class="other-link-sub xpull-right">

        <li>
          <router-link :to="{ name: 'index' }" @click="catNow = ''" title="">
            Витрина.
          </router-link>
        </li>

        <template v-for="n in stepCrumb">
          <li :key="n.id" v-if="n.name">
            <!-- <span v-if="n.type == 'page'">{{ n.name }}</span> -->
            <router-link
              xv-else
              :to="{
                name: n.type ?? 'cat',
                params: { cat_id: n.id },
              }"
            >
              {{ n.name }}
            </router-link>
          </li>
        </template>

      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
const nowPage = ref([])

import { useRoute } from 'vue-router'
const route = useRoute()

import catalogs from './../use/catalogs.js'
const { catNow, stepCrumb } = catalogs()

import page from './../use/page.js'
const { whatThisPage } = page()

// тащим инфу по каталогам что выше текущего
function getStepCats(cat_id) {
  const { loading, data } = catalogs()

  if (route.name == 'page') {
    // return [['name' > 'ppage'], []]
  } else {
    if (loading.value == false) {
      const c1 = data.value.find((el) => el.id == cat_id)
      let c2 = []
      let c3 = []
      let c4 = []
      let c5 = []

      if (c1.cat_up_id && c1.cat_up_id > 0) {
        c2 = data.value.find((el) => el.id == c1.cat_up_id)

        if (c2.cat_up_id && c2.cat_up_id > 0) {
          c3 = data.value.find((el) => el.id == c2.cat_up_id)

          if (c3.cat_up_id && c3.cat_up_id > 0) {
            c4 = data.value.find((el) => el.id == c3.cat_up_id)

            if (c4.cat_up_id && c4.cat_up_id > 0) {
              c5 = data.value.find((el) => el.id == c4.cat_up_id)
            }
          }
        }
      }

      return [c5 ?? [], c4 ?? [], c3 ?? [], c2 ?? [], c1 ?? []]
    }
  }
}

const stopWatch = watchEffect(() => {
  // console.log('breadcrumb');

  stepCrumb.value = []

  if (route.name == 'page') {
    nowPage.value = whatThisPage(route.params.id)

    stepCrumb.value.push({
      type: 'page',
      head: nowPage.value.head,
      a_id: nowPage.value.id,
    })
    // console.log(stepCrumb.value)
    window.scrollTo(0, 0)
  } else if (route.name == 'search') {
    stepCrumb.value.push({
      type: 'search',
      head: 'Поиск',
      a_id: 'x',
    })
  } else if (route.name == 'cart') {
    nowPage.value = whatThisPage(route.params.id)

    // console.log({ type: 'page', head: route.params.id, a_id: route.params.id })
    stepCrumb.value.push({
      type: 'cart',
      head: 'Корзина покупок',
      a_id: 'x',
    })
    // console.log(stepCrumb.value)
    window.scrollTo(0, 0)
  } else if (catNow.value && catNow.value > 0) {
    stepCrumb.value = getStepCats(catNow.value)
  }
})
</script>

<style scoped>
.container {
  padding-top: 15px;
  padding-bottom: 25px;
}
</style>
