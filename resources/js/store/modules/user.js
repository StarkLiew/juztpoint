import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
  user: null,
  users: []
}

/**
 * Mutations
 */
export const mutations = {
  [types.FILL_USERS](state, { users }) { 
 
    state.users = users
  },
  [types.FETCH_USERS_FAILURE](state) {
    state.user = null
    state.users = []
  },
}

/**
 * Actions
 */
export const actions = {
  async fetchUsers({ commit }) {
    try {
      const { data } = await axios.get(graphql.path('query'), {params: { query: '{users{ id, name, pin}}'}})
     
      commit(types.FILL_USERS, data.data )
   
    } catch (e) {
      commit(types.FETCH_USERS_FAILURE)
    }
  },
}

/**
 * Getters
 */
export const getters = {
  users: state => state.users,
  user: state => state.user !== null,
}
