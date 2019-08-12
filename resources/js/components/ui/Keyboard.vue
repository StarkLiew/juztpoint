<template>
 <v-bottom-sheet v-model="show" :hide-overlay="true">
   <v-layout  justify-center>                  
    <v-flex xs12 sm4>
        <v-container fluid grid-list-sm>
                <v-layout wrap>
                  <v-flex v-for="(key, i) in keys" :key="i" xs4  @click="touched(key)">
                    <v-img
                        :src="``"
                        :lazy-src="``"
                        aspect-ratio="1"
                        v-ripple
                        class="blue lighten-2 v-btn"
                      >
                        <template v-slot:placeholder>
                          <v-layout
                            fill-height
                            align-center
                            justify-center
                            ma-0>
                          </v-layout>
                        </template>

                        <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                          <v-spacer></v-spacer>
                          <v-flex shrink>
                            <div class="subheading text-wrap">{{ key }}</div>
                             <v-icon color="white">{{key}}</v-icon>
                            
                          </v-flex>
                        </v-layout>

                      </v-img>
                  </v-flex>
                </v-layout>
         </v-container>
       </v-flex>
      </v-layout> 
    </v-bottom-sheet>   
</template>

<script>

export default {
  data: () => ({
     valid: false,
     lazy: false,
     discountFixed: false, 
     value: '0',
     tab: 'tab-1', 
     keys: ['1','2','3','4','5','6','7','8','9','clear','0','done'],
  }),
  props: ['item', 'show'],
  methods: {
      touched(key) {
          let deci = false

          let prop = ''
          if(this.tab == 'tab-1') {
             prop = 'qty'
          } 
          if(this.tab == 'tab-2') {
              prop = 'discount' 
              deci = true
          }
          if(this.tab == 'tab-3') {
             prop = 'price'
            deci = true
          }

          if(key=='clear') {
              this.value = '0'
              this.item[prop] = ''
              if(this.tab == 'tab-1') this.item[prop] = '1'  
              return
          }
          if(key=='done') {
            this.$emit('done')
            return
          }

          let val = this.value

       
          if(deci) {
  
            val = val.toString().replace('.', '')
            val = val.toString() + key
  
            val = parseFloat(Math.round(val) / 100).toFixed(2);
          } else {
            val += key
            val = parseInt(val)
          }
          this.value = val
          this.item[prop] = val


      },
      inc(neg, prop) {
          let val = parseFloat(this.item[prop]) + neg
          if(val > 0) this.item[prop] = val
      },
      done() {
           this.$emit('done', this.item)
      },
      cancel() {
           this.$emit('cancel') 
      },
  }
}
</script>
