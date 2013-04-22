// tabs.js

/**
 * For every element containing the ".nav". Every <li> iten that
 * have the "data-tab-of" attribute, will display / hide the element
 * with the id equals to the "data-tab-of" value. It will "smartly"
 * hide any data-tab-of of other <li> elements within the ".nav"
 *
 * -tree='true' attribute.
 *
 * Ex:
 *   <ul class="nav nav-tabs">
 *       <li class="active" data-tab-of="div_one">
 *           <a>One</a>
 *       </li>
 *       <li data-tab-of="div_two">
 *           <a>Two</a>
 *       </li>
 *   </ul>
 *
 *   <div id='div_one'>
 *       Content of one
 *   </div>
 *   <div id='div_two'>
 *       Content of two
 *   </div>
 *
 * PS:
 *   Requires: jquery.querystring.js
 *
 */

$(function(){

    // Function to hide the contents ('data-tab-of') of the tabs
    // provided
    function hideContentOfTabs( tabs )
    {
        tabs.each(function(){
            var el = $(this);
            var target = $('#'+el.attr('data-tab-of'));

            if(target.length === 0)
                return;

            target.hide();
            el.removeClass('active');
        });
    }

    function displayTab( tab )
    {
        var el = tab;
        var other_tabs = el.parent().find('li');

        var target = $('#'+el.attr('data-tab-of'));

        if(target.length === 0)
            return;

        hideContentOfTabs( other_tabs );

        target.show();
        el.addClass('active');

        setupChosen();
    }

    // Make <li> that have the 'data-tab-of' attibute clickable
    // in order to display it's content and hide the content of other
    // tabs.
    $('.nav [data-tab-of]').click(function(){
        displayTab( $(this) );
    });

    // Hide the "data-tab-of" of each <li> that it's not '.active'
    hideContentOfTabs($('.nav li').not('[class*=active]'));

    if($.QueryString['tab'])
    {
        displayTab( $('.nav li[data-tab-of="'+$.QueryString['tab']+'"]') );
    }
    else
    {
        displayTab( $('.nav li[class*=active]') );
    }
});
