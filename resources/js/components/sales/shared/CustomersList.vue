<template>
  <div> 

   <v-card
       max-width="600"
    class="mx-auto"
   >
    
      <v-list dense v-for="(a,i) in alphabets" :key="i">
        <v-subheader>{{a}}</v-subheader>
        <v-list-item-group v-model="customer" color="primary">
          <v-list-item
            v-for="(customer, i) in mapCustomers(a)"
            :key="i"
            @click="selected(customer)"
          >
            <v-list-item-icon>
              <v-icon v-text="'people'"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-text="customer.name"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
        </v-list-item-group>
      </v-list>
    </v-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data: () => ({
    alphabets: ['#','A','B','C','D','E','F','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
    customer: null,
  }),

  computed: mapGetters({
    customers: 'account/customers',

  }),

  methods: {
    mapCustomers(alpha) {
          let customers = this.$store.getters['account/customers']
          return customers.filter(row => row.name.toString().toLowerCase().startsWith(alpha.toLowerCase()))
    },
    selected(customer) {
        this.$emit('selected', customer)

    }
  }
}
</script>
