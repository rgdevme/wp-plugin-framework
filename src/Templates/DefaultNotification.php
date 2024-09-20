<?php
/** @var (array{
 *  message: string,
 *  code: string,
 *  setting: string,
 *  type: string
 * }[]) $notifications */
?>

<?php foreach ($notifications as $notification) { ?>
  <div
    class="notice notice-<?= $notification['type'] ?> is-dismissible <?= $notification['setting'] ?>">
    <p>
      <?= $notification['message'] ?>
    </p>
  </div>
<?php } ?>