<?php
//c:\Users\user\Dropbox\apps\server\php\php.exe -f h:\pre-commit.php
$data = var_export($argv, true);

$data .= "\n\n" . '=======================================111=============================================>' . "\n\n\n\n";
$data .= file_get_contents($argv[1]);
$data .= "\n\n" . '=======================================111 strlen=========================================>' . "\n\n\n\n";
$data .= strlen(file_get_contents($argv[1]));
$data .= "\n\n" . '========================================333 =========================================>' . "\n\n\n\n";
$data .= file_get_contents($argv[3]);
$data .= "\n\n" . '========================================333 strlen=========================================>' . "\n\n\n\n";
$data .= strlen(file_get_contents($argv[3]));
$data .= "\n\n" . '=====================================================================================>' . "\n\n\n\n";
file_put_contents('h:/test.txt', $data);

$files = file($argv[1]);

ob_start();


foreach($files as $key => $file) {
	$file = trim($file);
	$files[$key] = $file;

	if(empty($file)) unset($files[$key]);
	if(!file_exists($file)) unset($files[$key]);
}

var_dump($files);

file_put_contents('h:/test.txt', ob_get_contents(), FILE_APPEND);
ob_end_clean();


//$filesString = '"' . implode('" "', $files) . '"';

$errors = false;
ob_start();
foreach($files as $file) {
	system('csslint --errors=known-properties,errors "' . $file . '"');
	$cssLinkOutput = ob_get_contents();
	$cssLinkOutput = trim($cssLinkOutput);
	if(strpos($cssLinkOutput, 'csslint: No errors') !== 0) {
		$errors = true;
		break;
	}
}
ob_end_clean();

file_put_contents('h:/test.txt', $cssLinkOutput, FILE_APPEND);

function getLine($match, $code) {
	return substr_count($code, "\n", 0, strpos($code, $match)) + 1;
}
/*
if($errors == false) {
	foreach($files as $file) {
		$cssCode = file_get_contents($file);
		$cssCode = preg_replace("#url\('data\:(.+?)'\)#msi", "url('base64')", $cssCode);
		
		// {      }
		if(preg_match("#\{(\s*)\}#ms", $cssCode, $m)) {
			$errors = true;
			$line = getLine($m[0], $cssCode);
			$cssLinkOutput = 'Empty {}! on line:' . $line;
			break;
		}
		
		// : ;!\n
		if(preg_match("#\:[^;]+\n#msi", $cssCode, $m)) {
			$errors = true;
			$line = getLine($m[0], $cssCode);
			$cssLinkOutput = 'no ;!! on line:' . $line;
			break;
		}
		
		
		// find color hex if not lower
		// ^....\t{2} error
		if(preg_match("#^#msi", $cssCode, $m)) {
			$errors = true;
			$line = getLine($m[0], $cssCode);
			$cssLinkOutput = 'no ;!! on line:' . $line;
			break;
		}
		// после } должно быть два \n
		// if strpos "  == fail
		// find :[notspace] or more than one or tab fail
		// 
	}
}*/

if($errors == false) {
	foreach($files as $file) {
		$filename = basename($file);
		$tempFile = 'h:/temp/'.$filename;
		copy($file, $tempFile);
		
		$tempFileContent = file_get_contents($tempFile);
		
		
		preg_match_all("#\/\*(.*?)\*\/#msi", $tempFileContent, $matches);
		foreach($matches[0] as $key => $match)
		{
			$tempFileContent = str_replace($match, '!!comments_var_' . $key.'!$$!', $tempFileContent);
		}
		
		$tempFileContent = preg_replace("#^[ \t]+#msi", '', $tempFileContent);
		$tempFileContent = preg_replace("#[ \t]+(\r\n|\n)#msi", "\n", $tempFileContent);
		$tempFileContent = preg_replace("#\}[ \t]#msi", '}', $tempFileContent);
		
		$tempFileContent = preg_replace("#[ \t]+#msi", ' ', $tempFileContent);
		$tempFileContent = preg_replace("#^\s*(\r\n|\n)#mi", '', $tempFileContent);
		

		$tempFileContent = preg_replace("#}(\n|\r\n)+#msi", '}', $tempFileContent);
		$tempFileContent = str_replace("}", "}\n\n", $tempFileContent);
		
		
		//$tempFileContent = str_replace('!$$!', '!$$!' . "\n", $tempFileContent);
		
		

		foreach($matches[0] as $key => $match)
		{
			$tempFileContent = str_replace('!!comments_var_' . $key.'!$$!', $match, $tempFileContent);
		}
		
		$tempFileContent = preg_replace("#\*\/\s+\/\*#msi", "*/\n/*", $tempFileContent);
		
		$tempFileContent = str_replace("\r\n", "\n", $tempFileContent);
		
		file_put_contents($tempFile, $tempFileContent);
		
	
		
		ob_start();
		system('csscomb --config h:/config-csscomb.json --verbose "' . $tempFile . '"');
		$csscomb = ob_get_contents();
		$csscomb = trim($csscomb);
		ob_end_clean();
		
		$originalFileContent = file_get_contents($file);
		$originalFileContent = str_replace("\r\n", "\n", $originalFileContent);
		
		file_put_contents('h:/temp/originalFile.css', $originalFileContent);
		
		system('C:/Users/user/AppData/Local/Programs/Git/bin/diff.exe -Naur "'.'h:/temp/originalFile.css'.'" "'.$tempFile.'" > "h:/temp/commit.patch"');
		
		
		$cssLinkOutput = file_get_contents('h:/temp/commit.patch');
		$cssLinkOutput = trim($cssLinkOutput);
		
		if(!empty($cssLinkOutput)){
			$cssLinkOutput = 'File not perfect. See: h:/temp/commit.patch';
			$errors = true;
			break;
		}
		
		
		/*
		$tempFileLines = file($tempFile);
		$sourceFileLines = file($file);
		foreach($sourceFileLines as $key => $line) {
			//$line = trim($line);
			$cssCombLine = $tempFileLines[$key];
			if($line != $cssCombLine) {
				$errors = true;
				$cssLinkOutput = ($key+1) . ' :: '. $line;
				break;
			}
			
		}
		if($errors) break;*/
		
	}
}




if($errors) {
	$stderr = fopen('php://stderr', 'w');
	fwrite($stderr, $cssLinkOutput);
	exit(1);
}


