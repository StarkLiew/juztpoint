import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    service: null,
    services: []
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_SERVICE](state, { service }) {
        state.service = service
    },
    [types.FILL_SERVICES](state, { products }) {

        state.services = products.data
    },

    [types.FETCH_SERVICE_FAILURE](state) {
        state.service = null

    },
}

/**
 * Actions
 */
export const actions = {
    async fetchServices({ commit }) {
        try {
            const { data } = await axios.get(graphql.path('query'), { params: { query: '{products(type: "%service%", limit:0, page: 1){ data{id, type, name, sku, category{id, name}, commission{id, name,  properties{rate, type}},tax{id, name, properties{rate, code}}, variants{name, value}, composites{id},properties{price, thumbnail, contain, color, duration, stocks{name, price}}}}}' } })
            commit(types.FILL_SERVICES, data.data)
        } catch (e) {
            commit(types.FETCH_SERVICE_FAILURE)
        }
    },
}

/**
 * Getters
 */
export const getters = {
    collection: state => state.services,
    selected: state => state.service !== null,
}
