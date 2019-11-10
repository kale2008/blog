window.onload = function(){
    var provinceCode = document.getElementById('c_province').value;
    var cityCode = document.getElementById('c_city').value;
    var countyCode = document.getElementById('c_county').value;
    set_select_checked('province', provinceCode);
    set_select_checked('city', cityCode);
    set_select_checked('county', countyCode);
}