// tag-picker.js

/**
 * For every select element containing the "tag-picker" attribute.
 * the tokeninput plugin will be applied
 *
 * Ex:
 *   <select tag-picker="url/to/script" id="category" name="category">
 *       <option value="1">something</option>
 *       <option value="2">something else</option>
 *       ...
 *
 * PS:
 *   Requires: jquery.tokeninput.js
 *
 */

 setupTagPicker = function(){
    $('[tag-picker]').each(function(){
        el = $(this);
        el.tagsInput({
            autocomplete_url: el.attr('tag-picker')
        });
    });
};

$(function(){
    setupTagPicker();
});
