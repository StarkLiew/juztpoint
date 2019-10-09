import axios from 'axios'
import { api } from '~~//config'
import * as types from '../mutation-types'
import VueCookies from 'vue-cookies'

/**
 * Initial state
 */
export const state = {
    user: null,
    access: false,
    terminal: null,
    store: null,
    //token: window.localStorage.getItem('token')
    token: VueCookies.get('JXPTBCK'),
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_USER](state, { user }) {
        state.access = true
        state.user = user
    },

    [types.LOGOUT](state) {
        state.access = false
        state.user = null
    },

    [types.DEREGISTER](state) {
        state.access = false
        state.user = null
        state.store = null
        state.terminal = null
        // state.token = null
        //window.localStorage.removeItem('token')
        VueCookies.remove('JXPTBCK')
    },


    [types.FETCH_USER_FAILURE](state) {
        state.user = null
        state.store = null
        state.terminal = null
        VueCookies.remove('JXPTBCK')
    },

    [types.SET_TOKEN](state, { token, expires_at, store, terminal }) {
        // state.token = token
        // window.localStorage.setItem('token', token)
        const expire = new Date(expires_at)
        state.store = store
        state.terminal = terminal
        VueCookies.set('JXPTBCK', token, expire, true)

    }
}

/**
 * Actions
 */
export const actions = {
    saveToken({ commit }, payload) {
        commit(types.SET_TOKEN, payload)
    },

    async fetchUser({ commit }) {
        try {
            const { data } = await axios.get(api.path('me'))
            commit(types.SET_USER, data)
        } catch (e) {
            commit(types.FETCH_USER_FAILURE)
        }
    },


    setUser({ commit }, payload) {
        commit(types.SET_USER, payload)
    },


    async logout({ commit }, payload) {
        commit(types.LOGOUT)
    },

    async deregister({ commit }) {
        /* await axios.post(api.path('logout')) */
        commit(types.DEREGISTER)
    },

    destroy({ commit }) {
        commit(types.LOGOUT)
    }
}

/**
 * Getters
 */
export const getters = {
    user: state => state.user,
    check: state => state.access,
    registered: state => VueCookies.get('JXPTBCK') !== null,
    token: state => VueCookies.get('JXPTBCK'),
    terminal: state => state.terminal,
    store: state => state.store,
}
