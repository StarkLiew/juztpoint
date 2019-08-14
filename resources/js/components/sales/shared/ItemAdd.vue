<template>
  <v-layout justify-center top>
    <v-dialog v-model="show" persistent max-width="350" >
      <v-card>
    <v-form
      ref="form"
      v-model="valid"
      :lazy-validation="lazy"
    >

        <v-toolbar flat dark color="primary">
          <v-btn icon dark @click="cancel()">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{item.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
     
            <v-btn icon dark text @click="done()" :disabled="!valid">
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
                  v-model="item.note"
                  shaped
            ></v-textarea>
        </v-layout>
           
      <v-layout px-10>
        <v-combobox
          v-model="item.saleBy"
          :items="users"
          :rules="saleByRules"
          chips
          required
          label="Sale Person"
        >

          <template v-slot:item="{ index, item }">
            <v-list-item-content>
                <v-chip>
                 <v-avatar class="accent white--text" left>
                    {{ item.name.slice(0, 1).toUpperCase() }}
                  </v-avatar>
                  {{ item.name }}
                </v-chip>
            </v-list-item-content>
          </template>

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
                {{ data.item.name.slice(0, 1).toUpperCase() }}
              </v-avatar>
              {{ data.item.name }}
            </v-chip>
          </template>
        </v-combobox>
      </v-layout>
     
              
    </v-form>
      </v-card>
    </v-dialog>

  </v-layout>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
     showKeyboard: false,
     valid: false,
     lazy: false,
     saleByRules: [
        v => !!v || 'Sale person is required',
      ],
     value: '0',
     tab: 'tab-1', 
     keys: ['1','2','3','4','5','6','7','8','9','clear','0','done'],
  }),
  computed: mapGetters({
    users: 'user/users',
    auth: 'auth/user'
  }),
  mounted() {
    this.item.saleBy = this.auth
  },
  props: ['item', 'show'],
  watch: {
     show() {
        this.item.saleBy = this.auth
     },
  },
  methods: {
      inc(neg, prop) {
          let val = parseFloat(this.item[prop]) + neg
          if(val > 0) this.item[prop] = val
      },
      done() {
        if (this.$refs.form.validate()) {
             this.$emit('done', this.item)
        }    
      },
      cancel() {
           
          this.$emit('close', this.item)
      },


  }
}
</script>
