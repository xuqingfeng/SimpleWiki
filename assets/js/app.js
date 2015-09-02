/**
 * Created by jsxqf on 15/9/2.
 */

(function () {

    var getFilesUrl = "/getFiles";

    mixpanel.track('PV');

    // open dir
    $('#fileArea').on('click', '#file', function () {

        var target = $(this);
        var name = target.data('name');
        var type = target.data('type');
        var link = target.data('link');
        var yo = target.data('yo');
        if ('dir' == type && 'close' == yo) {
            target.data('yo', 'open');
            getFilesByDir(link, target);
        }
    });

    // instant search
    var lock = false;
    $('#instantSearch').on('keyup', function () {

        if (!lock) {
            lock = true;
            var searchData = $(this).val();
            $('#fileArea').empty();
            if ('' != searchData) {
                searchFiles(searchData);
            } else {
                getFilesByDir('', $('#fileArea'));
            }
        }

    });

    function getFilesByDir(link, target) {
        loading.start();
        $.ajax({
            url: getFilesUrl,
            type: 'POST',
            data: {'link': link},
            // fix 'Unexpected end of input'
            dataType: 'json',
            success: function (msg) {
                console.log(msg);
                var stringInserted = "<div class='folder'>";
                $.each(msg['files'], function (key, value) {
                    stringInserted += "<div data-name='" + value['name'] + "' data-link='" + value['link'] + "' data-type='" + value['type'] + "' data-yo='close' id='file'>";
                    if ('dir' == value['type']) {
                        stringInserted += value['name'];
                    } else {
                        stringInserted += "<a href='" + value['link'] + "'>" + value['name'] + "</a>";
                    }
                    stringInserted += "<br></div>";
                });
                stringInserted += "</div>";
                target.append(stringInserted);
            },
            error: function () {
            },
            complete: function () {
                lock = false;
                loading.stop();
            }
        });
    }

    function searchFiles(searchData) {

        loading.start();
        $('#fileArea').empty();
        $.ajax({
            url: getFilesUrl,
            type: 'POST',
            data: {'search': searchData},
            dataType: 'json',
            success: function (msg) {
                var stringInserted = "<ul>";
                $.each(msg['files'], function (key, value) {
                    stringInserted += "<li><a href='" + value['link'] + "'>" + addBackground(value['name'], searchData) + "</a></li>";
                });
                stringInserted += "</ul>";

                $('#fileArea').append(stringInserted);
            },
            error: function () {
            },
            complete: function () {
                lock = false;
                loading.stop();
            }
        });
    }
    function addBackground(fileName, searchData){
        var searchResult = fileName.replace(searchData, "<span class='bg-yellow'>"+searchData+"</span>");
        return searchResult;
    }


    var loading = {
        intervalId: 0,
        start: function(){
            var dot = document.getElementById("loading");
            dot.innerHTML = "";
            this.intervalId = setInterval(function(){
                if( dot.innerHTML.length < 4){
                    dot.innerHTML += ".";
                }else{
                    dot.innerHTML = "";
                }
            }, 200);
        },
        stop: function(){
            clearInterval(this.intervalId);
        }
    };

})();