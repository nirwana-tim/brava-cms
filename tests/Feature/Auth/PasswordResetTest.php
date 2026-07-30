<?php

test('password reset routes are disabled and return 404 for security', function () {
    $this->get('/forgot-password')->assertStatus(404);
    $this->post('/forgot-password', ['email' => 'admin@brava.id'])->assertStatus(404);
    $this->get('/reset-password/dummy-token')->assertStatus(404);
});
