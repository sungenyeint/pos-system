<?php

return [
    // common
    'default_paginate_number' => 10,
    'default_text_maxlength' => 191,
    // 大文字・小文字を含めた半角英数字記号を、6文字以上60文字以内
    'password_regex' => '/^((?=.*[a-z])(?=.*[A-Z]))([a-zA-Z0-9\-+=^$*.\[\]{}()?\"!@#%&\/\\\\,><\':;|_~`\-+=]){6,60}$/',

    'import_csv_file_path' => 'csv/',
];
