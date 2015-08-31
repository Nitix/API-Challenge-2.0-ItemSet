$(document).ready(function () {

    var mouseDown = false;
    $('.hover-relative').hover(function(){
        var child = $(this).find(".hover-absolute");
        child.css('top', (child.height()/-2) + $(this).height()/2);
        child.show();
    }, function() {
        var child = $(this).find(".hover-absolute");
        child.hide();
    }).mousedown(function(){
        $(this).find(".hover-absolute").hide();
        mouseDown = true;
    }).mousemove(function() {
        if(mouseDown)
            $(this).find(".hover-absolute").hide();
    }).mouseup(function () {
        mouseDown = false;
    }).mouseout(function () {
        mouseDown = false;
    });

    var code = $('.stringify');
    if(code.html() != undefined)
        code.html(syntaxHighlight(JSON.parse(code.html())));


    $('pre, code').on('click', function(){
        if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(this);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.selection != "undefined" && typeof document.body.createTextRange != "undefined") {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(this);
            textRange.select();
        }
    });

    $('.checkbox-search').on('change', function(){
        $('.search-group').removeClass('active');
        $('.search-all').removeClass('active');
        $('#item-search-input').val('');
        var hide = [];
        $('.checkbox-search').each(function(){
            if($(this).is(':checked')){
                var search = $(this).data('tag');
                $('.item-search', '#itemlist').each(function(){
                    if($.inArray(search, $(this).data('tags').split(';')) == -1){
                        $(this).hide();
                        hide.push(this);
                    }
                });
            }
        });
        $('.item-search').each(function(){
            if($.inArray(this, hide) == -1){
                $(this).show();
            }
        });
    });

    $('#item-search-input').on('input',function(){
        $('.checkbox-search').prop('checked', false);
        $('.search-group').removeClass('active');
        $('.search-all').removeClass('active');

        var hide = [];
        var search = $(this).val().toLowerCase();
        var items = $('.item-search', '#itemlist');
        items.each(function(){
            if($.inArray(search, $(this).data('tags').toLowerCase().split(';')) == -1 && $(this).find('.popover-title').html().toLowerCase().indexOf(search) == -1){
                $(this).hide();
                hide.push(this);
            }
        });
        items.each(function(){
            if($.inArray(this, hide) == -1){
                $(this).show();
            }
        });
    });

    $('.search-all').on('click', function(){
        $('.checkbox-search').prop('checked', false);
        $('.search-group').removeClass('active');
        $('.search-all').addClass('active');
        $('#item-search-input').val('');
        $('.item-search').show();
    });

    $('.search-group').on('click', function(){
        var hide = [];
        $('.checkbox-search').prop('checked', false);
        $('.search-group').removeClass('active');
        $('#item-search-input').val('');
        var items = $('.item-search', '#itemlist');
        var tags = [];
        $(this).siblings().each(function(){
            tags.push($(this).find('.checkbox-search').data('tag'));
        });
        $(this).addClass("active");
        items.each(function(){
            var item = $(this);
            var found = false;
            for (var search = 0, len = tags.length; search < len; search++) {
                if($.inArray(tags[search], item.data('tags').split(';')) != -1){
                    found = true;
                    break;
                }
            }
            if(!found){
                $(this).hide();
                hide.push(this);
            }
        });
        items.each(function(){
            if($.inArray(this, hide) == -1){
                $(this).show();
            }
        });
    });

    var itemlist = byId('itemlist');
    if(itemlist != undefined) {
        Sortable.create(itemlist, {
            sort: false,
            group: {
                name: "creator",
                pull: 'clone',
                put: true
            },
            animation: 150,
            scroll: true,
            scrollSensitivity: 100, // px, how near the mouse must be to an edge to start scrolling.
            scrollSpeed: 20, // px
            onAdd: function (/**Event*/evt) {
                evt.item.parentNode.removeChild(evt.item);
            },
        });
    }

    var sortableblock0 = byId('sortable-block-0');
    if(sortableblock0 != undefined) {
        Sortable.create(sortableblock0, {
            sort: true,
            group: {
                name: "creator",
                pull: true,
                put: true
            },
            animation: 150,
            // dragging ended
            onSort: function (/**Event*/evt) {
                sortItem(evt.to, $(evt.to).parent().data('id'));
            },
            scroll: false
        });
    }

    var sortItem = function(block, id){
        var i = 0;
        $(block).children().each(function(){
            $(this).find('input').remove();
            $('<input>').attr({
                type: 'hidden',
                id: 'item-blocks[' +id +']['+i+']',
                name: 'blocks[' +id +'][items]['+i+']',
                value: $(this).data('id')
            }).appendTo(this);
            i++;
        });
    };

    $('#new-block').on('click', function(){
        var text = blocktext.replace(/BLOCKID/g, blockid);

        $(text).insertBefore(this)
        Sortable.create(byId('sortable-block-'+blockid), {
            sort: true,
            group: {
                name: "creator",
                pull: true,
                put: true
            },
            animation: 150,
            onSort: function (/**Event*/evt) {
                sortItem(evt.to, $(evt.to).parent().data('id'));
            },
            scroll: false
        });
        blockid++;
    });

    var blockid = 1;

    $('#championkey').on('change', function(){
        $("#text-champion").html('League of Legends\\Config\\Champions\\'+$(this).val()+'\\Recommended\\');
    });
});

var byId = function (id) { return document.getElementById(id); };

function syntaxHighlight(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
