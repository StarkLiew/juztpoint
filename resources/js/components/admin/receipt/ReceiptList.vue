<template>

 <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" :value="show" :width="350">

      <template v-slot:prepend>
       <v-toolbar dark  dense flat color="secondary">
           <v-btn icon to="{name: 'pos'}"><v-icon>close</v-icon></v-btn>
            <v-spacer></v-spacer>
        
           <v-toolbar-title class="white--text">Receipts</v-toolbar-title>   
           <v-spacer></v-spacer>
          <v-btn icon><v-icon>search</v-icon></v-btn>
        </v-toolbar>

       </template> 
       
       <div v-for="(item, index) in receipts" :key="index" >
        <v-list-item two-line @click="select(item)">
            
            <v-list-item-content>
                  <v-list-item-title v-on="on">{{item.date + 'Z' | moment('timezone','Malaysia/Kuala_Lumpur','hh:mmA')  }}</v-list-item-title>
                 <span>{{item.reference}}</span>
            </v-list-item-content>
            <v-btn icon>{{item.charge | currency}}</v-btn>

          </v-list-item>
          <v-divider></v-divider>
       </div>
   
  </v-navigation-drawer>

</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({

   }),
  props: ['show'],
  components: {

  },
  mounted() {
     
  },
  computed: mapGetters({
    receipts: 'receipt/receipts',
  }),
  watch: { 
  

  },
  methods: {
     select(item) {
       this.$emit('selected', item)
     }
  }
}
</script>
<style>
  .v-navigation-drawer__content {
      max-height: 100vh;
  }
</style>

