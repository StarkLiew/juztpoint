import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    company: null,
    shiftId: 0,
    payment_method: {
        cash: true,
        card: true,
        transfer: true,
        boost: false,
    },
    shifts: [],
    shift: null,
    users: [],
    categories: [],
    offline: false,
}

/**
 * Mutations
 */
export const mutations = {

    [types.FILL_SYSTEM](state, { system }) {

        state.company = system.company
        state.categories = system.categories
        state.users = system.users
    },
    [types.AUTO_INCREMENT](state, { system }) {
        state.autoincrement += 1
    },
    [types.SET_OFFLINE](state, { status }) {
        state.offline = status
    },
    [types.SET_PAYMENT_OPTION](state, { option }) {
        state.payment_method[option.name] = option.value
    },
    [types.OPEN_SHIFT](state, { open }) {

        state.shift = { status: 'open', open }
        state.shiftId = state.shiftId + 1
        state.shift.id = state.shiftId
        state.shifts.push(state.shift)

    },
    [types.CLOSE_SHIFT](state, { close }) {

        const index = state.shifts.findIndex(s => s.id === state.shift.id)
        let shift = state.shifts[index]
        shift.close = close
        shift.status = 'close'
        state.shifts[index] = shift
        state.shift = null
    },

    [types.FETCH_SYSTEM_FAILURE](state) {

    },
}

/**
 * Actions
 */
export const actions = {

    async openShift({ commit, rootState }, amount) {
        const open = { amount, date: new Date(), user: rootState.auth.user }
        commit(types.OPEN_SHIFT, { open })
    },
    async closeShift({ commit, rootState }, amount) {
        const close = { amount, date: new Date(), user: rootState.auth.user }
        commit(types.CLOSE_SHIFT, { close })
    },
    async setPaymentMethod({ commit }, option) {
        commit(types.SET_PAYMENT_OPTION, option)
    },
    async setOffline({ commit }, status) {
        commit(types.SET_OFFLINE, status)
    },
    async fetchSystem({ commit }) {
        try {
            const company = await axios.get(graphql.path('query'), { params: { query: '{settings(type: "company"){ id, name, properties{address, timezone, email, mobile}}}' } })
            // const payments = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "payment"){ id, name, properties{email, mobile}}}'}})
            const categories = await axios.get(graphql.path('query'), { params: { query: '{settings(type: "category"){ id, name}}' } })
            const users = await axios.get(graphql.path('query'), { params: { query: '{users{ id, name, pin}}' } })
            const system = { company: company.data.data.settings[0], payments: payments.data.data.settings, users: users.data.data.settings, categories: categories.data.data.settings }
            commit(types.FILL_SYSTEM, { system })

        } catch (e) {
            commit(types.FETCH_SYSTEM_FAILURE)
        }
    },
}

/**
 * Getters
 */
export const getters = {
    company: state => state.company,
    users: state => state.users,
    shift: state => state.shift,
    lastShift: state => state.shifts[state.shifts.length - 1],
    categories: state => state.categories,
    offline: state => state.offline,
    paymentMethod: state => state.payment_method,
}
