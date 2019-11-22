window.onload = function() {
  $('.menu > li .menu__item__toggler').on('click', function() {
    let parentLi = $(this).parent().parent();
    $('.menu > li').not(parentLi).removeClass('expand').addClass('collapse');

    if ($(parentLi).hasClass('expand')) {
      $(parentLi).addClass('collapse').removeClass('expand');
    } else {
      $(parentLi).addClass('expand').removeClass('collapse');
    }
  });

  $('.menu > li .submenu__item__toggler').on('click', function() {
    let parentLi = $(this).parent().parent();
    let subsubmenu = $(parentLi).find('.subsubmenu');

    $('.menu .submenu li.expand').removeClass('expand').addClass('collapse');
    $('.menu > li .subsubmenu').not(subsubmenu).removeClass('show').addClass('hide');

    if ($(subsubmenu).hasClass('show')) {
      $(subsubmenu).removeClass('show').addClass('hide');
      $(parentLi).addClass('collapse').removeClass('expand');
    } else {
      $(subsubmenu).removeClass('hide').addClass('show');
      $(parentLi).addClass('expand').removeClass('collapse');
    }
  });
};
