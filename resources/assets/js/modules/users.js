

import UserAPI from '../api/users.js';

export const users={
    state:{
        user: {},
        userLoadStatus: 0,
        userUpdateStatus:0,
    },

    actions:{
        loadUser({commit}) {
            commit('setUserLoadStatus',1);
            UserAPI.getUser().then(function (response) {
                commit('setUser',response.data);
                commit('setUserLoadStatus',2)
            }).catch(function () {
                commit('setUser',[]);
                commit('setUserLoadStatus',3)
            })
        },
        logoutUser({commit}) {
            // console.log(111111111);
            UserAPI.logout().then(function () {

                commit('setUserLoadStatus', 2);
                commit('setUser', '');
            })

        },
        editUser({commit, state, dispatch}, data) {
            commit('setUserUpdateStatus', 1);
            // console.log(1111);

            UserAPI.putUpdateUser(data.public_visibility, data.favorite_coffee, data.flavor_notes, data.city, data.state)
                .then(function (response) {
                    // console.log(3333);
                    commit('setUserUpdateStatus', 2);
                    dispatch('loadUser');
                })
                .catch(function () {
                    commit('setUserUpdateStatus', 3);
                });
        },


    },
    //每个 mutation 中，我们将局部模块的 state 数据设置为传入的更新后数据，这也正是每个 mutation 所要做的操作
    mutations:{
        setUserLoadStatus(state, status) {
            state.userLoadStatus=status;
        },
        setUser(state, user) {
            state.user=user;
        },
        setUserUpdateStatus(state, status) {
            state.userUpdateStatus = status;
        }

    },
    //每个 getter 方法都会传入一个局部模块 state 作为参数并返回相应的 state 数据，这就是 getters 所做的全部工作

    getters:{

        getUserLoadStatus(state) {
            return function () {
                return state.userLoadStatus;
            }

        },
        getUser(state){
            return state.user;
        },
        getUserUpdateStatus( state, status ){
            return state.userUpdateStatus;
        }

    },


}