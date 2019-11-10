// buildParamsStr
function buildParamsStr(paramsObj){
    var str = '';
    for (key in paramsObj) {
        // 排除原型中属性
        if (paramsObj.hasOwnProperty(key)) {
            // 对查询字符串中每个参数名称和值用encdoeURIComponent()进行编码
            str += "&" + encodeURIComponent(key) + "=" + encodeURIComponent(paramsObj[key]);
        }
    }
    return str.slice(1);
}

// addURLParams
function addURLParams(url, paramsStr) {
    if(!paramsStr) return url;
    url += (url.indexOf('?') === -1) ? '?' : '&';
    url += paramsStr + '&' + new Date().getTime();
    return url;
}

// create xhr
function createXHR() {
    if (window.ActiveXObject) {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    }
}

/**
 *
 * @param type 请求类型：get,post,...
 * @param url
 * @param dataObj 请求参数：对象字面量{key:value,...}
 * @param callback
 * @param async 是否异步
 */
function ajax(type, url, dataObj, callback, async) {
    var paramsStr = buildParamsStr(dataObj), xhr = null;
    if (type === 'get' && dataObj) {
        url = addURLParams(url, paramsStr);
    }
    xhr = createXHR();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            var status = xhr.status;
            if (xhr.status == 200) {
                //console.log(xhr.responseText);
                callback(xhr.responseText);
            } else {
                console.log("请求异常！状态码：" + xhr.status);
            }
        }
    };
    xhr.open(type, url, true); // open()方法启动一个请求以备发送；
    if (type == 'get') {
        xhr.send(null);
    } else {
        xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send(paramsStr);
    }
}

// 获取市数据 - 根据省code
function provinceChange (code) {
    var s='<option value ="0">请选择市</option>';
    var cityUrl = '/index.php/index/index/cityList';
    if(code != '0'){
        var params = {'code':code};
        ajax('get', cityUrl, params, function fnSucc(str){
            obj = JSON.parse(str);
            for(var i in obj){
                s+=  '<option value="'+obj[i].code+'">'+obj[i].name+'</option>';
            }
            document.getElementById("city").innerHTML=s;
        });
    }else{
        var sq='<option value ="0">请选择区</option>';
        document.getElementById("city").innerHTML=s;
        document.getElementById("county").innerHTML=sq;
    }
}

// 获取区域数据 - 根据市code
function cityChange (code) {
    var s='<option value ="0">请选择区</option>';
    var areaUrl = '/index.php/index/index/areaList';
    if(code != '0'){
        var params = {'code':code};
        ajax('get', areaUrl, params, function fnSucc(str){
            obj = JSON.parse(str);
            for(var i in obj){
                s+=  '<option value="'+obj[i].code+'">'+obj[i].name+'</option>';
            }
            document.getElementById("county").innerHTML=s;
        });
    }else{
        document.getElementById("county").innerHTML=s;
    }
}

window.onload = function(){
    // 获取省数据
    var provinceUrl = '/index.php/index/index/provinceList';
    ajax('get', provinceUrl, {}, function fnSucc(str){
        obj = JSON.parse(str);
        var s='<option value ="0">请选择省</option>';
        for(var i in obj){
            s+=  '<option value="'+obj[i].code+'">'+obj[i].name+'</option>';
        }
        document.getElementById("province").innerHTML=s;
    });
}

//收藏博文
function addCollection(blog_id,member_id){
    if (!member_id){
        alert('您未登录，登录后操作');
        return false;
    }
    var url = '/index.php/index/index/addCollection';
    var params = {'blog_id':blog_id,'member_id':member_id};
    ajax('post', url, params, function fnSucc(str){
        obj = JSON.parse(str);
        if (obj.status >0){
            alert('收藏操作成功');
            window.location.reload(true);
        }else{
            alert('收藏操作失败');
            window.location.reload(true);
        }
        return false;
    });
}

//取消收藏博文
function cancelCollection(blog_id,member_id){
    if (!member_id){
        alert('您未登录，登录后操作');
        return false;
    }
    var url = '/index.php/index/index/cancelCollection';
    var params = {'blog_id':blog_id,'member_id':member_id};
    ajax('post', url, params, function fnSucc(str){
        obj = JSON.parse(str);
        if (obj.status >0){
            alert('取消收藏操作成功');
            window.location.reload(true);
        }else{
            alert('取消收藏操作失败');
            window.location.reload(true);
        }
        return false;
    });
}

//博文点赞
function addFabulous(blog_id,member_id){
    if (!member_id){
        alert('您未登录，登录后操作');
        return false;
    }
    var url = '/index.php/index/index/addFabulous';
    var params = {'blog_id':blog_id,'member_id':member_id};
    ajax('post', url, params, function fnSucc(str){
        obj = JSON.parse(str);
        if (obj.status >0){
            alert('点赞操作成功');
            window.location.reload(true);
        }else{
            alert('点赞操作失败');
            window.location.reload(true);
        }
        return false;
    });
}

//取消点赞
function cancelFabulous(blog_id,member_id){
    if (!member_id){
        alert('您未登录，登录后操作');
        return false;
    }
    var url = '/index.php/index/index/cancelFabulous';
    var params = {'blog_id':blog_id,'member_id':member_id};
    ajax('post', url, params, function fnSucc(str){
        obj = JSON.parse(str);
        if (obj.status >0){
            alert('点赞操作操作成功');
            window.location.reload(true);
        }else{
            alert('点赞操作操作失败');
            window.location.reload(true);
        }
        return false;
    });
}
