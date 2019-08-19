<template>
    <div>
        <v-container v-if="!paid">
       
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
    <v-card-title class="display-4 ">
        <v-spacer></v-spacer>
         <div >{{trxn.footer.charge | currency}} 
         </div>
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
           <span>{{ cash.amount | currency }}</span>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item @click="editCard()">
          <v-list-item-icon>
            <v-icon>payment</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Card</v-list-item-title>
          </v-list-item-content>
           <span>{{ card.amount | currency }}</span>
        </v-list-item>

      </v-list-item-group>
    </v-list>
 
          <v-list>
              <v-list-item>
                  <v-spacer></v-spacer>
                   <v-list-item-content class="title">
                       Received {{ parseFloat(cash.amount) + parseFloat(card.amount) | currency}} 
                   </v-list-item-content>
                 
                  <v-btn dark large fab color="teal" @click="paid = true">
      
                    <v-icon>arrow_forward</v-icon>
                  </v-btn>
              </v-list-item>
     
            </v-list>
  </v-card>



          
        </v-container>



       <v-container v-if="paid">

       
              <v-toolbar>
                   <v-btn icon @click="backToReceive()">
                      <v-icon>arrow_back</v-icon>
                    </v-btn>

                  <v-spacer></v-spacer>
                  <v-text> Thank You </v-text>
                   <v-spacer></v-spacer>
                  <v-toolbar-items>
                  </v-toolbar-items>
              </v-toolbar>

                <v-card
                  class="mx-auto"

                  tile
                >

                  

                        <v-list>
                               <v-list-item>
                      
                                 <v-list-item-content class="title">
                                     Charge  
                                 </v-list-item-content>
                               
                                <span>
                    
                                {{ parseFloat(trxn.footer.charge) | currency}}
                                </span>
                            </v-list-item>
                            <v-list-item>
                 
                                 <v-list-item-content class="title">
                                     Received  
                                 </v-list-item-content>
                               
                                <span>
                    
                                 {{ parseFloat(cash.amount) + parseFloat(card.amount) | currency}}
                                </span>
                            </v-list-item>
                         <v-divider></v-divider>   

                         <v-list-item>
                               
                                 <v-list-item-content class="title">
                                     Change  
                                 </v-list-item-content>
                               
                                <span class="display-3" color="error">{{ trxn.footer.change | currency}}
                                </span>
                            </v-list-item>
                   
                          </v-list>
                   

                </v-card>


          
        </v-container>
        <v-container  v-if="paid">
            <v-card
              class="mx-auto"

              tile
            >

              

                    <v-list>
                           <v-list-item>
                  
                             <v-list-item-content class="title">
                                 Reprint  
                             </v-list-item-content>
                             <v-btn >
                                <v-icon>print</v-icon>
                             </v-btn>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item>
                           <v-list-item-content>
                        
                              <v-text-field
                                    
                                    label="Email"
                                    prepend-inner-icon="email"
                                  ></v-text-field>
 
                            </v-list-item-content>  
                             <v-btn>
                                <v-icon>send</v-icon>
                             </v-btn>
                        </v-list-item>
                   </v-list>
               

            </v-card>
   
        </v-container>
        
         <v-container v-if="paid"> 
                 <v-btn rounded block  large color="primary">Done</v-btn> 
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
      paid: false,
  }),
  components: {
      Keyboard,
  },
  props: ['trxn'],


  methods: {
      back() {
         this.$emit('back')
      },
      backToReceive() {
          this.paid = false
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
      editCard() {
         this.showKeyboard = true
         this.target = this.card
      },
  }
}
</script>
