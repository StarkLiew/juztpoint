import Vue from 'vue'
import Vuex from 'vuex'
import localforage from 'localforage';
import VuexPersistence from 'vuex-persist'


Vue.use(Vuex)


const vuexLocal = new VuexPersistence({
    storage: localforage,
    asyncStorage: true,
    saveState: (key, state, storage) => Promise.resolve(storage.setItem(key, state)),
    restoreState: (key, storage) => Promise.resolve(storage.getItem(key)),
    
});


const requireContext = require.context('./modules', false, /.*\.js$/)

const modules = requireContext.keys()
    .map(file => [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)])
    .reduce((modules, [name, module]) => {
        if (module.namespaced === undefined) {
            module.namespaced = true
        }

        return { ...modules, [name]: module }
    }, {})



export default new Vuex.Store({
    modules,
    plugins: [vuexLocal.plugin]
})
