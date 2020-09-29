<template>

<div>
    <h5 class="text-white" >{{activeUser.name}}</h5>
</div>
    
</template>

<script>
import mixinMethods from '@/mixins'
import {mapState, mapMutations} from 'vuex'

export default {

    computed: {
        ...mapState(['api','tkn', 'activeUser'])
    
    }

    
}
</script>



<style scoped>

</style>