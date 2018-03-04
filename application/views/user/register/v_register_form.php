<div id='form_register'>
    <h2><?= $title ?></h2>
    <table>
        <?= form_open('user/register', $form_att) ?>
        <?php if(isset($error)) : ?>
        <tr><td colspan='3'><?= $error ?></td></tr>
        <?php endif ?>
        <tr><td>Username: </td><td><?= form_input($username_input) ?></td><td><?= form_error('username') ?></td></tr>
        <tr><td>Password: </td><td><?= form_input($password_input) ?></td><td><?= form_error('password') ?></td></tr>
        <tr><td>Confirm Password: </td><td><?= form_input($confirm_pw) ?></td><td><?= form_error('confirm_pw') ?></td></tr>
        <tr><td><?= form_submit($form_submit) ?></td></tr>
        <?= form_close() ?>
        
    </table>

    


</div>