<style>

</style>

<template>
    <div class="page">
        <form >
            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>名称
                            <input type="text" placeholder="Cafe店名" v-model="name">
                        </label>
                        <span class="validation" v-show="!validations.name.is_valid">{{validations.name.text}}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell" >
                        <label>网址
                            <input type="text" placeholder="网址" v-model="website" value="http://www.baidu.com">
                        </label>
                        <span class="validation" v-show="!validations.website.is_valid">{{validations.website.text}}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>简介
                            <input type="text" placeholder="简介" v-model="description">
                        </label>

                    </div>
                   <div class="large-12 medium-12 small-12 cell">
                        <label for="">图片
                            <input type="file" id="cafe_photo" ref="photo" v-on:change="handleFileUpload()">
                        </label>

                    </div>
                    <div class="grid-x grid-padding-x " v-for="(location,key) in locations">
                        <div class="large-12 medium-12 small-12 cell">
                            <h3>位置</h3>
                        </div>
                       <div class="large-6 medium-6 small-12 cell">
                           <label for="">位置名称
                               <input type="text" placeholder="位置名称" v-model="locations[key].name">
                           </label>
                       </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <label for="">详细地址
                                <input type="text" placeholder="详细地址" v-model="locations[key].address">
                            </label>
                            <span class="validation" v-show="!validations.locations[key].address.is_valid">{{validations.locations[key].address.text}}</span>

                        </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <label for="">城市
                                <input type="text" placeholder="城市" v-model="locations[key].city">
                            </label>
                            <span class="validation" v-show="!validations.locations[key].city.is_valid">{{validations.locations[key].city.text}}</span>
                        </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <label for="">省份
                                <input type="text" placeholder="省份" v-model="locations[key].state">
                            </label>
                            <span class="validation" v-show="!validations.locations[key].state.is_valid">{{validations.locations[key].state.text}}</span>
                        </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <label for="">邮编
                                <input type="text" placeholder="邮编" v-model="locations[key].zip">
                            </label>
                            <span class="validation" v-show="!validations.locations[key].zip.is_valid">{{validations.locations[key].zip.text}}</span>
                        </div>

                        <div class="large-12 medium-12 small-12 cell">
                            <label>支持的冲泡方法</label>
                            <span class="brew-method" v-for="brewMethod in brewMethods">
                                <input type="checkbox" v-bind:id="'brew-method-'+brewMethod.id+'-'+key" v-bind:value="brewMethod.id" v-model="locations[key].methodsAvailable">
                            <label v-bind:for="'brew-method-'+brewMethod.id+'-'+key">{{brewMethod.method}}</label>
                        </span>
                        </div>

                        <div class="large-12 medium-12 small-12 cell">
                            <tags-input v-bind:unique="key"></tags-input>
                        </div>


                        <div class="large-12 medium-12 small-12 cell">
                            <a class="button" v-on:click="removeLocation(key)">移除位置</a>
                        </div>

                    </div>


                    <div class="grid-x grid-padding-x">
                        <div class="large-12 medium-12 small-12 cell">
                            <button class="button" v-on:click="addLocation()">新增位置</button>
                        </div>
                        <div class="large-12  medium-12 small-12 cell">
                            <button class="button" v-on:click="submitNewCafe()">提交</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
    import TagsInput from '../components/global/forms/TagsInput.vue';
    import {EventBus} from "../event-bus.js";

    export default {
        components:{
            TagsInput
        },
        data(){
            return {
                name:'',
                //location用于存放所有新增的位置字段数据
                locations:[],
                website:'',
                //简介
                description:'',
                roaster:false,//烘培师

                //数据前端验证，is_valid字段标识是否验证成功，text字段验证文本
                validations:{
                    name:{
                        is_valid:true,
                        text:''
                    },
                    locations:[],
                    oneLocation:{
                        is_valid:true,
                        text:''
                    },
                    website:{
                        is_valid:true,
                        text:''
                    }
                },
                picture:''

            }

        },
        methods:{
            //上传图片
            handleFileUpload(){
                this.picture=this.$refs.photo.files[0];

            },
            //清除表单数据
            clearForm(){
                this.name='';
                this.locations=[];
                this.website='';
                this.description='';
                this.roaster=false;
                this.picture='';
                this.$refs.photo.value='';
                this.validations={
                    name:{
                        is_valid:true,
                        text:''
                    },
                    locations:[],
                    oneLocation:{
                        is_valid:true,
                        text:''
                    },
                    website:{
                        is_valid:true,
                        text:''
                    }
                };

                EventBus.$emit('clear-tags');

                this.addLocation();


            },
            //移除分店信息
            removeLocation(key){
                this.locations.splice(key,1);
                this.validations.locations.splice(key,1);
            },
            //添加分店信息
            addLocation(){
                this.locations.push({name:'',address:'',city:'',state:'',zip:'',methodsAvailable:[],tags:''});
                this.validations.locations.push({
                    address:{
                        is_valid:true,
                        text:''
                    },
                    city:{
                        is_valid:true,
                        text:''
                    },
                    state:{
                        is_valid:true,
                        text:''
                    },
                    zip:{
                        is_valid:true,
                        text:''
                    },
                });
            },
            //提交新的咖啡店
            submitNewCafe:function () {
                // console.log(1111111);

                if(this.validateNewCafe()){
                    this.$store.dispatch('addCafe',{
                        name:this.name,
                        locations:this.locations,
                        website:this.website,
                        description:this.description,
                        roaster: this.roaster,
                        picture:this.picture
                    });
                }
            },
            //验证
            validateNewCafe:function () {

                let validNewCafeFrom=true;

                for(var index in this.locations){
                    //hasOwnProperty表示是否有自己的属性
                    //确保地址字段不为空
                    if(this.locations.hasOwnProperty(index)){
                        if(this.locations[index].address.trim()===''){
                            validNewCafeFrom=false;
                            this.validations.locations[index].address.is_valid=false;
                            this.validations.locations[index].address.text='请输入新的咖啡店地址';
                        }else{
                            this.validations.locations[index].address.is_valid=true;
                            this.validations.locations[index].address.text='';
                        }
                    }
                    //确保城市字段不为空
                    if(this.locations[index].city.trim()===''){
                        validNewCafeFrom=false;
                        this.validations.locations[index].city.is_valid=false;
                        this.validations.locations[index].city.text='请输入新的咖啡店所在城市';
                    }else{
                        this.validations.locations[index].city.is_valid=true;
                        this.validations.locations[index].city.text='';
                    }

                    //确保省份字段不为空
                    if(this.locations[index].state.trim()===''){
                        validNewCafeFrom=false;
                        this.validations.locations[index].state.is_valid=false;
                        this.validations.locations[index].state.text='请输入新的咖啡店所在城市';
                    }else{
                        this.validations.locations[index].state.is_valid=true;
                        this.validations.locations[index].state.text='';
                    }

                    //确保邮编字段不为空
                    if(this.locations[index].zip.trim()===''){
                        validNewCafeFrom=false;
                        this.validations.locations[index].zip.is_valid=false;
                        this.validations.locations[index].zip.text='请输入新的咖啡店所在城市';
                    }else{
                        this.validations.locations[index].zip.is_valid=true;
                        this.validations.locations[index].zip.text='';
                    }
                }
                if(this.website.trim()!==''&&!this.website.match(/^((https?):\/\/)?([w|W]{3}\.)+[a-zA-Z0-9\-\.]{3,}\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/)){
                    validNewCafeFrom=false;
                    this.validations.website.is_valid=false;
                    this.validations.website.text='请输入有效的URL';
                }else{
                    this.validations.website.is_valid=true;
                    this.validations.website.text='';
                }

                return validNewCafeFrom;
            }
        },
        created(){
            this.addLocation()
        },
        computed:{
            brewMethods(){
                return this.$store.getters.getBrewMethods;
            },
            addCafeStatus(){
                return this.$store.getters.getCafeAddStatus;
            }
        },
        watch:{
            'addCafeStatus':function () {
                if(this.addCafeStatus()===2){
                    //添加成功
                    this.clearForm();
                    $("#cafe-add-successfully").show().delay(5000).fadeOut();

                }
                if(this.addCafeStatus()===3){
                    //添加失败

                    $("#cafe-add-unsuccessfully").show().delay(5000).fadeOut();

                }

            }
        },
        mounted() {
            EventBus.$on('tags-edited', function (tagsAdded) {
                this.locations[tagsAdded.unique].tags = tagsAdded.tags;
            }.bind(this));
        }
    }
</script>