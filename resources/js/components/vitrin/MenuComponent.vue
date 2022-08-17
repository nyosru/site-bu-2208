<template>

<!-- <div>
  leftMenu: {{ leftMenu }}
</div> -->

  <div v-if="leftMenu && leftMenu.length" class="row">
    <div class="text-center mb-5 leading-8">
      <template v-for="v in leftMenu" :key="v.id">
        <router-link
          :to="'/cat/' + v.id"
          class="text-base mr-3 px-2 py-1 whitespace-nowrap"
          :class="{ 'bg-green-300': route.params.cat_id == v.id }"
        >
          {{ v.name }}
        </router-link>
        &nbsp;
      </template>
    </div>
  </div>
</template>

<script setup>
// import { watchEffect, ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import catalogs from './../../use/catalogs.js'

const route = useRoute()
// console.log(77, route)

// catsLevelLower
const {
  // loadData,
  // data: allMenu,
  // loading,
  // catsLevelLower,
  // catsLevelLowerStart,
  leftMenu,
} = catalogs()

// onMounted( () => {
// console.log(99 , route.name);
//   if( route.name == 'index' ){
//     console.log(888);
//    loadData(
//   0
//   )
//   catsLevelLowerStart()
//   }else{
//   loadData(
//     route.params.cat_id && route.params.cat_id > 0 ? route.params.cat_id : 0,
//   )
//   }

//   catsLevelLower(
//     route.params.cat_id && route.params.cat_id > 0 ? route.params.cat_id : 0,
//   )

//   loadData(route.params.cat_id ?? 0 )
//   catsLevelLower(route.params.cat_id ?? '')
// })

// watch:{
//   $route(to, from) {
//      console.log(window.location.href);
//      console.log(this.$route.path);
//      console.log(this.$route.params);
//   }
// }

// watch(
//   // () => route.name,
//   () => route,
//   async (newId) => {
//     console.log(123123, route, newId)
//     // userData.value = await fetchUser(newId)
//   },
// )

// const stopWatch1 = watchEffect(() => {
//   // leftMenu.value = { 1: 2 }
//   if (loading.value == false && allMenu.value) {
//     // leftMenu.value = { 1: 3 }
//     // }
//     // if (loading.value == false && allMenu.value && allMenu.value.length ) {
//     // if (allMenu.value && allMenu.value.length ) {
//     console.log('watchEffect start calc menu dop')
//     if (route.name == 'index') {
//       console.log('00 start');
//       catsLevelLowerStart()
//     } else {
//       console.log('00 11 start');
//       catsLevelLower(
//         route.params.cat_id && route.params.cat_id > 0
//           ? route.params.cat_id
//           : 0,
//       )
//     }
//     console.log('watchEffect fin')
//   }
// })


</script>

<style scoped>
.leftMenu a {
  display: block;
  padding-left: 6px;
  padding-top: 3px;
  padding-bottom: 3px;
  background-color: rgba(0, 0, 255, 0.1);
  margin-bottom: 1px;
}
.leftMenu a:hover {
  background-color: rgba(0, 0, 255, 0.2);
}
</style>
