import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'


const columns = `id, reference, account{id, name}, store_id, shift_id, terminal_id, transact_by, charge, refund`

/**
 * Initial state
 */
export const state = {
    item: null,
    items: [],
    count: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_RECEIPT](state, { item }) {
        state.item = item
    },
    [types.FILL_RECEIPTS](state, { items }) {
        state.items = items.data
        state.count = items.total
    },
    [types.REFUND_RECEIPT](state, { item }) {

    },
    [types.VOID_RECEIPT](state, { item }) {
        const index = state.items.findIndex(u => u.id === item.id)
        Vue.set(state.items, index, item)
        state.items.splice(index, 1)
    },
    [types.FETCH_RECEIPT_FAILURE](state) {
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
        commit(types.FETCH_RECEIPT_FAILURE)
    },
    async fetch({ commit }, { type, search, limit, page, sort, desc, noCommit, customFields }) {

        if (!noCommit) commit(types.FETCH_RECEIPT_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{receipts(limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{${!!customFields ? customFields : columns}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.receipts.data
            }
            commit(types.FILL_RECEIPTS, { items: data.data.receipts })

        } catch (e) {
            commit(types.FETCH_RECEIPT_FAILURE)
        }
    },
    async trash({ commit }, item) {
        try {

            const { id } = item

            const mutation = `mutation receipts{trashReceipt(id: "${id}") {id, name }}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })

            commit(types.VOID_RECEIPT, { item })

            return item
        } catch (e) {

            return e
        }
    },


}

/**
 * Getters
 */
export const getters = {
    items: state => state.items,
    item: state => state.item !== null,
    count: state => state.count,

}
