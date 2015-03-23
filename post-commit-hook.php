<?php
$revision = trim($argv[4]);
$previousRevision = $revision - 1;

$commitMessage = file_get_contents($argv[3]);
$commitMessage = trim($commitMessage);

preg_match("#^[A-Z]{3,5}\-\d{1,6}#", $commitMessage, $issueKey);
if(!empty($issueKey[0])) {
	$patchFile = 'h:/temp/patches/' . $revision . '_-_' . $issueKey[0] . '.patch';
	system('svn diff -r ' . $previousRevision . ':' . $revision . ' > "' . $patchFile . '"');
}

function exitWithError($errorMessage) {
	$stderr = fopen('php://stderr', 'w');
	fwrite($stderr, $errorMessage);
	fclose($stderr);
	exit(1);
}
