import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'


const columns = `id, thumbnail, note, name,sku, status, type,cat_id, category{id, name}, commission_id, commission{id, name, properties{rate, type}},tax_id,tax{id, name, properties{rate, code}}, allow_assistant, discount, stockable, variants{name, value}, composites, properties{ cost, price, thumbnail, color, opening, stocks{name, cost, price, qty}}`
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

        if (!noCommit) commit(types.FETCH_PRODUCT_FAILURE) //reset
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
            const { formData, thumbnail, name, type, properties, status, cat_id, sku, tax_id, commission_id, allow_assistant, discount, stockable, note, variants, composites, qty } = item
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            let input = `name: "${name}",
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
                           note: "${note}",`

            if (!!variants) {
                const variantsCasted = JSON.stringify(variants).replace(/"/g, '\\"')
                input += `variants: "${variantsCasted}",`
            }
            if (!!composites) {
                const compositesCasted = JSON.stringify(composites).replace(/"/g, '\\"')
                input += `composites: "${compositesCasted}",`
            }


            if (!formData) {
                const mutation = `mutation products{newProduct(${input}){${columns}}}`

                const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
                item = data.data.newProduct

            } else {
                const mutation = `mutation products($thumbnail: Upload!){
                               newProduct(
                                    ${input}
                                    thumbnail: $thumbnail
                             ) {${columns}}}`

                const files = formData.getAll('thumbnail')
                formData.set('operations', JSON.stringify({
                    'query': mutation,
                    'variables': { "thumbnail": files[0] }
                }))

                formData.set('map', JSON.stringify({ "thumbnail": ['variables.thumbnail'] }))
                formData.set('operationName', null)

                const { data } = await axios.post(graphql.path('query'), formData, { 'Content-Type': 'multipart/form-data' })
                item = data.data.newProduct
            }

            commit(types.ADD_PRODUCT, { item })

            return item
        } catch (e) {
            console.log(e)
            return e
        }
    },
    async update({ commit }, item) {
        try {
            const { formData, id, thumbnail, name, type, properties, status, cat_id, sku, tax_id, allow_assistant, discount, commission_id, stockable, note } = item

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const input = `id: ${id},
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
                           note: "${note}",`

            if (!formData) {
                const thumbInput = thumbnail === '' ? `clear_image: true` : ''

                const mutation = `mutation products {
                               updateProduct(
                                    ${input}
                                    ${thumbInput}
                             ) {${columns}}}`

                const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
                item = data.data.updateProduct

            } else {


                const mutation = `mutation products($thumbnail: Upload!) {
                               updateProduct(
                                    ${input}
                                    thumbnail: $thumbnail,
                             ) {${columns}}}`

                const files = formData.getAll('thumbnail')
                formData.set('operations', JSON.stringify({
                    'query': mutation,
                    'variables': { "thumbnail": files[0] }
                }))

                formData.set('map', JSON.stringify({ "thumbnail": ['variables.thumbnail'] }))
                formData.set('operationName', null)

                const { data } = await axios.post(graphql.path('query'), formData, { 'Content-Type': 'multipart/form-data' })
                item = data.data.updateProduct
            }



            commit(types.ADD_PRODUCT, { item })

            return item
        } catch (e) {
            console.log(e)
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
