<?php
declare(strict_types=1);

namespace Recaptcha\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Recaptcha helper
 */
class RecaptchaHelper extends Helper
{
    /**
     * Default config
     *
     * These are merged with user-provided config when the component is used.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        // This is test only key/secret
        'sitekey' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
        'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
        'theme' => 'light',
        'type' => 'image',
        'callback' => null,
        'enable' => true,
        'lang' => 'en',
        'size' => 'normal',
        'scriptBlock' => true,
    ];

    /**
     * Helpers
     *
     * @var array
     */
    protected array $helpers = ['Form'];

    /**
     * initialize
     *
     * @param array $config config
     * @return void
     */
    public function initialize(array $config = []): void
    {
        $config += Configure::read('Recaptcha', []);
        $this->setConfig($config);
    }

    /**
     * Display recaptcha function
     *
     * @param array $config Config
     * @return string
     */
    public function display(array $config = []): string
    {
        $recaptcha = $config + $this->getConfig();
        if (!(bool)$recaptcha['enable']) {
            return '';
        }

        return $this->_View->element('Recaptcha.recaptcha', compact('recaptcha'));
    }
}
