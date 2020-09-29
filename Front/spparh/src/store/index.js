import Vue from 'vue'
import Vuex from 'vuex'

 Vue.use(Vuex)

export default new Vuex.Store({
    state: {
       api: 'https://spparh.tech/api/v2/',  
       tkn: '',
       activeUser:{}
    },
    mutations: {
        setToken: function(state,token){
            state.tkn = token
            localStorage.setItem('localToken', token)
        },
        setUserOnStore: function(state, user){
            state.activeUser = user
            localStorage.setItem('localUser', JSON.stringify(user))
        }

    },
    actions: {
    },
    modules: {
    }
})

