<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 

require_once(__ROOT__.'/inc/sec.php');
require_once(__ROOT__.'/inc/opendb.php');
require_once(__ROOT__.'/inc/settings.php');

?>

<h5>Use the API to integrate PV with other applications</h5>
<hr />

<div class="row mb-3">
	<?php if (!isset($system_settings['API_enabled']) || $system_settings['API_enabled'] != 1) { ?>
		<div class="col-12">
			<div class="alert alert-danger">
				<i class="fa-solid fa-circle-exclamation mx-2"></i> The API has been administratively disabled.
			</div>
		</div>
		<?php return; ?>
	<?php } ?>
    <div class="col-2">
        <label for="pv_api">Enable API</label>
        <input class="mx-2" name="pv_api" type="checkbox" id="pv_api" value="1" 
        <?php if($user['isAPIActive'] === '1'){ ?> checked="checked" <?php } ?>/>
    </div>
</div>
<div class="row mb-3">
	<div class="col-4">
		<label for="pv_api_key">API Key</label>
		<div class="col-md-8 password-input-container">
			<input name="pv_api_key" type="password" class="form-control password-input bg-secondary" id="pv_api_key" value="<?=$user['API_key']?>" readonly />
			<i class="toggle-password fa fa-eye"></i>
			<i class="copy-api-key fa fa-copy mx-2" style="cursor: pointer;"></i>
		</div>
		<div class="col-md-4">
			<button type="button" id="regenerate-api-key" class="btn btn-warning border border-secondary mt-2">Regenerate</button>
		</div>
	</div>
</div>
<div class="col-2">
    <input type="submit" name="save-api" id="save-api" value="Save" class="btn btn-primary"/>
</div>


<script>
$(document).ready(function() {
	$.fn.dataTable.ext.errMode = 'none';
	var	api_key = '******';

	$(".toggle-password").click(function () {
        var passwordInput = $($(this).siblings(".password-input"));
        
		var icon = $(this);
		//reload_data();
        if (passwordInput.attr("type") == "password") {
            passwordInput.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
			api_key = $("#pv_api_key").val();
        } else {
            passwordInput.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
			api_key = '******';
        }
    });

	$(".copy-api-key").click(function () {
		var copyText = document.getElementById("pv_api_key");
		copyText.select();
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");
		$('#toast-title').html('<i class="fa-solid fa-circle-check mx-2"></i>API Key copied to clipboard');
		$('.toast-header').removeClass().addClass('toast-header alert-success');
		$('.toast').toast('show');
	});
	
	
	function syntax(data, type, row){
		var furl = '/api.php?key=' + api_key + '&do=' + row.do + '&type=' + row.type;
		if (api_key === '******') {
			data = '<span class="text-info-emphasis">' + furl + '</span>';
		} else {
			data = '<a href="' + furl + '" id="apitest" target="_blank" class="text-info-emphasis">' + furl + '<i class="fa-solid fa-arrow-up-right-from-square mx-2"></i></a>';
		}
		return data;
	}
	
	
	$('#save-api').click(function() {
		$.ajax({ 
			url: '/core/core.php', 
			type: 'POST',
			data: {
				manage: 'api',		
				api: $("#pv_api").is(':checked'),
			},
			dataType: 'json',
			success: function (data) {
				if(data.success){
					$('#toast-title').html('<i class="fa-solid fa-circle-check mx-2"></i>' + data.success);
					$('.toast-header').removeClass().addClass('toast-header alert-success');
					$('#pv_api_key').val(data.API_key);
					reload_data();
				} else if(data.error) {
					$('#toast-title').html('<i class="fa-solid fa-warning mx-2"></i>' + data.error);
					$('.toast-header').removeClass().addClass('toast-header alert-danger');
				}
				$('.toast').toast('show');
			},
			error: function (xhr, status, error) {
				$('#toast-title').html('<i class="fa-solid fa-circle-exclamation mx-2"></i>An ' + status + ' occurred, check server logs for more info. '+ error);
				$('.toast-header').removeClass().addClass('toast-header alert-danger');
				$('.toast').toast('show');
			}
		});
	});


	$('#regenerate-api-key').click(function() {
		$.ajax({ 
			url: '/core/core.php', 
			type: 'POST',
			data: {
				'regenerate-api-key': 'true'
			},
			dataType: 'json',
			success: function (data) {
				if(data.success){
					$('#toast-title').html('<i class="fa-solid fa-circle-check mx-2"></i>' + data.success);
					$('.toast-header').removeClass().addClass('toast-header alert-success');
					$('#pv_api_key').val(data.API_key);
					reload_data();
				} else if(data.error) {
					$('#toast-title').html('<i class="fa-solid fa-warning mx-2"></i>' + data.error);
					$('.toast-header').removeClass().addClass('toast-header alert-danger');
				}
				$('.toast').toast('show');
			},
			error: function (xhr, status, error) {
				$('#toast-title').html('<i class="fa-solid fa-circle-exclamation mx-2"></i>An ' + status + ' occurred, check server logs for more info. '+ error);
				$('.toast-header').removeClass().addClass('toast-header alert-danger');
				$('.toast').toast('show');
			}
		});
	});
});

</script>
