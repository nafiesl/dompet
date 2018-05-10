<?php
/**
 * Function helper to add flash notification.
 *
 * @param  null|string $message The flashed message.
 * @param  string $level   Level/type of message
 * @return void
 */
function flash($message = null, $level = 'info')
{
    $session = app('session');
    if (!is_null($message)) {
        $session->flash('flash_notification.message', $message);
        $session->flash('flash_notification.level', $level);
    }
}
