

import CafeAPI from '../api/cafe.js';
/*常量作为咖啡店模块*/
export const cafes={
    state:{
        /*咖啡店数组*/

        /*
        * 初始化状态
        * status = 0 -> 数据尚未加载
        * status = 1 -> 数据开始加载
        * status = 2 -> 数据加载成功
        * status = 3 -> 数据加载失败
        * */
        cafes:[],
        cafesLoadStatus:0,
        /*咖啡店单个对象*/
        cafe:{},
        cafeLoadStatus: 0,

        cafeAddStatus:0,
        //监听喜欢
        cafeLikeActionStatus:0,
        // 监听取消喜欢
        cafeUnlikeActionStatus:0,
         //用户是否已经喜欢这个咖啡店
        cafeLiked:false
    },

    actions:{
        /*添加方法来加载所有和单个咖啡店信息*/
        /*
        *
        * commit参数是通过vuex传入
        * */
        loadCafes({commit}){
            commit('setCafesLoadStatus',1);

            CafeAPI.getCafes().then(function (response) {
                commit('setCafes',response.data);
                commit('setCafesLoadStatus',2)
            }).catch(function () {
                commit('setCafes',[]);
                commit('setCafesLoadStatus',3)
            });
        },
        /*data是一个对象，包含想要加载的咖啡店的id*/
        loadCafe({commit},data){
            commit('setCafeLikedStatus',false);
            commit('setCafeLoadStatus',1);

            CafeAPI.getCafe(data.id).then(function (response) {
                commit('setCafe',response.data);
                if (response.data.user_like.length>0){
                    commit('setCafeLikedStatus',true)
                }
                commit('setCafeLoadStatus',2);
            }).catch(function () {
                commit('setCafe',{});
                commit('setCafeLoadStatus',3)
            });

            /*commit函数是用于提交一个mutation*/
        },
        addCafe({commit,state,dispatch},data){
            //状态表示开始添加
            commit('setCafeAddStatus',1);
            // console.log(data);

            CafeAPI.postAddNewCafe(data.name,data.locations,data.website,data.description,data.roaster,data.picture)
                .then(function (response) {
                    //状态2表示添加成功
                    commit('setCafeAddStatus',2);
                    dispatch('loadCafes')
                }).catch(function () {
                //状态3表示添加失败
                    commit('setCafeAddStatus',3);
            });

        },
        likeCafe({commit,state},data){
            commit('setCafeLikeActionStatus',1);

            CafeAPI.postLikeCafe(data.id).then(function (response) {
                commit('setCafeLikeActionStatus',2);
                commit('setCafeLikedStatus',true);
            }).catch(function () {
                commit('setCafeLikeActionStatus',3);
            })
        },
        unlikeCafe({commit,state},data){
            commit('setCafeUnlikeActionStatus',1);

            CafeAPI.deleteLikeCafe(data.id).then(function (response) {
                commit('setCafeUnlikeActionStatus',2);
                commit('setCafeLikedStatus',false);
            }).catch(function () {
                commit('setCafeUnlikeActionStatus',3);
            })
        },
    },
    //每个 mutation 中，我们将局部模块的 state 数据设置为传入的更新后数据，这也正是每个 mutation 所要做的操作
    mutations:{
        setCafesLoadStatus(state,status){
            state.cafesLoadStatus=status;
        },
        setCafes(state,cafes){
            state.cafes=cafes;
        },
        setCafeLoadStatus(state, status) {
            state.cafeLoadStatus=status;
        },
        setCafe(state, cafe) {
            state.cafe=cafe;
        },
        setCafeAddStatus(state, status) {
            state.cafeLoadStatus=status;
        },
        setCafeLikedStatus(state, status){
            state.cafeLiked=status;
        },
        setCafeLikeActionStatus(state, status) {
            state.cafeLikeActionStatus=status;
        },
        setCafeUnlikeActionStatus(state, status) {
            state.cafeUnlikeActionStatus=status;
        }


    },
    //每个 getter 方法都会传入一个局部模块 state 作为参数并返回相应的 state 数据，这就是 getters 所做的全部工作
    getters:{
        getCafesLoadStatus(state){
            return state.cafesLoadStatus;
        },
        getCafes(state){
            return state.cafes;
        },
        getCafeLoadStatus(state){
            return state.cafeLoadStatus;
        },
        getCafe(state){
            return state.cafe;
        },
        //新增
        getCafeAddStatus(state) {
            return state.cafeAddStatus;
        },

        getCafeLikeActionStatus(state) {
            return state.cafeLikeActionStatus;
        },
        getCafeUnlikeActionStatus(state) {
            return state.cafeUnlikeActionStatus;
        },
        getCafeLikedStatus(state) {
            return state.cafeLiked;
        }

    }

}