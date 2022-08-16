<template>
  <!-- This example requires Tailwind CSS v2.0+ -->
  <div
    v-if="inf && inf.links"
    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
  >
    <div class="flex-1 flex justify-between sm:hidden">
      <a
        href="#"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Previous
      </a>
      <a
        href="#"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Next
      </a>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <!-- <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">1</span>
          to
          <span class="font-medium">10</span>
          of
          <span class="font-medium">97</span>
          results
        </p> -->
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <template v-for="li in inf.links" :key="li.label">
            <span
              v-if="li.label === '...'"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
            >
              ...
            </span>

            <router-link
              v-else-if="li.label > 0 && li.label < 999"
              :to="{
                name: 'cat',
                params: {
                  cat_id: cat_id_now,
                  page: li.label != 1 ? li.label : '',
                },
              }"
              class="hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium"
              :class="{
                'bg-blue-300 hover:bg-blue-400 text-gray-800 hover:text-gray-900': li.active === true,
                'bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 border-gray-300': li.active !== true,
              }"
            >
              {{ li.label }}
            </router-link>
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
const route = useRoute()

const cat_id_now = route.params.cat_id ?? ''

const props = defineProps({
  inf: Object,
})

// console.log(223, props , props.inf)
</script>

<style scoped></style>
