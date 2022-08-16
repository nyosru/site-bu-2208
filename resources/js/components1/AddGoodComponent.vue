<template>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 text-center">
      <h2>Форма добавления обьявления</h2>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <form>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">
            Email address
          </label>
          <input
            type="email"
            class="form-control"
            id="exampleInputEmail1"
            aria-describedby="emailHelp"
          />
          <div id="emailHelp" class="form-text">
            We'll never share your email with anyone else.
          </div>
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="exampleInputPassword1"
          />
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="exampleCheck1" />
          <label class="form-check-label" for="exampleCheck1">
            Check me out
          </label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</template>

<script setup>
// import catalogs from './../use/catalogs.ts'
// import goods from '../use/goods.js'
// import cart from '../use/cart.js'

// // import VitrinGoodsListItem from './VitrinGoodsListItemComponent.vue'
// // import OpenGraph from './phpcat/OpenGraphComponent.vue'

// import {
//   ref,
//   watchEffect,
//   // onMounted
// } from 'vue'
// import { useRoute } from 'vue-router'

// import VitrinGoodsListItem from './VitrinGoodsListItemComponent.vue'
// // import GoodAnalogi from './GoodDownTabAnalogi0Component.vue'
// // import GoodSellAuptoparts from './GoodSellAuptopartsComponent.vue'
// import GoodDownComponent from './GoodDownComponent.vue'

// const route = useRoute()
// const goodQuantity = ref(1)

// const props = defineProps({
//   // показывать или нет заказ с удалённого склада
//   showOrdersOnSklad: Boolean,
// })

// // onMounted(() => {
// //   goodQuantity.value = 1
// // })

// // catsLevelLower
// // const { loading } = catalogs()

// const { goodLoading, goodData, loadGood } = goods()

// // загрузка товара
// const stopWatch3 = watchEffect(() => {
//   if (route.params.good_id && route.params.good_id.length) {
//     loadGood(route.params.good_id)
//     // console.log(880, route.params.good_id)
//   }
// })

// // показ списка каталогов по загруженному товару
// const stopWatch4 = watchEffect(() => {
//   if (goodData.value.a_categoryid && goodData.value.a_categoryid.length) {
//     const { catNow } = catalogs()
//     catNow.value = goodData.value.a_categoryid
//     // console.log(882, goodData.value.a_categoryid)
//   }
// })

// const {
//   // товары в корзине a_id = quantity
//   cartAr,
//   // cartArGoods,
//   // добавляем
//   cartAdd,
//   // отнимаем
//   cartMinus,
//   // поиск есть или нет товар в корзине
//   goodInCart,
// } = cart()

// const goodAdd = () => {
//   if (route.params.good_id && route.params.good_id.length) {
//     // cartAdd(route.params.good_id, goodQuantity.value)
//     cartAdd(goodData.value, goodQuantity.value)
//     console.log(route.params.good_id, goodQuantity.value)
//   }
// }

// const NumberFormat = (num) => {
//   return new Intl.NumberFormat('ru-RU', {
//     style: 'currency',
//     currency: 'RUB',
//     maximumFractionDigits: 0,
//   }).format(num)
//   // return num + ' 777 ';
// }
</script>

<style scoped></style>
