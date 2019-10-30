import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    product: null,
    products: []
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_PRODUCT](state, { product }) {
        state.product = product
    },
    [types.FILL_PRODUCTS](state, { products }) {

        state.products = products.data
    },

    [types.FETCH_PRODUCT_FAILURE](state) {
        state.product = null

    },
}

/**
 * Actions
 */
export const actions = {
    async fetchProducts({ commit }) {
        try {
            const { data } = await axios.get(graphql.path('query'), { params: { query: '{products(type: "%product%", limit:0, page:1){ data{id,  thumbnail, name,  sku, type,  category{id, name}, commission{id, name, properties{rate, type}},tax{id, name, properties{rate, code}}, variants{name, value}, composites{id}, properties{ price, thumbnail, color, stocks{name, price}}}}}' } })
            commit(types.FILL_PRODUCTS, data.data)
        } catch (e) {
            commit(types.FETCH_PRODUCT_FAILURE)
        }
    },
}

/**
 * Getters
 */
export const getters = {
    collection: state => state.products,
    selected: state => state.product !== null,
}
