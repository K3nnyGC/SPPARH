<template>

<div>

    
     <p @click="option.clic()" v-for="(option, index) in options" :key="index+'sidebar'">
        <font-awesome-icon :icon="option.icon" />
        {{option.name}}
    </p>
</div>
    
</template>

<script>
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'

export default {

    props: { 
        options:{
            type: Array, 
            default: function(){
                return []
            }
        }
          
    },

    computed: {
        ...mapState(['api','tkn', 'activeUser'])
    
    },

    mixins: [mixinMethods],


    methods:{

    }

    
}
</script>



<style scoped>

</style>