/*activity-start*/
$(document).ready(function(){
    /*活动修改页面对平台选项的设置*/
    var ac_plat = $("#ac_plat").val();
    if (ac_plat) {
        if (ac_plat == 0) {
            $("#plat-qq").attr("checked", true);
            $("#plat-wx").attr("checked", true);
            $("#plat-wb").attr("checked", true);
            $("#plat-self").attr("checked", true);
        }else if(ac_plat == 1){
            $("#plat-qq").attr("checked", true);
        }else if(ac_plat == 2){
            $("#plat-wx").attr("checked", true);
        }else if(ac_plat == 3){
            $("#plat-wb").attr("checked", true);
        }else if(ac_plat == 4){
            $("#plat-qq").attr("checked", true);
            $("#plat-wx").attr("checked", true);
        }else if(ac_plat == 5){
            $("#plat-qq").attr("checked", true);
            $("#plat-wb").attr("checked", true);
        }else if(ac_plat == 6){
            $("#plat-wx").attr("checked", true);
            $("#plat-wb").attr("checked", true);
        }else if(ac_plat == 7){
            $("#plat-self").attr("checked", true);
        }
    }
    var ac_anonymous = $("#ac_anonymous").val();
    if (ac_anonymous == '1') {
        $("#anonymous").attr("checked", true);
    }
});

$(document).ready(function(){
    /*提交表单——修改活动*/
    $("#change-ac").click(function() {
        /*activity-plat 判断*/
        var wx   = $("#plat-wx").prop("checked");
        var wb   = $("#plat-wb").prop("checked");
        var qq   = $("#plat-qq").prop("checked");
        var self = $("#plat-self").prop("checked");
        if (wx && wb && qq) {
            var ac_plat = 0;
        }else if(!wx && !wb && qq){
            var ac_plat = 1;
        }else if(wx && !wb && !qq){
            var ac_plat = 2;
        }else if(!wx && wb && !qq){
            var ac_plat = 3;
        }else if(wx && !wb && qq){
            var ac_plat = 4;
        }else if(!wx && wb && qq){
            var ac_plat = 5;
        }else if(wx && wb && !qq){
            var ac_plat = 6;
        }else if(!wx && !wb && !qq && self){
            var ac_plat = 7;
        }else if (!wx && !wb && !qq && !self) {
            //终止提交弹出提示
            $("#warning-value").text("请勾选用户来源！");
            $("#alert-warning").show(500);
            return;
        }
        var anonymous = $("#anonymous").prop("checked");
        if (anonymous) { anonymous = 1; } else {anonymous = 0; }
        //活动名、主办方、活动时间、活动简介、票数、周期检查
        var activity_name   = $("#activity-name").val();
        var activity_host   = $("#activity-host").val();
        var activity_start  = $("#activity-start-time").val();
        var activity_end    = $("#activity-end-time").val();
        var activity_intro  = $("#activity-intro").val();
        var activity_vote   = $("#activity-vote").val();
        var activity_cycle  = $("#activity-cycle").val();
        var activity_key    = $("#activity-key").val();
        var activity_rule1  = $("#rule1").val();
        var activity_rule2  = $("#rule2").val();
        var activity_rule3  = $("#rule3").val();
        var activity_rule4  = $("#rule4").val();
        var activity_rule5  = $("#rule5").val();
        if (activity_name.length <=0 || activity_host.length <=0 || activity_intro.length <=0 || !activity_vote || !activity_cycle) {
            $("#warning-value").text("信息不能为空！");
            $("#alert-warning").show(500);
            return;
        }else if(!activity_start || !activity_end){
            $("#warning-value").text("日期不能为空！");
            $("#alert-warning").show(500);
            return;
        }else if(activity_start > activity_end){
            $("#warning-value").text("开始时间不能晚于结束时间！");
            $("#alert-warning").show(500);
            return;
        }else if(containSpecial(activity_name) || containSpecial(activity_host) || containSpecial(activity_intro) || containSpecial(activity_rule1) || containSpecial(activity_rule2) ||containSpecial(activity_rule3) ||containSpecial(activity_rule4) ||containSpecial(activity_rule5)){
            $("#warning-value").text("表单信息中存在非法字符！");
            $("#alert-warning").show(500);
            return;
        }else if(containSpecial(activity_key) || !activity_key){
            $("#warning-value").text("未知错误发生了，请刷新页面！");
            $("#alert-warning").show(500);
            return;
        }else{
            var ac_name  = activity_name;
            var ac_host  = activity_host;
            var ac_intro = activity_intro;
            var ac_start = activity_start;
            var ac_end   = activity_end;
            var ac_vote  = activity_vote;
            var ac_cycle = activity_cycle;
            var ac_key   = activity_key;
            // rule数据处理
            var rule_array = new Array();
            if (activity_rule1) rule_array.push(activity_rule1);
            if (activity_rule2) rule_array.push(activity_rule2);
            if (activity_rule3) rule_array.push(activity_rule3);
            if (activity_rule4) rule_array.push(activity_rule4);
            if (activity_rule5) rule_array.push(activity_rule5);
            var ac_rules = JSON.stringify(rule_array);
            $.ajax({
                url:"../../../changeActivity.php",
                type:"post",
                data:{ac_name:ac_name, ac_host:ac_host, ac_intro:ac_intro, ac_start:ac_start, ac_end:ac_end, ac_vote:ac_vote, ac_cycle:ac_cycle, ac_plat:ac_plat, ac_rules:ac_rules, ac_key:ac_key, anonymous:anonymous},
                success:function(data){
                    var jsonD=JSON.parse(data);
                    if (jsonD.code >= 0) {
                        $("#success-value").text(jsonD.msg);
                        $("#alert-success").show(500);
                        setTimeout("window.location.href='activity-view.php'",2000);
                    }else{
                        $("#danger-value").text(jsonD.msg);
                        $("#alert-danger").show(500);
                    }
                },
                error:function(data){
                    alert("未知错误导致失败，请重新创建。");
                }
            });    
        }
    });

    /*提交表单——创建活动*/
    $("#create").click(function() {
        /*activity-plat 判断*/
        var wx   = $("#plat-wx").prop("checked");
        var wb   = $("#plat-wb").prop("checked");
        var qq   = $("#plat-qq").prop("checked");
        var self = $("#plat-self").prop("checked");
        if (wx && wb && qq) {
            var ac_plat = 0;
        }else if(!wx && !wb && qq){
            var ac_plat = 1;
        }else if(wx && !wb && !qq){
            var ac_plat = 2;
        }else if(!wx && wb && !qq){
            var ac_plat = 3;
        }else if(wx && !wb && qq){
            var ac_plat = 4;
        }else if(!wx && wb && qq){
            var ac_plat = 5;
        }else if(wx && wb && !qq){
            var ac_plat = 6;
        }else if(!wx && !wb && !qq && self){
            var ac_plat = 7;
        }else if (!wx && !wb && !qq && !self) {
            //终止提交弹出提示
            $("#warning-value").text("请勾选用户来源！");
            $("#alert-warning").show(500);
            return;
        }
        var anonymous = $("#anonymous").prop("checked");
        if (anonymous) { anonymous = 1; } else {anonymous = 0; }
        //活动名、主办方、活动时间、活动简介、票数、周期检查
        var activity_name   = $("#activity-name").val();
        var activity_host   = $("#activity-host").val();
        var activity_start  = $("#activity-start-time").val();
        var activity_end    = $("#activity-end-time").val();
        var activity_intro  = $("#activity-intro").val();
        var activity_vote   = $("#activity-vote").val();
        var activity_cycle  = $("#activity-cycle").val();
        if (activity_name.length <=0 || activity_host.length <=0 || activity_intro.length <=0 || !activity_vote || !activity_cycle) {
            $("#warning-value").text("信息不能为空！");
            $("#alert-warning").show(500);
            return;
        }else if(!activity_start || !activity_end){
            $("#warning-value").text("日期不能为空！");
            $("#alert-warning").show(500);
            return;
        }else if(activity_start > activity_end){
            $("#warning-value").text("开始时间不能晚于结束时间！");
            $("#alert-warning").show(500);
            return;
        }else if(containSpecial(activity_name) || containSpecial(activity_host) || containSpecial(activity_intro)){
            $("#warning-value").text("表单信息中存在非法字符！");
            $("#alert-warning").show(500);
            return;
        }else{
            var ac_name  = activity_name;
            var ac_host  = activity_host;
            var ac_intro = activity_intro;
            var ac_start = activity_start;
            var ac_end   = activity_end;
            var ac_vote  = activity_vote;
            var ac_cycle = activity_cycle;
            $.ajax({
                url:"../../../createActivity.php",
                type:"post",
                method : "POST",
                data:{ac_name:ac_name, ac_host:ac_host, ac_intro:ac_intro, ac_start:ac_start, ac_end:ac_end, ac_vote:ac_vote, ac_cycle:ac_cycle, ac_plat:ac_plat, anonymous:anonymous},
                success:function(data){
                    var jsonD=JSON.parse(data);
                    if (jsonD.code >= 0) {
                        $('input').val("");
                        $('textarea').val("");
                        $(":checkbox").attr("checked", false);
                        $("#success-value").text(jsonD.msg);
                        $("#alert-success").show(500);
                        setTimeout("window.location.href='activity-view.php'",2000);
                    }else{
                        $("#danger-value").text(jsonD.msg);
                        $("#alert-danger").show(500);
                    }
                    console.log(jsonD);
                },
                error:function(data){
                    alert("未知错误导致失败，请重新创建。");
                }
            });    
        }
    });

    /*表单内容即时检测*/
    // activity
    $("#activity-name").blur(function(){
        var activity_name  = $("#activity-name").val();
        $("#activity-name-error").text("");
        if (containSpecial(activity_name)) {
            $("#ac-name").addClass("has-error");
            $("#activity-name-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }
    })
    $("#activity-name").focus(function(){
        $("#ac-name").removeClass("has-error");
    })

    $("#activity-host").blur(function(){
        var activity_host  = $("#activity-host").val();
        $("#activity-host-error").text("");
        if (containSpecial(activity_host)) {
            $("#ac-host").addClass("has-error");
            $("#activity-host-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }
    })
    $("#activity-host").focus(function(){
        $("#ac-host").removeClass("has-error");
    })

    $("#activity-intro").blur(function(){
        var activity_intro  = $("#activity-intro").val();
        $("#activity-intro-error").text("");
        if (containSpecial(activity_intro)) {
            $("#ac-intro").addClass("has-error");
            $("#activity-intro-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (activity_intro.length > 500) {
                $("#ac-intro").addClass("has-error");
                $("#activity-intro-error").text("超过了500字，请修改。");
            }
        }
    })
    $("#activity-intro").focus(function(){
        $("#ac-intro").removeClass("has-error");
    })

    $("#activity-start-time").blur(function(){
        var activity_start  = $("#activity-start-time").val();
        var activity_end    = $("#activity-end-time").val();
        $(".activity-time-error").text("");
        if (activity_start && activity_end) {
            if (activity_start > activity_end) {
                $(".ac-time").addClass("has-error");
                $(".activity-time-error").text("开始时间晚于结束时间，请修改。");
            }
        }
    })
    $("#activity-start-time").focus(function(){
        $(".ac-time").removeClass("has-error");
    })

    $("#activity-end-time").blur(function(){
        var activity_start  = $("#activity-start-time").val();
        var activity_end    = $("#activity-end-time").val();
        $(".activity-time-error").text("");
        if (activity_start && activity_end) {
            if (activity_start > activity_end) {
                $(".ac-time").addClass("has-error");
                $(".activity-time-error").text("开始时间晚于结束时间，请修改。");
            }
        }
    })
    $("#activity-end-time").focus(function(){
        $(".ac-time").removeClass("has-error");
    })

    $("#rule1").blur(function(){
        var rule  = $("#rule1").val();
        $("#rule1-error").text("");
        if (containSpecial(rule)) {
            $("#ac-rule1").addClass("has-error");
            $("#rule1-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (rule.length > 60) {
                $("#ac-rule1").addClass("has-error");
                $("#rule1-error").text("超过了60字，请修改。");
            }
        }
    })
    $("#rule1").focus(function(){
        $("#ac-rule1").removeClass("has-error");
    })

    $("#rule2").blur(function(){
        var rule  = $("#rule2").val();
        $("#rule2-error").text("");
        if (containSpecial(rule)) {
            $("#ac-rule2").addClass("has-error");
            $("#rule2-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (rule.length > 60) {
                $("#ac-rule2").addClass("has-error");
                $("#rule2-error").text("超过了60字，请修改。");
            }
        }
    })
    $("#rule2").focus(function(){
        $("#ac-rule2").removeClass("has-error");
    })    

    $("#rule3").blur(function(){
        var rule  = $("#rule3").val();
        $("#rule3-error").text("");
        if (containSpecial(rule)) {
            $("#ac-rule3").addClass("has-error");
            $("#rule3-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (rule.length > 60) {
                $("#ac-rule3").addClass("has-error");
                $("#rule3-error").text("超过了60字，请修改。");
            }
        }
    })
    $("#rule3").focus(function(){
        $("#ac-rule3").removeClass("has-error");
    })    

    $("#rule4").blur(function(){
        var rule  = $("#rule4").val();
        $("#rule4-error").text("");
        if (containSpecial(rule)) {
            $("#ac-rule4").addClass("has-error");
            $("#rule4-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (rule.length > 60) {
                $("#ac-rule4").addClass("has-error");
                $("#rule4-error").text("超过了60字，请修改。");
            }
        }
    })
    $("#rule4").focus(function(){
        $("#ac-rule4").removeClass("has-error");
    })    

    $("#rule5").blur(function(){
        var rule  = $("#rule5").val();
        $("#rule5-error").text("");
        if (containSpecial(rule)) {
            $("#ac-rule5").addClass("has-error");
            $("#rule5-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (rule.length > 60) {
                $("#ac-rule5").addClass("has-error");
                $("#rule5-error").text("超过了60字，请修改。");
            }
        }
    })
    $("#rule5").focus(function(){
        $("#ac-rule5").removeClass("has-error");
    })  
    // activity-end



    /*表单内容即时检测end*/     

    /*关闭提示框*/
    $(".alert-block").click(function(){
        $(".alert-block").hide(500);
    })


    
});
/*activity-end*/
/*---------------------------------*/

/*Candidate-start*/
$(document).ready(function(){
    /*candidate页面对type选项的设置*/
    if ($("#select-value").val() == 0) {
        $("#candidate-type").val("图片");
    }else if($("#select-value").val() == 1){
        $("#candidate-type").val("视频");
    }else if ($("#select-value").val() == 2) {
        $("#candidate-type").val("外链");
    }else if ($("#select-value").val() == 3) {
        $("#candidate-type").val("音频");
    }
    var candidate_type = $("#candidate-type").get(0).selectedIndex;
    if (candidate_type == 0) {
        $('.candidate-img-footer').show(200);
        $('#candidate-video-footer').hide();
        $('#candidate-link-footer').hide();
        $('#candidate-audio-footer').hide();
    }else if(candidate_type == 1){
        $('.candidate-img-footer').hide();
        $('#candidate-video-footer').show(200);
        $('#candidate-link-footer').hide();
        $('#candidate-audio-footer').hide();
    }else if(candidate_type == 2){
        $('.candidate-img-footer').hide();
        $('#candidate-video-footer').hide();
        $('#candidate-link-footer').show(200);
        $('#candidate-audio-footer').hide();
    }else if(candidate_type == 3){
        $('.candidate-img-footer').hide();
        $('#candidate-video-footer').hide();
        $('#candidate-link-footer').hide();
        $('#candidate-audio-footer').show(200);
    } 
    // type监听
    $("#candidate-type").change(function(){
        var candidate_type = $("#candidate-type").get(0).selectedIndex;
        if (candidate_type == 0) {
            $('.candidate-img-footer').show(200);
            $('#candidate-video-footer').hide();
            $('#candidate-link-footer').hide();
            $('#candidate-audio-footer').hide();
            $('#candidate-add-upload').removeClass('candidate-link-up');
            $('#candidate-add-upload').removeClass('candidate-audio-up');
            $('#candidate-add-upload').addClass('candidate-img-up');
        }else if(candidate_type == 1){
            $('.candidate-img-footer').hide();
            $('#candidate-video-footer').show(200);
            $('#candidate-link-footer').hide();
            $('#candidate-audio-footer').hide();
            $('#candidate-add-upload').removeClass('candidate-link-up');
            $('#candidate-add-upload').removeClass('candidate-audio-up');
            $('#candidate-add-upload').removeClass('candidate-img-up');
        }else if(candidate_type == 2){
            $('.candidate-img-footer').hide();
            $('#candidate-video-footer').hide();
            $('#candidate-link-footer').show(200);
            $('#candidate-audio-footer').hide();
            $('#candidate-add-upload').removeClass('candidate-audio-up');
            $('#candidate-add-upload').removeClass('candidate-img-up');
            $('#candidate-add-upload').addClass('candidate-link-up');
        }else if(candidate_type == 3){
            $('.candidate-img-footer').hide();
            $('#candidate-video-footer').hide();
            $('#candidate-link-footer').hide();
            $('#candidate-audio-footer').show(200);
            $('#candidate-add-upload').removeClass('candidate-link-up');
            $('#candidate-add-upload').removeClass('candidate-img-up');
            $('#candidate-add-upload').addClass('candidate-audio-up');
        }    
    });

    // 表单内容监听
    $("#candidate-name-input").blur(function(){
        var input_val  = $("#candidate-name-input").val();
        $("#candidate-name-error").text("");
        if (containSpecial(input_val)) {
            $("#candidate-name").addClass("has-error");
            $("#candidate-name-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }
    })
    $("#candidate-name-input").focus(function(){
        $("#candidate-name").removeClass("has-error");
    })

    $("#candidate-contact-input").blur(function(){
        var input_val  = $("#candidate-contact-input").val();
        $("#candidate-contact-error").text("");
        if (containSpecial(input_val)) {
            $("#candidate-contact").addClass("has-error");
            $("#candidate-contact-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }
    })
    $("#candidate-contact-input").focus(function(){
        $("#candidate-contact").removeClass("has-error");
    })

    $("#candidate-key-input").blur(function(){
        var input_val  = $("#candidate-key-input").val();
        $("#candidate-key-error").text("");
        if (containSpecial(input_val)) {
            $("#candidate-key").addClass("has-error");
            $("#candidate-key-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }
    })
    $("#candidate-key-input").focus(function(){
        $("#candidate-key").removeClass("has-error");
    })

    $("#candidate-intro-input").blur(function(){
        var input_val  = $("#candidate-intro-input").val();
        $("#candidate-intro-error").text("");
        if (containSpecial(input_val)) {
            $("#candidate-intro").addClass("has-error");
            $("#candidate-intro-error").text("含有特殊字符：# ^ ( ) [ ] { } | \\ ; : ' 。");
        }else{
            if (input_val.length > 500) {
                $("#candidate-intro").addClass("has-error");
                $("#candidate-intro-error").text("超过了500字，请修改。");
            }
        }
    })
    $("#candidate-intro-input").focus(function(){
        $("#candidate-intro").removeClass("has-error");
    })

    // // 视频监听
    // $("#candidate-video-input").change(function(){
    //     var candidate_video_url  = $("#candidate-video-input").val();
    //     var candidate_video_code = candidate_video_url.match(/(.*)\/(.*?)\.html/);
    //     $("#candidate-video-error").text("");
    //     if (candidate_video_code) {
    //         var video_url = "http://imgcache.qq.com/tencentvideo_v1/player/TPout.swf?vid="+candidate_video_code[2]+"&amp;auto=0"
    //         $('#plugin').attr("src", video_url);
    //         $('#video-content').show(300);
    //     }else{
    //         if (candidate_video_url.length > 0) {
    //             $("#candidate-video").addClass("has-error");
    //             $("#candidate-video-error").text("视频链接格式错误。");
    //         }else{
    //             $('#video-content').hide(300);
    //         }
    //     }  
    // })

    $("#candidate-video-input").blur(function(){
        var candidate_video_url  = $("#candidate-video-input").val();
        var candidate_video_code = candidate_video_url.match(/(.*)\/(.*?)\.html/);
        $("#candidate-video-error").text("");
        if (candidate_video_code) {
            $('#video-content').html('');
            var video_url = "http://imgcache.qq.com/tencentvideo_v1/player/TPout.swf?vid="+candidate_video_code[2]+"&amp;auto=0"
            var html = '<embed width="100%" height="700px" name="plugin" id="plugin" src="'+video_url+'" type="application/x-shockwave-flash">';
            // $('#plugin').attr("src", );
            $('#video-content').html(html);
            $('#video-content').show(300);
        }else{
            $('#plugin').attr("src", '');
            if (candidate_video_url.length > 0) {
                $("#candidate-video").addClass("has-error");
                $("#candidate-video-error").text("视频链接格式错误。");
            }else{
                $('#video-content').hide(300);
            }
        }      
    })
    $("#candidate-video-input").focus(function(){
        $("#candidate-video").removeClass("has-error");
    })   
    


    //  添加候选人-表单提交
    $("#candidate-add-upload").click(function(){
        // 信息校验
        var candidate_name    = $("#candidate-name-input").val();
        var candidate_contact = $("#candidate-contact-input").val();
        var candidate_key     = $("#candidate-key-input").val();
        var candidate_intro   = $("#candidate-intro-input").val();
        var candidate_type    = $("#candidate-type").get(0).selectedIndex;
        var activity_key      = $("#activity-key").val();
        if (candidate_name.length <= 0 || candidate_contact.length <= 0 || candidate_intro.length <= 0) {
            $("#warning-value").text("信息不能为空！");
            $("#alert-warning").show(500);
            return;  
        }
        if (candidate_type == 0) {
            var candidate_media = '';
            if ($('#candidate-img-input').prop('files').length < 1) {
                $("#warning-value").text("请选择至少一张图片！");
                $("#alert-warning").show(500);
                return; 
            }
        }else if (candidate_type == 1) {
            var candidate_media = $("#candidate-video-input").val();
            if (candidate_media.length <= 0) {
                $("#warning-value").text("请填写视频链接！");
                $("#alert-warning").show(500);
                return;
            }else if (!candidate_media.match(/(.*)\/(.*?)\.html/)) {
                $("#warning-value").text("视频链接格式错误，请修改！");
                $("#alert-warning").show(500);
                return;                
            }
        }else if (candidate_type == 2) {
            var candidate_media = $("#candidate-link-input").val();
            if (candidate_media.length <= 0) {
                $("#warning-value").text("请填写外链链接！");
                $("#alert-warning").show(500);
                return;  
            }
            if ($('#candidate-linkcover-input').prop('files').length < 1) {
                $("#warning-value").text("请选择一张图片作为封面！");
                $("#alert-warning").show(500);
                return;  
            }
        }else if (candidate_type == 3) {
            var candidate_media = '';
            if ($('#candidate-audio-input').prop('files').length < 1) {
                $("#warning-value").text("请选择音频文件！");
                $("#alert-warning").show(500);
                return;  
            }
        }
        // 信息校验完毕后先提交表单数据
        $.ajax({
            url:"../../../addCandidate.php",
            type:"post",
            method : "POST",
            data:{candidate_name:candidate_name, candidate_contact:candidate_contact, candidate_key:candidate_key, candidate_intro:candidate_intro, candidate_type:candidate_type, candidate_media:candidate_media, activity_key:activity_key},
            success:function(data){
                var jsonD=JSON.parse(data);
                if (candidate_type == 0 || candidate_type == 2 || candidate_type == 3) {
                    if (jsonD.code >= 0) {
                        $('#candidate-key-input').val(jsonD.msg);
                        $('#candidate-files-upload').click();
                    }else{
                        $("#danger-value").text(jsonD.msg);
                        $("#alert-danger").show(500);
                    }
                }else{
                    if (jsonD.code >= 0) {
                        $("#success-value").text("候选人添加成功，即将跳转！");
                        $("#alert-success").show(500);
                        setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",2000);
                    }
                }
                console.log(jsonD);
            },
            error:function(data){
                alert("未知错误导致失败，请重新创建。");
            }
        });
    })


    //  添加候选人-表单提交
    $("#candidate-change-upload").click(function(){
        // 信息校验
        var candidate_name    = $("#candidate-name-input").val();
        var candidate_contact = $("#candidate-contact-input").val();
        var candidate_key     = $("#candidate-key-input").val();
        var candidate_intro   = $("#candidate-intro-input").val();
        var candidate_type    = $("#candidate-type").get(0).selectedIndex;
        var activity_key      = $("#activity-key").val();
        if (candidate_name.length <= 0 || candidate_contact.length <= 0 || candidate_intro.length <= 0) {
            $("#warning-value").text("信息不能为空！");
            $("#alert-warning").show(500);
            return;  
        }
        if (candidate_type == 0) {
            var candidate_media = '';
        }else if (candidate_type == 1) {
            var candidate_media = $("#candidate-video-input").val();
            if (candidate_media.length <= 0) {
                $("#warning-value").text("请填写视频链接！");
                $("#alert-warning").show(500);
                return;
            }else if (!candidate_media.match(/(.*)\/(.*?)\.html/)) {
                $("#warning-value").text("视频链接格式错误，请修改！");
                $("#alert-warning").show(500);
                return;                
            }
        }else if (candidate_type == 2) {
            var candidate_media = $("#candidate-link-input").val();
            if (candidate_media.length <= 0) {
                $("#warning-value").text("请填写外链链接！");
                $("#alert-warning").show(500);
                return;  
            }
        }else if (candidate_type == 3) {
            var candidate_media = '';
        }
        imgJson = JSON.stringify(imgArray);
        // 信息校验完毕后先提交表单数据
        $.ajax({
            url:"../../../changeCandidate.php",
            type:"post",
            method : "POST",
            data:{candidate_name:candidate_name, candidate_contact:candidate_contact, candidate_key:candidate_key, candidate_intro:candidate_intro, candidate_type:candidate_type, candidate_media:candidate_media, activity_key:activity_key, imgJson:imgJson},
            success:function(data){
                var jsonD=JSON.parse(data);
                if (candidate_type == 0 || candidate_type == 2 || candidate_type == 3) {
                    if (jsonD.code >= 0) {
                        if (document.getElementById("candidate-img-input").files.length == 0) setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",200);
                        $('#candidate-key-input').val(jsonD.msg);
                        $('#candidate-files-upload').click();
                    }else{
                        $("#danger-value").text(jsonD.msg);
                        $("#alert-danger").show(500);
                    }
                }else{
                    if (jsonD.code >= 0) {
                        $("#success-value").text("候选人修改成功，即将跳转！");
                        $("#alert-success").show(500);
                        setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",2000);
                    }
                }
                console.log(jsonD);
            },
            error:function(data){
                alert("未知错误导致失败，请重新创建。");
            }
        });
    })


});


/*特殊字符检测*/
function containSpecial( s ){      
    var containSpecial = RegExp(/[(\#)(\^)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\')(\)]+/);     
    return ( containSpecial.test(s) );      
}

imgArray = new Array();
function removeImg(fileName){
    imgArray.push(fileName);
}