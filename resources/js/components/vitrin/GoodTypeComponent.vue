<template>
  <div
    v-if="t.name != ''"
    :class="[t.bg]"
    class="text-xs text-white px-3 py-1 typeInfo"
  >
    {{ t.name }}
  </div>
</template>

<script setup>
import {
  ref,
  // watch, watchEffect
} from 'vue'

import types_js from './../../use/types.js'
const { types, typesNow } = types_js()

const typeName = ref('')

const props = defineProps({
  type: String,
})

const t = types.value.find((i) => i.str == props.type)

// if (props.type == 'sell') {
//   typeName.value = 'Продаю'
// } else if (props.type == 'buy') {
//   typeName.value = 'Куплю'
// } else if (props.type == 'renta') {
//   typeName.value = 'Сдаю в аренду'
// } else if (props.type == 'need_renta') {
//   typeName.value = 'Возьму в аренду'
// }
</script>

<style>
.typeInfo {
  position: absolute;
  top: 0;
  right: 10px;
}
</style>
