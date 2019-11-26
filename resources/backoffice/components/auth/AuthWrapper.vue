<template>
    <v-content>

        <v-container fluid fill-height justify-center align-center>

        <v-app-bar absolute flat color="white">
            <v-toolbar-title v-if="siteUrl">
               <a :href="siteUrl">{{ siteName }}</a>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text color="primary" :to="{ name: 'register' }">
                SIGN UP
            </v-btn>
        </v-app-bar>
        	
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </v-container>
    </v-content>
</template>
<script>
import { settings } from '~~/config'
export default {
    data: () => ({
        siteName: settings.siteName,
        siteUrl: settings.siteUrl,
    }),
}

</script>
