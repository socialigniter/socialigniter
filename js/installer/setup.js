
/**
 * @author Brennan Novak
 * @description Iteration or installer steps 1-4 via AJAX
 **/


(function($) {

  /**
   * @method determineBaseURL
   * @return {String} Current Base URL with protocol and port (if irregular)
   **/

  function determineBaseURL() {
    if ($.url.attr('port')) {
      var port = ':' + $.url.attr('port') + '/';
    } else {
      var port = '/';
    };
    return $.url.attr('protocol') + '://' + $.url.attr('host') + port;
  };

  $(document).ready(function() {

    // SHOW FRESH SETUP
    $('#step_1').fadeIn();
    $('#base_url').val(determineBaseURL()); 

    // STEP 1
    $('#install_step_1').live('submit', function(e) { 
      e.preventDefault();
      var step_1_data = $('#install_step_1').serialize();
      console.log(step_1_data);
      $.ajax({
        url      : 'install.php',
        type     : 'POST',
        dataType : 'json',
        data     : step_1_data,
        success  : function(result) {
          console.log('Step 1');
          console.log(result);
          $('#step_1').fadeOut();
          $('#step_2').fadeIn();
          $.ajax({
            url      : $('#base_url').val() + 'setup',
            type     : 'POST',
            dataType : 'json',
            data     : step_1_data,
            success  : function(result) {
              console.log('Step 2');
              console.log(result);
              $('#step_2').fadeOut();
              $('#step_3').fadeIn();        
            }
          }); 
        }
      });
    });

    // STEP 3
    $("#install_step_3").live('submit', function(e) { 
      e.preventDefault(); 
      $.validator({
        elements :    
          [{
            'selector'  : '#signup_name', 
            'rule'    : 'require', 
            'field'   : 'Enter your name',
            'action'  : 'label'         
          },{
            'selector'  : '#signup_email', 
            'rule'    : 'email', 
            'field'   : 'Please enter a valid email',
            'action'  : 'label'             
          },{
            'selector'  : '#signup_password', 
            'rule'    : 'require', 
            'field'   : 'Please enter a password',
            'action'  : 'label'         
          },{
            'selector'  : '#signup_password_confirm', 
            'rule'    : 'confirm', 
            'field'   : 'Please confirm your password',
            'action'  : 'label'         
          }],
        message : '',
        success : function() {         
          var signup_data = $('#install_step_3').serialize();
          console.log(signup_data);
          $.ajax({
            url       : $('#base_url').val() + 'setup/admin',
            type      : 'POST',
            dataType  : 'json',
            data      : signup_data,
            success : function(result) {
              console.log('Step 3');
              console.log(result);
              $('#step_3').fadeOut();
              $('#step_4').fadeIn();            
            }
          });
        }
      });
    });
     
    // STEP 4
    $('#install_step_4').live('submit', function(e){
      e.preventDefault();
      var step_4_data = $('#install_step_4').serialize();
      console.log(step_4_data);
      $.ajax({
        url      : $('#base_url').val() + 'setup/site',
        type     : 'POST',
        dataType : 'json',
        data     : step_4_data,
        success  : function(result) {
          console.log('Step 4');
          console.log(result);
          base_url = $('#base_url').val();
          $('#go_to_website').attr('href', base_url);
          $('#go_to_dashboard').attr('href', base_url + 'home');
          $('#go_to_apps').attr('href', base_url + 'settings/apps');
          $('#go_to_design').attr('href', base_url + 'settings/design');
          $('#step_4').fadeOut();
          $('#step_5').fadeIn();          
        }
      });
    });

  });

})(jQuery);

/* EOF */