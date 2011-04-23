<link type="text/css" href="<?=base_url()?>css/<?= $this->config->item('theme') ?>.css" rel="stylesheet" media="screen" />

<script src="<?=base_url()?>js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
        jQuery(window).ready(function(){  
            jQuery("#btnInit").click(initiate_geolocation);  
        });  
  
        function initiate_geolocation() {  
            navigator.geolocation.getCurrentPosition(handle_geolocation_query,handle_errors);  
        }  
  
        function handle_errors(error)  
        {  
            switch(error.code)  
            {  
                case error.PERMISSION_DENIED: alert("user did not share geolocation data");  
                break;  
  
                case error.POSITION_UNAVAILABLE: alert("could not detect current position");  
                break;  
  
                case error.TIMEOUT: alert("retrieving position timed out");  
                break;  
  
                default: alert("unknown error");  
                break;  
            }  
        }  
  
        function handle_geolocation_query(position){  
            alert('Lat: ' + position.coords.latitude +  
                  ' Lon: ' + position.coords.latitude);  
        }
</script>