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
         <div >{{ rounding(trxn.footer.charge) | currency}} 
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


 <v-dialog
        ref="dialog"
        v-model="modalDateTime"
        persistent
        width="500"

      >
        <template v-slot:activator="{ on }">

             <v-btn dark :disabled="!trxn.customer" large fab color="green"  v-on="on">
                   <v-icon>schedule</v-icon>
             </v-btn>
        </template>
       <v-stepper v-model="appStep" vertical>
 

          <v-stepper-step :complete="appStep > 1" step="1">
            Date
            <small>Set Appointment Date</small>
          </v-stepper-step>

          <v-stepper-items>
            <v-stepper-content step="1">
              <v-row justify="center">
                <v-date-picker v-model="appDate">
                  
                    <div class="flex-grow-1"></div>
                     <v-btn text color="primary" @click="modalDateTime = false">Cancel</v-btn>
                     <v-btn text color="primary" :disabled="!appDate"  @click="appStep = 2">Next</v-btn>
                </v-date-picker>
              </v-row>
            </v-stepper-content>


          <v-stepper-step :complete="appStep > 2" step="2">
            Time
            <small>Set Appointment Time</small>
          </v-stepper-step>

            <v-stepper-content step="2">
               <v-row justify="center">
               <v-time-picker
                  v-model="appTime"
                  
                >
                  
                    <div class="flex-grow-1"></div>
                     <v-btn text color="primary" @click="modalDateTime = false">Cancel</v-btn>
                     <v-btn text color="primary" :disabled="!appTime"  @click="appStep = 3">Next</v-btn>

                </v-time-picker>
               </v-row>
            </v-stepper-content>

          <v-stepper-step  step="3">
            Confirm?
            <small>Confirm Appointment</small>
          </v-stepper-step>

            <v-stepper-content step="3">
              <v-row justify="center">


                   <v-card class="mx-auto">
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title class="headline">{{!trxn.customer ? '' : trxn.customer.name}}</v-list-item-title>
                          <v-list-item-subtitle>
                                  
                                  {{appDate + ' ' + appTime + ':00Z'| moment('timezone', store.properties.timezone.replace(/\\/g, ''), 'dddd, DD/MM/YYYY hh:mmA') }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                       </v-list-item two-line>
                      <v-card-actions>
                         <v-spacer></v-spacer>
                        <v-btn large color="primary" @click="saveAppointment()">Confirm</v-btn>
                        <v-btn large @click="modalDateTime = false">Cancel</v-btn>
                      </v-card-actions>
                    </v-card>



                </v-row>    

            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>




      </v-dialog>


            

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
                    
                                {{ rounding(parseFloat(trxn.footer.charge)) | currency}}
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
                               
                                <span class="display-3" color="error">{{  parseFloat(cash.amount) + parseFloat(card.amount) - rounding(trxn.footer.charge) | currency}}
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
                                      <receipt v-model="receipt" :header="{company, store}"></receipt>
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
      appDate: null,
      appTime: null,
      appStep: 1,
      modalDateTime: false,

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
    store: 'auth/store',
    terminal: 'auth/terminal',
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
      rounding(amount) {
        
         return Math.round(parseFloat(amount) * 20)/20
         

      },
      async save() {
         const amount_received = parseFloat(this.cash.amount) + parseFloat(this.card.amount)
         const rounded =  this.rounding(this.trxn.footer.charge)
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
               account_id: customer ? customer.uid : '',
               terminal_id: this.terminal.id,
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
               rounding: rounded - footer.charge,
               note:"",
               refund: 0,
               items: items,
               payments: payments,

         }
    
         this.receipt = await this.$store.dispatch('receipt/addReceipt', receipt)

         this.paid = true

      },
      saveAppointment() {
        this.done()
         
      },
      print(){
            this.$refs.easyPrint.print()
      },

  }
}
</script>
