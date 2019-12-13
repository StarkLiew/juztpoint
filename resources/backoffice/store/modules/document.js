import axios from 'axios'
import Vue from 'Vue'
import moment from 'moment'
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

        if (!noCommit) commit(types.FETCH_DOCUMENT_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{documents(type:"${type}",limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{id, name, type, status, properties{mobile, email}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.documents.data
            }
            commit(types.FILL_DOCUMENTS, { items: data.data.documents })

        } catch (e) {
            commit(types.FETCH_DOCUMENT_FAILURE)
        }
    },
    async add({ commit }, item) {
        try {

            const { reference, account, transact_by, type, date, note, items, properties, charge, tax_amount } = item
            let castItems = ''

            for (const [seq, line] of items.entries()) {

                const item_id = line.item.id
                const user_id = line.user_id
                const qty = line.qty
                const tax_id = line.tax.id
                const discount_amount = line.discount_amount
                const tax_amount = line.tax_amount
                const total_amount = line.total_amount
                const note = line.note
                const price = line.properties.price


                const line_props = `{\\"price\\": ${price}}`

                const cast = `{line: ${seq + 1}, 
                         type: "pitem", 
                         item_id: ${item_id},
                         discount: "${JSON.stringify(line.discount).replace(/"/g, '\\"')}", 
                         discount_amount: ${parseFloat(discount_amount)}, 
                         tax_id: ${tax_id}, 
                         qty: ${qty},
                         refund_qty: 0,
                         refund_amount: 0.00,
                         tax_amount: ${parseFloat(tax_amount)}, 
                         user_id: ${user_id},
                         terminal_id: 0,
                         store_id: 0,
                         shift_id: 0,
                         total_amount: ${parseFloat(total_amount)}, 
                         note: "${note}",
                         properties:"${line_props}"
                        },`

                castItems += cast
            }

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `{newDocument(
                                 reference: "${reference}",
                                 status: "ordered",
                                 type: "${type}",
                                 terminal_id: 0,
                                 store_id: 0,
                                 account_id: "${account.id}",
                                 transact_by: ${transact_by},
                                 shift_id: 0,
                                 date: "${date}",
                                 discount: "{}",
                                 discount_amount: 0.00,
                                 tax_amount: ${parseFloat(tax_amount)},
                                 service_charge: 0.00,
                                 rounding: 0.00,
                                 charge: ${parseFloat(charge)},
                                 received: 0.00,
                                 change: 0.00,
                                 refund: 0.00,
                                 note: "${note}",
                                 items: [${castItems}],
                                 properties:"${props}"
                             ) {id}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: 'mutation documents' + mutation.replace(/[,]\s+/g, ',') } })

            item = data.data.newDocument

            commit(types.ADD_DOCUMENT, { item })

            return item
        } catch (e) {
            console.log(e)
            return e
        }
    },
    async update({ commit }, item) {
        try {

            const { id, reference, account, transact_by, type, date, note, items, status, properties, charge, tax_amount } = item
            let castItems = ''

            for (const [seq, line] of items.entries()) {

                const item_id = line.item.id
                const user_id = line.user_id
                const qty = line.qty
                const tax_id = line.tax.id
                const discount_amount = line.discount_amount
                const tax_amount = line.tax_amount
                const total_amount = line.total_amount
                const note = line.note
                const price = line.properties.price


                const line_props = `{\\"price\\": ${price}}`

                const cast = `{line: ${seq + 1}, 
                         type: "pitem", 
                         item_id: ${item_id},
                         discount: "${JSON.stringify(line.discount).replace(/"/g, '\\"')}", 
                         discount_amount: ${parseFloat(discount_amount)}, 
                         tax_id: ${tax_id}, 
                         qty: ${qty},
                         refund_qty: 0,
                         refund_amount: 0.00,
                         tax_amount: ${parseFloat(tax_amount)}, 
                         user_id: ${user_id},
                         terminal_id: 0,
                         store_id: 0,
                         shift_id: 0,
                         total_amount: ${parseFloat(total_amount)}, 
                         note: "${note}",
                         properties:"${line_props}"
                        },`

                castItems += cast
            }

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `{updateDocument(
                                 id: ${id},
                                 reference: "${reference}",
                                 status: "${status}",
                                 type: "${type}",
                                 terminal_id: 0,
                                 store_id: 0,
                                 account_id: "${account.id}",
                                 transact_by: ${transact_by},
                                 shift_id: 0,
                                 date: "${moment(date).format('YYYY-MM-DD')}",
                                 discount: "{}",
                                 discount_amount: 0.00,
                                 tax_amount: ${parseFloat(tax_amount)},
                                 service_charge: 0.00,
                                 rounding: 0.00,
                                 charge: ${parseFloat(charge)},
                                 received: 0.00,
                                 change: 0.00,
                                 refund: 0.00,
                                 note: "${note}",
                                 items: [${castItems}],
                                 properties:"${props}"
                             ) {id}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: 'mutation documents' + mutation.replace(/[,]\s+/g, ',') } })

            item = data.data.newDocument

            commit(types.ADD_DOCUMENT, { item })

            return item
        } catch (e) {
            console.log(e)
            return e
        }
    },

    async receive({ commit }, { line, receivedItem }) {
        try {
      
            const { id, refund_qty } = line

            const props = JSON.stringify(receivedItem.properties).replace(/"/g, '\\"')


            const receiveLine = `{
                         line: 1, 
                         type: "ritem", 
                         item_id: ${line.item_id},
                         discount_amount: 0, 
                         discount: "{}", 
                         tax_id: 0, 
                         qty: ${receivedItem.qty},
                         refund_qty: 0,
                         refund_amount: 0.00,
                         tax_amount: 0, 
                         user_id: ${receivedItem.user.id},
                         terminal_id: 0,
                         store_id: ${receivedItem.store.id},
                         shift_id: 0,
                         total_amount: 0, 
                         note: "",
                         properties:"${props}"
                    },`


            const mutation = `mutation document{updateDocumentStatus(id: ${id}, qty: ${refund_qty}, receive: ${receiveLine}) {id}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })

            return data.data.updateDocumentStatus

        } catch (e) {

            return e
        }
    },
    async undo({ commit }, { line, receivedItem }) {
        try {

            const { id, refund_qty } = line




            const mutation = `mutation document{removeDocumentStatus(id: ${id}, qty: ${refund_qty}, receive_id: ${receivedItem.id}) {id }}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })


            return item
        } catch (e) {

            return e
        }
    },
    async trash({ commit }, item) {
        try {

            const { id } = item

            const mutation = `mutation document{trashDocument(id: ${id}) {id, name }}`

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
