<template>
  <div :class="loading ? 'bg-orange-400' : 'bg-orange-100' ">
    {{ nn }} / {{ loading }} <b>{{ data.jobed ?? 'x' }}</b>
    <!-- echo '<iframe src="http://localhost/api/gg/8/' . (500 * $t) . '" ></iframe> '; -->
    <br />
    <!-- lines_id: -->
    <div style="height: 100px; overflow: auto;">
      {{ data.lines_id ?? 'x' }}
    </div>
  </div>
</template>

<script setup>
import Aframe from './LinesComponent.vue'

const props = defineProps({
  nn: Number,
})

import { ref, watchEffect } from 'vue'

// import page from '../../use/page.js'
// import { useRoute } from 'vue-router'
// import LoaderComponent from '../LoaderComponent.vue'
// // import page_contact from './contact.vue'
// const route = useRoute()
// const { whatThisPage, pageLoad, pageLoading, pageData, pageError } = page()

// import lines from './lines.js'
// const { load, loading, data } = lines()

// const nowPage = ref([])
// const nowMod = ref('')
// // const stopWatch5 = watchEffect(() => {
// //   nowMod.value = markRaw('page_'+route.params.id)
// // })

const loading = ref(false)
const data = ref([])

// // const loadData = (module, db_connection = 'out') => {
const load = (uri) => {
  // goodsData.value = []
  // goodsLoading.value = true
  loading.value = true

  // if (page > 0) {
  //     page0.value = 'page=' + page
  //     page01.value = page
  // } else {
  //     page0.value = ''
  //     page01.value = 0
  // }

  // if (typesNow.value && typesNow.value.length) {
  //     page0.value += '&type[]=' + typesNow.value.join('&type[]=')
  // }

  // window.scrollTo(0,0)
  axios
    .get(
      uri,
      // '/api/goodscat/' +
      // cat_id +
      // (page0.value.length > 0 ? '?' + page0.value : ''),
      // , { signal: controller.signal }
    )
    .then((response) => {
      loading.value = false
      console.log('get_datar', response.data)
      data.value = response.data
      // data.value = items_now_loading.value;

      // // data_filtered.value =
      // goodsData.value = response.data
      //     // localStorage.cats = JSON.stringify(response.data.data)
      //     // cfg.value = response.data.cfg;
      // goodsLoading.value = false
      //     // return response.data;
      //     // items_loading.value = dfalse
      // window.scrollTo(0, 0)
    })
    .catch((error) => {
      console.log(error)
      // this.errored = true;
    })
}

const stopWatch = watchEffect(() => {
  if (loading.value == false) {
    setTimeout(() => {
      if (loading.value == false) {
        load('/api/gg/7/' + props.nn + '/json')
      }
    }, 2000)
  }
  //   if (route.name == 'page') {
  //     nowPage.value = whatThisPage(route.params.id)
  //     pageLoad(route.params.id)
  //   }
})

// load('/api/gg/7/' + props.nn + '/json')
load('/api/gg/17/' + props.nn + '/json')
</script>

<style></style>
