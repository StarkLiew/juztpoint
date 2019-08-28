<template>
  <v-flex sm8 md6 lg4>
    <v-card>
      <v-toolbar dark color="primary" flat>
        <v-toolbar-title>Login</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <login-form @success="success"></login-form>
      </v-card-text>
    </v-card>

    <div class="text-center mt-4">Don't have an account? <router-link :to="{ name: 'register' }">Register</router-link></div>
  </v-flex>
</template>

<script>
import LoginForm from './LoginForm'

export default {
  components: {
    LoginForm
  },
  
  methods: {
    async success(data) { 
           
      await this.$store.dispatch('auth/saveToken', data)
      await this.$store.dispatch('auth/setUser', data)

      /* Fetch latest data */
      await this.$store.dispatch('user/fetchUsers')
      await this.$store.dispatch('product/fetchProducts')
      await this.$store.dispatch('account/fetchCustomers')
      await this.$store.dispatch('system/fetchSystem')

      this.$router.push({ name: 'index' })
    }
  }
}
</script>

