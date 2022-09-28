<?php


namespace App\Packages;

use App\Builders\IIosNotificationBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IosNotification
{
    const SANDBOX_ADDR = 'https://api.sandbox.push.apple.com';
    const PROD_ADDR = 'https://api.push.apple.com';
    const PORT = '443';
    const VERSION = 2.0;

    private static $connection = null;
    private static $sandboxConnection = null;

    private $certificate = null;
    private $passphrase = null;

    private $container;
    private $history;
    private $stack;

    /**
     *
     */
    public function __construct()
    {
        $this->openConnection();
        $this->openSandBoxConnection();
        $this->setConfig();
    }

    /**
     * @return bool
     */
    private function checkSandboxConnection()
    {
        return is_object(self::$sandboxConnection);
    }

    /**
     * @return bool
     */
    private function checkConnection()
    {
        return is_object(self::$connection);
    }

    /**
     * @return void
     */
    private function openConnection()
    {
        $this->container = [];
        $this->history = Middleware::history($this->container);

        $this->stack = HandlerStack::create();
        $this->stack->push($this->history);

        self::$connection = new Client([
            'base_uri' => self::PROD_ADDR . ':' . self::PORT,
            'handler'  => $this->stack,
        ]);
    }

    /**
     * @return void
     */
    private function openSandBoxConnection()
    {
        self::$sandboxConnection = new Client([
            'base_uri' => self::SANDBOX_ADDR . ':' . self::PORT,
        ]);
    }

    /**
     * @return void
     */
    public function setConfig()
    {
        $this->certificate = Storage::disk('local')->path('apns/certificate.pem');
        $this->passphrase = Config::get('push_notification.apple_passphrase');
    }

    /**
     * @param IIosNotificationBuilder $builder
     * @return void
     */
    public function sendNotification(IIosNotificationBuilder $builder)
    {
        if (!$this->checkConnection()) {
            $this->openConnection();
        }

        if (!$this->checkSandboxConnection()) {
            $this->openSandBoxConnection();
        }

        if ($builder->isSandbox()) {
            $connection = self::$sandboxConnection;
        } else {
            $connection = self::$connection;
        }


        $deviceToken = $builder->getDeviceToken();

        try {
            $response = $connection->request('POST', '/3/device/' . $deviceToken, [
                'headers' => [
                    'apns-push-type'  => $builder->getPushType(),
                    'apns-topic'      => $builder->getTopic(),
                    'apns-priority'   => $builder->getPriority(),
                    'apns-expiration' => $builder->getExpiration(),
                ],
                'cert'    => [
                    $this->certificate,
                    $this->passphrase,
                ],
                'version' => self::VERSION,
                'body'    => json_encode($builder->getBody()),
                'debug'   => true,
            ]);

            //For sending request debug
            /*foreach ($this->container as $transaction) {
                echo ((string) $transaction['request']->getBody());
                Log::channel('apple_push_notifications')->info((string) $transaction['request']->getBody());
            }*/

            Log::channel('apple_push_notifications')->info('Sending to token : ' . $deviceToken . ' ' . $response->getBody()->getContents());
        } catch (ClientException $e) {
            Log::channel('error_apple_push_notifications')->error('Problem with sending to token : ' . $deviceToken . ' ' . $e->getResponse()->getBody()->getContents());
        }
    }
}