import 'babel-polyfill'
import Vue from 'vue'

import router from '~/router/index'
import store from '~/store/index'
import App from '$comp/App'
import '~/plugins/index'
import vuetify from '~/plugins/vuetify'
import IdleVue from 'idle-vue'

const eventsHub = new Vue()
Vue.use(IdleVue, {
     eventEmitter: eventsHub,
     idleTime: 30000
})


export const app = new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
  async onIdle() {

      await this.$store.dispatch('auth/logout')
      this.$toast.info('You are logged out.')
      this.$router.push({ name: 'login' })
  },
  onActive() {

  }


}).$mount('#app')
