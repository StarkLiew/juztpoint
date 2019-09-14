<template>
    <v-list two-line subheader>
      <v-subheader inset>System</v-subheader>

      <v-list-item
        v-for="item in items"
        :key="item.title"

      >
        <v-list-item-avatar>
          <v-icon
            :class="[item.iconClass]"
            v-text="item.icon"
          ></v-icon>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title v-text="item.title"></v-list-item-title>
          <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
        </v-list-item-content>

        <v-list-item-action>
          <v-btn v-if="item.input.type === 'button'" @click="item.input.click">
            <v-icon color="grey lighten-1">{{ item.icon }}</v-icon>
          </v-btn>

          <v-switch v-if="item.input.type === 'switch'"
              v-model="item.input.model"
              @change="item.input.change"
            ></v-switch>

        </v-list-item-action>
      </v-list-item>

      <v-divider inset></v-divider>

  
    </v-list>

</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
      items: [],
   }),
  props: [],
  components: {

  },
  mounted() {
    this.settings()
  },
  computed: mapGetters({
      offline: 'system/offline',

  }),
  watch: { 
  

  },
  methods: {
     async setOffline() {
         const status =  !this.offline
         await this.$store.dispatch('system/setOffline',{status})
         if(!status) {
             this.refresh()
         }
    
     },
     settings() {
       this.items = [
        { icon: 'wifi_off', iconClass: 'grey lighten-1 white--text', title: 'Offline', subtitle: 'Work in offline mode', input: {type: 'switch', model: this.offline, change: this.setOffline} },
        { icon: 'refresh', iconClass: 'primary lighten-1 white--text', title: 'Sync', subtitle: 'Sync to Backoffice', input: {type: 'button', click: this.refresh} },
      ]
     },
      async refresh() {
    
        this.$emit('overlay', true)

        await setTimeout(async () => {

          /* Upload all offline transaction to server */

          const offline_receipts = this.$store.getters['receipt/receipts'].filter(r => r.status === 'offline')

          for(const r of offline_receipts) {
               await this.$store.dispatch('receipt/addReceipt', r)
          }

          const offline_appointments = this.$store.getters['receipt/appointments'].filter(a => a.status === 'offline')

          for(const a of offline_appointments) {
               await this.$store.dispatch('receipt/addReceipt', a)
          }


          const offline_customers = this.$store.getters['account/customers'].filter(c => c.status === 'offline')
         
          for(const c of offline_customers) {
                console.log(c)
               await this.$store.dispatch('account/addCustomer', c)
          }

         /* Fetch latest data */
          await this.$store.dispatch('user/fetchUsers')
          await this.$store.dispatch('product/fetchProducts')
          await this.$store.dispatch('service/fetchServices')
          await this.$store.dispatch('account/fetchCustomers')
          await this.$store.dispatch('system/fetchSystem')

          

          this.$emit('overlay', false)
        }, 3000)
 


    },
  }
}
</script>


