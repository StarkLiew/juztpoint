<template>

 <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" :value="show" :width="350" right>

      <template v-slot:prepend>
       <v-toolbar dark  dense flat color="secondary">
           <v-btn icon v-if="show"><v-icon>close</v-icon></v-btn>
            <v-spacer></v-spacer>
        
           <v-toolbar-title class="white--text">{{count}}</v-toolbar-title>   
           <v-spacer></v-spacer>
          <v-btn icon @click="enableRemoveItem()"><v-icon>delete</v-icon></v-btn>
        </v-toolbar>

        <v-list-item two-line>
        <v-btn icon @click="customerToggle()"><v-icon>person_add</v-icon></v-btn>
          <v-list-item-content>
            <v-list-item-title v-if="!customer">Add Customer</v-list-item-title>
            <v-list-item-title v-if="customer">{{ customer.name }}</v-list-item-title>
          </v-list-item-content>
             <v-btn icon @click="removeCustomer()"><v-icon>remove</v-icon></v-btn>
        </v-list-item> 

       <v-divider></v-divider>


       </template> 
       
       <div v-for="(item, index) in items"
              :key="index" >
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
            <v-btn icon v-if="allowRemoveItem" @click="removeItem(index)"><v-icon>remove</v-icon></v-btn>
          </v-list-item>
          <v-divider></v-divider>
       </div>
  

       <v-footer
          flat
          dense
          absolute
          padless
        >
            <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Discount</v-list-item-title>
              </v-list-item-content>

          <v-menu
                absolute
                v-model="editDiscountFooter"
                :close-on-content-click="false"
                :close-on-click="false"
                :min-width="380"
                :nudge-left="30"
                offset-x
                left
              >
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on">{{ footer.discount.amount | currency}}</v-btn>
                </template>

                <discount-add :discount="footer.discount" @done="updateDiscountFooter" @cancel="editDiscountFooter = false"></discount-add>
            </v-menu>


       
            </v-list-item>
             <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Service</v-list-item-title>
              </v-list-item-content>
               <v-btn icon>{{ footer.service.amount  | currency}}</v-btn>
            </v-list-item>
             <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Tax</v-list-item-title>
              </v-list-item-content>
              <v-btn icon>{{ footer.tax | currency}}</v-btn>
            </v-list-item>
     
            <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Charge</v-list-item-title>
              </v-list-item-content>
                <v-btn icon>{{ footer.charge | currency}}</v-btn>
            </v-list-item>

        </v-footer>
        
   
  </v-navigation-drawer>

</template>

<script>
import { mapGetters } from 'vuex'
import ItemEdit from './ItemEdit'
import DiscountAdd from './DiscountAdd'

export default {
  data: () => ({
      items: [],
      allowRemoveItem: false,
      editDiscountFooter: false,
      editItem: [],
      footer: {charge: 0.00, discount: {rate: 0.00, type: 'percent', amount: 0.00}, tax: 0.00, service: {rate: 0.00, type: 'percent', amount: 0.00}}
   }),
  props: ['show', 'customer', 'product'],
  components: {
    ItemEdit,
    DiscountAdd,
  },
  mounted() {

  },
  watch: { 
    product: function(newVal, oldVal) {
      this.allowRemoveItem = false
      if(newVal) {
        //const defaultItem = {qty: 1, price: 0.00, discount: 0.00}

        const item = this.sumAmount({...newVal})
      
        this.items.push(item)  
        setTimeout(() => {
               this.sumTotal()
               let container = this.$el.querySelector(".v-navigation-drawer__content");
               container.scrollTop = container.scrollHeight;
        }, 100);
   
      }     
    }
  },
  computed: {
    count() {
       return this.items.length
    },

  },
  methods: {

    updateDiscountFooter(discount) {
    
        this.footer.discount = discount
        this.sumTotal()
        this.editDiscountFooter = false
    },
    navToggle() {
      this.$emit('nav-toggle')
    },
    customerToggle() {
      this.$emit('customer-toggle')
    },
    removeCustomer() {
       this.$emit('customer-remove')
    },
    enableRemoveItem() {
        this.allowRemoveItem = !this.allowRemoveItem
    },
    removeItem(index) {
       this.items.splice(index, 1)
       this.sumTotal()
    },
    editedItem(item, index) {
      this.items[index] = this.sumAmount(item)
      this.sumTotal()
      this.editItem = []
    },
    sumAmount(item) {

        if(item.properties && !item.properties.price) {
             item.price = item.properties.price = 0.00
        }

        item.amount = item.qty * item.properties.price
        
        if(item.discount) {
     
          if(item.discount.type === 'fix') {
             item.amount =  item.amount - item.discount.rate
             item.discount.amount = item.discount.rate
          } else {
             item.discount.amount = item.amount * item.discount.rate / 100
             item.amount =  item.amount - item.discount.amount
          }
        }


           
        return item
    },
    sumTotal() {
        let total = 0
        let taxTotal = 0
        let discountAmount = 0

        this.items.forEach((item) => {
            total += item.amount
            taxTotal += item.amount * item.tax.properties.rate / 100
        })
        
        if(this.footer.discount.type == "percent") {
            this.footer.discount.amount = total * this.footer.discount.rate /100
        }
        if(this.footer.discount.type == "fix") {
            this.footer.discount.amount = this.footer.discount.rate
        }

        this.footer.charge = (total - this.footer.discount.amount) + taxTotal
        this.footer.tax = taxTotal
        
    },


  }
}
</script>

<style>
  .v-navigation-drawer__content {
      max-height: calc(100vh - (56px * 6));
  }
</style>
