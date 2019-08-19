import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
  customer: null,
  customers: []
}

/**
 * Mutations
 */
export const mutations = {
  [types.SET_CUSTOMER](state, { customer }) { 
    state.customer = customer
  },
  [types.FILL_CUSTOMERS](state, { accounts }) { 
 
    state.customers = accounts
  },
  [types.ADD_CUSTOMER](state, { customer }) { 
 
     state.customers.push(customer)
  },
  [types.FETCH_CUSTOMER_FAILURE](state) {
    state.customer = null
    state.customers = []
  },
}

/**
 * Actions
 */
export const actions = {
  async fetchCustomers({ commit }) {
    try {
      const { data } = await axios.get(graphql.path('query'), {params: { query: '{accounts(type:"customer"){ id, name, properties{email, mobile}}}'}})
      commit(types.FILL_CUSTOMERS, data.data )
   
    } catch (e) {
      commit(types.FETCH_CUSTOMER_FAILURE)
    }
  },
  async addCustomer({ commit }, customer) {
    try {

       const {name, properties} = customer
       const props = JSON.stringify(properties).replace(/"/g, '\\"')

           
        const mutation = `mutation accounts{
                             newAccount(
                                 name: "${name}",
                                 status: "active",
                                 type: "customer",
                                 properties: "${props}"
                             ) {id, name, properties{email, mobile}}}`

       const { data }  = await axios.get(graphql.path('query'), {params: { query: mutation }})
  
       customer = data.data.newAccount


       commit(types.ADD_CUSTOMER, { customer })
       return customer
    } catch (e) {
        
    }
  },
}

/**
 * Getters
 */
export const getters = {
  customers: state => state.customers,
  customer: state => state.customer !== null,
}
