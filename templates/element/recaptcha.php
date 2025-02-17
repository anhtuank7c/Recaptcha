<?php
/**
 * @var \Cake\View\View $this
 */
use Cake\Core\Exception\CakeException;

try {
    $this->Form->unlockField('g-recaptcha-response');
} catch (CakeException) {
    // If FormProtectorComponent is not loaded, an exception in thrown in older CakePHP versions.
}
?>
<?= $this->Html->script(
    'https://www.google.com/recaptcha/api.js?hl=' . $recaptcha['lang'],
    [
        'block' => $recaptcha['scriptBlock'],
        'async' => true,
        'defer' => true,
    ]
) ?>
<div
    class="g-recaptcha"
    data-sitekey="<?= $recaptcha['sitekey'] ?>"
    data-theme="<?= $recaptcha['theme'] ?>"
    data-type="<?= $recaptcha['type'] ?>"
    data-size="<?= $recaptcha['size'] ?>"
    <?php if ($recaptcha['callback']) : ?>
        data-callback="<?= $recaptcha['callback'] ?>"
    <?php endif ?>
>
</div>
<noscript>
  <div>
    <div style="width: 302px; height: 422px; position: relative;">
      <div style="width: 302px; height: 422px; position: absolute;">
        <iframe src="https://www.google.com/recaptcha/api/fallback?k=<?= $recaptcha['sitekey'] ?>"
                frameborder="0" scrolling="no"
                style="width: 302px; height:422px; border-style: none;">
        </iframe>
      </div>
    </div>
    <div style="width: 300px; height: 60px; border-style: none;
                   bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
                   background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
      <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                   class="g-recaptcha-response"
                   style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
                          margin: 10px 25px; padding: 0px; resize: none;" >
      </textarea>
    </div>
  </div>
</noscript>
