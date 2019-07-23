<style lang="scss">
    @import "~@/abstracts/_variables.scss";

    nav.top-navigation{
        background: $white;
        height: 50px;
        border-bottom: 2px solid $dark-color;
        span.logo{
            border-right: 1px solid $dark-color;
            display: block;
            float: left;
            height: 50px;
            line-height: 50px;
            padding: 0px 20px 0px 20px;
            font-family: 'Arial',sans-serif;
            font-weight: bold;
            color: $dark-color;

            &:hover{
                color: white;
                background-color: $dark-color;
            }
        }
        
        ul.links{
            display:block;
            float: left;
            
            li{
                display: inline-block;
                list-style-type: none;
                line-height: 50px;
                
                a{
                    font-family: "Al Nile",sans-serif;
                    font-weight: bold;
                    line-height: $black;
                    
                    &:hover{
                        clear: $dark-color;
                    }
                }
            }
        }

        div.right{
            float: right;
            margin-right: 20px;

            img.avatar{
                width: 40px;
                height: 40px;
                border-radius: 40px;
                margin-top: 5px;
                margin-left: 10px;
            }
            span.logout{
                display: inline-block;
                list-style-type: none;
                line-height: 50px;
                font-family: "Al Nile",sans-serif;
                font-weight: bold;
                cursor: pointer;
                &:hover{
                    color: #3adb76;
                }
            }
            span.login{
                display: inline-block;
                list-style-type: none;
                line-height: 50px;
                font-family: "Al Nile",sans-serif;
                font-weight: bold;
                cursor: pointer;
                &:hover{
                    color: #3adb76;
                }
            }
        }
    }
</style>

<template>
    <nav class="top-navigation">
        <router-link :to="{name:'home'}">
            <span class="logo">Cafe</span>
        </router-link>

        <ul class="links">
            <li>
                <router-link :to="{name:'cafes'}">
                    Cafes
                </router-link>
            </li>

        </ul>
        <ul class="links">
            <li>
                <router-link :to="{name:'newcafe'}">
                    New Cafes
                </router-link>
            </li>
        </ul>

    <!--    <div class="right">
            <img class="avatar" :src="user.avatar" v-show="userLoadStatus===2">
        </div>-->

        <div class="right">
            <img class="avatar" v-if="user !== '' && userLoadStatus === 2" :src="user.avatar" v-show="userLoadStatus === 2"/>
            <router-link :to="{ name: 'profile'}" v-if="user !== '' && userLoadStatus === 2" class="profile">
                个人信息
            </router-link>
            <span class="logout" v-if="user !== '' && userLoadStatus === 2" v-on:click="logout()" >Logout</span>
            <!--<router-link class="logout" :to="{name:'/'}" v-if="user !== '' && userLoadStatus === 2" v-on:click="logout()">+退出</router-link>-->
            <span class="login" v-if="user ===''&& userLoadStatus === 2" v-on:click="login()">Login</span>
        </div>

    </nav>
</template>

<script>
    import {EventBus} from "../../event-bus";

    export default {

        computed:{
            userLoadStatus(){
                return this.$store.getters.getUserLoadStatus();
            },
            user(){
                return this.$store.getters.getUser;
            }
        },
        methods:{
            login(){
                EventBus.$emit('prompt-login');
            },
            logout(){
                this.$store.dispatch('logoutUser');
                window.location='/#/home';
            }
        }

    }
</script>