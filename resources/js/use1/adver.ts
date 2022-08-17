import { ref } from 'vue'

import axios from 'axios'

const items = ref({})
const loading = ref(true)

const load = async () => {
  items.value = {}
  loading.value = true

  let d = new Date()
  const now_d = d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate() + '-' + d.getHours()

  if (
    localStorage.adver &&
    localStorage.adver.length > 0 &&
    localStorage.adver_date &&
    localStorage.adver_date == now_d
  ) {
    items.value = JSON.parse(localStorage.adver)
    loading.value = false
    // console.log('cash 77',localStorage.cats)
    console.log('cat', 'cash 77')
  } else {
    localStorage.adver_date = now_d

    // window.scrollTo(0,0)

    await axios
      .get('/api/adverIndex')
      .then((response) => {
        // console.log("get_datar", response.data);
        // items_loading_module.value = items_now_loading.value;
        // data_filtered.value =

        items.value = response.data.data
        localStorage.adver = JSON.stringify(response.data.data)
        // localStorage.cats = JSON.stringify(response.data.data)
        // cfg.value = response.data.cfg;

        loading.value = false
        // return response.data;
        // items_loading.value = dfalse

        // window.scrollTo(0,0)
      })
      .catch((error) => {
        console.log(error)
        loading.value = false
        // this.errored = true;
      })
  }
}

export default function adver() {
  return {
    load,
    items,
    loading,
  }
}
