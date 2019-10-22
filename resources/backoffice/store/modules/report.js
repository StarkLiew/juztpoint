import axios from 'axios'
import Vue from 'Vue'
import { graphql } from '~~//config'
import * as types from '../mutation-types'
import moment from 'moment'

/**
 * Initial state
 */
export const state = {
    items: [],
    count: 0,
}

/**
 * Mutations
 */
export const mutations = {
    [types.FILL_REPORT_ITEMS](state, { items }) {

        state.items = items.data
        state.count = items.total
    },
    [types.FETCH_REPORT_FAILURE](state) {
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
        commit(types.FETCH_REPORT_FAILURE)
    },
    async fetch({ commit }, { name, fields, dates, location, user, terminal, limit, page, sort, desc, noCommit }) {

        if (!noCommit) commit(types.FETCH_REPORT_FAILURE) //reset
        try {
            let _from = ''
            let _to = ''
            if (dates && dates.length === 2) {
                _from = moment(dates[0]).format('YYYY-MM-DD')
                _to = moment(dates[1]).format('YYYY-MM-DD')
            }

            const sorting = `sort: "${sort[0] ? sort[0] : 'name'}", desc: "${!desc[0] ? '' : 'desc'}"`
            const { data } = await axios.get(graphql.path('query'), { params: { query: `{reports(from:"${_from}",to:"${_to}",name:"${name}",limit: ${limit}, page: ${page}, ${sorting}){data{${fields}}, total,per_page}}` } })
            if (noCommit) {
                return data.data.reports
            }
            commit(types.FILL_REPORT_ITEMS, { items: data.data.reports })

        } catch (e) {
            commit(types.FETCH_REPORT_FAILURE)
        }
    },




}

/**
 * Getters
 */
export const getters = {
    items: state => state.items,
    count: state => state.count,

}

