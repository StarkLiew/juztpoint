<template>

  <v-flex sm8 md6 lg4 >
    <v-card v-if="!hasToken()" >
      <v-toolbar dark color="primary" flat>
        <v-toolbar-title>Login</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
           <login-form @success="success"></login-form>
      </v-card-text>
    </v-card>

      <pin-form v-if="hasToken() || showPin"></pin-form>
  </v-flex>




</template>

<script>

import { mapGetters } from 'vuex'
import LoginForm from './LoginForm'
import PinForm from './PinForm'
import VueCookies from 'vue-cookies'


export default {
  components: {
    LoginForm,
    PinForm
  },
  data: () => ({
     showPin: false,
     
  }),
  computed: mapGetters({
    registered: 'auth/registered',
  }),
  methods: {
    hasToken(){

         const token = VueCookies.get('JXPT')
         if(token !== null) return true

         return false
    
    },
    async success(data) { 
           
      await this.$store.dispatch('auth/saveToken', data)
      // await this.$store.dispatch('auth/setUser', data)
            /* Fetch latest data */
      await this.$store.dispatch('product/fetchProducts')
      await this.$store.dispatch('account/fetchCustomers')
      await this.$store.dispatch('system/fetchSystem')
      await this.$store.dispatch('user/fetchUsers')


      this.showPin = true
    
   
 
    }
  }
}
</script>

