/*
*	url: 		发送地址
*	data： 		数据
*	type: 		发送方式
*	callback: 	回调函数
*/
function ajax(url, data, type, callback)
{
	//创建ajax对象
	var xhr = new XMLHttpRequest();

	//监听ajax状态
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4 && xhr.status == 200) {
			//将返回的信息转化为对象传入到回调函数中
			callback(JSON.parse(xhr.responseText));
		}
	};

	if (type == 'get') {
		//get发送方式
		url += '?' + data 
		xhr.open(type, url);
		xhr.send();

	} else if (type == 'post') {	
		//post发送方式
		xhr.open(type, url);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send(data);

	}
}