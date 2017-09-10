// 背景图上传
var params = {
    fileInput: $("#ac-img-input").get(0),
    upButton: $("#ac-img-up").get(0),
    url: "../../../changeAcImg.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("image") == 0) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是图片。');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        console.log(files);
        $(".alert-block").hide(500);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    html = '<span class="mailbox-attachment-icon has-img"><img src="' + e.target.result + '"/></span>';
                    filename = file.name;
                    $("#rate-img").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                $("#ac-img-img").html(html);
                $("#ac-img-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    onProgress: function(file, loaded, total) {
        var eleProgress = $("#rate-img"), percent = (loaded / total * 100).toFixed(2) + '%';
        eleProgress.show(300).html(percent);
        eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            $("#success-value-img").text(jsonD.msg);
            $("#alert-success-img").show(500);
        }else{
            $("#danger-value-img").text(jsonD.msg);
            $("#alert-danger-img").show(500);
        }
        console.log(jsonD);
    },
    onFailure: function(file) {
        $("#danger-value-img").text("图片上传失败！");
        $("#alert-danger-img").show(500);
    },
    onComplete: function() {
        
    }
};
// logo上传
var params2 = {
    fileInput: $("#ac-logo-input").get(0),
    upButton: $("#ac-logo-up").get(0),
    url: "../../../changeAcLogo.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("image") == 0) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是图片。');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    html = '<span class="mailbox-attachment-icon has-img"><img src="' + e.target.result + '"/></span>';
                    filename = file.name;
                    $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                $("#ac-logo-img").html(html);
                $("#ac-logo-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    onProgress: function(file, loaded, total) {
        var eleProgress = $("#rate-logo"), percent = (loaded / total * 100).toFixed(2) + '%';
        eleProgress.show(300).html(percent);
        eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            $("#success-value-img").text(jsonD.msg);
            $("#alert-success-img").show(500);
        }else{
            $("#danger-value-img").text(jsonD.msg);
            $("#alert-danger-img").show(500);
        }
        console.log(jsonD);
    },
    onFailure: function(file) {
        $("#danger-value-img").text("图片上传失败！");
        $("#alert-danger-img").show(500);
    },
    onComplete: function() {
        
    }
};
// 导入投票者
var params3 = {
    fileInput: $("#leadVoter").get(0),
    upButton: $("#leadVoter-up").get(0),
    url: "../../../leadVoter.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("application/vnd.ms-excel") == 0 || file.type.indexOf("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") == 0 ) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是xls或xlsx文件');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    // html = '<span class="mailbox-attachment-icon has-img"><img src="' + e.target.result + '"/></span>';
                    filename = file.name;
                    // $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                // $("#ac-logo-img").html(html);
                $("#leadVoter-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    onProgress: function(file, loaded, total) {
        // var eleProgress = $("#rate-logo"), percent = (loaded / total * 100).toFixed(2) + '%';
        // eleProgress.show(300).html(percent);
        // eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            var jsonD2 = JSON.parse(jsonD.msg);
            if (jsonD2.code >= 0 && jsonD2.fail ==0) {
                $("#success-value").text("导入完毕，成功："+jsonD2.success+"，失败："+jsonD2.fail+"，"+jsonD2.reason+"。");
                $("#alert-success").show(500);                
            }else{
                $("#danger-value").text("发生错误，成功："+jsonD2.success+"，失败："+jsonD2.fail+"，"+jsonD2.reason+"。");
                $("#alert-danger").show(500);                 
            }

        }else{
            $("#danger-value").text(jsonD.msg);
            $("#alert-danger").show(500);
        }
        console.log(response);
    },
    onFailure: function(file) {
        $("#danger-value").text("图片上传失败！");
        $("#alert-danger").show(500);
    },
    onComplete: function() {
        
    }
};
// 导入候选人
var params4 = {
    fileInput: $("#leadCandidate").get(0),
    upButton: $("#leadCandidate-up").get(0),
    url: "../../../leadCandidate.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("application/vnd.ms-excel") == 0 || file.type.indexOf("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") == 0 ) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是xls或xlsx文件');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    // html = '<span class="mailbox-attachment-icon has-img"><img src="' + e.target.result + '"/></span>';
                    filename = file.name;
                    // $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                // $("#ac-logo-img").html(html);
                $("#leadCandidate-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    onProgress: function(file, loaded, total) {
        // var eleProgress = $("#rate-logo"), percent = (loaded / total * 100).toFixed(2) + '%';
        // eleProgress.show(300).html(percent);
        // eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            var jsonD2 = JSON.parse(jsonD.msg);
            if (jsonD2.code >= 0 && jsonD2.fail ==0) {
                $("#success-value").text("导入完毕，成功："+jsonD2.success+"，失败："+jsonD2.fail+"，"+jsonD2.reason+"。");
                $("#alert-success").show(500);                
            }else{
                $("#danger-value").text("发生错误，成功："+jsonD2.success+"，失败："+jsonD2.fail+"，"+jsonD2.reason+"。");
                $("#alert-danger").show(500);                 
            }

        }else{
            $("#danger-value").text(jsonD.msg);
            $("#alert-danger").show(500);
        }
        console.log(response);
    },
    onFailure: function(file) {
        $("#danger-value").text("图片上传失败！");
        $("#alert-danger").show(500);
    },
    onComplete: function() {
        
    }
};
// 选择候选人图片
var params5 = {
    fileInput: $("#candidate-img-input").get(0),
    upButton: $("#candidate-files-upload").get(0),
    url: "../../../setCandidateImg.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("image") == 0) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是图片');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0, html = '';
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    filename = file.name;
                    html = html + '<li class="candidate-img-img-'+i+'" style="overflow:hidden;"><div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: none;" id="rate-candidate-img-'+i+'">10%</div><div><span class="mailbox-attachment-icon has-img"><img src="'+e.target.result+'" alt="Attachment"></span>'+filename+'</div><a href="javascript:" class="upload_delete" title="删除" data-index="'+ i +'">删除</a><br /></li>';
                    // $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                $("#candidate-img-block").html(html);
                if (html) {
                    //删除方法
                    $(".upload_delete").click(function() {
                        ZXXFILE5.funDeleteFile(files[parseInt($(this).attr("data-index"))]);
                        return false;   
                    });
                }
                // $("#leadCandidate-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    onDelete: function(file) {
        $(".candidate-img-img-" + file.index).hide(300);
    },
    onProgress: function(file, loaded, total) {
        var eleProgress = $("#rate-candidate-img-" + file.index), percent = (loaded / total * 100).toFixed(2) + '%';
        eleProgress.show(300).html(percent);
        eleProgress.css("width",percent);
        $(".candidate-img-img-" + file.index).removeClass("candidate-img-img-" + file.index)
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            $("#success-value").text(jsonD.msg);
            $("#alert-success").show(500);
            setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",2000);
        }else{
            $("#danger-value").text(jsonD.msg);
            $("#alert-danger").show(500);
        }
        
    },
    onFailure: function(file) {
        $("#danger-value").text("图片上传失败！");
        $("#alert-danger").show(500);
    },
    onComplete: function() {
        
    }
};
// 选择外链封面
var params6 = {
    fileInput: $("#candidate-linkcover-input").get(0),
    upButton: $("#candidate-files-upload").get(0),
    url: "../../../setCandidateLinkCover.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("image") == 0) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是图片');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    filename = file.name;
                    html = '<span class="mailbox-attachment-icon has-img"><img src="' + e.target.result + '"/></span>';
                    // $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                $("#candidate-link-img").html(html);
                $("#candidate-link-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    // onDelete: function(file) {
    //     $("#candidate-img-img-" + file.index).hide(300);
    // },
    onProgress: function(file, loaded, total) {
        var eleProgress = $("#rate-candidate-link"), percent = (loaded / total * 100).toFixed(2) + '%';
        eleProgress.show(300).html(percent);
        eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            $("#success-value").text(jsonD.msg);
            $("#alert-success").show(500);
            // $('input').val("");
            // $('textarea').val("");
            setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",2000);
        }else{
            $("#danger-value").text(jsonD.msg);
            $("#alert-danger").show(500);
        }
        console.log(jsonD);
    },
    onFailure: function(file) {
        $("#danger-value").text("图片上传失败！");
        $("#alert-danger").show(500);
    },
    onComplete: function() {
        
    }
};
// 选择音频
var params7 = {
    fileInput: $("#candidate-audio-input").get(0),
    upButton: $("#candidate-files-upload").get(0),
    url: "../../../setCandidateAudio.php",
    filter: function(files) {
        var arrFiles = [];
        for (var i = 0, file; file = files[i]; i++) {
            if (file.type.indexOf("audio") == 0) {
                arrFiles.push(file);    
            } else {
                alert('文件"' + file.name + '"不是mp3格式文件');    
            }
        }
        return arrFiles;
    },
    onSelect: function(files) {
        $(".alert-block").hide(500);
        console.log(files);
        var i = 0;
        var funAppendImage = function() {
            file = files[i];
            if (file) {
                var reader = new FileReader()
                reader.onload = function(e) {
                    filename = file.name;
                    html = '<audio controls="controls"><source src="'+e.target.result+'" type="audio/mp3" ></audio>'
                    // $("#rate-logo").css("width","0%");
                    i++;
                    funAppendImage();
                }
                reader.readAsDataURL(file);
            } else {
                $("#candidate-audio").html(html);
                $("#candidate-audio-txt").html(filename);
            }
        };
        funAppendImage();       
    },
    // onDelete: function(file) {
    //     $("#candidate-img-img-" + file.index).hide(300);
    // },
    onProgress: function(file, loaded, total) {
        var eleProgress = $("#rate-candidate-audio"), percent = (loaded / total * 100).toFixed(2) + '%';
        eleProgress.show(300).html(percent);
        eleProgress.css("width",percent);
    },
    onSuccess: function(file, response) {
        var jsonD=JSON.parse(response);
        if (jsonD.code >= 0) {
            $("#success-value").text(jsonD.msg);
            $("#alert-success").show(500);
            // $('input').val("");
            // $('textarea').val("");
            setTimeout("window.location.href='candidate-view.php?ackey="+$('#activity-key').val()+"'",2000);
        }else{
            $("#danger-value").text(jsonD.msg);
            $("#alert-danger").show(500);
        }
        console.log(jsonD);
    },
    onFailure: function(file) {
        $("#danger-value").text("图片上传失败！");
        $("#alert-danger").show(500);
    },
    onComplete: function() {
        
    }
};
ZXXFILE = $.extend(ZXXFILE, params);
ZXXFILE.init();
ZXXFILE2 = $.extend(ZXXFILE2, params2);
ZXXFILE2.init();
ZXXFILE3 = $.extend(ZXXFILE3, params3);
ZXXFILE3.init();
ZXXFILE4 = $.extend(ZXXFILE4, params4);
ZXXFILE4.init();
ZXXFILE5 = $.extend(ZXXFILE5, params5);
ZXXFILE5.init();
ZXXFILE6 = $.extend(ZXXFILE6, params6);
ZXXFILE6.init();
ZXXFILE7 = $.extend(ZXXFILE7, params7);
ZXXFILE7.init();