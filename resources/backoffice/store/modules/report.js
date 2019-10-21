import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    items: [],
    count: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.FILL_REPORT_ITEMS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.FETCH_REPORT_FAILURE](state) {
        state.item = null
        state.items = []
        state.count = 0
    },
}

/**
 * Actions
 */
export const actions = {
    async reset() {
        commit(types.FETCH_REPORT_FAILURE)
    },
    async fetch({ commit }, { name, fields, dates, location, user, terminal, limit, page, sort, desc }) {

        commit(types.FETCH_REPORT_FAILURE) //reset
        try {
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{reports(limit: ${limit}, page: ${page}, ${sorting}){data{${fields}}, total,per_page}}` } })

            commit(types.FILL_REPORT_ITEMS, { items: data.data.reports })

        } catch (e) {
            commit(types.FETCH_REPORT_FAILURE)
        }
    },




}

/**
 * Getters
 */
export const getters = {
    items: state => state.items,
    count: state => state.count,

}
