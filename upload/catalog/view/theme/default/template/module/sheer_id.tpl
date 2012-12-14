<?php
if (isset($config)) {
?>
<style type="text/css">
.verify-form label {
	width: 200px;
	display: block;
	float: left;
	margin-top: 0.5em;
}
.verify-form fieldset, .uploadForm {
	display: block;
	background-color: #efefef;
	border: 1px solid #ccc;
	padding: 1em;
}

#column-right, #column-left {
	width: 50%;
	margin: 75px 3em 3em;
}

#content .buttons {
	display: none;
}

</style>
<?php
	$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	
	function renderField($field, $required, $type="text", $label=null, $value=null) {
		$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	?>
	<p>
		<label for="<?php echo $field; ?>">
			<?php if ($required) { ?>
				<em class="required">* </em>
			<?php } ?>
			<span><?php echo $label ? $label : $field; ?>:</span>
		</label>
		<?php if ($type == "date") {
			$month_val = 0;
			$day_val = 0;
			$year_val = 0;
			if ($value == 'now') {
				$month_val = date('m');
				$day_val = date('d');
				$year_val = date('Y');
			}
		?>
			<select id="<?php echo $field; ?>_month" name="<?php echo $field; ?>_month">
				<option value="">Month</option>
				<?php for ($i=0; $i<count($MONTHS); $i++) { ?><option value="<?php echo $i+1; ?>" <?php if ($i+1 == $month_val) echo "selected"; ?>><?php echo $MONTHS[$i]; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_day" name="<?php echo $field; ?>_day">
				<option value="">Day</option>
				<?php for ($i=1; $i<=31; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i == $day_val) echo "selected"; ?>><?php echo $i; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_year" name="<?php echo $field; ?>_year">
				<option value="">Year</option>
				<?php for ($i=date("Y")/1; $i >= 1900; $i--) { ?><option value="<?php echo $i; ?>" <?php if ($i == $year_val) echo "selected"; ?>><?php echo $i; ?></option><?php } ?>
			</select>
		<?php } else { ?>
			<input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="" />
		<?php } ?>
	</p>
	<?php }
?>
<h2>Enter your information to qualify:</h2>

<div id="sheerid-form">
	
	<?php if (isset($this->session->data['verify_error'])) {
		$err = $this->session->data['verify_error'];
		unset($this->session->data['verify_error']);
		
		if (isset($this->session->data['sheer_id_token_url'])) {
			$tokenUrl = $this->session->data['sheer_id_token_url'];
			unset($this->session->data['sheer_id_token_url']);
		}
	?>
	<div class="warning">
		<span><?php echo $err; ?></span>
		<?php if (isset($tokenUrl)) { ?>
			<span><?php if (isset($this->session->data['upload_suggestion'])) { echo $this->session->data['upload_suggestion']; } ?></span>
			<br/><span><a class="link-upload" href="javascript:;"><?php echo $upload_link_text; ?></a>.</span>
		<?php } ?>
	</div>
	<?php } ?>
	
	<form method="POST" action="index.php?route=common/sheer_id" class="verify-form">
		<fieldset>
		<?php if ($org_type) { ?>
			<p>
				<label for="organization"><?php echo $label_organization; ?>:</label>
				<input type="text" id="organization" name="organizationId" />
			</p>
		<?php } ?>
	
		<?php foreach ($fields as $f) {
			$reqd = false;
			if ($f[0] == '*') {
				$reqd = true;
				$f = substr($f, 1);
			}
			renderField($f, $reqd, strpos($f, "_DATE") !== false ? "date" : "text", ${"field_$f"}, $f=="STATUS_START_DATE"?"now":null);
		} ?>
			<input type="hidden" name="coupon_code" value="<?php echo $config['coupon_code'] ?>" />
			<button type="submit" class="button">Verify</button>
		</fieldset>
	</form>
</div>

<?php if (isset($tokenUrl)) { ?>
	<script src="https://services.sheerid.com/jsapi/SheerID.js"></script>
	<script>
	jQuery(function($){
		$('.link-upload').click(function(){
			$.get('<?php echo $tokenUrl; ?>', {}, function(data){
				SheerID.load('asset', '1.0', {
					config: {
						maxFiles: 3,
						baseUrl: data.baseUrl,
						container: 'sheerid-form',
						success : '?success',
						failure : '?failure',
						ajax: true,
						token: data.token,
						formClass : 'uploadForm',
						messages : {
							'instructions' : '<?php echo $upload_instructions; ?>',
							'add_file' : '<?php echo $add_file; ?>',
							'error400' : '<?php echo $error400; ?>',
							'error401' : '<?php echo $error401; ?>',
							'error500' : '<?php echo $error; ?>',
							'error' : '<?php echo $error; ?>',
							'submit' : '<?php echo $upload_submit; ?>',
							'success' : '<?php echo $upload_success; ?>',
							'uploading' : '<?php echo $uploading; ?>'
						}
					}
				});
			}, 'json');
			return false;
		});
	});
	</script>
<?php } ?>

<?php if (isset($org_type)) { ?>
	<script src="https://services.sheerid.com/jsapi/SheerID.js"></script>
	<script>
	SheerID.load('combobox', '1.0', {
		config: {
			input: document.getElementById('organization'),
			allowName: true,
			proxyName: 'organizationName',
			params: {
				type: '<?php echo $org_type; ?>'
			}
		}
	});
	</script>
	
<?php } // end if $org_type

} else if (isset($this->session->data["sheerid_message"])) {
	$message = $this->session->data["sheerid_message"]["message"];
	$type = $this->session->data["sheerid_message"]["type"];
	unset($this->session->data["sheerid_message"]);
?>
	<div class="<?php echo $type; ?>"><?php echo $message; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php }
