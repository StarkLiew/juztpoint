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
}

/**
 * Mutations
 */
export const mutations = {

  [types.FILL_SYSTEM](state, { system }) { 
 
    state.company = system.company
    state.payments = system.payments
    state.users = system.payments
  },

  [types.FETCH_SYSTEM_FAILURE](state) {
    state.company = null
    state.payments = []
    state.users = []
  },
}

/**
 * Actions
 */
export const actions = {
  async fetchSystem({ commit }) {
    try {
      const company = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "company"){ id, name, properties{address, timezone, email, mobile}}}'}})
      const payments = await axios.get(graphql.path('query'), {params: { query: '{settings(type: "payment"){ id, name, properties{email, mobile}}}'}})
      const users = await axios.get(graphql.path('query'), {params: { query: '{users{ id, name, pin}}'}})
      const system = {company: company.data.data.settings[0],payments: payments.data.data.settings, users: users.data.data.settings }
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
}
