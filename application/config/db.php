<?php

// Connect DB

if (ENV === 'production')
{
    ORM::configure('mysql:host=localhost;dbname=fullio');
    ORM::configure('username', 'YOUR_USERNAME');
    ORM::configure('password', 'YOUR_PASSWORD');
}

if (ENV === 'dev')
{
    ORM::configure('mysql:host=localhost;dbname=fulliodb');
    ORM::configure('username', 'root');
    ORM::configure('password', '');
}
