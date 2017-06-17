jQuery(function($) {
    var panelList = $('.draggable');

    panelList.sortable({
        handle: '.panel-heading',
        update: function() {
            $('.panel', panelList).each(function(index, elem) {
                 var $listItem = $(elem),
                     newIndex = $listItem.index();
            });
        }
    });
});
