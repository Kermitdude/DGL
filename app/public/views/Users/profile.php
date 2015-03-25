<h1><?= $this->title ?></h1>

<?php if (isset($user)): ?>

Profile for <?= $user->name ?> goes here.

<br />

Some of the info you can use for this person is here:
<br />

<?php var_dump($user); ?>

<?php else:  ?>

No profile found for that user.

<?php endif; ?>

