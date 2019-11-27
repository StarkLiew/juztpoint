
import * as types from '../mutation-types'

/**
 * Initial state
 */
export const state = {
    selected: {
        item: null,
        company: null,
    }
}

/**
 * Mutations
 */
export const mutations = {

    [types.SELECTED_RECEIPT](state, {item, company}) {
        this.selected = {item, company}
    },
}

/**
 * Actions
 */
export const actions = {
 
    selectItem(item, company) {

       commit(types.SELECTED_RECEIPT, { item, company })
    }

}

/**
 * Getters
 */
export const getters = {
    selected: state => state.selected,

}
