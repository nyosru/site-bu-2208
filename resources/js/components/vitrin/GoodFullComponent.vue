<template>
  <div class="text-lg">
    <div class="flex flex-row px-1 md:pr-3">
      <div class="basis-2/3 border-3 border-solid border-red-500">
        <good-full-image-component :g="g" />
      </div>
      <!-- правый блок -->
      <div class="basis-1/3 align-top border-3 border-solid border-red-500">
        <template v-if="goodLoading">
          <div
            class="border border-blue-300 shadow rounded-md p-4 max-w-sm w-full mx-auto"
          >
            <div class="animate-pulse flex space-x-4">
              <!-- <div class="rounded-full bg-slate-700 h-10 w-10"></div> -->
              <div class="flex-1 space-y-6 py-1">
                <div class="h-2 bg-slate-700 rounded"></div>
                <div class="space-y-3">
                  <div class="grid grid-cols-3 gap-4">
                    <div class="h-2 bg-slate-700 rounded col-span-2"></div>
                    <div class="h-2 bg-slate-700 rounded col-span-1"></div>
                  </div>
                  <div class="h-2 bg-slate-700 rounded"></div>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <b>{{ g.name }}</b>
          <br />
          <br />
          <!-- {{ g.price }} -->
          <VueNumberFormat
            v-model:value="g.price"
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
        </template>
      </div>
    </div>

    <div class="flex flex-row px-1 md:pr-3">
      <div
        class="basis-full border-3 border-solid border-green-500 bg-green-100"
      >
        <h2>Opis</h2>
        <p>{{ g.opis }}</p>
      </div>
    </div>

    <br />
    <br />
    <br />
    <br />
    <br />
    <br />

    <div class="grid grid-cols-3 gap-4 place-content-stretch h-48 ...">
      <div
        class="col-span-2 border-blue-700 border-solid border-1 xbg-cyan-300"
        :style="
          'background-size: cover; background-image: url(' +
          (lookImg ?? '') +
          '); height: 65vh;'
        "
      >
        <div
          class="relative"
          style="height: 65vh; background-color: rgba(255, 255, 255, 0.7);"
        >
          <div
            style="height: 65vh;"
            class="big-img xtext-center xalign-middle overflow-hidden xinline-block xalign-middle grid grid-row place-content-center"
          >
            <!-- <div > -->

            <div>
              <template v-if="goodLoading">
                ... загружаю ...
              </template>

              <template v-else>
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
              </template>
            </div>
            <!-- </div> -->
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
        </div>
      </div>

      <!-- <div class="border-blue-700 border-solid border-1">02</div> -->

      <div class="col-span-2 bg-cyan-300 border-blue-700 border-solid border-1">
        01
      </div>
      <div class="border-blue-700 border-solid border-1">04</div>
    </div>

    <br clear="all" />
    <br clear="all" />
    000001
    <br clear="all" />
    <br clear="all" />

    <div class="flex flex-row px-1 md:pr-3 border-3 border-red-500">
      <div class="basis-2/3"></div>
      <div class="basis-1/3 align-top"></div>
    </div>

    <br clear="all" />
    <br clear="all" />
    000002
    <br clear="all" />
    <br clear="all" />

    <div class="flex flex-row px-1 md:pr-3">
      <div class="basis-2/3">
        <br />
        <br />
        {{ g.opis }}
      </div>
      <div class="basis-1/3">
        111
      </div>
    </div>

    <br clear="all" />
    <br clear="all" />
    0000003
    <br clear="all" />
    <br clear="all" />
    <div style="max-height: 150px; overflow: auto;">
      goodLoading: {{ goodLoading }}
      <Br />
      <Br />
      goodData: {{ g }}
    </div>
  </div>
</template>

<script setup>
import VueNumberFormat from 'vue-number-format'
import GoodFullImageComponent from './GoodFullImageComponent.vue'

// import {
//   ref,
//   // watch, watchEffect
// } from 'vue'
// // import cart from './../../use/cart.js'

import { ref, watchEffect } from '@vue/runtime-core'
import { useRoute } from 'vue-router'
const route = useRoute()

import goods from './../../use/goods.js'
const { loadGood, goodData: g, goodLoading } = goods()

import catalogs from './../../use/catalogs.js'
const { catNow } = catalogs()

const watchStop5 = watchEffect(() => {
  // console.log('route.params.good_id', route.params.good_id ?? 'x')
  if (route.params.good_id) {
    loadGood(route.params.good_id)
  }
})

// товар загрузился подгружаем всё остальное
const watchStop51 = watchEffect(() => {
  // каталоги
  catNow.value = g.value.cat_id
})

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

<style></style>
