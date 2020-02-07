import axios from 'axios'
import { graphql } from '~/config'
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    customer: null,
    customers: []
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
    },
    [types.ADD_CUSTOMER](state, { customer }) {

        const index = state.customers.findIndex(c => c.uid === customer.uid)
        if (index > -1) {
            state.customers[index] = customer
        } else {
            state.customers.push(customer)
        }


    },
    [types.FETCH_CUSTOMER_FAILURE](state) {
        state.customer = null
    },
}

/**
 * Actions
 */
export const actions = {
    async fetchCustomers({ commit }) {
        try {
            const { data } = await axios.get(graphql.path('query'), { params: { query: '{accounts(type:"customer", limit:0, page: 1){data{id, uid, name, status, properties{email, mobile}}}}' } })
            commit(types.FILL_CUSTOMERS, data.data)

        } catch (e) {
            commit(types.FETCH_CUSTOMER_FAILURE)
        }
    },
    async addCustomer({ commit }, customer) {
        try {



            // uniqid

            if (customer.status !== 'offline') {
                var ts = String(new Date().getTime()),
                    i = 0,
                    uniqid = ''
                for (i = 0; i < ts.length; i += 2) {
                    uniqid += Number(ts.substr(i, 2)).toString(36)
                }
                customer.uid = 'T' + customer.uid + '-' + uniqid
            }

            const { name, uid, properties } = customer
            const props = JSON.stringify(properties).replace(/"/g, '\\"')

            const mutation = `mutation accounts{
                             newCustomer(
                                 name: "${name}",
                                 uid: "${uid}",
                                 status: "active",
                                 type: "customer",
                                 properties: "${props}"
                             ) {id, name, uid, status, properties{email, mobile}}}`

            const { data } = await axios.get(graphql.path('query'), { params: { query: mutation } })



            customer = data.data.newCustomer


            commit(types.ADD_CUSTOMER, { customer })
            return customer
        } catch (e) {

            customer.status = 'offline'
            commit(types.ADD_CUSTOMER, { customer })
            return customer
        }
    },
}

/**
 * Getters
 */
export const getters = {
    customers: state => state.customers,
    customer: state => state.customer !== null,
}
