
require('es6-promise').polyfill();

import Vue from 'vue'
import Vuex from 'vuex'
//告知Vue使用Vuex作为数据存储器
Vue.use(Vuex);

import {cafes} from "./modules/cafes.js";
import {brewMethods} from './modules/brewMethods';
import {users} from './modules/users.js';


const store=new Vuex.Store({
    modules:{
        cafes,
        brewMethods,
        users
    }
});
export default store
