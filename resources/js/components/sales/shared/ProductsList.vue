<template>
    <div>
        <v-container grid-list-sm fluid>
          <v-layout wrap>


        <v-flex 
              xs4 sm2 md2
              d-flex
              child-flex
              v-if="(show === 'service' || show === 'product') && !filter"
              @click="swap('calendar')"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn light-blue"
                >
                    <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap"><v-icon>event_available</v-icon><br />Appointment</div>
                              </v-overlay>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
           </v-flex>



        <v-flex 
              xs4 sm2 md2
              d-flex
              child-flex
              v-if="show === 'product' && !filter"
              @click="swap('service')"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn red darken-1"
                >
                    <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                   <div class="subheading text-wrap"><v-icon>build</v-icon><br />Service</div>
                              </v-overlay>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
           </v-flex>

        <v-flex 
              xs4 sm2 md2
              d-flex
              child-flex
              v-if="show === 'service' && !filter"
              @click="swap('product')"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn green"
                >
                    <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                  <div class="subheading text-wrap"><v-icon>watch</v-icon><br />Product</div>
                              </v-overlay>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
           </v-flex>



          <v-flex
              xs4 sm2 md2
              d-flex
              child-flex
              v-if="show !== 'category' && show !== 'calendar'  && !filter"
              @click="swap('category', show)"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn cyan"
                >
                    <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                      <div class="subheading text-wrap"><v-icon>all_inbox</v-icon><br />Category</div>
                              </v-overlay>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>



            <v-flex v-if="show === 'product' && !filter"
              v-for="(product, index) in filterProducts(search)"
              :key="index"
              xs4 sm2 md2
              d-flex
              child-flex
              @click="selected(product)"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  :src="product.properties.thumbnail ? product.properties.thumbnail :  ``"
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn"
                  :class="[product.properties.color ? product.properties.color : 'blue']"
                >
                      <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap">{{ product.name }}</div>
                              </v-overlay>

                     
                      
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>


            <v-flex v-if="show === 'service' && !filter"
              v-for="(service, index) in filterServices(search)"
              :key="index"
              xs4 sm2 md2
              d-flex
              child-flex
              @click="selected(service)"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  :src="service.properties.thumbnail ? service.properties.thumbnail :  ``"
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn"
                  :class="[service.properties.color ? service.properties.color : 'blue']"
                >
                      <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap">{{ service.name }}</div>
                              </v-overlay>

                     
                      
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>


          <v-flex  v-if="show === 'category'"
              xs4 sm2 md2
              d-flex
              child-flex
            >
              <v-card flat tile class="d-flex">

                <v-img
                 
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn cyan"
                  @click="swap(back)"
                >
                    <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap"><v-icon>backspace</v-icon></div>
                              </v-overlay>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>


            <v-flex v-if="show === 'category' && !filter"
              v-for="(category, index) in categories"
              :key="index"
              xs4 sm2 md2
              d-flex
              child-flex
              @click="selectFilter(category)"
            >
              <v-card flat tile class="d-flex">

                <v-img
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn blue"
                >
                      <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap">{{ category.name }}</div>
                              </v-overlay>

                     
                      
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>

       <v-flex v-if="show === 'category' && filter"
              v-for="(item, index) in filterItems(filter)"
              :key="index"
              xs4 sm2 md2
              d-flex
              child-flex
              @click="selected(item)"
            >
              <v-card flat tile class="d-flex">

                <v-img
                :src="item.properties.thumbnail ? item.properties.thumbnail :  ``"
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn"
                  :class="[item.properties.color ? item.properties.color : 'blue']"
                >
                      <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <v-overlay
                                absolute
                                color="grey darken-3"
                              >
                                 <div class="subheading text-wrap">{{ item.name }}</div>
                              </v-overlay>

                     
                      
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>

             
          </v-layout>

          <calendar v-if="show === 'calendar'" @close="swap('product')"></calendar>
        </v-container>
   </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Calendar from './Calendar'
export default {


  data: () => ({
      show: 'product',
      filter: '',
      back: '',
  }),
  components: {
     Calendar: Calendar
  },
  props: ['search'],
  computed: { 

    ...mapGetters({
      products: 'product/collection',
      services: 'service/collection',
      categories: 'system/categories'
    })
  },
  
  methods: {
    selected(product) {
       const defaultItem = {
                             qty: 1,
                             price: 0.00,
                             discount: 0.0,
                             saleBy: null,
                          }
       const item = {...defaultItem, ...product}
       this.$emit('selected', item)
    },
    swap(panel, back = '') {

        this.show = panel
        this.back = back
        this.filter = null    
        if(panel === 'calendar') this.$emit('calendar', true)
        else this.$emit('calendar', false)
    },
    selectFilter(category) {
        this.filter = category
    },
    filterItems(selected) {
        
        if(this.back === 'product' && selected) {
          
            const filters = this.products.filter(p => p.category.id === selected.id)

            return filters
        }
        if(this.back === 'service' && selected) {
              return this.services.filter(p => p.category.id === selected.id)
        }
        return []
    },
    filterProducts(text) {
         if(!text) return this.products
           console.log(text)
          return this.products.filter(p =>  p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()) )
      
    },

    filterServices(text) {
         if(!text) return this.services
          
          return this.services.filter(p =>  p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()) )
      
    }


  }
}
</script>
