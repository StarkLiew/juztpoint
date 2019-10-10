import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    customer: null,
    customers: [],
    customerCount: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.SET_CUSTOMER](state, { customer }) {
        state.customer = customer
    },
    [types.FILL_CUSTOMERS](state, { accounts }) {
        state.customers = accounts.data
        state.customerCount = accounts.total
    },
    [types.ADD_CUSTOMER](state, { customer }) {
        const index = state.customers.findIndex(c => c.uid === customer.uid)
        if (index > -1) {
            Vue.set(state.customers, index, customer)
        } else {
            state.customers.push(customer)
        }
    },
    [types.REMOVE_CUSTOMER](state, { customer }) {
          const index = state.customers.findIndex(c => c.uid === customer.uid)
          Vue.set(state.customers, index, customer)
          state.customers.splice(index , 1)
    },
    [types.FETCH_CUSTOMER_FAILURE](state) {
        state.customer = null
    },
}

/**
 * Actions
 */
export const actions = {
    async fetchCustomers({ commit }, { search, limit, page, sort, desc }) {
        try {
            const filter = `name: "${search}"`
            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{accounts(type:"customer", limit: ${limit}, page: ${page}, ${filter}, ${sorting}){data{id, uid, name, status, properties{email, mobile}}, total,per_page}}` } })
            commit(types.FILL_CUSTOMERS, data.data)

        } catch (e) {
            commit(types.FETCH_CUSTOMER_FAILURE)
        }
    },
    async addCustomer({ commit }, customer) {
        try {

            // uniqid
            if (!customer.id) {
                var ts = String(new Date().getTime()),
                    i = 0,
                    uniqid = ''
                for (i = 0; i < ts.length; i += 2) {
                    uniqid += Number(ts.substr(i, 2)).toString(36)
                }
                customer.uid = 'B-' + uniqid
                customer.id = -1
            }

            const { id, name, uid, note, properties } = customer
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation accounts{
                             newAccount(
                                 name: "${name}",
                                 uid: "${uid}",
                                 status: "active",
                                 type: "customer",
                                 properties: "${props}"
                             ) {id, uid, name, status, properties{email, mobile}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })
            customer = data.data.newAccount
            commit(types.ADD_CUSTOMER, { customer })

            return customer
        } catch (e) {

            return e
        }
    },
    async trashCustomer({ commit }, customer) {
        try {

            const { uid } = customer

            const mutation = `mutation accounts{
                             newAccount(
                                 uid: "${uid}",
                                 action: "delete"
                             ) {id, uid, name, status, properties{email, mobile}}}`

            await axios.get(graphql.path('query'), { params: { query: mutation } })
         
            commit(types.REMOVE_CUSTOMER, { customer })

            return customer
        } catch (e) {

            return e
        }
    },
}

/**
 * Getters
 */
export const getters = {
    customers: state => state.customers,
    customer: state => state.customer !== null,
    customerCount: state => state.customerCount,
}
