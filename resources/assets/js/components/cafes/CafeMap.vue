<template>

        <div id="cafe-map-container">
            <div id="cafe-map">
                <!--<ul>-->
                    <!--<li v-for="cafe in cafes">-->
                        <!--<span>{{cafe.name}}</span>-->
                        <!--<span>{{cafe.latitude}}</span>-->
                        <!--<span>{{cafe.longitude}}</span>-->
                    <!--</li>-->
                <!--</ul>-->
            </div>
            <cafe-map-filter></cafe-map-filter>
        </div>



    
</template>

<script>
    import {ROAST_CONFIG} from '../../config.js';
    import CafeMapFilter from './CafeMapFilter.vue';
    import {EventBus} from "../../event-bus";
    import {CafeTextFilter} from "../../mixins/filters/CafeTextFilter";
    import {CafeBrewMethodsFilter} from "../../mixins/filters/CafeBrewMethodsFilter";
    import {CafeIsRoasterFilter} from "../../mixins/filters/CafeIsRoasterFilter";

    export default {
        name: "CafeMap",
        components: {
            CafeMapFilter
        },
        mixins:[
           CafeBrewMethodsFilter,
           CafeIsRoasterFilter,
           CafeTextFilter
        ],
        props: {
            //经度
            'latitude':{
               type: Number,
               default: function () {
                  return 113
               }
            },
            //纬度
            'longitude':{
                type: Number,
                default: function () {
                    return 22.5
                }
            },
            //缩放级别
            'zoom':{
                type: Number,
                default: function () {
                    return 8
                }
            },
        },
        data(){
            return {
                //初始化点标记数组
                markers:[],
                infoWindows:[]
            }
        },
        mounted() {
            // this.markers = [];
            this.map=new AMap.Map('cafe-map',{
                center: [this.latitude,this.longitude],
                zoom: this.zoom
            });
            this.clearMarkers();
            this.buildMarkers();

            //监听filter-updated事件过滤点标记
            EventBus.$on('filters-updated',function (filters) {
                this.processFilters(filters);

            }.bind(this))
        },
        computed:{
            cafes(){
                return this.$store.getters.getCafes;
            }

        },
        methods:{
            //筛选窗体x
            processFilters(filters){
                for(var i=0;i<this.markers.length;i++){
                    //如果没有筛选条件，则显示全部
                    if(filters.text==='' && filters.roaster===false && filters.brew_methods.length===0 ){
                        this.markers[i].setMap(this.map);
                    }else{

                        var textPassed=false;
                        var brewMethodsPassed=false;
                        var roasterPassed=false;

                        //判断是否勾选烘培点
                        if(filters.roaster&& this.processCafeIsRoasterFilter(this.markers[i].getExtData().cafe)){
                            roasterPassed=true;
                        }else if(!filters.roaster){
                            roasterPassed=true;
                        }
                        //判断搜索框内容
                        if(filters.text!=='' && this.processCafeTextFilter(this.markers[i].getExtData().cafe,filters.text)){
                            textPassed=true;
                        }else if(filters.text===''){
                            textPassed=true;
                        }
                        //判断冲泡方式

                        if(filters.brew_methods.length!==0 && this.processCafeBrewMethodsFilter(this.markers[i].getExtData().cafe,filters.brew_methods)){
                            brewMethodsPassed=true;
                        }else if(filters.brew_methods.length===0){
                            brewMethodsPassed=true;
                        }

                        if(roasterPassed&&textPassed&&brewMethodsPassed){
                            this.markers[i].setMap(this.map);
                        }else{
                            this.markers[i].setMap(null);
                        }
                    }

                }

            },

            //为所有咖啡店创建标记点
            buildMarkers(){
                this.markers=[];
                //遍历所有咖啡店并为每个咖啡店创建标记

                var  image=ROAST_CONFIG.APP_URL+'/storage/img/coffee-marker.png';
                var icon=new AMap.Icon({
                    image:image,
                    imageSize:new AMap.Size(19,33)
                });

                var infoWindow =new AMap.InfoWindow();


                for(var i=0;i<this.cafes.length;i++){
                    //通过高德地图API为每个咖啡店创建标记，并且设置经纬度
                    // console.log(this.cafes[i]);
                    // console.log(i);
                    var marker=new AMap.Marker({
                        // position:AMap.LngLat(parseFloat(22),parseFloat(200)),
                        position:new AMap.LngLat(parseFloat(this.cafes[i].latitude),parseFloat(this.cafes[i].longitude)),
                        title:this.cafes[i].location_name,
                        icon:icon,
                        // map:this.map,
                        extData: {
                            'cafe':this.cafes[i]
                        }
                    });

              /*    var infoWindow=new AMap.InfoWindow({
                        content:this.cafes[i].name
                    });*/
              //自定义信息窗体
                    var contentString='<div class="cafe-info-window">' +
                      '<div class="cafe-name">' + this.cafes[i].name + this.cafes[i].location_name + '</div>' +
                      '<div class="cafe-address">' +
                      '<span class="street">' + this.cafes[i].address + '</span>' +
                      '<span class="city">' + this.cafes[i].city + '</span> ' +
                      '<span class="state">' + this.cafes[i].state + '</span>' +
                      '<span class="zip">' + this.cafes[i].zip + '</span>' +
                      '<a href="/#/cafes/' + this.cafes[i].id + '">Visit</a>' +
                      '</div>' +
                      '</div>';

                    marker.content=contentString;
                    //点击事件
                    marker.on('click',mapClick);

                    //将每个标记店放在点标记数组中
                    this.markers.push(marker);
                    // this.map.add(marker);
                }
                function mapClick(mapEvent) {
                    infoWindow.setContent(mapEvent.target.content);
                    infoWindow.open(this.getMap(),this.getPosition());

                }

                this.map.add(this.markers);
            },
            clearMarkers(){
                //遍历所有点标记将其设置为null
                for(var i=0;i<this.markers.length;i++){
                    //通过高德地图API为每个咖啡店创建标记，并且设置经纬度
                    this.markers[i].setMap(null);

                }
            }
        },
        watch:{
            cafes(){
                this.clearMarkers();
                this.buildMarkers();
            }
        }
    }
</script>

<style lang="scss">
    div#cafe-map-container {
        position: absolute;
        top: 50px;
        left: 0px;
        right: 0px;
        bottom: 50px;

        div#cafe-map {
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
            width: 100%;
            height: 550px;
        }

        div#cafe-map-filter{
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
            padding: 5px;
            z-index: 999999;
            position: absolute;
            right: 45px;
            top: 50px;
            width: 25%;
        }
        div.cafe-info-window{
            div.cafe-name {
                display: block;
                text-align: center;
                color: chocolate;
                font-family: 'Josefin Sans', sans-serif;
            }
            div.cafe-address {
                display: block;
                text-align: center;
                margin-top: 5px;
                color: gray;
                font-family: 'Lato', sans-serif;
                span.street {
                    font-size: 14px;
                    display: block;
                }
                span.city {
                    font-size: 12px;
                }
                span.state {
                    font-size: 12px;
                }
                span.zip {
                    font-size: 12px;
                    display: block;
                }
                a {
                    color: darkgoldenrod;
                    font-weight: bold;
                }
            }
        }
    }

</style>