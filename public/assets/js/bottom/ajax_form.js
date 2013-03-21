// ajax_form.js

/**
 * Every form element containing the "data-ajax=true" attribute will
 * be submitted using Ajax. The response to that submit will be considered
 * javascript code and will be run'd.
 * PS: Requires jquery.form.js plugin (http://www.malsup.com/jquery/form/)
 *
 *
 * Ex:
 *   // form.php
 *   <form action="somewhere.php" method="POST" data-ajax="true">
 *       <input type="text" name="username">
 *       ...
 *   </form>
 *   
 *   //somewhere.php
 *   <?php
 *      if($_POST['username']) {
 *          echo "alert('Form submitted!'); //Javascript code";
 *      }else{
 *          echo "alert('Username missing'); //Javascript code";    
 *      }
 * 
 */

$(function(){

    $('form[data-ajax=true]').ajaxForm();

    $('input[data-submit-on-type=true]').keyup(function(){
        $(this).closest('form[data-ajax=true]').ajaxSubmit();
    })
})
