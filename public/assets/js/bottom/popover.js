var Popover = function() {
  this.init = function() {
    $('span[data-with-popover]').hover(function() {
      var x = $(this).offset().left;
      var y = $(this).offset().top;
      var height_popover = $(this).find('.popover').height();

      $(this).find('.popover').css('left', x).css('top', y - height_popover).show();

    }, function() {
      $(this).find('.popover').hide();
    });
  };

  this.init();
};

new Popover();
