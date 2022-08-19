<template>
  <div v-if="typeName != ''" 
    :class="{ 
      'bg-blue-500':type == 'sell' ,
      'bg-green-500':type == 'buy' ,
      'bg-orange-500':type == 'renta' ,
      'bg-red-500':type == 'need_renta' ,
      }"
  class="text-xs text-white px-3 py-1 typeInfo">{{ typeName }}</div>
</template>

<script setup>
import {
  ref,
  // watch, watchEffect
} from 'vue'

const typeName = ref('')

const props = defineProps({
  type: String,
})

if (props.type == 'sell') {
  typeName.value = 'Продаю'
} else if (props.type == 'buy') {
  typeName.value = 'Куплю'
} else if (props.type == 'renta') {
  typeName.value = 'Сдаю в аренду'
} else if (props.type == 'need_renta') {
  typeName.value = 'Возьму в аренду'
}
</script>

<style>
.typeInfo{
  position: absolute;
  top: 0;
  right: 10px;
}
</style>
