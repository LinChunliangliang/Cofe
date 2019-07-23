
<style lang="scss">
    @import '~@/abstracts/_variables.scss';


    a.add-cafe-button{
        float: right;
        display: block;
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: $dark-color;
        /*font-size: 20px;*/
        color: white;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 10px;
        padding-right: 10px;
    }

</style>



<template>
    <div class="grid-container">
        <div class="grid-x">
            <div class="large-12 medium-12 small-12 column">
                <router-link :to="{name:'newcafe'}" v-if="user!==''&& userLoadStatus===2" class="add-cafe-button">+新增咖啡店</router-link>
                <a class="add-cafe-button" v-if="user===''&& userLoadStatus === 2" v-on:click="login()">登陆后添加咖啡店</a>
            </div>
        </div>

        <cafe-filter></cafe-filter>

        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <loader v-show="cafesLoadStatus==1" :width="100" :height="100"></loader>
                <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
            </div>
        </div>
    </div>

</template>

<script>
    import CafeFilter from '../components/cafes/CafeFilter.vue'
    import CafeCard from '../components/cafes/CafeCart.vue'
    import Loader from '../components/global/Loader.vue'
    import {EventBus} from "../event-bus";
    export default {
        created() {
            this.$store.dispatch('loadCafes');
        },
        /*
        * 定义组件的计算属性
        * */
        computed:{
            //获取cafes加载状态
            cafesLoadStatus(){
                return this.$store.getters.getCafesLoadStatus;
            },
            //获取cafes
            cafes(){
                return this.$store.getters.getCafes;
            },
            userLoadStatus(){
                return this.$store.getters.getUserLoadStatus();
            },
            user(){
                return this.$store.getters.getUser;
            }

        },
        components:{
            CafeFilter,
            CafeCard,
            Loader

        },
        methods:{
            login(){
                EventBus.$emit('prompt-login')
            }
        }
    }
</script>