<template>
  <div>
    <img
      v-if="lookImg && lookImg.length"
      class="block align-middle"
      style="max-height 60vh;"
      :src="lookImg"
      alt=""
    />
    <img
      v-else-if="g.image[0]['uri'].length"
      style="max-height 60vh;"
      class="block align-middle"
      :src="g.image[0]['uri']"
      alt=""
    />
  </div>

  <div
    class="whitespace-nowrap overflow-auto"
    style="width: 100%; height: 95px;"
  >
    <template v-for="im in g.image" :key="im.id">
      <img
        :src="im['uri']"
        alt=""
        style="height: 80px;"
        class="pr-1 hover:border-blue-500 border-white border-2"
        xmouseover="lookImg = im['uri']"
        @click="lookImg = im['uri']"
      />
    </template>
  </div>
</template>

<script setup>
// import VueNumberFormat from 'vue-number-format'

// // import {
// //   ref,
// //   // watch, watchEffect
// // } from 'vue'
// // // import cart from './../../use/cart.js'

import { ref, watchEffect } from '@vue/runtime-core'
// import { useRoute } from 'vue-router'
// const route = useRoute()

const props = defineProps({
  g: Object
})

// import goods from '../../use/goods.js'
// const { loadGood, goodData: g, goodLoading } = goods()

// import catalogs from '../../use/catalogs.js'
// const { catNow } = catalogs()

// const watchStop5 = watchEffect(() => {
//   // console.log('route.params.good_id', route.params.good_id ?? 'x')
//   if (route.params.good_id) {
//     loadGood(route.params.good_id)
//   }
// })

// // товар загрузился подгружаем всё остальное
// const watchStop51 = watchEffect(() => {
//   // каталоги
//   catNow.value = g.value.cat_id
// })

// const watchStop51 = watchEffect(() => {
//   // console.log('route.params.good_id',route.params.good_id ?? 'x');
//   if (
//     !goodLoading &&
//     goodData.value &&
//     goodData.value.length &&
//     goodData.value.cat_id
//   ) {
//     const { catNow } = catalogs()
//     // loadGood(route.params.good_id)
//     catNow.value = goodData.value.good_id
//   }
// })

const lookImg = ref('')
</script>

<style>
.big-img {
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
}
.big-img > div {
  background-color: rgba(255, 255, 255, 0.7);
  xdisplay: inline-flex;
}
</style>
