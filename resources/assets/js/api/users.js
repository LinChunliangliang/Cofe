import {ROAST_CONFIG} from '../config.js'

export default {
    /*
    *
    * 发请求获取数据
    * */
    getUser:function () {
        return axios.get(ROAST_CONFIG.API_URL+'/user');
    },
    logout:function () {
        // console.log(33333);
        return axios.get(ROAST_CONFIG.API_URL+'/logout')
    },
    putUpdateUser:function (public_visibility,favorite_coffee,flavor_notes,city,state) {
        // console.log(public_visibility);
        return axios.put(ROAST_CONFIG.API_URL+'/user',
            {

                profile_visibility: public_visibility,
                favorite_coffee: favorite_coffee,
                flavor_notes: flavor_notes,
                city: city,
                state: state
            }
        );
    }


}