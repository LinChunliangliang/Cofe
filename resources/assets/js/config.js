var api_url='';

var app_url='';


///添加高德的JS API Key
var gaode_maps_js_api_key='108a1a121e660dcf303671c320cf406d';

switch (process.env.NODE_ENV) {
    case 'development':
        api_url='http://www.cofe.com/api/v1';
        app_url='http://www.cofe.com';
        break;
    case 'production':
        api_url='http://www.hello.com/api/v1';
        app_url='http://www.hello.com';
        break;
}
export const ROAST_CONFIG={
    API_URL:api_url,
    APP_URL:app_url,
    GAODE_MAPS_JS_API_KEY: gaode_maps_js_api_key
}