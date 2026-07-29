(function () {

    var getFilesUrl = "/getFiles";

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
                getFilesByDir('', $('#fileArea'), expandPathToCurrentFile);
            }
        }

    });

    function getFilesByDir(link, target, done) {
        loading.start();
        $.ajax({
            url: getFilesUrl,
            type: 'POST',
            data: {'link': link},
            // fix 'Unexpected end of input'
            dataType: 'json',
            success: function (msg) {
                var stringInserted = "<ul class='folder'>";
                $.each(msg['files'], function (key, value) {
                    stringInserted += "<li data-name='" + value['name'] + "' data-link='" + value['link'] + "' data-type='" + value['type'] + "' data-yo='close' id='file'>";
                    if ('dir' == value['type']) {
                        stringInserted += value['name'];
                    } else {
                        stringInserted += "<a href='" + value['link'] + "'>" + value['name'] + "</a>";
                    }
                    stringInserted += "<br></li>";
                });
                stringInserted += "</ul>";
                target.append(stringInserted);
                if (typeof done === 'function') {
                    done();
                }
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
                var stringInserted = "";
                $.each(msg['files'], function (key, value) {
                    stringInserted += "<li><a href='" + value['link'] + "'>" + addBackground(value['name'], searchData) + "</a></li>";
                });
                stringInserted += "";

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

    // Keep ancestor folders open after navigating to a nested file.
    // File links trigger a full page reload, which resets the tree to closed.
    function expandPathToCurrentFile() {
        var pathname = window.location.pathname;
        if (!pathname || pathname === '/') {
            return;
        }

        var segments = pathname.replace(/^\/+|\/+$/g, '').split('/');
        // Top-level files need no folder expansion.
        if (segments.length < 2) {
            return;
        }

        var dirSegments = segments.slice(0, -1);

        function dirLinkAt(index) {
            // All dirs use a leading slash, e.g. "/test", "/test/a/b".
            return '/' + dirSegments.slice(0, index + 1).join('/');
        }

        function expandAt(index, $parent) {
            if (index >= dirSegments.length) {
                return;
            }

            var dirLink = dirLinkAt(index);
            var $dir = $parent.children('li').filter(function () {
                return $(this).data('type') === 'dir'
                    && String($(this).data('link')) === String(dirLink);
            }).first();

            if (!$dir.length) {
                return;
            }

            function goDeeper() {
                expandAt(index + 1, $dir.children('ul.folder'));
            }

            if ($dir.data('yo') === 'open') {
                goDeeper();
                return;
            }

            $dir.data('yo', 'open');
            getFilesByDir(dirLink, $dir, goDeeper);
        }

        expandAt(0, $('#fileArea'));
    }

    var dot = document.getElementById("loading");

    var loading = {
        intervalId: 0,
        pending: 0,
        start: function(){
            this.pending++;
            if (this.pending > 1) {
                return;
            }
            clearInterval(this.intervalId);
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
            this.pending = Math.max(0, this.pending - 1);
            if (this.pending > 0) {
                return;
            }
            clearInterval(this.intervalId);
            this.intervalId = 0;
            dot.innerHTML = "";
        }
    };

    expandPathToCurrentFile();

})();
