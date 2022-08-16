<template>
  <!-- <div class="container mx-auto"> -->
  <!-- <nav      class="relative w-full flex flex-wrap items-center justify-between py-3 bg-gray-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg navbar navbar-expand-lg navbar-light" > -->
  <!-- <div class="container-fluid w-full flex flex-wrap items-center justify-between px-6" > -->
  <!-- <nav class="bg-grey-light rounded-md w-full" aria-label="breadcrumb"> -->

  <div class="container mx-auto">
    <div
      class="relative w-full pl-5 py-3 bg-gray-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg navbar navbar-expand-lg navbar-light"
    >
      <ul class="other-link-sub xpull-right">
        <li>
          <router-link :to="{ name: 'index' }" title="">
            Витрина
          </router-link>
        </li>

        <template v-for="n in stepCrumb">
          <li :key="n.id" v-if="n.name">
            <span v-if="n.type == 'page'">{{ n.name }}</span>
            <router-link
              v-else
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
import { useRoute } from 'vue-router'
import { ref, watchEffect } from 'vue'
import catalogs from './../use/catalogs.js'

const route = useRoute()
const stepCrumb = ref([])

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

      // console.log(11, c1.cat_up_id)
      // console.log( 11 , c1 , c1.value );

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

      // console.log(
      //   // alert(
      //   '2222',
      //   1,
      //   c1 ?? 'x1',
      //   2,
      //   c2 ?? 'x2',
      //   3,
      //   c3 ?? 'x3',
      //   4,
      //   c4 ?? 'x4',
      //   5,
      //   c5 ?? 'x5',
      //   // data,
      //   stepCrumb.value,
      // )

      return [c5 ?? [], c4 ?? [], c3 ?? [], c2 ?? [], c1 ?? []]
    }
  }
  // return []
}

const { catNow } = catalogs()

const nowPage = ref([])

import page from './../use/page.js'
const { whatThisPage } = page()

const stopWatch = watchEffect(() => {
  // const { loading, data } = catalogs()
  // route.params.cat_id
  // console.log(77, data, route.params.cat_id)

  // if( route.params.cat_id && route.params.cat_id.length ){
  // stepCrumb.value = getStepCats(route.params.cat_id)
  // }

  // console.log(
  //   'route',
  //   11,
  //   // route,
  //   route.name,
  //   route.params,
  //   route.params.good_id,
  //   route.params.cat_id,
  //   route.params.page,
  // )

  stepCrumb.value = []

  if (route.name == 'page') {
    nowPage.value = whatThisPage(route.params.id)

    // console.log({ type: 'page', head: route.params.id, a_id: route.params.id })
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
  } else if (catNow.value && catNow.value.length) {
    stepCrumb.value = getStepCats(catNow.value)
  }

  // console.log(77, stepCrumb ?? 'xx' )
  // async newId => {
  //   userData.value = await fetchUser(newId)
  // }
})
</script>

<style scoped>
.container {
  padding-top: 15px;
  padding-bottom: 25px;
}
</style>
