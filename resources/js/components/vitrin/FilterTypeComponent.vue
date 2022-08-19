<template>
  <div class="text-center pb-6">
    <!-- typesNow: {{ typesNow }} -->
    <!-- <br /> -->
    <!-- types: {{ types }} -->
    <!-- <br /> -->
    <!-- <br /> -->
    <!-- route: {{ route }} -->
    <!-- <br /> -->
    <!-- <br /> -->
    <!-- <br /> -->
    <!-- <br /> -->

    Типы обьявлений:

    <template v-for="i in types" :key="i.str">
      <!-- focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 -->
      <!-- {{ i }} -->

      <router-link
        :to="{
          name: 'cat',
          params: {
            cat_id: route.params.cat_id ?? 0,
            // page: '',
            // show: [ 'sell','buy' ]
          },
        }"
        @click="changeType(i.str)"
                class="px-4 py-1 text-sm font-semibold rounded-full border"
        :class="[
          i.active ? i.buttonClassActive : i.buttonClass,

          // ' hover:bg-' + i.color,
          // ' hover:text-white ',
          // ' hover:border-transparent ',
        ]"

        xclass="hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium"
        xxclass="{
                'bg-blue-300 hover:bg-blue-400 text-gray-800 hover:text-gray-900': li.active === true,
                'bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 border-gray-300': li.active !== true,
              }"
      >
        {{ i.name }} / {{ i.str }}
      </router-link>

      <button
        v-if="false"
        @click="i.active = !i.active"
        class="px-4 py-1 text-sm font-semibold rounded-full border"
        :class="[
          i.active ? i.buttonClassActive : i.buttonClass,

          // ' hover:bg-' + i.color,
          // ' hover:text-white ',
          // ' hover:border-transparent ',
        ]"
      >
        {{ i.name }}
      </button>
    </template>

    <!-- <button class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Message</button>
<button class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Message</button>
<button class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Message</button>
<button class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Message</button> -->
  </div>
</template>

<script setup>
import types_js from './../../use/types.js'
const { types, changeType } = types_js()

// import {
//   // watchEffect, ref,
//   onMounted,
//   // watch
// } from 'vue'
import { useRoute } from 'vue-router'
// import catalogs from '../../use/catalogs.js'

const route = useRoute()
// // console.log(77, route)

// // catsLevelLower
// const {
//   // loadData,
//   // data: allMenu,
//   // loading,
//   // catsLevelLower,
//   // catsLevelLowerStart,
//   leftMenu,
// } = catalogs()

// // onMounted( () => {
// // console.log(99 , route.name);
// //   if( route.name == 'index' ){
// //     console.log(888);
// //    loadData(
// //   0
// //   )
// //   catsLevelLowerStart()
// //   }else{
// //   loadData(
// //     route.params.cat_id && route.params.cat_id > 0 ? route.params.cat_id : 0,
// //   )
// //   }

// //   catsLevelLower(
// //     route.params.cat_id && route.params.cat_id > 0 ? route.params.cat_id : 0,
// //   )

// //   loadData(route.params.cat_id ?? 0 )
// //   catsLevelLower(route.params.cat_id ?? '')
// // })

// // watch:{
// //   $route(to, from) {
// //      console.log(window.location.href);
// //      console.log(this.$route.path);
// //      console.log(this.$route.params);
// //   }
// // }

// // watch(
// //   // () => route.name,
// //   () => route,
// //   async (newId) => {
// //     console.log(123123, route, newId)
// //     // userData.value = await fetchUser(newId)
// //   },
// // )

// // const stopWatch1 = watchEffect(() => {
// //   // leftMenu.value = { 1: 2 }
// //   if (loading.value == false && allMenu.value) {
// //     // leftMenu.value = { 1: 3 }
// //     // }
// //     // if (loading.value == false && allMenu.value && allMenu.value.length ) {
// //     // if (allMenu.value && allMenu.value.length ) {
// //     console.log('watchEffect start calc menu dop')
// //     if (route.name == 'index') {
// //       console.log('00 start');
// //       catsLevelLowerStart()
// //     } else {
// //       console.log('00 11 start');
// //       catsLevelLower(
// //         route.params.cat_id && route.params.cat_id > 0
// //           ? route.params.cat_id
// //           : 0,
// //       )
// //     }
// //     console.log('watchEffect fin')
// //   }
// // })

// onMounted(() => {
//   const to = useRoute()

//   console.log('to', to)

//   const {
//     // loadData,
//     // data: allMenu,
//     // loading,
//     catsLevelLower,
//     catsLevelLowerStart,
//     // leftMenu,
//   } = catalogs()

//   if (to.name == 'index') {
//     //     // console.log('001')
//     //     // @click="catNow='';stepCrumb={}"
//     catsLevelLowerStart()
//   } else {
//     let cat_id = to.params.cat_id ? to.params.cat_id : 0
//     console.log('002', 'cat_id = ', cat_id)
//     catsLevelLower(cat_id)
//   }
// })
</script>

<style scoped>
/* .leftMenu a {
  display: block;
  padding-left: 6px;
  padding-top: 3px;
  padding-bottom: 3px;
  background-color: rgba(0, 0, 255, 0.1);
  margin-bottom: 1px;
}
.leftMenu a:hover {
  background-color: rgba(0, 0, 255, 0.2);
} */
</style>
