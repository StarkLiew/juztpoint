import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '~/store/index'
import routes from './routes'
import { api } from '~/config'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes
})


export default router
