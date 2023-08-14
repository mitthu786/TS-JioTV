<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

$filePath = "assets/data/creds.json";

if (file_exists($filePath)) {
  if (unlink($filePath)) {
    echo "Logged out and deleted credentials.";
  } else {
    echo "Error deleting credentials.";
  }
} else {
  echo "Credentials file not found.";
}
