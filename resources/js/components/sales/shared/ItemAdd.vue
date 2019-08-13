<template>
  <v-layout justify-center top>
    <v-dialog v-model="show" persistent max-width="350" >
      <v-card style="height:100vh;">
        <v-toolbar flat dark color="primary">
          <v-btn icon dark @click="cancel()">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{item.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
     
            <v-btn icon dark text @click="done()">
                 Add
            </v-btn>
            
      
        </v-toolbar>
        <v-toolbar flat>

               <v-btn  color="success"  large dark @click="inc(1, 'qty')">
                    <v-icon>add</v-icon>
               </v-btn>
               <v-spacer></v-spacer>
               <v-toolbar-title @click="showKeyboard = true" class="display-1" >{{ item.qty }}</v-toolbar-title>
               <v-spacer></v-spacer>
               <v-btn  color="success" large dark @click="inc(-1, 'qty')">
                    <v-icon>remove</v-icon>
               </v-btn>       
        </v-toolbar>

        <v-layout>
            <v-textarea
                  filled
                  auto-grow
                  label="Note"
                  rows="4"
                  row-height="30"
                  shaped
            ></v-textarea>
        </v-layout>
           
      <v-layout px-10>
        <v-combobox
          v-model="item.saleBy"
          :items="['def','abc']"
          chips
          label="Sale Person"
        >
          <template v-slot:selection="data">
            <v-chip
              :key="JSON.stringify(data.item)"
              v-bind="data.attrs"
              :input-value="data.selected"
              :disabled="data.disabled"
              @click.stop="data.parent.selectedIndex = data.index"
              @click:close="data.parent.selectItem(data.item)"
            >
              <v-avatar class="accent white--text" left>
                {{ data.item.slice(0, 1).toUpperCase() }}
              </v-avatar>
              {{ data.item }}
            </v-chip>
          </template>
        </v-combobox>
      </v-layout>
     
              

      </v-card>
    </v-dialog>

  </v-layout>
</template>

<script>

export default {
  data: () => ({
     showKeyboard: false,
     valid: false,
     lazy: false,
     discountFixed: false, 
     value: '0',
     tab: 'tab-1', 
     keys: ['1','2','3','4','5','6','7','8','9','clear','0','done'],
  }),

  props: ['item', 'show'],
  methods: {
      inc(neg, prop) {
          let val = parseFloat(this.item[prop]) + neg
          if(val > 0) this.item[prop] = val
      },
      done() {
     
           this.$emit('done', this.item)
          
      },
      cancel() {
           
          this.$emit('close', this.item)
      },


  }
}
</script>
