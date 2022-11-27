<template>
  <div class="text-lg">
    <!-- i.id {{ i.id }} -->
    <router-link
      :to="{
        name: 'good',
        params: {
          good_id: i.id,
        },
      }"
    >
      <div
        v-if="i.image[0]['uri']"
        class="preview text-center"
        :style="'background-image: url(' + i.image[0]['uri'] + ');'"
        style="position: relative;"
      >
        <span class="flex items-center">
          <good-type-component :type="i.type" />
          <img :src="i.image[0]['uri']" loading="lazy" alt="" />
        </span>
      </div>
    </router-link>
    
    <div v-if="catName != ''" class="color-gray">{{ catName }}</div>
    <b>{{ i.name }}</b>
    <br />
    <!-- {{ i.opis }} -->
    <VueNumberFormat
      v-model:value="i.price"
      :options="{
        precision: 0,
        prefix: '',
        suffix: ' ₽',
        decimal: '0',
        thousand: '`',
        acceptNegative: false,
        isInteger: false,
      }"
    ></VueNumberFormat>
    <div v-if="true">
      <!-- <Br/> -->
      <!-- {{ i.price }} -->
      <br />
      <br />
      <button @click="showAr = !showAr" class="text-xs">инфо</button>
      <small v-if="showAr">
        {{ i }}
      </small>
    </div>
  </div>
</template>

<script setup>
import {
  ref,
  // watch, watchEffect
} from 'vue'

// import cart from './../../use/cart.js'

import { useRoute } from 'vue-router'
const route = useRoute()

// import catalogs from './../../use/catalogs.js'
// import { searchCat } from catalogs()

import catalogs from './../../use/catalogs.js'
const { searchCat } = catalogs()

import VueNumberFormat from 'vue-number-format'
import GoodTypeComponent from './GoodTypeComponent.vue'

const props = defineProps({
  i: Object,
})

const catName = ref('')

if (route.params.cat_id != props.i.cat_id) {
  let cc = searchCat(props.i.cat_id)
  // console.log('searchCat', cc)
  catName.value = cc.name
}

const showAr = ref(false)

// const {
//   // // товары в корзине a_id = quantity
//   // cartAr,
//   // добавляем
//   cartAdd,
//   // // отнимаем
//   // cartMinus,
//   // поиск есть или нет товар в корзине
//   goodInCart,
// } = cart()

// const goodAdd = () => {
//   if (props.i.id && props.i.id.length) {
//     cartAdd(props.i)
//     // console.log( 778 , props.i.id)
//   }
// }

// // watch: { '$route': function (value) { } }
// // watchEffect()
</script>

<style scoped>
/* h3 {
  font-size: 1.2em;
}
.item0{
  margin-top: 1vh;
  margin-bottom: 1vh;
}
.manufacturer{ color: rgb(41,59,119) } */
.preview {
  background-size: cover;
  xvertical-align: middle;
  background-position: center center;
  /* max-width: 300px; */
}
.preview span {
  background-color: rgba(255, 255, 255, 0.8);
  min-height: 150px;
}
.preview span img {
  max-height: 200px;
}
</style>
