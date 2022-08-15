<?php

mysqly::update('metrics', ['user_id' => user::id(), 'metric' => $_POST['metric']], ['folder_id' => $_POST['folder']]);