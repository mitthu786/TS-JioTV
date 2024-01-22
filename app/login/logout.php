<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

$dir = "../assets/data/";
$fPattern = "*.jtv";

$files = glob($dir . $fPattern);

if ($files !== false && count($files) > 0) {
  $sCount = 0;
  $eCount = 0;

  foreach ($files as $filePath) {
    if (unlink($filePath)) {
      $sCount++;
    } else {
      $eCount++;
    }
  }

  if ($sCount > 0) {
    echo "Successfully deleted $sCount credential file(s).";
  }

  if ($eCount > 0) {
    echo "Encountered errors deleting $eCount credential file(s).";
  }
} else {
  echo "No credential files found in the directory.";
}
