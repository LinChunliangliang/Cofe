import {ROAST_CONFIG} from '../config.js'

export default {

        /*
        *
        * 发请求获取数据
        * */
        getCafes:function () {
            return axios.get(ROAST_CONFIG.API_URL+'/cafes');
        },
        getCafe:function (cafeID) {
            return axios.get(ROAST_CONFIG.API_URL+'/cafes/'+cafeID)
        },
        postAddNewCafe:function (name,locations,website,description,roaster,picture) {
 /*           console.log(7878);
            console.log(name);
            console.log(locations);
            console.log(website);
            console.log(description);
            console.log(picture)*/
            // console.log(picture);
            console.log(locations);
            // console.log(locations.toArray());
            var formData = new FormData();
            formData.append('name',name);
            formData.append('locations',JSON.stringify(locations));
            formData.append('website',website);
            formData.append('description',description);
            formData.append('roaster',roaster);
            formData.append('picture',picture);

            // console.log(formData);
            // console.log(formData.get('picture'));
            // console.log(formData.get('locations'));

            // console.log(formData.get('locations'));

            // return false;
            //json格式数据



            return axios.post(ROAST_CONFIG.API_URL+'/cafes',
                    formData
                ,
                //说明请求头
                {
                    headers:{
                        'Content-Type': 'multipart/form-data'
                    }
                }
            );

        },
        postLikeCafe:function (cafeID) {
            return axios.post(ROAST_CONFIG.API_URL+'/cafes/'+cafeID+'/like');
        },
        deleteLikeCafe:function (cafeID) {
            return axios.delete(ROAST_CONFIG.API_URL+'/cafes/'+cafeID+'/like');

        },


    // addCafe()
}