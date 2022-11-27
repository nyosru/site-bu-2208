<template>
  <div class="text-center pb-6 leading-9">
    Типы обьявлений:
    <template v-for="i in types" :key="i.str">
      <router-link
        :to="{
          name: 'cat',
          params: {
            cat_id: route.params.cat_id ?? 0,
          },
        }"
        @click="loadGoodsFirstCancel(); changeType(i.str)"
        class="px-4 py-1 text-sm font-semibold rounded-full border whitespace-nowrap"
        :class="[i.active ? i.buttonClassActive : i.buttonClass]"
      >
        {{ i.name }} 00
        <!-- / {{ i.str }} -->
      </router-link> &nbsp;
    </template>
  </div>
</template>

<script setup>
import types_js from './../../use/types.js'
const { types, changeType } = types_js()

import goods from './../../use/goods.js'
const { loadGoodsFirstCancel } = goods()

import { useRoute } from 'vue-router'
const route = useRoute()
</script>

<style scoped></style>
