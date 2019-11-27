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
    summary: {
        count: 0,
        sum: 0,
    },
    count: 0,
    selected: {
        item: null,
        company: null,
    }
}

/**
 * Mutations
 */
export const mutations = {
    [types.FILL_RECEIPT_ITEMS](state, { items }) {

        state.items = items.data
        state.summary = items.summary
        state.count = items.data.total
    },
    [types.VOID_RECEIPT](state, { receipt }) {
        const index = state.items.data.findIndex(r => r.reference === receipt.reference)
        state.items.data[index] = receipt
        Vue.set(state.items.data, index, receipt)
    },
    [types.FETCH_RECEIPT_FAILURE](state) {
        state.item = null
        state.summary = { count: 0, sum: 0 }
        state.items = []
        state.count = 0
    },
    [types.SELECTED_RECEIPT](state, {item, company}) {
        this.selected = {item, company}
    },
}

/**
 * Actions
 */
export const actions = {
    async reset() {
        commit(types.FETCH_RECEIPT_FAILURE)
    },
    async fetch({ commit }, { name, fields, filter, location, user, terminal, limit, page, sort, desc, noCommit }) {

        if (!noCommit) commit(types.FETCH_RECEIPT_FAILURE) //reset
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
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{reports(name:"${name}", limit: ${limit}, page: ${page}, ${sorting}, ${param}){data{data{${fields}}, total, per_page}, summary{count, sum}}}` } })

            if (noCommit) {
                return data.data.reports
            }

            commit(types.FILL_RECEIPT_ITEMS, { items: data.data.reports })

        } catch (e) {
            commit(types.FETCH_RECEIPT_FAILURE)
        }
    },
    async voidReceipt({ commit, rootState }, receipt) {
        receipt.status = 'void'
        const { reference } = receipt
        await axios.get(graphql.path('query'), { params: { query: `mutation receipts { voidReceipt(reference: "${reference}") {id}}` } })
        commit(types.VOID_RECEIPT, { receipt })

    },
    selectItem(item, company) {

       commit(types.SELECTED_RECEIPT, { item, company })
    }




}

/**
 * Getters
 */
export const getters = {
    items: state => state.items,
    summary: state => state.summary,
    count: state => state.count,
    selected: state => state.selected,

}
