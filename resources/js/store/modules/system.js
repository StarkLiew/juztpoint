import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
  company: null,
  payments: [],
  users: [],  
  categories: [], 
  offline: false,
}

/**
 * Mutations
 */
export const mutations = {

  [types.FILL_SYSTEM](state, { system }) { 
 
    state.company = system.company
    state.categories = system.categories
    state.payments = system.payments
    state.users = system.payments
  },
  [types.AUTO_INCREMENT](state, { system }) { 
        state.autoincrement += 1
  },
  [types.SET_OFFLINE](state, {status}) {
         state.offline = status
  },

  [types.FETCH_SYSTEM_FAILURE](state) {

  },
}

/**
 * Actions
 */
export const actions = {

   async setOffline({commit}, status ) {
      
      commit(types.SET_OFFLINE, status)

  },

  async fetchSystem({ commit }) {
    try {
      const company = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "company"){ id, name, properties{address, timezone, email, mobile}}}'}})
      const payments = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "payment"){ id, name, properties{email, mobile}}}'}})
      const categories = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "category"){ id, name}}'}})
      const users = await axios.get(graphql.path('query'), {params: { query: '{users{ id, name, pin}}'}})
      const system = {company: company.data.data.settings[0],payments: payments.data.data.settings, users: users.data.data.settings, categories: categories.data.data.settings }
      commit(types.FILL_SYSTEM, {system})
   
    } catch (e) {
      commit(types.FETCH_SYSTEM_FAILURE)
    }
  },
}

/**
 * Getters
 */
export const getters = {
  company: state => state.company,
  payments: state => state.payments,
  users: state => state.users,
  categories: state => state.categories, 
  offline: state => state.offline, 
}
