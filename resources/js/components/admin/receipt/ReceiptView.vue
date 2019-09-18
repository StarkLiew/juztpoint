<template>
    <div>
         
   
        <v-card v-if="selected" >

				  <v-sheet
				      id="scroll-receipt-content"
				      class="overflow-y-auto"

				    >
				      <v-container class="text-center">
		                  <h1 class="display-2">{{selected.charge | currency}}</h1>
		               	  <p class="caption">Reference: {{ selected.reference }}</p>
		               	  <p class="caption">Teller: {{ selected.teller.name }}</p>

		               	  <p class="subtitle" v-if="selected.customer">{{ selected.customer.name }}</p>
                         <v-divider></v-divider>
					     <div v-for="(item, index) in selected.items" :key="index" >
							        <v-list-item two-line>

							            <v-menu
							                absolute
							                v-model="editItem[index]"
							                :close-on-content-click="false"
							                :close-on-click="false"
							                :min-width="380"
							                :nudge-left="30"
							                offset-x
							                left
							              >
							                <template v-slot:activator="{ on }">
							                   <v-btn @click="editItem = []" icon v-on="on">{{item.qty}}</v-btn>
							                </template>
							                <item-edit :item="item" :show="editItem[index]" :index="index" @done="editedItem" @cancel="editItem = []"></item-edit>
							            </v-menu>
							              
							            <v-list-item-content>
							  
							               <v-tooltip bottom>
							                  <template v-slot:activator="{ on }">
							                      <div>
							                        <v-list-item-title v-on="on">{{item.name}}</v-list-item-title>
							                        <span class="caption" v-if="item.saleBy">
							                              <v-avatar class="accent white--text" left size="16">
							                                     {{ item.saleBy.name.slice(0, 1).toUpperCase() }}
							                              </v-avatar>
							                               {{item.saleBy.name}}
							                        </span>
							                      </div>
							                  </template>
							                  <span>{{item.note}}</span>
							                </v-tooltip>

							            </v-list-item-content>
							            <v-btn icon>{{item.amount | currency}}</v-btn>
							      
							          </v-list-item>
							          <v-divider></v-divider>
							       </div>

                        

						  




				      </v-container>

	           <vue-easy-print tableShow style="display: none" ref="receipt">
                                  <template slot-scope="func">
                                      <receipt v-model="selected" :header="{company, store}"></receipt>
                                  </template>
                               </vue-easy-print>

                


				    </v-sheet>  

			




        </v-card>

        <v-footer v-if="selected" 
						          flat
						          dense
						          padless
						        >

						 	  <v-sheet
						 	  width="100%"
				      id="receipt-footer"
				      class="overflow-y-auto"

				    >       
						            <v-list-item one-line>
						              <v-list-item-content>
						                <v-list-item-title>Discount</v-list-item-title>
						              </v-list-item-content>
						                    <v-btn icon>{{ selected.discount_amount | currency}}</v-btn>
						     
						            </v-list-item>
						             <v-list-item one-line>
						              <v-list-item-content>
						                <v-list-item-title>Service</v-list-item-title>
						              </v-list-item-content>
						               <v-btn icon>{{ selected.service_charge  | currency}}</v-btn>
						            </v-list-item>
						             <v-list-item one-line>
						              <v-list-item-content>
						                <v-list-item-title>Tax</v-list-item-title>
						              </v-list-item-content>
						              <v-btn icon>{{ selected.tax_total | currency}}</v-btn>
						            </v-list-item>

						                <v-list-item one-line >
						                  <v-list-item-content>
						                    <v-list-item-title>Charge</v-list-item-title>
						                  </v-list-item-content>
						                    <v-btn  icon class="caption">{{ selected.charge | currency}}</v-btn>
						                </v-list-item>
                               
                                          
                                        <v-list-item one-line v-for="(payment, index) in selected.payments" :key="index">
						                  <v-list-item-content>
						                    <v-list-item-title>Received {{ payment.name }}</v-list-item-title>
						                  </v-list-item-content>
						                    <v-btn  icon class="caption">{{ payment.total_amount | currency}}</v-btn>
						                </v-list-item>

                                         <v-list-item one-line >

						                  <v-list-item-content>
						                    <v-list-item-title>Refund</v-list-item-title>
						                  </v-list-item-content>
						                    <v-btn  icon class="caption">{{ selected.refund | currency}}</v-btn>
						                </v-list-item>
                                     

                                 </v-sheet>
						              <v-bottom-navigation
									         
					                     horizontal
									  >
									    <v-btn value="recent" @click="print()">
									      <span>Reprint</span>
									      <v-icon>print</v-icon>
									    </v-btn>

									    <v-btn value="favorites">
									      <span>Resend</span>
									      <v-icon>email</v-icon>
									    </v-btn>
						                <v-btn value="nearby">
									      <span>Refund</span>
									      <v-icon>cancel</v-icon>
									    </v-btn>
									    <v-btn value="nearby">
									      <span>Void</span>
									      <v-icon>cancel</v-icon>
									    </v-btn>
									  </v-bottom-navigation>
                                   
						        </v-footer>



         <v-card v-if="!selected">
        </v-card>




    </div>
 </template>
 <script>
    import { mapGetters } from 'vuex'
    import ItemEdit from '../../sales/shared/ItemEdit'
    import vueEasyPrint from 'vue-easy-print'
    import receipt from "../../sales/shared/ReceiptTemplate"

    export default {
    	data: () => ({
           editItem: [],
    	}),
    	components: {
    		ItemEdit,
    		vueEasyPrint,
    		receipt,
    	},
        props:['selected'],
        computed: mapGetters({
		    company: 'system/company',
		    store: 'auth/store',
		}),
        methods: {
			print(){
			    this.$refs.receipt.print()
			},
			editedItem() {
				
			},
        },
    }
 </script> 	
<style>
  #scroll-receipt-content {
      max-height: calc(100vh - (54px + (56px * 6)) );
  }
  #receipt-footer {
  	  max-height: calc(100vh - (54px + (56px * 10)) );
  }
</style>
