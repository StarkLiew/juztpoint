import axios from 'axios'
import Vue from 'vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

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
    [types.SET_ACCOUNT](state, { item }) {
        state.item = item
    },
    [types.FILL_ACCOUNTS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.ADD_ACCOUNT](state, { item }) {


        const index = state.items.findIndex(u => u.id === item.id)

        if (index > -1) {
            Vue.set(state.items, index, item)

        } else {
           state.items.push(item)

        }
    },
    [types.REMOVE_ACCOUNT](state, { item }) {
        const index = state.items.findIndex(u => u.id === item.id)
        Vue.set(state.items, index, item)
        state.items.splice(index, 1)
    },
    [types.FETCH_ACCOUNT_FAILURE](state) {
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
        commit(types.FETCH_ACCOUNT_FAILURE)
    },
    async fetch({ commit }, { type, search, limit, page, sort, desc, noCommit }) {

        if (!noCommit) commit(types.FETCH_ACCOUNT_FAILURE) //reset
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{accounts(type:"${type}",limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{id, name, type, status, properties{mobile, email}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.accounts.data
            }
            commit(types.FILL_ACCOUNTS, { items: data.data.accounts })

        } catch (e) {
            commit(types.FETCH_ACCOUNT_FAILURE)
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
                             ) {id, uid, name, type, properties{email, mobile}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })

            item = data.data.newAccount

            commit(types.ADD_ACCOUNT, { item })

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

            commit(types.ADD_ACCOUNT, { item })

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

            commit(types.REMOVE_ACCOUNT, { item })

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
