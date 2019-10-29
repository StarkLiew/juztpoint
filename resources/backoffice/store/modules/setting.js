import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

const commissionFields = "id, name, description, type, properties{rate, type}"
const taxFields = "id, name, description, type, properties{rate, type}"
const categoryFields = "id, name, description, type"
const storeFields = "id, name, description,  properties{currency, timezone}"
const companyFields = "id, name, description,  properties{address, currency, timezone}"
const terminalFields = "id, name, description"

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
    [types.SET_SETTING](state, { item }) {
        state.item = item
    },
    [types.FILL_SETTINGS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.ADD_SETTING](state, { item }) {


        const index = state.items.findIndex(u => u.id === item.id)

        if (index > -1) {
            Vue.set(state.items, index, item)
        } else {
            state.items.push(item)
        }
    },
    [types.REMOVE_SETTING](state, { item }) {
        const index = state.items.findIndex(u => u.id === item.id)
        Vue.set(state.items, index, item)
        state.items.splice(index, 1)
    },
    [types.FETCH_SETTING_FAILURE](state) {
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
        commit(types.FETCH_SETTING_FAILURE)
    },
    async fetch({ commit }, { type, search, limit, page, sort, desc, noCommit }) {

        commit(types.FETCH_SETTING_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            let fields = ''
            if (type === 'commission') fields = commissionFields
            if (type === 'tax') fields = taxFields
            if (type === 'category') fields = categoryFields
            if (type === 'store') fields = storeFields
            if (type === 'terminal') fields = terminalFields
            if (type === 'company') fields = companyFields

            const { data } = await axios.get(graphql.path('query'), { params: { query: `{settings(type:"${type}",limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{${fields}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.settings.data
            }
            commit(types.FILL_SETTINGS, { items: data.data.settings })

        } catch (e) {
            commit(types.FETCH_SETTING_FAILURE)
        }
    },
    async add({ commit }, item) {
        try {
            const { name, type, properties, status } = item
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation accounts{
                                newAccount(
                                    name: "${name}",
                                    type: "${type}",
                                    status: "${status}",
                                    properties: "${props}"
                             ) {id, name, type, properties{email, mobile}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })

            item = data.data.newAccount

            commit(types.ADD_SETTING, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async update({ commit }, item) {
        try {
            const { id, name, properties } = item

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation accounts{
                               updateAccount(
                                    id: ${id},
                                    name: "${name}",
                                    properties: "${props}"
                             ) {id, name,type, properties{mobile, email}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            item = data.data.updateAccount

            commit(types.ADD_SETTING, { item })

            return item
        } catch (e) {

            return e
        }
    },
    async trash({ commit }, item) {
        try {

            const { id } = item

            const mutation = `mutation account{trashAccount(id: "${id}") {id, name }}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })

            commit(types.REMOVE_SETTING, { item })

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
