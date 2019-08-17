<template>
    <div>
        <v-container>
       
              <v-toolbar>
                   <v-btn icon @click="back()">
                      <v-icon>arrow_back</v-icon>
                    </v-btn>

                  <v-spacer></v-spacer>

                  <v-toolbar-items>
                  </v-toolbar-items>
              </v-toolbar>

  <v-card
    class="mx-auto"

    tile
  >
    <v-card-title class="display-4 text-center">
        <v-spacer></v-spacer>
         {{trxn.footer.charge | currency}}
          <v-spacer></v-spacer>
   </v-card-title>
    <v-list shaped>
    
      <v-list-item-group color="primary" large>
        <v-divider></v-divider>
        <v-list-item @click="editCash()">
          <v-list-item-icon>
            <v-icon>attach_money</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Cash</v-list-item-title>
          </v-list-item-content>
          <v-icon>add</v-icon> <span>{{ cash.amount | currency }}</span>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item>
          <v-list-item-icon>
            <v-icon>payment</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Card</v-list-item-title>
          </v-list-item-content>
          <v-icon>add</v-icon>
        </v-list-item>

      </v-list-item-group>
    </v-list>

          <v-card-actions>
              <v-spacer></v-spacer>

              <v-btn dark large fab color="teal">
                <v-icon>arrow_forward</v-icon>
              </v-btn>

     
            </v-card-actions>
  </v-card>



          
        </v-container>

          <keyboard 
            @done="showKeyboard = false"
            @clear="clear"
            @change="change"
            @close=""
            :decimal="2"  
            :show="showKeyboard">
        </keyboard>

        <v-overlay
            :value="showKeyboard"
            opacity="0"
        >
        </v-overlay>

   </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Keyboard from '../../ui/Keyboard'

export default {

   
  data: () => ({
      showKeyboard: false,
      cash: {amount: 0.00, change: 0.00},
      card: {amount: 0.00, ref: ''},
      ewallet: {amount: 0.00, ref: ''},
      transfer: {amount: 0.00, ref: ''},
      target: null,
  }),
  components: {
      Keyboard,
  },
  props: ['trxn'],


  methods: {
      back() {
         this.$emit('back')
      },
      change(val) {
         this.target.amount = val
      },
      clear() {
         this.target.amount = 0
      },
      editCash() {
         this.showKeyboard = true
         this.target = this.cash

      },
  }
}
</script>
