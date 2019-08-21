import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
  receipt: null,
  receipts: []
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
 
     state.receipts.push(receipt)
  },
  [types.FETCH_RECEIPT_FAILURE](state) {
    state.receipt = null
    state.receipts = []
  },
}

/**
 * Actions
 */
export const actions = {
  async fetchReceipts({ commit }) {
    try {
      // const { data } = await axios.get(graphql.path('query'), {params: { query: '{accounts(type:"receipt"){ id, name, properties{email, mobile}}}'}})

      commit(types.FILL_RECEIPTS, data.data )
   
    } catch (e) {
      commit(types.FETCH_RECEIPT_FAILURE)
    }
  },
  async addReceipt({ commit }, receipt) {
    try {

       const {reference, account_id,transact_by, date,discount,discount_amount, service_charge, charge,received, change, note,  items, payments} = receipt
        

        let castItems = "" 
        let castComm = "" 
        for(const [line, item] of items.entries() ) {
           const {discount, discount_amount, tax_id, tax_amount, total_amount, note} = item

           const item_id = item.id  
           const comm_id = commission.id  
           const user_id = item.user_id  
           const comm_ammount = commission.type == 'fix' ? commission.rate : total_amount * (commission.rate /100)


           const cast = `{line: ${line}, 
                         type: "item", 
                         item_id: ${item_id},
                         discount: "${discount}", 
                         discount_amount: ${discount_amount}, 
                         tax_id: ${tax_id}, 
                         tax_amount: ${tax_amount}, 
                         user_id: ${user_id},
                         total_amount: ${total_amount}, 
                         note: "${note}"},`

          const comm = `{line: ${line}, 
                         type: "commission", 
                         item_id: ${comm_id},
                         discount: "{}", 
                         discount_amount:0, 
                         tax_id: 1, 
                         tax_amount: 0, 
                         user_id: ${user_id},
                         total_amount: ${comm_ammount}, 
                         note: ""},`   

           castItems += cast
           castComm  += comm
        }

        let castPayments = "" 
        for(const [line, payment] of payments.entries() ) {
           const {item_id, total_amount, note} = payment
           const cast = `{line: ${line}, 
                         type: "payment", 
                         item_id: ${item_id},
                         discount: "{}", 
                         discount_amount:0, 
                         tax_id: 1, 
                         tax_amount: 0, 
                         total_amount: ${total_amount}, 
                         note: "${note}"}, `
           castPayments +=  cast

        }
   
           
        const mutation = `mutation receipts{
                             newReceipt(
                                 reference: "${reference}",
                                 status: "active",
                                 type: "receipt",
                                 account_id: "${account_id}",
                                 transact_by: "${transact_by}",
                                 date: "${date}",
                                 discount: "${discount}",
                                 discount_amount: ${discount_amount},
                                 service_charge: ${service_charge},
                                 charge: ${charge},
                                 received: ${received},
                                 change: ${change},
                                 note: "${note}",
                                 items: [${castItems}],
                                 payments: [$[castPayments],
                                 commissions: [$[castComm]
                             ) {id}}`


       const { data }  = await axios.get(graphql.path('query'), {params: { query: mutation }})
  
       receipt = data.data.newReceipt


       commit(types.ADD_RECEIPT, { receipt })
       return receipt
    } catch (e) {
        
    }
  },
}

/**
 * Getters
 */
export const getters = {
  receipts: state => state.receipts,
  receipt: state => state.receipt !== null,
}
