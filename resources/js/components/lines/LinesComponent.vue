<template>
  <main class="min-h-full">
    
      <template v-for="ii in 2" :key="ii">
      <div class="w-36 inline-block p-3 m-3 border-gray-700 border-3">
        <aframe :nn="500 * ii" />
        </div>
      </template>
    
  </main>
</template>

<script setup>
import Aframe from './LinesFrameComponent.vue'

// const ii = 1

// import { ref, watchEffect } from 'vue'
// import page from '../../use/page.js'

// import { useRoute } from 'vue-router'

// import LoaderComponent from '../LoaderComponent.vue'
// // import page_contact from './contact.vue'

// const route = useRoute()

// const { whatThisPage, pageLoad, pageLoading, pageData, pageError } = page()

// const nowPage = ref([])
// const nowMod = ref('')

// // const stopWatch5 = watchEffect(() => {
// //   nowMod.value = markRaw('page_'+route.params.id)
// // })

// const stopWatch = watchEffect(() => {
//   if (route.name == 'page') {
//     nowPage.value = whatThisPage(route.params.id)
//     pageLoad(route.params.id)
//   }
// })
</script>

<style>
.links a {
  color: blue;
  text-decoration: underline;
}
</style>
