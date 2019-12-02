import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    user: null,
    users: [],
    userCount: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_USER](state, { user }) {
        state.user = user
    },
    [types.FILL_USERS](state, { users }) {

        state.users = users.data
        state.userCount = users.total
    },
    [types.ADD_USER](state, { user }) {
        const index = state.users.findIndex(u => u.id === user.id)

        if (index > -1) {
            Vue.set(state.users, index, user)
        } else {
            state.users.push(user)
        }
    },
    [types.REMOVE_USER](state, { user }) {
        const index = state.users.findIndex(u => u.id === user.id)
        Vue.set(state.users, index, user)
        state.users.splice(index, 1)
    },
    [types.FETCH_USER_FAILURE](state) {
        state.user = null
    },


}

/**
 * Actions
 */
export const actions = {
    async fetchUsers({ commit }, { search, limit, page, sort, desc, noCommit }) {
        try {
            const filter = `search: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{users(limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{id, avatar, name, email, tenant, properties{role, backoffice}}, total,per_page}}` } })

            if (noCommit) {

                return data.data.users.data
            }
            commit(types.FILL_USERS, data.data)

        } catch (e) {
            commit(types.FETCH_USER_FAILURE)
        }
    },
    async addUser({ commit }, user) {
        try {
            const { name, email, properties } = user
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation users{
                                newUser(
                                    name: "${name}",
                                    email: "${email}",
                                    properties: "${props}"
                             ) {id, avatar, email, name, tenant, properties{backoffice, role}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            user = data.data.newUser

            commit(types.ADD_USER, { user })

            return user
        } catch (e) {

            return e
        }
    },
    async updateUser({ commit }, user) {
        try {
            const { id, name, properties } = user

            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation users{
                               updateUser(
                                    id: ${id},
                                    name: "${name}",
                                    properties: "${props}"
                             ) {id, avatar, email, name, tenant, properties{backoffice, role}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            user = data.data.updateUser

            commit(types.ADD_USER, { user })

            return user
        } catch (e) {

            return e
        }
    },
    async updatePin({ commit }, user) {
        try {
            const { id, pin } = user
            const mutation = `mutation users{assignPin(id:${id}, pin:"${pin}"){id, name}}`
            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation }})
            return data

        } catch (e) {

            return e
        }
    },
    async trashCustomer({ commit }, user) {
        try {

            const { id } = user

            const mutation = `mutation user{trashUser(id: "${id}") {id, uid, name, email}}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })

            commit(types.REMOVE_USER, { user })

            return user
        } catch (e) {

            return e
        }
    },


}

/**
 * Getters
 */
export const getters = {
    users: state => state.users,
    user: state => state.user !== null,
    userCount: state => state.userCount,

}
