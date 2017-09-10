/*遮罩*/
/* Open when someone clicks on the span element */
function openNav1() {
    document.getElementById("myNav-rank").style.width = "100%";
    document.body.style.overflow='hidden';
    document.documentElement.style.overflow='hidden';
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav1() {
    document.getElementById("myNav-rank").style.width = "0%";
    document.body.style.overflow='visible';
    document.documentElement.style.overflow='visible';
}
/* Open when someone clicks on the span element */
function openNav2() {
    document.getElementById("myNav-ques").style.width = "100%";
    document.body.style.overflow='hidden';
    document.documentElement.style.overflow='hidden';
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav2() {
    document.getElementById("myNav-ques").style.width = "0%";
    document.body.style.overflow='visible';
    document.documentElement.style.overflow='visible';
}

/* Open when someone clicks on the span element */
function openNav3() {
    document.getElementById("myNav-login").style.width = "100%";
    document.body.style.overflow='hidden';
    document.documentElement.style.overflow='hidden';
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav3() {
    $(document).ready(function(){$('.fa').hide();})
    document.getElementById("myNav-login").style.width = "0%";
    document.body.style.overflow='visible';
    document.documentElement.style.overflow='visible';
}

$(document).ready(function(){
    $.ajax({
        url: 'api/getIp.php',
        type: 'get',
        success:function(data){
            var jsonD = JSON.parse(data);
            $('#ip').val(jsonD.ip);
        },
        error:function(data){
            alert("发生了错误，请刷新重试!");
        }
    })
    /*点击事件*/
    //返回主页
    $("#return-btn").click(function(){
        var url = "index.php?ackey="+$("#ackey").val();
        window.location.href=url;
    })

    //进行搜索
    $("#search-btn").click(function(){
        var keyword = $("#keyword").val();
        var ackey   = $("#ackey").val();
        if (keyword.length <= 0) {
            alert("搜索关键字不能为空！");
            return;
        }
        $.ajax({
            url:  'api/searchCheck.php?keyword='+keyword+'&cankey='+ackey,
            type: 'get',
            success:function(data){
                var jsonD = JSON.parse(data);
                if (jsonD.code == 0) {
                    $('#button').html('<button id="search-btn" class="mui-btn mui-btn--primary">搜索</button>');
                    $('#search-btn').click();
                }else{
                    alert("没有搜索到结果");
                }
            },
            error:function(data){
                alert("发生了错误，请刷新重试");
            }
        })
    })

    // 跳转到详情页
    $('.btn-href').click(function() {
        cankey = $(this).attr("cankey");
        ackey  = $('#ackey').val();
        window.location.href="index-view.php?ackey="+ackey+"&cankey="+cankey;
    });

    // 外链跳转
    $('.btn-link').click(function() {
        linkurl = $(this).attr("linkurl");
        window.location.href=linkurl;
    });

    // 投票
    var voting = false;
    $('.btn-vote').click(function(){
        if(voting) return false;
        ackey    = $('#ackey').val();
        cankey   = $(this).attr("cankey");
        //用户信息
        openId   = $('#openId').val();
        plat     = $('#plat').val();
        username = $('#username').val();
        ip       = $('#ip').val();
        ///////////////////////////////////////
        if (openId.length <= 0 || plat.length <= 0) {
            $('.fa').show();
            openNav3()
            return;
        }
        ///////////////////////////////////////
        voting   = true;
        $(this).html("ing...");
        var counter    = $(".votes[cankey="+cankey+"]");
        var counterNum = parseInt(counter.html());
        var cur        = $(this);
        $.ajax({
            url:"api/vote.php",
            type:"post",
            data:{openId:openId, cankey:cankey, ackey:ackey, username:username, plat:plat, ip:ip},
            success:function(data){
                console.log(data);
                var jsonD=JSON.parse(data);
                if (jsonD.code >= 0) {
                    alert(jsonD.msg);
                    counterNum++;
                    counter.html(counterNum);
                    voting=false;
                    cur.html("投票");
                }else{
                    alert(jsonD.msg);
                    voting=false;
                    cur.html("投票");
                    return false;
                }
            },
            error:function(data){
                alert("未知错误导致失败，请刷新重试。");
            }            
        });
    })

    // view页面投票
    var voting = false;
    $('#btn-vote').click(function(){
        if(voting) return false;
        ackey    = $('#ackey').val();
        cankey   = $('#cankey').val();
        //用户信息
        openId   = $('#openId').val();
        plat     = $('#plat').val();
        username = $('#username').val();
        ip       = $('#ip').val();
        ///////////////////////////////////////
        if (openId.length <= 0 || plat.length <= 0) {
            $('.fa').show();
            openNav3()
            return;
        }
        ///////////////////////////////////////
        voting   = true;
        $(this).html("ing...");
        var counter    = $(".view-votes");
        var counterNum = parseInt(counter.html());
        var cur        = $(this);
        $.ajax({
            url:"api/vote.php",
            type:"post",
            data:{openId:openId, cankey:cankey, ackey:ackey, username:username, plat:plat, ip:ip},
            success:function(data){
                console.log(data);
                var jsonD=JSON.parse(data);
                if (jsonD.code >= 0) {
                    alert(jsonD.msg);
                    counterNum++;
                    counter.html(counterNum);
                    voting=false;
                    cur.html("投票");
                }else{
                    alert(jsonD.msg);
                    voting=false;
                    cur.html("投票");
                    return false;
                }
            },
            error:function(data){
                alert("未知错误导致失败，请刷新重试。");
            }            
        });
    })
})
