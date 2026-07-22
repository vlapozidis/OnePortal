<?php

test('the root url redirects guests to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});
