<?php
    session_start();
include_once '_functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Конструктор письма</title>
        <meta charset="utf-8"/>
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<style>


</style>
        <script>

            var CONST = {
                MESSAGES: {
                    FILE_UPLOAD_ERROR: "Ошибка загрузки файла",
                    FILE_PROCESS_ERROR: "Ошибка обработки файла"
                }
            };

            function addItem(form) {
                var formData = new FormData($('#' + form)[0]);
                $.ajax({
                    url: 'add-item.php',  //Server script to process data
                    type: 'POST',
                    xhr: function() {  // Custom XMLHttpRequest
                        var myXhr = $.ajaxSettings.xhr();
                        if(myXhr.upload){ // Check if upload property exists
                            myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                        }
                        return myXhr;
                    },
                    //Ajax events

                    success: function(response){
                        if (response.success == false) {
                            alert(CONST.MESSAGES[response.message]);
                            return;
                        }
                        $('#itemData')[0].reset();

                        $('progress').attr({value:100, max: 100});

                        $("#preview").html(response.code);
                        console.log(response.code);



                    },
                    error: function(p1,p2){
                        console.log("Error");
                        console.log(p1);
                        console.log(p2);

                    },
                    // Form data
                    data: formData,
                    //Options to tell jQuery not to process data or worry about content-type.
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }



            function progressHandlingFunction(e){
                if(e.lengthComputable){
                    $('progress').attr({value:e.loaded,max:e.total});
                }
            }

            function tabClick (e) {
                e.preventDefault();
                console.log(e);
            }

        </script>
    </head>
    <body>

        <div class="span12">
            <h5>Добавить элемент</h5>
            <hr/>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#announce" data-toggle="tab" >Анонс</a></li>
                <li><a href="#banner" data-toggle="tab">Банер</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="announce">
                    <div id="control">
                        <div class="form-horizontal">
                            <form id="itemData">
                            <input type="hidden" name="type" value="announce" />
                                <div class="control-group">
                                    <lable class="control-label" for="header">Заголовок</lable>
                                    <div class="controls">
                                        <input type="text" name="header" id="header" class="input-xxlarge"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <lable class="control-label" for="date">Дата</lable>
                                    <div class="controls">
                                        <input type="text" name="date" id="date" class="input-medium"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <lable class="control-label" for="link">Ссылка</lable>
                                    <div class="controls">
                                        <input type="text" name="link" id="link" class="input-xxlarge"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <lable class="control-label" for="image">Изображение</lable>
                                    <div class="controls">
                                        <input type="file" id="image" name="image"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <lable class="control-label" for="text">Текст</lable>
                                    <div class="controls">
                                        <textarea id="text" name="text" rows="5" class="input-xxlarge"></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="control-group">
                                <div class="controls">
                                    <button class="btn btn-primary" onclick="addItem('itemData')">Добавить элемент</button>
                                    <progress></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="banner"><div id="control">
                    <div class="form-horizontal">
                        <form id="bannerData">
                            <input type="hidden" name="type" value="banner" />
                            <div class="control-group">
                                <lable class="control-label" for="link">Ссылка</lable>
                                <div class="controls">
                                    <input type="text" name="link" id="link" class="input-xxlarge"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <lable class="control-label" for="image">Изображение</lable>
                                <div class="controls">
                                    <input type="file" id="image" name="image"/>
                                </div>
                            </div>

                        </form>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-primary" onclick="addItem('bannerData')">Добавить элемент</button>
                                <progress></progress>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <script>
                $('progress').attr({value:0, max: 100});
            </script>
            <hr/>
            <h5>Предпросмотр</h5>
            <div class="control-group">
                <div class="controls">
            <a class="btn btn-success" target="_blank" href="view.php">Смотреть отдельно</a>
            <a class="btn btn-danger" href="javascript:void(0)" onclick="$.ajax({url: 'clear.php'});$('#preview').html('');">Очистить</a> <br/>
            </div></div>
            <div class="control-group">
            <div class="controls">
                <input id="to" class="input-medium"/> <button class="btn btn-warning" onclick="$.ajax({url: 'test-send.php?to=' + $('#to').val()});">Отправить письмо</button>
            </div>
                </div>
        </div>

        <div class="row">
            <div class="span12">
                <hr/>
                <div id="preview">
                    <?php echo getHtmlPreviewCode();?>
                </div>
            </div>
        </div>
    </body>
</html>