<template>

  <v-flex sm8 md6 lg4 >
    <v-layout justify-center>                  
      <v-flex xs8 sm6>
         <v-layout justify-center mb-10>
            <h1 class="display-3">JuxtPoint</h1>
        </v-layout>
         <v-layout justify-center mb-5>
             <v-icon :color="val.length >= 1 ? 'red' : 'dark'">fiber_manual_record</v-icon>
             <v-icon :color="val.length >= 2 ? 'red' : 'dark'">fiber_manual_record</v-icon>
             <v-icon :color="val.length >= 3 ? 'red' : 'dark'">fiber_manual_record</v-icon>
             <v-icon :color="val.length >= 4 ? 'red' : 'dark'">fiber_manual_record</v-icon>
          </v-layout >
          <v-container fluid grid-list-sm>
                  <v-layout wrap>
                    <v-flex v-for="(key, i) in keys" :key="i" xs4 @click="touched(key)">
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
                              <div class="subheading text-wrap" v-if="key.length < 2">{{ key }}</div>
                               <v-icon color="white">{{key}}</v-icon>
                              
                            </v-flex>
                          </v-layout>

                        </v-img>
                    </v-flex>
                  </v-layout>
           </v-container>
         </v-flex>
        </v-layout> 
       <v-overlay :value="overlay">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>

  </v-flex>
</template>
<script>

import { mapGetters } from 'vuex'

export default {

  data: () => ({
     keys: ['1','2','3','4','5','6','7','8','9','clear','0','backspace',],
     val: '',
     overlay: false,
  }),
  computed: mapGetters({
      users: 'user/users'
  }),
  
  methods: {
     async touched(key) {
       
         if(key=='clear') {
             this.val = ''
            this.$emit('clear') 
            return
          }
          if(key=='backspace') {
              this.val = this.val.slice(0, -(this.val.length - 1))
              return 
          }
          let val = this.val

          val = val.toString() + key.toString()
          
              this.val = val

          this.$emit('change', val)

          if(val.length === 4) {
            this.overlay = true
             await setTimeout(async () => {
      
                const user = this.users.find(user => user.pin === this.val)

                if(!user) {
                    this.val = ''
                     this.overlay = false
                    return
                }
                await this.$store.dispatch('auth/setUser', { user })
          
                this.$router.push({ name: 'index' })
                this.overlay = false
                return
            }, 1000)

          }


    

      },
      close() {
        this.$emit('close')
      }
  }
}
</script>

