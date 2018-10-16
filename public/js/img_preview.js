//  实现图片预览
$('.img_preview').change(function(){
    //  获取到当前图片路径
    var file = this.files[0];
    //  将图片转换为二进制流
    var url = getObjectUrl(file);
    //  获取value值
    var c = $(this).attr('k');
    //  删除前一个img 框
    var imgID = document.getElementsByClassName('imgID');
    console.log(imgID);
    for(var i=0;i<imgID.length;i++)
    {
        if(i == c)
            imgID[i].remove();
    }

    $(this).prev('.divImg').remove();
    //  在input框前添加一个元素
    $(this).before('<div class="divImg"><img class="imgID" src="'+url+'" width="200" height="200" /></div>');
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