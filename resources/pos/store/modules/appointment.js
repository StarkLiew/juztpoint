import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    appointment: null,
    appointments: [],
    autoincrement: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_APPOINTMENT](state, { receipt }) {
        state.receipt = receipt
    },
    [types.FILL_APPOINTMENTS](state, { receipts }) {

        state.receipts = receipts
    },
    [types.ADD_APPOINTMENT](state, { receipt }) {

        const index = state.appointments.findIndex(r => r.reference === receipt.reference)
        if (index > -1) {
            state.receipts[index] = receipt
        } else {

            state.appinc += 1
            state.appointments.push(receipt)
        }


    },
    [types.FETCH_APPOINTMENT_FAILURE](state) {

    },
}

/**
 * Actions
 */
export const actions = {
    async fetchAppointments({ commit }) {
        try {
            // const { data } = await axios.get(graphql.path('query'), {params: { query: '{accounts(type:"receipt"){ id, name, properties{email, mobile}}}'}})

            commit(types.FILL_APPOINTMENTS, data.data)

        } catch (e) {
            commit(types.FETCH_APPOINTMENT_FAILURE)
        }
    },

    async addReceipt({ commit, getters }, item) {
        try {


            if (!item.trxn_id && item.type == 'appointment') {
                let number = '00000' + (getters['autoincrement'] + 1)
                number = number.substr(number.length - 6)
                item.trxn_id = 'APP' + terminal_id + receipt.reference + number
            }

            const { reference, account_id, terminal_id, type, transact_by, date, discount, discount_amount, tax_total, service_charge, rounding, charge, received, change, note, refund, items, payments } = receipt

            let castItems = ""
            let castComm = ""
            for (const [line, item] of items.entries()) {

                const item_id = item.id
                const commission = item.commission.properties
                const comm_id = item.commission.id
                const user_id = item.saleBy.id
                const terminal_id = item.saleBy.id
                const tax_id = item.tax.id
                const tax_amount = item.tax_amount
                const total_amount = item.amount
                const note = item.note
                const discount_amount = item.discount.amount

            }





            const mutation = `mutation appointments{
                             newAppointment(
                                  {line: ${line + 1}, 
                                   type: "item", 
                                   item_id: ${item_id},
                                   discount: null, 
                                   discount_amount: 0, 
                                   tax_id: ${tax_id}, 
                                   refund_amount: 0.00,
                                   tax_amount: 0, 
                                   user_id: ${user_id},
                                   total_amount: 0, 
                                   properties: , 
                                   note: "${note}"},
                             ) {id}}`




            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })

            receipt.id = data.data.newReceipt.id
            receipt.status = 'active'


            if (receipt.type === 'receipt') commit(types.ADD_RECEIPT, { receipt })
            if (receipt.type === 'appointment') commit(types.ADD_APPOINTMENT, { receipt })


            return receipt

        } catch (e) {


            receipt.status = 'offline'
            if (receipt.type === 'receipt') commit(types.ADD_RECEIPT, { receipt })
            if (receipt.type === 'appointment') commit(types.ADD_APPOINTMENT, { receipt })

            return receipt
        }
    },
}

/**
 * Getters
 */
export const getters = {
    appointments: state => state.appointments,
    appointment: state => state.appointment !== null,
    autoincrement: state => state.autoincrement,

}
