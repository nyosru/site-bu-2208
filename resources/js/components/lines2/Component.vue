<template>
  <div class="container">
    <main class="pt-2 min-h-full">
      <!-- шаги
      <button
        @click="rounding = !rounding"
        class="xinline-block xcursor-pointer pb-0.5 px-1 ml-1 rounded-lg black"
        :class="{
          'bg-green-200': rounding,
          'bg-red-200': !rounding,
        }"
      >
        <span v-if="rounding">крутим</span>
        <span v-else>не крутим</span>
      </button> -->

      <div class="flex items-center justify-center">
        <a
          href="/lines2"
          type="button"
          class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 transition ease-in-out duration-150 xcursor-not-allowed"
        >
          reload
        </a>
        <button
          @click="rounding = !rounding"
          type="button"
          class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white transition ease-in-out duration-150 xcursor-not-allowed"
          xdisabled=""
          :class="{
            'bg-green-500': rounding,
            'hover:bg-green-400': rounding,
            'bg-orange-300': !rounding,
          }"
        >
          <svg
            class="-ml-1 mr-3 h-5 w-5 text-white"
            :class="{ 'animate-spin': rounding }"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          <!-- Обработка... -->
          <span v-if="rounding">крутим</span>
          <span v-else>не крутим</span>
        </button>
      </div>

      <br />
      <br />
      nowFunct: {{ nowFunct }}
      <br />
      <br />

      <div class="grid grid-cols-4 gap-1">
        <div class="col-span-3">
          <div class="grid grid-cols-4 gap-1">
            <template v-for="s in steps" :key="s.id">
              <div class="ee py-1 px-3" :class="{ 'bg-green-200': s.active }">
                {{ s.id }} {{ s.name }}
                <br />
                <small>
                  статус:
                  <span v-if="s.active">ON</span>
                  <span v-else>OFF</span>
                </small>
              </div>
              <div
                class="py-1 px-3 col-span-3"
                :class="{ 'bg-green-200': s.active }"
              >
                <template v-if="s.active && s.id == 3">
                  обработано линий: {{ obr_pr }} %
                </template>

                {{ s.link }}
                <br />
                {{ s }}
                <!-- {{ s.active }} -->
              </div>
            </template>
          </div>
        </div>
        <div>
          место для картинки
          <br />
          showImage: {{ showImage }}
          <br />
          <img
            :src="showImage"
            xwidth="100%"
            alt="11"
            style="border: 1px solid gray;"
          />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
// import Aframe from './LinesFrameComponent.vue'

import { ref } from '@vue/reactivity'
import { watchEffect } from '@vue/runtime-core'

import lines from './lines.js'
const {
  waveRun,
  steps,
  rounding,
  nowFunct,
  max,
  Start,
  Fin,
  reruneAction,
  setAction,

  action1,
  action2,
  action3,
  action4,
  action5,

  finGood,

  showImage,
  obr_pr,
} = lines()

setInterval(() => {
  // console.log('--')

  if (rounding.value == true) {
    // console.log('7-1', nowFunct.value, max.value, Start.value, Fin.value)

    if (Start.value === false && Fin.value === false) {
      // nowFunct.value++
      Start.value = true
      startJob()
    } else if (Fin.value === true) {
      // nowFunct.value++
      Start.value = false
      Fin.value = false
    } else if (Start.value === true) {
      // nowFunct.value++
      // Start.value = false
      // Fin.value = true
      console.log('job', nowFunct.value)
    }

    // console.log('7-2', nowFunct.value, max.value, Start.value, Fin.value)

    // do something expensive ...
    // console.log('777')
    // showmessage()
  }
}, 1000)

const startJob = () => {
  if (nowFunct.value >= max.value) {
    nowFunct.value = 1
  } else {
    nowFunct.value++
  }

  // console.log('start job', nowFunct.value)
  // setTimeout(a1finf, 5000)
}

// const a1fin = ref(false)

// const a1 = () => {
//   setTimeout(a1finf, 5000)
// }
const a1finf = () => {
  Fin.value = true
}

const stopWatch2 = watchEffect(() => {
  // steps.value[nowFunct.value]['active'] = true
  setAction(nowFunct.value)

  if (reruneAction.value == true) {
    reruneAction.value = false
  }

  if (nowFunct.value == 1) {
    action1()
  } else if (nowFunct.value == 2) {
    action2()
  } else if (nowFunct.value == 3) {
    action3()
  } else if (nowFunct.value == 4) {
    action4()
  } else if (nowFunct.value == 5) {
    action5()
  }

  // // console.log(1)
  // if (loading.value == false) {
  //   // console.log(2 , loading.value)
  //   if (route.params.cat_id && route.params.cat_id.length) {
  //     // if ( typesNow.value && typesNow.value.length ) {
  //     console.log(typesNow.value)
  //     const { catNow } = catalogs()
  //     catNow.value = route.params.cat_id
  //     loadGoods(route.params.cat_id, route.params.page)
  //     // }
  //   }
  // }
})

// const a2 = () => {}
// const a3 = () => {}
// const a4 = () => {}

// // var interval_id = setInterval('showmessage', 1000)

// let ee = 1
// const showmessage = () => {
//   console.log('Прошла одна секунда ' + ee)
//   if (ee == 1) ee = 2
//   if (ee == 2) ee = 3
//   if (ee == 3) ee = 1
// }

// // clearInterval(interval_id)

// // import { ref, watchEffect } from 'vue'
// // import page from '../../use/page.js'

// // const { whatThisPage, pageLoad, pageLoading, pageData, pageError } = page()

// // import { useRoute } from 'vue-router'

// // import LoaderComponent from '../LoaderComponent.vue'
// // // import page_contact from './contact.vue'

// // const route = useRoute()

// // const nowPage = ref([])
// // const nowMod = ref('')

// // // const stopWatch5 = watchEffect(() => {
// // //   nowMod.value = markRaw('page_'+route.params.id)
// // // })

// // const stopWatch = watchEffect(() => {
// //   if (route.name == 'page') {
// //     nowPage.value = whatThisPage(route.params.id)
// //     pageLoad(route.params.id)
// //   }
// // })
</script>

<style>
.links a {
  color: blue;
  text-decoration: underline;
}
</style>
