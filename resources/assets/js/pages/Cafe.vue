<style lang="scss">
    @import '~@/abstracts/_variables.scss';

        div.cafe-page {
            h2 {
                text-align: center;
                color: $primary-color;
                font-family: 'Josefin Sans', sans-serif;
            }

            h3 {
                text-align: center;
                color: $secondary-color;
                font-family: 'Josefin Sans', sans-serif;
            }
            div.grid-x{
                div.prompt{
                    text-align: center;
                    span.prompt-log-in{
                        display: inline-block;
                        font-weight: bold;
                        text-decoration: underline;
                        font-size: 20px;
                        cursor: pointer;


                        color: red;
                    }

                }

            }

            span.address {
                text-align: center;
                display: block;
                font-family: 'Lato', sans-serif;
                color: #A0A0A0;
                font-size: 20px;
                line-height: 30px;
                margin-top: 50px;
            }

            a.website {
                text-align: center;
                color: $dull-color;
                font-size: 30px;
                font-weight: bold;
                margin-top: 50px;
                display: block;
                font-family: 'Josefin Sans', sans-serif;
            }

            div.brew-methods-container {
                max-width: 700px;
                margin: auto;

                div.cell {
                    text-align: center;
                }
            }
            div.tags-container {
                max-width: 700px;
                margin: auto;
                text-align: center;
                margin-top: 30px;

                span.tag {
                    color: $dark-color;
                    font-family: 'Josefin Sans', sans-serif;
                    margin-right: 20px;
                    display: inline-block;
                    line-height: 20px;
                }
            }
        }



</style>

<template>

    <div id="cafe" class="page">

        <div class="grid-container">
            <div class="grid-x grid-padding-x">

                <div class="large-12 medium-12 small-12 cell">
                    <loader v-show="cafeLoadStatus===1" :width="100" :hight="100">

                    </loader>
                    <div class="cafe-page" v-show="cafeLoadStatus===2">
                        <h2>{{cafe.name}}</h2>
                        <h3 v-if="cafe.location_name !==''">{{cafe.location_name}}</h3>
                        <div class="grid-x">
                            <div class="prompt large-12 medium-12 small-12 cell">
                                <toggle-like v-if="user!==''&& userLoadStatus===2"></toggle-like>
                                <span class="prompt-log-in" v-if="user==='' && userLoadStatus===2" v-on:click="login()">登陆后喜欢该咖啡店</span>
                            </div>
                        </div>
                        <span class="address">
                            {{cafe.address}}<br>
                            {{cafe.city}},{{cafe.state}}<br>
                            {{cafe.zip}}
                        </span>
                        <a class="website" v-bind:href="cafe.website" target="_blank">{{cafe.website}}</a>

                        <div class="brew-methods-container">
                            <div class="grid-x grid-padding-x">
                                <div class="large-3 medium-4 small-12 cell" v-for="brewMethod in cafe.brew_methods">
                                    {{brewMethod.method}}
                                </div>

                            </div>

                        </div>
                        <div class="tags-container">
                            <div class="grid-x grid-padding-x">
                                <div class="large-12 medium-12 small-12 cell">
                                    <span class="tag" v-for="tag in cafe.tags">#{{ tag.name }}</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <individual-cafe-map></individual-cafe-map>
                    </div>

                </div>

            </div>

        </div>



    </div>
</template>

<script>
    import Loader from '../components/global/Loader.vue';
    import IndividualCafeMap from '../components/cafes/IndividualCafeMap.vue';
    import ToggleLike from '../components/cafes/ToggleLike.vue';
    import {EventBus} from "../event-bus";

    export default {
        created() {
            //通过id获取咖啡店数据
            this.$store.dispatch('loadCafe',{
                id:this.$route.params.id
            });
        },
        components :{
            Loader,
            IndividualCafeMap,
            ToggleLike,


        },
        computed:{
            cafeLoadStatus(){
                return this.$store.getters.getCafeLoadStatus;

            },
            cafe(){
                return this.$store.getters.getCafe;
            },
            userLoadStatus(){
                return this.$store.getters.getUserLoadStatus();
            },
            user(){
                return this.$store.getters.getUser;
            }
        },
        methods:{
            login(){

                console.log(23333);
                EventBus.$emit('prompt-login');
            }
        }

    }
</script>