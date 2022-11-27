import {
    ref,
    //     // reactive, toRefs, readonly,
    //     // watch,
    //     // computed
} from 'vue'

// import axios from 'axios'
// // import Router from '../router';

// // конфиг загружаемой инфы
// // const cfg = ref({})

// // данные что загрузил
// const goodsData = ref({})
// const goodData = ref([])

// // отфильтрованный список
// // const data_filtered = ref({})

// // загрузка идёт или нет
// const goodsLoading = ref(true)
// const goodLoading = ref(true)

// // какой модуль сейчас загружен
// // const loading_module_now = ref('')

// const page0 = ref('')
// const page01 = ref(0)

// import types_js from '../../use/types.js'
// const { types, changeType, typesNow } = types_js()

// const loadAx = ref(false)

// сколько повторов расчётов прошло
const waveRun = ref(0)

const data = ref([])
const loading = ref(false)
const showImage = ref('')

// const uri = 'http://localhost/api/gg/8/' + (500 * $t) + '/json'
// const uri1 = '/api/gg/8/'
// const uri2 = '/json'

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

// const searchString = ref('')
// const searchStringNow = ref('')

// // import { useRouter, useRoute } from 'vue-router'
// // const router = useRouter()
// // const route = useRoute()

// const loadSearchGoods = (search, page = 0) => {
//     goodsData.value = []
//     goodsLoading.value = true

//     if (page > 0) {
//         page0.value = '?page=' + page
//         page01.value = page
//     } else {
//         page0.value = ''
//         page01.value = 0
//     }

//     // window.scrollTo(0,0)

//     axios
//         .post('/api/good' + page0.value, { search })
//         .then((response) => {
//             searchStringNow.value = search
//                 // console.log("get_datar", response.data);
//                 // items_loading_module.value = items_now_loading.value;

//             // data_filtered.value =
//             goodsData.value = response.data
//                 // localStorage.cats = JSON.stringify(response.data.data)
//                 // cfg.value = response.data.cfg;
//             goodsLoading.value = false
//                 // return response.data;
//                 // items_loading.value = dfalse
//             window.scrollTo(0, 0)

//             // let itemsCount = Object.keys(response.data.data).length
//             // console.log('itemsCount', itemsCount, response.data.data[0]['head'])

//             // router.push({
//             //   name: 'good',
//             //   params: { good_id: response.data.data[0]['a_id'] },
//             //   props: { showOrdersOnSklad: true },
//             // })
//         })
//         .catch((error) => {
//             console.log(error)
//                 // this.errored = true;
//         })
// }

const setAction = (id) => {
    // console.log('setAction', id)
    steps.value.forEach(function(item, i, arr) {
        // alert( i + ": " + item + " (массив:" + arr + ")" );
        if (item.id == id) {
            // console.log(999, id );
            item.active = true
        } else {
            item.active = false
        }
        // steps.value]
    })
}

// const loadGood = async(good_id) => {
//     goodData.value = []
//     goodLoading.value = true

//     // window.scrollTo(0,0)

//     await axios
//         .get('/api/good/' + good_id)
//         .then((response) => {
//             // console.log("get_datar", response.data);
//             // items_loading_module.value = items_now_loading.value;

//             // data_filtered.value =

//             goodData.value = response.data.data[0]
//                 // localStorage.cats = JSON.stringify(response.data.data)
//                 // cfg.value = response.data.cfg;

//             goodLoading.value = false
//                 // return response.data;
//                 // items_loading.value = dfalse

//             window.scrollTo(0, 0)
//         })
//         .catch((error) => {
//             console.log(error)
//                 // this.errored = true;
//         })
// }

// const ii = 1
const steps = ref([{
        id: 1,
        name: '6 собираем массив возможных линий',
        // link: 'asdas',
        // active: false,
    },
    {
        id: 2,
        name: 'сохраняем тёмные',
        link: 'asdas4',
        // active: false,
    },
    {
        id: 3,
        name: '17 считаем все линии',
        // link: 'asd5',
        // active: false,
    },
    {
        id: 4,
        name: '20 вытаскиваем самые тёмные линии в список фиксированных линий',
        // link: 'asd5',
        // active: false,
    },
    {
        id: 5,
        name: '5555',
        // link: 'asd5',
        // active: false,
    },
])

const rounding = ref(false)

const nowFunct = ref(0)
const max = ref(5)
const Start = ref(false)
const Fin = ref(false)
const reruneAction = ref(false)

const obr_pr = ref(0)

const action1 = () => {
    // setTimeout(finGood, 5000)
    // http://localhost/api/gg/6

    axios
        .get('/api/gg/6/json')
        .then((response) => {
            console.log('axios', '/api/gg/6/json', response.data)
            Fin.value = true
        })
        .catch((error) => {
            console.log('error', error)
                // this.errored = true;
        })
}

const action2 = () => {
    setTimeout(finGood, 500)
}
const action3 = () => {
    // setTimeout(finGood, 2000)
    // http://localhost

    axios
        .get('/api/gg/17/0/json')
        .then((response) => {
            console.log('axios', '/api/gg/17/0/json', response.data)
            console.log('good', response.data.jobed ? response.data.jobed : 'x')

            obr_pr.value = response.data.pr_obr

            if (response.data.jobed == 0) {
                Fin.value = true
            } else {
                reruneAction.value = true
            }
            // showImage.value = '/api/gg/18/' + Math.random()
        })
        .catch((error) => {
            console.log('error', error)
                // this.errored = true;
        })
}

const action4 = () => {
    // setTimeout(finGood, 500)

    let str_setup = 'json'

    if (waveRun.value == 0) str_setup = str_setup + '-new'

    axios
        .get('/api/gg/20/25/' + str_setup)
        .then((response) => {
            console.log('axios', 'uri', '/api/gg/20/25/' + str_setup)
            console.log('axios', 'uri', 'data', response.data)
                // console.log('good', response.data.jobed ? response.data.jobed : 'x')
                // obr_pr.value = response.data.pr_obr
                // if (response.data.jobed == 0) {
            Fin.value = true
                // } else {
                //     reruneAction.value = true
                // }
                // showImage.value = '/api/gg/18/' + Math.random()
            showImage.value = '/api/gg/18/' + Math.random()
            waveRun.value++
        })
        .catch((error) => {
            console.log('error', error)
                // this.errored = true;
        })
}
const action5 = () => {
    setTimeout(finGood, 500)
}

const finGood = () => {
    console.log('finGood')
    Fin.value = true
}

export default function lines() {
    return {
        // сколько повторов расчётов прошло
        waveRun,

        load,
        loading,
        data,
        // loadGoods,
        // goodsLoading,
        // goodsData,
        // loadGood,
        // goodLoading,
        // goodData,
        // searchString,
        // searchStringNow,
        // loadSearchGoods,

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
    }
}