<div id='form_login'>
    <h2><?= $title ?></h2>
    <table>
        <?= form_open('user/login', $form_att) ?>
        <?php if(isset($error)) : ?>
        <tr><td colspan='3'><?= $error ?></td></tr>
        <?php endif ?>
        <tr><td>Username: </td><td><?= form_input($username_input) ?></td><td><?= form_error('username') ?></td></tr>
        <tr><td>Password: </td><td><?= form_input($password_input) ?></td><td><?= form_error('password') ?></td>
        <td><?php if (isset($error_password)) : ?> <?= $error_password ?> <?php endif ?></td></tr>
        <tr><td colspan='3'><?= form_checkbox($remember_input) ?> Remember me</td></tr>
        <tr><td><?= form_submit($form_submit) ?></td></tr>
        <?= form_close() ?>
    </table>


</div>