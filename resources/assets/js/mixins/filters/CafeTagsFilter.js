

export const CafeTagsFilter={

    methods:{
        processCafeTagsFilter(cafe,tags) {

            //筛选文本不为空时才会处理
            if(tags.length>0){
                var cafeTags=[];
                //将咖啡店所有标签推送到cafeTags数组中
                for (var i=0;i<cafe.tags.length;i++){
                    cafeTags.push(cafe.tags[i].name);
                }
                // console.log(cafeTags);

                //遍历所有待处理标签，如果标签在cafeTags数组中返回true
                for (var i=0;i<tags.length;i++){
                    if(cafeTags.indexOf(tags[i])>-1){
                        return true;
                    }
                }
                //如果所有待处理标签都不在cafeTags数组中则返回false
                return false;

            }else {
                return true;
            }

        }
    }
}