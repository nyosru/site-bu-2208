import { ref } from 'vue'

import axios from 'axios'

// import { useRoute } from 'vue-router'
// const route = useRoute()

// данные что загрузил
const data = ref([])
    // загрузка идёт или нет
const loading = ref(true)

// тащим все вложенные каталоги в указанный
const catsInner = (parent_id) => {
    return data.value.filter(function(e) {
        return e.cat_up_id == parent_id
    })
}

const leftMenu = ref([])

/**
 * тащим все вложенные каталоги в указанный
 */
const catsLevelLower = (id = '') => {
    const res = data.value.filter(function(e) {
        return e.cat_up_id == id
    })

    if (res.length) {
        leftMenu.value = res
    }
}

const catsLevelLowerStart = () => {
    // console.log('catsLevelLowerStart' , data.value)

    if (data.value.length == 0) {
        loadData()
    }

    if (data.value.length > 0) {
        const res = data.value.filter(function(e) {
            return !e.cat_up_id
        })

        if (res.length) {
            leftMenu.value = res
                // console.log('catsLevelLowerStart 00 fin', 'результатов:', res.length)
        }
    }
}

// показ или скрыть меню главное
const showMenu = ref(false)

// каталог сейчас (для крошек)
const catNow = ref('')

const loadData = async(cat_id = null) => {
    // console.log('modules menu load = loadData()', cat_id)

    let d = new Date()
    const now_d = d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate()

    if (
        localStorage.cats &&
        localStorage.cats.length > 0 &&
        localStorage.cats_date &&
        localStorage.cats_date == now_d
    ) {
        data.value = JSON.parse(localStorage.cats)
        loading.value = false

        // console.log('cash 77',localStorage.cats)
        // console.log('cat', 'cash 77')
        catsLevelLower(cat_id > 0 ? cat_id : null)
            // console.log('cat', 'cash 77 2')
    } else {
        localStorage.cats_date = now_d
        await axios
            .get('/api/catalog')
            .then((response) => {
                data.value = response.data.data
                localStorage.cats = JSON.stringify(response.data.data)
                loading.value = false
                catsLevelLower(cat_id > 0 ? cat_id : null)
            })
            .catch((error) => {
                // console.log(error)
                // this.errored = true;
            })
    }
}


const stepCrumb = ref([])

// const goToIndex = () => {
//   console.log('const goToIndex')
//   stepCrumb.value = []
//   catNow.value = ''
//   // catsLevelLowerStart()
//   // catsLevelLower('')
// }

export default function catalogs() {
    return {
        catNow,
        // goToIndex,
        // список шагов для хлебных крошек
        stepCrumb,

        // конфиг загружаемой инфы
        // cfg,
        // данные что загрузил
        data,

        // показываем или нет меню
        showMenu,

        // отфильтрованный список
        // data_filtered,
        // загрузка идёт или нет
        loading,
        // какой модуль сейчас загружен
        // loading_module_now,
        // ф загрузка данных
        //  loadData = (module, db_connection = 'out') => {
        loadData,
        // показ массива с вложенными каталогами, на входе id родителя
        catsInner,
        // тащим все вложенные каталоги в указанный
        // catsLevelLower(id)
        catsLevelLower,
        catsLevelLowerStart,
        // левое меню
        leftMenu,
        //
        catNow,
    }
}