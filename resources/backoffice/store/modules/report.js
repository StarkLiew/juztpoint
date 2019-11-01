import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'
import moment from 'moment'

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
    async fetch({ commit }, { name, fields, filter, location, user, terminal, limit, page, sort, desc, noCommit }) {

        if (!noCommit) commit(types.FETCH_REPORT_FAILURE) //reset
        try {
            let _from = ''
            let _to = ''
            let param = ''

            if (filter && filter.dates && filter.dates.length === 2) {
                _from = moment(filter.dates[0]).format('YYYY-MM-DD')
                _to = moment(filter.dates[1]).format('YYYY-MM-DD')
                param += `from: "${_from}", to: "${_to}"`
            }

            if (filter && filter.store) {
                if (param !== '') param += ','
                param += `store: ${filter.store}`
            }
            if (filter && filter.terminal) {
                if (param !== '') param += ','
                param += `terminal: ${filter.terminal}`
            }
            if (filter && filter.user) {
                if (param !== '') param += ','
                param += `user: ${filter.user}`
            }

            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{reports(name:"${name}", limit: ${limit}, page: ${page}, ${sorting}, ${param}){data{${fields}}, summary, total,per_page}}` } })
            if (noCommit) {
                return data.data.reports
            }
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
