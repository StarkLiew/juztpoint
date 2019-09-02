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
                 
                  <v-btn dark large fab color="teal" @click="save()" :disabled="valid()">
      
                    <v-icon>arrow_forward</v-icon>
                  </v-btn>
              </v-list-item>
     
            </v-list>
  </v-card>



          
        </v-container>



       <v-container v-if="paid">

       
              <v-toolbar>
            

                  <v-spacer></v-spacer>
                  <span> Thank You </span>
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
                               
                                <span class="display-3" color="error">{{  parseFloat(cash.amount) + parseFloat(card.amount) - trxn.footer.charge | currency}}
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
                                 Print  
                             </v-list-item-content>
                             <v-btn @click="print()">
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
                       <vue-easy-print tableShow style="display: none" ref="easyPrint">
                                  <template slot-scope="func">
                                      <receipt v-model="receipt" :header="company"></receipt>
                                  </template>
                               </vue-easy-print>


            </v-card>
   
        </v-container>
         
         <v-container v-if="paid"> 
                 <v-btn rounded block  large color="primary" @click="done()">Done</v-btn> 
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
import vueEasyPrint from 'vue-easy-print'
import receipt from "./ReceiptTemplate"

export default {

   
  data: () => ({
      showKeyboard: false,
      cash: {amount: 0.00},
      card: {amount: 0.00, ref: ''},
      ewallet: {amount: 0.00, ref: ''},
      transfer: {amount: 0.00, ref: ''},
      target: null,
      paid: false,
      receipt: null,
  }),
  components: {
      Keyboard,
      vueEasyPrint,
      receipt,
  },
  props: ['trxn'],
  computed: mapGetters({
    auth: 'auth/user',
    company: 'system/company',
  }),
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
      editCard() {
         this.showKeyboard = true
         this.target = this.card
      },
      done() {
        /* this.target = null
        this.paid = false
        this.receipt = null */

        this.$emit('done') 
  
      },
      valid() {
     
            return (parseFloat(this.cash.amount) + parseFloat(this.card.amount)) < parseFloat(this.trxn.footer.charge)

      },
      async save() {
         const amount_received = parseFloat(this.cash.amount) + parseFloat(this.card.amount)
         const rounded =  Math.ceil(parseFloat(this.trxn.footer.charge) * 20)/20
         const amount_change = amount_received - rounded

         const {customer, footer, items} = this.trxn 
         let payments = []

         if(this.cash.amount > 0) {
            payments.push({item_id: 1, total_amount: this.cash.amount, note: ''})
         }
         if(this.card.amount > 0) {
              payments.push({item_id: 2, note: this.card.ref , total_amount: this.card.amount })
         }

          const dateObj = new Date()
          let month = dateObj.getUTCMonth() + 1 //months from 1-12
          let day = dateObj.getUTCDate()
          let year = dateObj.getUTCFullYear()
          let hours = dateObj.getUTCHours()
          let minutes = dateObj.getUTCMinutes()
          let seconds = dateObj.getUTCSeconds()

          let user_id = this.auth.id


            let cast_user_id = ("0" + user_id)
            cast_user_id =  cast_user_id.substr(cast_user_id.length - 2)
            day = ("0" + day)
            day =  day.substr(day.length - 2)
            month = ("0" + month)
            month =  month.substr(month.length - 2)

            const sqlYear = year
            year = year.toString().substr(year.toString().length-2)

            hours = ("0" + hours)
            hours =  hours.substr(hours.length - 2)
            minutes = ("0" + minutes)
            minutes =  minutes.substr(minutes.length - 2)
            seconds = ("0" + seconds)
            seconds =  seconds.substr(seconds.length - 2)
         
         const now = `${sqlYear}-${month}-${day} ${hours}:${minutes}:${seconds}`

         const reference = cast_user_id + year + month + day + hours + minutes + seconds 
         const receipt = {
               account_id: customer ? customer.id : 0,
               customer: customer ? customer : null,
               date: now,
               reference: reference,
               transact_by: user_id,
               teller: this.auth,
               discount: {rate: footer.discount.rate, type: footer.discount.type}, 
               discount_amount: footer.discount.amount,
               tax_total: footer.tax,
               service_charge: 0,
               charge: rounded,
               received: amount_received,
               change:  amount_change,
               note:"",
               refund: 0,
               items: items,
               payments: payments,

         }
    
         this.receipt = await this.$store.dispatch('receipt/addReceipt', receipt)

         this.paid = true

      },
      print(){
            this.$refs.easyPrint.print()
      },

  }
}
</script>
