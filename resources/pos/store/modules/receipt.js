import Vue from 'vue'
import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'
import moment from 'moment'
import _ from 'lodash'

/**
 * Initial state
 */
export const state = {
    receipt: null,
    receipts: [],
    appointments: [],
    autoincrement: 0,
    appinc: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_RECEIPT](state, { receipt }) {
        state.receipt = receipt
    },
    [types.FILL_RECEIPTS](state, { receipts }) {

        state.receipts = receipts
    },

    [types.ADD_RECEIPT](state, { receipt }) {

        const index = state.receipts.findIndex(r => r.reference === receipt.reference)
        if (index > -1) {
            state.receipts[index] = receipt
        } else {

            state.autoincrement += 1
            state.receipts.push(receipt)
        }


    },
    [types.VOID_RECEIPT](state, { receipt }) {
        const index = state.receipts.findIndex(r => r.reference === receipt.reference)
        state.receipts[index] = receipt
        Vue.set(state.receipts, index, receipt)
    },
    [types.REFUND_RECEIPT](state, { receipt }) {
        const index = state.receipts.findIndex(r => r.reference === receipt.reference)
        state.receipts[index] = receipt
        Vue.set(state.receipts, index, receipt)
    },
    [types.FETCH_RECEIPT_FAILURE](state) {

    },
}

/**
 * Actions
 */
export const actions = {
    async fetchReceipts({ commit }) {
        try {
            // const { data } = await axios.get(graphql.path('query'), {params: { query: '{accounts(type:"receipt"){ id, name, properties{email, mobile}}}'}})

            commit(types.FILL_RECEIPTS, data.data)

        } catch (e) {
            commit(types.FETCH_RECEIPT_FAILURE)
        }
    },
    async refundReceipt({ commit, dispatch }, receipt) {

        dispatch('addReceipt', receipt)
    },
    async voidReceipt({ commit, rootState }, receipt) {
        receipt.status = 'void'
        const { reference } = receipt
        const isOffline = rootState.system.offline
        try {
            if (!isOffline) {
                await axios.get(graphql.path('query'), { params: { query: `mutation receipts { voidReceipt(reference: "${reference}") {id}}` } })
            } else {
                receipt.status = 'void offline'
            }
            commit(types.VOID_RECEIPT, { receipt })
        } catch (e) {
            receipt.status = 'void offline'
            console.log(receipt)
            commit(types.VOID_RECEIPT, { receipt })
        }
    },
    async addReceipt({ commit, getters, context, rootState }, receipt) {
        try {

            if (!receipt.id && !receipt.status && receipt.type == 'receipt') {
                let number = '00000' + (getters['autoincrement'] + 1)
                number = number.substr(number.length - 6)
                receipt.reference = receipt.reference + number
            }
      

            const { reference, account_id, terminal_id, store_id, shiftId, type, teller, date, discount, discount_amount, tax_total, service_charge, rounding, charge, received, change, note, refund, items, payments } = receipt

            let castItems = ""
            let castComm = ""
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


                const shareWith = 0
                if (item.shareWith) {
                    shareWith = item.shareWith.id
                }


                let variant = ''
    
                if (item.properties.variant) {
                    variant = ',\\"variant\\":' + JSON.stringify(item.properties.variant).replace(/"/g, '\\"')
                }


                let composites = ""
                let tasks = []



                if (item.composites) {
                    for (const composite of item.composites) {
                        if ('performBy' in composite) {
                            if (composite.performBy.id !== 0) {
                                tasks.push({ item_id: composite.id, perform_by: composite.performBy.id })
                            }

                        }
                    }
                    if (tasks.length > 0) {
                        composites = ',\\"composites\\":' + JSON.stringify(tasks).replace(/"/g, '\\"')
                    }

                }


                const props = `{\\"price\\": ${item.properties.price}, \\"shareWith\\":${shareWith} ${variant} ${composites}}`


                const cast = `{line: ${line + 1}, 
                         type: "item", 
                         item_id: ${item_id},
                         discount: "${JSON.stringify(item.discount).replace(/"/g, '\\"')}", 
                         discount_amount: ${parseFloat(discount_amount)}, 
                         tax_id: ${tax_id}, 
                         price: ${item.properties.price}, 
                         qty: -${qty},
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

            let castPayments = ""


            if (type == 'receipt' && payments) {

                for (const [line, payment] of payments.entries()) {

                    const { item_id, total_amount, note } = payment
                    const cast = `{line: ${line + 1}, 
                               type: "payment", 
                               item_id: ${item_id},
                               discount: "{}", 
                               discount_amount:0.00
                               qty: 1,
                               refund_qty: 0.00,
                               refund_amount: 0.00,
                               tax_id: 1, 
                               tax_amount: 0.00, 
                               user_id: ${teller.id},
                               terminal_id: ${terminal_id},
                               store_id: ${store_id},
                               shift_id: ${shiftId},
                               total_amount: ${parseFloat(total_amount)}, 
                               note: "${note}"}, `
                    castPayments += cast

                }

            }



            const mutation = `{
                             newReceipt(
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




            let isOffline = rootState.system.offline


            if (!isOffline) {

                const { data } = await axios.get(graphql.path('query'), { params: { query: 'mutation receipts' + mutation.replace(/[,]\s+/g, ',') } })

                if (!receipt.id) {
                    receipt.id = data.data.newReceipt.id
                    receipt.status = 'active'
                    commit(types.ADD_RECEIPT, { receipt })
                } else {
                    receipt.status = 'active'
                    commit(types.REFUND_RECEIPT, { receipt })

                }


            } else {
                if (!receipt.id) {
                    receipt.status = 'offline'
                    commit(types.ADD_RECEIPT, { receipt })
                } else {
                    receipt.status = 'update offline'
                    commit(types.REFUND_RECEIPT, { receipt })

                }

            }


            return receipt

        } catch (e) {

            if (!receipt.id) {
                receipt.status = 'offline'
                commit(types.ADD_RECEIPT, { receipt })

            } else {
                receipt.status = 'update offline'
                commit(types.REFUND_RECEIPT, { receipt })
            }

            return receipt
        }

    },
}

/**
 * Getters
 */
export const getters = {
    receipts: state => state.receipts.sort((a, b) => { return new Date(b.date) - new Date(a.date) }),
    groupDates: state => _.groupBy(state.receipts.sort((a, b) => { return new Date(b.date) - new Date(a.date) }), t => moment(t.date).format('YYYY-MM-DD')),
    appointments: state => state.appointments,
    receipt: state => state.receipt !== null,
    autoincrement: state => state.autoincrement,

}
