import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'


const columns = `id, thumbnail, name,sku, status, type,cat_id, category{id, name}, commission_id, commission{id, name, properties{rate, type}},tax_id,tax{id, name, properties{rate, code}}, allow_assistant, discount, stockable, properties{ cost, price, thumbnail, color}`
/**
 * Initial state
 */
export const state = {
    item: null,
    items: [],
    count: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_PRODUCT](state, { item }) {
        state.item = item
    },
    [types.FILL_PRODUCTS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.ADD_PRODUCT](state, { item }) {


        const index = state.items.findIndex(u => u.id === item.id)

        if (index > -1) {
            Vue.set(state.items, index, item)
        } else {
            state.items.push(item)
        }
    },
    [types.REMOVE_PRODUCT](state, { item }) {
        const index = state.items.findIndex(u => u.id === item.id)
        Vue.set(state.items, index, item)
        state.items.splice(index, 1)
    },
    [types.FETCH_PRODUCT_FAILURE](state) {
        state.item = null
        state.items = []
        state.count = 0
    },


}

/**
 * Actions
 */
export const actions = {
    async reset() {
        commit(types.FETCH_PRODUCT_FAILURE)
    },
    async fetch({ commit }, { type, search, limit, page, sort, desc, noCommit }) {

        commit(types.FETCH_PRODUCT_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{products(type:"${type}",limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{${columns}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.products.data
            }
            commit(types.FILL_PRODUCTS, { items: data.data.products })

        } catch (e) {
            commit(types.FETCH_PRODUCT_FAILURE)
        }
    },
    async add({ commit }, item) {
        try {
            const { thumbnail, name, type, properties, status, cat_id, sku, tax_id, commission_id, allow_assistant, discount, stockable, note } = item
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation products{
                                newProduct(
                                    thumbnail: ${thumbnail},
                                    name: "${name}",
                                    type: "${type}",
                                    status: "${status}",
                                    properties: "${props}",
                                    cat_id: ${cat_id},
                                    sku: "${sku}",
                                    tax_id: ${tax_id},
                                    allow_assistant: ${allow_assistant},
                                    discount: ${discount},
                                    commission_id: ${commission_id},
                                    stockable: ${stockable},
                                    note: "${note}",
                             ) {${columns}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            item = data.data.newProduct

            commit(types.ADD_PRODUCT, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async update({ commit }, item) {
        try {
            const { id, thumbnail, name, type, properties, status, cat_id, sku, tax_id, allow_assistant, discount, commission_id, stockable, note } = item

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation products($thumbnail: upload!) {
                               updateProduct(
                                    id: ${id},
                                    thumbnail: $thumbnail,
                                    name: "${name}",
                                    type: "${type}",
                                    status: "${status}",
                                    properties: "${props}",
                                    cat_id: ${cat_id},
                                    sku: "${sku}",
                                    tax_id: ${tax_id},
                                    allow_assistant: ${allow_assistant},
                                    discount: ${discount},
                                    commission_id: ${commission_id},
                                    stockable: ${stockable},
                                    note: "${note}"
                             ) {${columns}}}`


            const { data } = await axios.post(graphql.path('query'), thumbnail, { params: { query: mutation } })
            item = data.data.updateProduct

            commit(types.ADD_PRODUCT, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async trash({ commit }, item) {
        try {

            const { id } = item

            const mutation = `mutation products{trashProduct(id: "${id}") {id, name }}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })

            commit(types.REMOVE_PRODUCT, { item })

            return item
        } catch (e) {

            return e
        }
    },


}

/**
 * Getters
 */
export const getters = {
    items: state => state.items,
    item: state => state.item !== null,
    count: state => state.count,

}
