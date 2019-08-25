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

       const {reference, account_id,transact_by, date,discount,discount_amount, tax_total, service_charge, charge,received, change, note,  refund, items, payments} = receipt

        let castItems = "" 
        let castComm = "" 
        for(const [line, item] of items.entries() ) {
    
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
    


           const comm_ammount = commission.type == 'fix' ? parseFloat(commission.rate) : parseFloat(total_amount) * (parseFloat(commission.rate) /100)

    
       
           const cast = `{line: ${line + 1}, 
                         type: "item", 
                         item_id: ${item_id},
                         discount: "${JSON.stringify(item.discount).replace(/"/g, '\\"')}", 
                         discount_amount: ${parseFloat(discount_amount)}, 
                         tax_id: ${tax_id}, 
                         tax_amount: ${parseFloat(tax_amount)}, 
                         user_id: ${user_id},
                         total_amount: ${parseFloat(total_amount)}, 
                         note: "${note}"},`

          const comm = `{line: ${line + 1}, 
                         type: "commission", 
                         item_id: ${comm_id},
                         discount: "{}", 
                         discount_amount:0.00, 
                         tax_id: 1, 
                         tax_amount: 0.00, 
                         user_id: ${user_id},
                         total_amount: ${parseFloat(comm_ammount)}, 
                         note: ""},`   

           castItems += cast
           castComm  += comm


        }


        let castPayments = "" 
        for(const [line, payment] of payments.entries() ) {
           const {item_id, total_amount, note} = payment
           const cast = `{line: ${line + 1}, 
                         type: "payment", 
                         item_id: ${item_id},
                         discount: "{}", 
                         discount_amount:0.00, 
                         tax_id: 1, 
                         tax_amount: 0.00, 
                         user_id: ${transact_by},
                         total_amount: ${parseFloat(total_amount)}, 
                         note: "${note}"}, `
           castPayments +=  cast

        }
   
           
        const mutation = `mutation receipts{
                             newReceipt(
                                 reference: "${reference}",
                                 status: "active",
                                 type: "receipt",
                                 terminal_id: ${transact_by},
                                 account_id: ${account_id},
                                 transact_by: ${transact_by},
                                 date: "${date}",
                                 discount: "${JSON.stringify(discount).replace(/"/g, '\\"')}",
                                 discount_amount: ${parseFloat(discount_amount)},
                                 tax_amount: ${parseFloat(tax_total)},
                                 service_charge: ${parseFloat(service_charge)},
                                 charge: ${parseFloat(charge)},
                                 received: ${parseFloat(received)},
                                 change: ${parseFloat(change)},
                                 refund: ${parseFloat(refund)},
                                 note: "${note}",
                                 items: [${castItems}],
                                 payments: [${castPayments}],
                                 commissions: [${castComm}]
                             ) {id}}`


       const { data }  = await axios.get(graphql.path('query'), {params: { query: mutation }})
  
       receipt.id = data.data.newReceipt.id


       commit(types.ADD_RECEIPT, { receipt })
      
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
