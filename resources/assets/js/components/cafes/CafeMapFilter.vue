<template>
    <div id="cafe-map-filter">
        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="large-12 medium-12 small-12 cell">
                    <label>搜索</label>
                    <input type="text" v-model="textSearch" placeholder="搜索">
                </div>
                <div class="is-roaster-container cell">
                    <input type="checkbox" v-model="isRoaster"><label>烘培店</label>
                </div>
                <div class="brew-methods-container cell">
                    <div class="filter-brew-method" v-on:click="toggleBrewMethodFilter(method.method)" v-bind:class="{'active':brewMethods.indexOf(method.method)>-1}" v-for="method in cafeBrewMethods">
                        {{method.method}}
                    </div>

                </div>

            </div>

        </div>

    </div>
    
</template>

<script>
    import {EventBus} from "../../event-bus";
    export default {
        name: "CafeMapFilter",
        data(){
            return{
                textSearch:'',
                isRoaster:false,
                brewMethods:[],
            }
        },
        computed:{
            cafeBrewMethods(){
                return this.$store.getters.getBrewMethods;
            },
        },
        methods:{
            //定义冲泡方法选中/取消选中处理函数
            toggleBrewMethodFilter(method){
                if(this.brewMethods.indexOf(method)>-1){
                    this.brewMethods.splice(this.brewMethods.indexOf(method),1);
                }else{
                    this.brewMethods.push(method);
                }
            },
            updateFilterDisplay(){
                EventBus.$emit('filters-updated',{
                    text:this.textSearch,
                    tags:[],
                    roaster:this.isRoaster,
                    brew_methods:this.brewMethods
                })
            }
        },
        watch:{
            textSearch(){
                this.updateFilterDisplay();
            },
            isRoaster(){
                this.updateFilterDisplay();
            },
            brewMethods(){
                this.updateFilterDisplay();
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/abstracts/_variables.scss';
    div.filter-brew-method{
        display: inline-block;
        height: 30px;
        text-align: center;
        border: 1px solid #ededed;
        border-radius: 5px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 5px;
        padding-bottom: 5px;
        margin-right: 10px;
        margin-top: 10px;
        cursor: pointer;
        color: #7F5F2A;
        font-family: 'Josefin Sans', sans-serif;
        font-size: 12px;

        &.active{
            border-bottom: 4px solid $primary-color;
        }
    }

</style>