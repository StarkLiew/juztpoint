import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

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
    [types.SET_DOCUMENT](state, { item }) {
        state.item = item
    },
    [types.FILL_DOCUMENTS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.ADD_DOCUMENT](state, { item }) {


        const index = state.items.findIndex(u => u.id === item.id)

        if (index > -1) {
            Vue.set(state.items, index, item)

        } else {
            state.items.push(item)

        }
    },
    [types.REMOVE_DOCUMENT](state, { item }) {
        const index = state.items.findIndex(u => u.id === item.id)
        Vue.set(state.items, index, item)
        state.items.splice(index, 1)
    },
    [types.FETCH_DOCUMENT_FAILURE](state) {
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
        commit(types.FETCH_DOCUMENT_FAILURE)
    },
    async fetch({ commit }, { type, search, limit, page, sort, desc, noCommit }) {

        if (!noCommit) commit(types.FETCH_ACCOUNT_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{documents(type:"${type}",limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{id, name, type, status, properties{mobile, email}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.accounts.data
            }
            commit(types.FILL_DOCUMENTS, { items: data.data.documents })

        } catch (e) {
            commit(types.FETCH_DOCUMENT_FAILURE)
        }
    },
    async add({ commit }, item) {
        try {
            const { reference, account_id, terminal_id, store_id, shiftId, type, teller, date, discount, discount_amount, tax_total, service_charge, rounding, charge, received, change, note, refund, items, payments } = item
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            for (const [line, item] of items.entries()) {
                const item_id = item.id
                const item_line = item.line
                const commission = item.commission.properties
                const comm_id = item.commission.id

                const user_id = item.saleBy.id
                const qty = item.qty
                const tax_id = item.tax.id
                const tax_amount = item.tax_amount
                const total_amount = item.amount
                const note = item.note

                const discount_amount = item.discount.amount
                const props = `{\\"price\\": ${item.properties.price}, \\"shareWith\\":${shareWith} ${variant} ${composites}}`

                const cast = `{line: ${line + 1}, 
                         type: "item", 
                         item_id: ${item_id},
                         discount: "${JSON.stringify(item.discount).replace(/"/g, '\\"')}", 
                         discount_amount: ${parseFloat(discount_amount)}, 
                         tax_id: ${tax_id}, 
                         qty: ${qty},
                         refund_qty: ${ item.refund ? parseFloat(item.qty) : 0.00},
                         refund_amount: ${ item.refund ? parseFloat(item.amount) : 0.00},
                         tax_amount: ${parseFloat(tax_amount)}, 
                         user_id: ${user_id},
                         terminal_id: ${terminal_id},
                         store_id: ${store_id},
                         shift_id: ${shiftId},
                         total_amount: ${parseFloat(total_amount)}, 
                         note: "${note}",
                         properties:"${props}"
                        },`

                castItems += cast
            }

            const mutation = `{
                             newDocument(
                                 reference: "${reference}",
                                 status: "active",
                                 type: "${type}",
                                 terminal_id: ${terminal_id},
                                 store_id: ${store_id},
                                 account_id: "${account_id}",
                                 transact_by: ${teller.id},
                                 shift_id: ${shiftId},
                                 date: "${date}",
                                 discount: "${JSON.stringify(discount).replace(/"/g, '\\"')}",
                                 discount_amount: ${parseFloat(discount_amount)},
                                 tax_amount: ${parseFloat(tax_total)},
                                 service_charge: ${parseFloat(service_charge)},
                                 rounding: ${parseFloat(rounding)},
                                 charge: ${parseFloat(charge)},
                                 received: ${parseFloat(received)},
                                 change: ${parseFloat(change)},
                                 refund: ${parseFloat(refund)},
                                 note: "${note}",
                                 items: [${castItems}],
                                 payments: [${castPayments}],
                                 commissions: [],
                             ) {id}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })

            item = data.data.newDocument

            commit(types.ADD_DOCUMENT, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async update({ commit }, item) {
        try {
            const { id, name, properties } = item

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation documents{
                               updateDocument(
                                    id: ${id},
                                    name: "${name}",
                                    properties: "${props}"
                             ) {id, name,type, properties{mobile, email}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            item = data.data.updateDocument

            commit(types.ADD_DOCUMENT, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async trash({ commit }, item) {
        try {

            const { id } = item

            const mutation = `mutation document{trashDocument(id: "${id}") {id, name }}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })

            commit(types.REMOVE_DOCUMENT, { item })

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
