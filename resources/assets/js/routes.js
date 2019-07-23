

import Vue from 'vue';
import VueRoute from 'vue-router'

import Layout from './pages/Layout.vue'
import Home from './pages/Home.vue'
import Cafes from './pages/Cafes.vue'
import NewCafe from './pages/NewCafe.vue'
import Cafe from './pages/Cafe.vue'
import Profile from './pages/Profile.vue'

import store from './store.js'


Vue.use(VueRoute);
function requireAuth(to,from,next) {

    function proceed() {
        //如果用户信息已经加载并且不为空则说明用户已登陆

        if(store.getters.getUserLoadStatus()===2){
            if(store.getters.getUser !==''){
                next();
            }else{
                next('/home');
            }
        }

    }
    if(store.getters.getUserLoadStatus()!==2){
        store.dispatch('loadUser');
        store.watch(store.getters.getUserLoadStatus,function () {
            if(store.getters.getUserLoadStatus()===2){
                proceed();
            }
        });
    }else{
        proceed();
    }

}


//添加前端路由
export default new VueRoute({
    routes:[
        {
            path:'/',
            redirect:{name:'home'},
            name:'layout',
            component:Layout,
            children:[
                {
                    path:'home',
                    name:'home',
                    component: Home
                },
                {
                    path:'cafes',
                    name:'cafes',
                    component:Cafes,
                },
                {
                    path:'cafes/new',
                    name:'newcafe',
                    component:NewCafe,
                    beforeEnter:requireAuth
                },
                {
                    path:'cafes/:id',
                    name:'cafe',
                    component:Cafe,
                },
                {
                    path:'profile',
                    name:'profile',
                    component:Profile,
                    beforeEnter:requireAuth
                }

            ]
        },

    ]
});
