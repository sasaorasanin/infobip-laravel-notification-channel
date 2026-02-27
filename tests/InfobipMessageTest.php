<?php

declare(strict_types=1);

use NotificationChannels\Infobip\InfobipMessage;

test('it can accept a content when constructing a message', function () {
    $message = new InfobipMessage('hello');

    expect($message->content)->toBe('hello');
});

test('it can set the content', function () {
    $message = (new InfobipMessage())->content('hello');

    expect($message->content)->toBe('hello');
});

test('it can set the from', function () {
    $message = (new InfobipMessage())->from('Infobip');

    expect($message->from)->toBe('Infobip');
});
