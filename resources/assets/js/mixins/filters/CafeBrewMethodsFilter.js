export const CafeBrewMethodsFilter={
    methods:{
        processCafeBrewMethodsFilter(cafe, brewMethods) {
            //如果冲泡方法不为空，则进行处理
            if(brewMethods.length>0){
                var cafeBrewMethods=[];
                for(var i=0;i<cafe.brew_methods.length;i++){
       /*             if(cafe.brew_methods[i]===undefined){
                        break;
                    }*/
                    cafeBrewMethods.push(cafe.brew_methods[i].method)
                }
                // console.log(cafeBrewMethods);
                // console.log(brewMethods);
                //遍历所有待处理的冲泡方法，如果在cafeBrewMethods数组中则返回true
                for (var i=0;i<brewMethods.length;i++){
                    if(cafeBrewMethods.indexOf(brewMethods[i])>-1){
                        return true;
                    }
                }

                return false;
            }else{
                return true;
            }

        }
    }
}