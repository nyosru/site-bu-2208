import {
    createRouter,
    // createWebHashHistory
    createWebHistory,
} from 'vue-router'

// import { onMounted } from 'vue'
// import { useRoute } from 'vue-router'

import BreadcrumbsComponent from './components/BreadcrumbsComponent.vue'
// import UpBannerComponent from './components/IndexUpBannersComponent.vue'
// import StarterComponent from './components/StarterComponent.vue'
// import VitrinComponent from './components/VitrinComponent.vue'
import VitrinComponent from './components/vitrin/Component.vue'
import MenuComponent from './components/vitrin/MenuComponent.vue'
// import SearchComponent from './components/SearchComponent.vue'
import PageComponent from './components/page/Component.vue'
import CartComponent from './components/Cart/Component.vue'

import GoodComponent from './components/vitrin/GoodFullComponent.vue'

import LinesComponent from './components/lines/LinesComponent.vue'
import Lines2Component from './components/lines2/Component.vue'

// import IndexLineAdver from './components/IndexLineAdverComponent.vue'
// import AddGoodComponent from './components/AddGoodComponent.vue'

const routes = [
    // {
    //     path: '/',
    //     component: () =>
    //         import ('./components/App/AppComponent.vue')
    // },

    {
        path: '/lines',
        name: 'lines',
        components: {
            // BreadcrumbsComponent,
            // MenuComponent,
            // adver: UpBannerComponent,
            // adverList: IndexLineAdver,
            // starter: StarterComponent,
            // page: null,
            // cart: null,
            // vitrin: null,
            vitrin: LinesComponent,
        },
    },

    {
        path: '/lines2',
        name: 'lines2',
        components: {
            // BreadcrumbsComponent,
            // MenuComponent,
            // adver: UpBannerComponent,
            // adverList: IndexLineAdver,
            // starter: StarterComponent,
            // page: null,
            // cart: null,
            // vitrin: null,
            vitrin: Lines2Component,
        },
    },

    {
        path: '/',
        name: 'index',
        components: {
            BreadcrumbsComponent,
            MenuComponent,
            // adver: UpBannerComponent,
            // adverList: IndexLineAdver,
            // starter: StarterComponent,
            // page: null,
            // cart: null,
            // vitrin: null,
            // vitrin: VitrinComponent,
        },
    },

    // текстовая страница
    {
        path: '/page/:id',
        name: 'page',
        components: {
            BreadcrumbsComponent,
            page: PageComponent,
        },
    },

    // текстовая страница
    {
        path: '/add',
        name: 'add',
        components: {
            BreadcrumbsComponent,
            // page: AddGoodComponent,
        },
    },

    // товар 1 показ
    {
        path: '/good/:good_id/:dop?',
        name: 'good',
        components: {
            BreadcrumbsComponent,
            vitrin: GoodComponent,
        },
    },
    // {
    //     path: '/show/i/:good_id/:dop?',
    //     name: 'good2',
    //     components: {
    //         BreadcrumbsComponent,
    //         vitrin: GoodComponent,
    //     },
    // },

    // // search товаров
    // {
    //     path: '/search/:search/:page?',
    //     name: 'search',
    //     components: {
    //         BreadcrumbsComponent,
    //         vitrin: SearchComponent,
    //     },
    // },

    // каталог товаров
    {
        path: '/cat/:cat_id/:page?',
        name: 'cat',
        components: {
            BreadcrumbsComponent,
            MenuComponent,
            vitrin: VitrinComponent,
        },
    },

    // {
    //     path: '/show/:cat_id/:page?',
    //     name: 'cat2',
    //     components: {
    //         BreadcrumbsComponent,
    //         vitrin: VitrinComponent,
    //     },
    // },

    // корзина товаров
    {
        path: '/cart',
        name: 'cart',
        components: {
            BreadcrumbsComponent,
            vitrin: CartComponent,
        },
    },
    // orderOk
    {
        path: '/cart',
        name: 'orderOk',
        components: {
            BreadcrumbsComponent,
            vitrin: CartComponent,
        },
        props: {
            orderGood: true,
        },
    },
    // если не сработал ни один роут, показываем первую страничку
    {
        path: '/:catchAll(.*)*',
        name: 'NotFound',
        components: {
            // adver: UpBannerComponent,
            // starter: StarterComponent,
            // adverList: IndexLineAdver,
        },
    },
]

const router = new createRouter({
    // history: createWebHashHistory('/vue/'),
    history: createWebHistory(),
    // routes: routes,
    routes,
    // scrollBehavior(to, from, savedPosition) {
    //     // always scroll to top
    //     return { top: 0 }
    //   },
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth',
            }
        } else {
            return { top: 0, behavior: 'smooth' }
        }
    },
})

// // скрываем меню при любом переходе по ссылке
import catalogs from './use/catalogs.js'
import { nextTick } from 'vue'
const {
    // showMenu,
    catsLevelLower,
} = catalogs()

// // товары отмена загрузки
// import goods from './use/goods.js'
// const { GoodsLevelLowerStart, GoodsCancelTokenSource } = goods()

// сброс типов при переходе на др каталог
// import types_js from './use/types.js'
// const { clearType } = types_js()

// router.beforeEach((to, from) => {
//     GoodsCancelTokenSource.cancel(
//         'GoodsCancelTokenSource Operation canceled by the user.',
//     )
// })

// router.beforeEach((to, from, next) => {
//     console.log('router.beforeEach', to, from, next)
//     next()
// })

router.afterEach((to, from) => {
    console.log('router.afterEach', to, from)
        // console.log('router.afterEach((to, from) => {')
        // console.log(
        //         'from', from,
        //         'to',
        //         to,
        //     )
        //   console.log(1122)
        // showMenu.value = false

    // сброс типов при переходе на др каталог
    // if (to.params.cat_id != from.params.cat_id) {
    //     clearType()
    // }

    if (to.name == 'index') {
        //     // console.log('001')
        //     // @click="catNow='';stepCrumb={}"
        catsLevelLowerStart()
    } else {
        let cat_id = to.params.cat_id ? to.params.cat_id : 0
            // console.log('002', 'cat_id = ', cat_id)
        catsLevelLower(cat_id)
    }

    // if (route.name == 'index') {
    //       console.log('00 start');
    //       catsLevelLowerStart()
    //     } else {
    //       console.log('00 11 start');
    //       catsLevelLower(
    //         route.params.cat_id && route.params.cat_id > 0
    //           ? route.params.cat_id
    //           : 0,
    //       )
    //     }
})

export default router