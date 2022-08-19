import { ref } from 'vue'

const types = ref([{
        str: 'sell',
        name: 'Продаю',
        buttonClass: 'text-blue-500 border-gray-500',
        buttonClassActive: 'text-blue-700 border-blue-500 bg-blue-200',
        bg: 'text-blue-700 bg-blue-200',
        active: false,
    },
    {
        str: 'buy',
        name: 'Ищу купить',
        buttonClass: 'text-green-500 border-gray-500',
        buttonClassActive: 'text-green-700 border-green-500 bg-green-200',
        bg: 'text-green-700 bg-green-200',
        active: false,
    },
    {
        str: 'renta',
        name: 'Сдаю в аренду',
        buttonClass: 'text-orange-500 border-gray-500',
        buttonClassActive: 'text-orange-700 border-orange-500 bg-orange-200',
        bg: 'text-orange-700 bg-orange-200',
        active: false,
    },
    {
        str: 'need_renta',
        name: 'Ищу взять в аренду',
        buttonClass: 'text-red-500 border-gray-500',
        buttonClassActive: 'text-red-700 border-red-500 bg-red-200',
        bg: 'text-red-700 bg-red-200',
        active: false,
    },
])

const typesNow = ref([])

// import lodash from 'lodash'
// const changeType = _.throttle(
//     function(typeStr) {
//         types.value.forEach(function(item, i, arr) {
//             if (item.str == typeStr) {
//                 item.active = !item.active
//             }
//         })
//         setTypesNow()
//     },
//     2000, { trailing: false },
// )

const changeType = (typeStr) => {
    let colvoActive = types.value.filter((i) => i.active == true)

    types.value.forEach(function(item, i, arr) {
        if (item.str == typeStr) {
            item.active = !item.active
        }
    })
    setTypesNow()
}

const clearType = () => {
    types.value.forEach(function(item, i, arr) {
        item.active = false
    })
    setTypesNow()
}

const changeType2 = (typeStr) => {
    // console.log('changeType()', typeStr)
    // let colvoActive = types.value.filter((i) => i.active == true)

    // // если все выбраны и мы кликаем по типу (он включается, остальные выключаются)
    // if (colvoActive.length == types.value.length) {
    //     types.value.forEach(function(item, i, arr) {
    //         if (item.str != typeStr) {
    //             item.active = !item.active
    //         }
    //     })
    // } else {
    //     let colvoActive1 = types.value.filter(
    //         (i) => i.str == typeStr && i.active == true,
    //     )

    //     // если выбран 1 и это тот по кому кликнули = включаем все
    //     if (colvoActive.length == 1 && colvoActive1.length == 1) {
    //         types.value.forEach(function(item, i, arr) {
    //             // if (item.str != typeStr) {
    //             item.active = true
    //                 // }
    //         })
    //     }
    //     // иначе просто изменяем состояние нажатой кнопочки
    //     else {
    types.value.forEach(function(item, i, arr) {
            if (item.str == typeStr) {
                item.active = !item.active
            }
        })
        //     }
        // }
    setTypesNow()
}

/**
 * обновление typesNow
 */
const setTypesNow = () => {
    typesNow.value = []
    types.value.forEach(function(it, i, arr) {
        if (it.active === true) {
            typesNow.value.push(it.str)
        }
    })
}

// import lodash from 'lodash'
// const setTypesNow = _.throttle(
//     function() {
//         typesNow.value = []
//         types.value.forEach(function(it, i, arr) {
//             if (it.active === true) {
//                 typesNow.value.push(it.str)
//             }
//         })
//     },
//     2000, { trailing: false },
// )

export default function types_js() {
    return {
        clearType,
        typesNow,
        types,
        changeType,
    }
}