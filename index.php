<?
/*
 * This script will fetch drupal module files from remote server
 * and decompress them in the appropriate directory, after which it cleans up.
 * This file should be executed in the public_html/ of your drupal site. 
 * doing otherwise may result in unexpected behaviour.
 * 
 * @author Allan Thue Rehhoff
 * @company SharksMedia
 * @date 2014-03-15
 */
	ini_set('display_errors', "On");
	error_reporting(E_ALL);
	set_time_limit(60);
	
	//Array key is used as identifier throughout this scirpt, so please keep those intact.
	//absolute paths only, must be .tar.gz packages
	//TODO support .zip
	$modules = array(
		'_pathauto' => array(
			'name' => 'Pathauto',
			'url'  => 'http://ftp.drupal.org/files/projects/pathauto-7.x-1.2.tar.gz',
			'folder' => 'pathauto',
			'version' => '7.x-1.2'
		),
		'_token' => array(
			'name' => 'Token',
			'url' => 'http://ftp.drupal.org/files/projects/token-7.x-1.5.tar.gz',
			'folder' => 'token',
			'version' => '7.x-1.5'
		),
		'_jquery_update' => array(
			'name' => 'jQuery Update',
			'url'  => 'http://ftp.drupal.org/files/projects/jquery_update-7.x-2.3.tar.gz',
			'folder' => 'jquery_update',
			'version' => '7.x-2.3'
		),
		'_entities' => array(
			'name' => 'Entity API',
			'url' => 'http://ftp.drupal.org/files/projects/entity-7.x-1.3.tar.gz',
			'folder' => 'entity',
			'version' => '7.x-1.3'
		),
		'_libraries' => array(
			'name' => 'Libraries API',
			'url' => 'http://ftp.drupal.org/files/projects/libraries-7.x-2.2.tar.gz',
			'folder' => 'libraries',
			'version' => '7.x-2.2'
		),
		'_ckeditor' => array(
			'name' => 'CKeditor',
			'url'  => 'http://ftp.drupal.org/files/projects/ckeditor-7.x-1.13.tar.gz',
			'folder' => 'ckeditor',
			'version' => '7.x-1.13'
		),
		'_imce' => array(
			'name' => 'IMCE',
			'url'  => 'http://ftp.drupal.org/files/projects/imce-7.x-1.8.tar.gz',
			'folder' => 'imce',
			'version' => '7.x-1.8'
		),
		'_imce_mkdir' => array(
			'name' => 'IMCE mkdir',
			'url'  => 'http://ftp.drupal.org/files/projects/imce_mkdir-7.x-1.0.tar.gz',
			'folder' => 'imce_mkdir',
			'version' => '7.x-1.0'
		),
		'_google_analytics' => array(
			'name' => 'Google Analytics',
			'url'  => 'http://ftp.drupal.org/files/projects/google_analytics-7.x-1.4.tar.gz',
			'folder' => 'google_analytics',
			'version' => '7.x-1.4'
		),
		'_rules' => array(
			'name' => 'Rules',
			'url' => 'http://ftp.drupal.org/files/projects/rules-7.x-2.6.tar.gz',
			'folder' => 'rules',
			'version' => '7.x-2.6'
		),
		'_i10n_update' => array( 
			'name' => 'Locale Update',
			'url' => 'http://ftp.drupal.org/files/projects/l10n_update-7.x-1.0-rc1.tar.gz',
			'folder' => 'l10n_update',
			'version' => '7.x-1.0-rc1'
		),
		'_internationalization' => array(
			'name' => 'i18n',
			'url' => 'http://ftp.drupal.org/files/projects/i18n-7.x-1.10.tar.gz',
			'folder' => 'i18n',
			'version' => '7.x-1.10'
		),
		'_gallery_formatter' => array(
			'name' => 'Gallery Formatter',
			'url' => 'http://ftp.drupal.org/files/projects/galleryformatter-7.x-1.3.tar.gz',
			'folder' => 'galleryformatter',
			'version' => '7.x-1.3'
		),
		'_shadowbox' => array(
			'name' => 'Shadowbox',
			'url' => 'http://ftp.drupal.org/files/projects/shadowbox-7.x-3.0-rc2.tar.gz',
			'folder' => 'shadowbox',
			'version' => '7.x-3.0-rc2'
		),
		'_webform' => array(
			'name' => 'Webform',
			'url' => 'http://ftp.drupal.org/files/projects/webform-7.x-3.20.tar.gz',
			'folder' => 'webform',
			'version' => '7.x-3.20'
		),
		'_ctools' => array(
			'name' => 'Chaos Tools Suite',
			'url' => 'http://ftp.drupal.org/files/projects/ctools-7.x-1.4.tar.gz',
			'folder' => 'ctools',
			'version' => '7.x-1.4'
		),
		'_views' => array(
			'name' => 'Views',
			'url' => 'http://ftp.drupal.org/files/projects/views-7.x-3.7.tar.gz',
			'folder' => 'views',
			'version' => '7.x-3.7'
		),
		'_menu_blocks' => array(
			'name' => 'Menu blocks',
			'url' => 'http://ftp.drupal.org/files/projects/menu_block-7.x-2.3.tar.gz',
			'folder' => 'menu_block',
			'version' => '7.x-2.3'
		),
		'_xml_sitemap' => array(
			'name' => 'XML Sitemap',
			'url' => 'http://ftp.drupal.org/files/projects/xmlsitemap-7.x-2.0-rc2.tar.gz',
			'folder' => 'xmlsitemap',
			'version' => '7.x-2.0-rc2'
		),
		'_devel' => array(
			'name' => 'Devel',
			'url' => 'http://ftp.drupal.org/files/projects/devel-7.x-1.4.tar.gz',
			'folder' => 'devel',
			'version' => '7.x-1.4'
		)
	);
	//Modules to mark for download as default, Provide only array key identifiers.
	$defaults = array(
	 	'_libraries',
	 	'_entities',
	 	'_pathauto',
	 	'_token',
	 	'_jquery_update',
	 	'_ckeditor',
	 	'_imce',
	 	'_imce_mkdir',
		'_google_analytics'
	);
		
	$cwd = realpath(dirname(__FILE__));
	$processed = false;
	$warning = false;
	$messages = false;
	
	//Remember trailing slash.
	//TODO make script check for trailing slash.
	$modulePath = $cwd."/sites/all/modules/";
	 
	//This is where the magic begins.
	function getHeaders($url) {	
	  $ch = curl_init($url);
	  curl_setopt( $ch, CURLOPT_NOBODY, true );
	  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
	  curl_setopt( $ch, CURLOPT_HEADER, false );
	  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	  curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
	  curl_exec( $ch );
	  $headers = curl_getinfo( $ch );
	  curl_close( $ch );
	
	  return $headers;
	}
	function download($url, $path) {
		$source = file_get_contents($url);
	  	file_put_contents($path, $source);
		return (filesize($path) > 0);
	}
	//Solely for debugging purposes
	function pre($obj) {
		if(is_bool($obj)) {
			var_dump($obj);
		} else {
			echo "<pre>";
			print_r($obj);
			echo "</pre>";
		}
	}
	if(isset($_POST['install'])) {
		$i = 0;		
		foreach($_POST['modules'] as $module) {
			$file = $modules[$module]['url'];
			$headers = getHeaders($file);
			if($headers['http_code'] == 404) {
				$messages[] = array(
					'type' => 'error',
					'message' => 'HTTP ERROR: 404 Not found - '.$file
				);
				continue;
			} elseif($headers['http_code'] == 403) {
				$messages[] = array(
					'type' => 'error',
					'message' => 'HTTP ERROR: 403 Forbidden - '.$file
				);
				continue;
			} elseif($headers['http_code'] == 500) {
				$messages[] = array(
					'type' => 'error',
					'message' => 'HTTP ERROR: 500 Remote server error - '.$file
				);
				continue;
			}
			if($headers['http_code'] == 200) {
				if(!isset($modules[$module]['folder'])) {
					$messages[] = array(
						'type' => 'notice',
						'message' => 'SKIPPED - Cannot perform directory security check, folder index not specified - '.$modules[$module]['name']
					);
					continue;
				}
				$fileParts = explode('/', $file);
				$filename = end($fileParts);
				$filePath = $modulePath.'/'.$filename;
				$moduleDir = $modulePath.'/'.$modules[$module]['folder'];
				
				if(file_exists($moduleDir)) {
					 $messages[] = array(
					 	'type' => 'notice',
					 	'message' => 'SKIPPED - Module directory already exists - '.$modules[$module]['name']
					 );
					 continue;
				}
				if (download($file, $filePath)) {
					try {
						$p = new PharData($filePath);
						$p->extractTo($modulePath);
			
						$messages[] = array(
							'type'    => 'done',
							'message' => 'INSTALLED - '.$modules[$module]['name']
						);
						
						unlink($filePath);

					} catch(Exception $e) {
						$messages[] = array(
							'type' => 'notice',
							'message' => 'DOWNLOAD - Decompress file manually - ' . $filename . " - ". $e->getMessage()
						);
					}

				} else {
					$messages[] = array(
						'type'    => 'error',
						'message' => 'WARNING: Something awful went wrong when downloading - '.$file
					);
				}
			} else {
				$messages[] = array(
						'type' => 'error',
						'message' => 'HTTP ERROR: '.$headers['http_code'].' - Something went wrong '.$file
					);
					continue;	
			} 
		}
		$endtime = time();
		$processed = true;
	}
?>
<html>
	<head>
		<title>Drupal 7 initial module downloader - SharksMedia</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<style type="text/css">
			*{margin:0;padding:0}
			body{background:#3D3D3D;font-family:tahoma}
			#head{margin-bottom:20px;min-width:1000px;max-width:100%;background-color:#2B2B2B;border-bottom:4px solid #444442;font-size:15px;height:36px;padding-top:3px}
			#head h1{text-align:center;color:#4EA6B2;font-size:25px}
			#content{border:2px solid #FFF;width:956px;background:#ECECEC;margin:0 auto;padding:20px}
			#actualContent{background:#FAFAFC;border:1px solid #A3A3A3;padding:20px}
			.release{background:#DFD}
			.dev,.alpha{background:#FDD}
			.rc{background:#FFD}
			.module-element{width:220px;margin-bottom:10px;display:inline-block}
			.error,.notice,.done{margin-top:5px;margin-bottom:5px;padding:10px}
			.error{background:#FFBABA;border:1px solid #D8000C}
			.notice{background:#BDE5F8;border:1px solid #00529B}
			.done{background:#DFF2BF;border:1px solid #4F8A10}
			label{cursor:pointer}
			.button{text-decoration:none;text-shadow:0 1px 0 #fff;cursor:pointer;font:bold 11px Helvetica, Arial, sans-serif;color:#444;line-height:17px;display:inline-block;display:inline-block;background:#F3F3F3;border:solid 1px #D9D9D9;border-radius:2px;-webkit-border-radius:2px;-moz-border-radius:2px;outline:none;-webkit-transition:border-color .20s;-moz-transition:border-color .20s;-o-transition:border-color .20s;transition:border-color .20s;margin:5px;padding:5px 6px 4px}
			.button:hover{text-decoration:none!important;background:#F4F4F4;color:#333;border-color:silver}
			.button:active{-moz-box-shadow:inset 0 0 10px #D4D4D4;-webkit-box-shadow:inset 0 0 10px #D4D4D4;box-shadow:inset 0 0 10px #D4D4D4}
		</style>
	</head>
	<body>
		<div id="head">
			<h1>&laquo; SharksMedia drupal 7 module downloader &raquo;</h1>
		</div>
		<div id="content">
			<?
				if(!is_dir($modulePath)) {
					echo '<div class="error">WARNING: ' . $modulePath . ' No such directory.</div>';
					$warning = true;
				}
				if(!is_writeable($modulePath)) {
					echo '<div class="error">WARNING: ' . $modulePath . ' Is not a writeable directory.</div>';
					$warning = true;
				}
			?>
				<? if($processed) { ?>
					<div class="done">INSTALL TIME - <? echo ($endtime - $_SERVER['REQUEST_TIME']); ?> seconds.</div>
				<? } else { ?>
					<div id="actualContent">
						<form action="" method="POST">
							<? foreach($modules as $key => $module): ?>
								<?
									if(stripos($module['version'], 'beta')) {
										$class = "beta";
									} elseif(stripos($module['version'], 'rc')) {
										$class = "rc";
									} elseif(stripos($module['version'], 'dev')) {
										$class = "dev";
									} elseif(stripos($module['version'], 'alpha')) {
										$class = "alpha";
									} elseif(!isset($module['version'])) {
										$class = 'na';
									} else {
										$class = "release";
									}
								?>
								<div class="module-element">
									<input id="<?=$key; ?>" type="checkbox" name="modules[]" value=<?=$key;?> <? echo in_array($key, $defaults) ? 'checked="checked"' : ''; ?> />
									<label for="<?=$key; ?>">
									<? echo $module['name'] . ' - ' . '<span class="'.$class.'">' . '<span class="version">' . (isset($module['version']) ? $module['version'] : '<em>N/A</em>') . '</span></span>'; ?>
									</label>
								</div>
							<? endforeach; ?>
							<? if($warning): ?>
								<span style="color:maroon;">There were some unresolved errors, please fix before proceeding.</span>
							<? else: ?>
								<input class="button install" type="submit" value="Install selected modules. (Please be patient)" name="install" />
								<input class="button markall" type="button" value="Check all" />
								<input class="button uncheckall" type="button" value="Uncheck all" />
							<? endif; ?>
							<div style="clear:both;"></div>
						</form>
					</div>
				<? } ?>
			<div id="messages">
				<?
					if($messages) {
						foreach($messages as $message) {
							echo '<div class="'.$message['type'].'">'.$message['message'].'</div>';
						}
					}
				?>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				//TODO Maybe we should notify if rc, dev, alpha packages are selected?
				
				$('.markall').click(function() {
					$('input[type="checkbox"]').prop('checked', true);
				})
				$('.uncheckall').click(function() {
					$('input[type="checkbox"]').prop('checked', false);
				})
			});
		</script>
	</body>
</html>