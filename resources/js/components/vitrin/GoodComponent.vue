<template>
  <div>
    <div
      v-if="i.image[0]['uri']"
      class="preview text-center"
      :style="'background-image: url(' + i.image[0]['uri'] + ');'"
    >
      <span class="flex items-center">
        <img :src="i.image[0]['uri']" loading="lazy" />
      </span>
    </div>
    <b>{{ i.name }}</b>
    {{ i.opis }}

    <!-- {{ i.image[0]['uri'] }} -->

    <button @click="showAr = !showAr">11</button>
    <small v-if="showAr">
      {{ i }}
    </small>
  </div>
</template>

<script setup>
import {
  ref,
  // watch, watchEffect
} from 'vue'

// import cart from './../../use/cart.js'
// import { useRoute } from 'vue-router'

// const route = useRoute()

const props = defineProps({
  i: Object,
})

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
