<?php
date_default_timezone_set('Europe/Paris');
file_put_contents(__DIR__ . '/../var/timezone.log', date_default_timezone_get());
