import './bootstrap'

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import VueGeolocation from 'vue-browser-geolocation';
Vue.use(VueGeolocation);

import HomeComponent from './components/HomeComponent'
import ResultComponent from './components/ResultComponent'

const routes = [
    { path: '/', component: HomeComponent },
    {
        path: '/search',
        component: ResultComponent,
        props: (route) => ({
            query: route.query.q,
            radius: route.query.radius,
        })
    }
]

const router = new VueRouter({
    routes // short for `routes: routes`
})

const app = new Vue({
    router
}).$mount('#app')
