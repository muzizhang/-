//  实现图片预览
$('input[name=smallimg]').change(function(){
    //  获取到当前图片路径
    var file = this.files[0];
    //  将图片转换为二进制流
    var url = getObjectUrl(file);
    //  删除前一个img 框
    var imgID = document.getElementById('imgID');
    console.log(imgID);
    if(imgID != null)
        imgID.remove();
    
    $(this).prev('img').remove();
    //  在input框前添加一个元素
    $(this).before('<img src="'+url+'" width="200" height="200" />');
});
function getObjectUrl(file)
{
    var url = null;
    if(window.createObjectURL != undefined)
    {
        url = window.createObjectUrl(file)
    }
    else if(window.URL != undefined)
    {
        url = window.URL.createObjectURL(file)
    }
    else if(window.webkitURL != undefined)
    {
        url = window.webkitURL.createObjectURL(file)
    }
    return url;
}