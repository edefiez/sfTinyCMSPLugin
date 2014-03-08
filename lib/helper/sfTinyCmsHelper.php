<?php
/**
 * Helper for TinyCMS content
 */

/**
 * Initializes a page with TinyCMS content
 *
 */
function tinycms_load_assets()
{
	if (!sfTinyCMS::checkAccess()) {
		return;
	}
	use_javascript('/sfTinyCMSPlugin/js/ckeditor/ckeditor.js','last');
	use_stylesheet('/sfTinyCMSPlugin/js/ckeditor/custom_editable.css');
}

/**
 * Initializes a page with CKEditor content
 *
 */
function tinycms_init()
{
	if (!sfTinyCMS::checkAccess())	return;

	$url = url_for('sfTinyCmsContent/save');
	echo <<<EOL
	<script type='text/javascript'>
	function tinycms_save_content(name)
	{
		var text = CKEDITOR.instances[name].getData()
		$.ajax({
			type: "POST",
			url: "$url",
			data: {name: name, text: text},
			success: function() {
				alert(name+' save !');
			}
		});
	}
	</script>
EOL;
}

/**
 * Start editable content
 *
 * @param string $name
 * @return string
 */
function tinycms_editable_start()
{
	ob_start();
}

function tinycms_editable_flush($name)
{
	$output    = ob_get_contents();
	ob_end_clean();

	$content = TinyCmsContentTable::getInstance()->findOneBy('name', $name);
	if ($content)	{
		$output = $content->getText();
	}
	if (!sfTinyCMS::checkAccess())	{
		echo $output;
	}
	else {
		echo '<div id="'.$name.'" contenteditable="true">'.$output.'</div><button class="btn btn-xs btn-warning" onclick="tinycms_save_content(\''.$name.'\');">Save '.$name.'</button>';
	}
}