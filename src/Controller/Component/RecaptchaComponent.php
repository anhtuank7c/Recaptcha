<?php
declare(strict_types=1);

namespace Recaptcha\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Client;
use Cake\I18n\I18n;
use Exception;
use Locale;

/**
 * Recaptcha component
 */
class RecaptchaComponent extends Component
{
    public const VERIFY_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

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
        'lang' => null,
        'size' => 'normal',
        'httpClientOptions' => [],
        'scriptBlock' => true,
    ];

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

        if (!$this->getConfig('lang')) {
            $this->setConfig('lang', Locale::getPrimaryLanguage(I18n::getLocale()));
        }
    }

    /**
     * beforeRender
     *
     * @param \Cake\Event\EventInterface $event Controller.beforeRender event
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        $config = $this->getConfig();
        unset($config['secret'], $config['httpClientOptions']);

        $this->getController()->viewBuilder()->addHelpers(['Recaptcha.Recaptcha' => $config]);
    }

    /**
     * verify recaptcha
     *
     * @return bool
     */
    public function verify(): bool
    {
        if (!(bool)$this->_config['enable']) {
            return true;
        }

        $controller = $this->_registry->getController();
        if ($controller->getRequest()->getData('g-recaptcha-response')) {
            try {
                $response = json_decode($this->apiCall(), flags: JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                return false;
            }

            if (isset($response->success)) {
                return (bool)$response->success;
            }
        }

        return false;
    }

    /**
     * Call reCAPTCHA API to verify
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function apiCall(): string
    {
        $controller = $this->_registry->getController();
        $client = new Client($this->_config['httpClientOptions']);
        $data = [
            'secret' => $this->_config['secret'],
            'response' => $controller->getRequest()->getData('g-recaptcha-response'),
            'remoteip' => $controller->getRequest()->clientIp(),
        ];

        return (string)$client->post(static::VERIFY_ENDPOINT, $data)->getBody();
    }
}
