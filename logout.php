<?php
session_start();
session_destroy();
header('Location: /hpc_renluyen/');
exit;