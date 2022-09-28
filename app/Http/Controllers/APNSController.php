<?php

namespace App\Http\Controllers;

use App\Jobs\AppleAlertNotificationJob;
use App\Jobs\AppleVoipNotificationJob;
use Illuminate\Http\Request;

/**
 * Class APNSController
 * @package App\Http\Controllers
 */
class APNSController extends Controller
{
    /**
     * This method will handle VOIP notification
     */
    public function sendVoipNotification(Request $request)
    {
        $request = (array) json_decode(file_get_contents('php://input'));

        if (!empty($request)) {
            AppleVoipNotificationJob::dispatch($request);
        }

    }

    /**
     * This method will handle Alert notification
     */
    public function sendAlertNotification(Request $request)
    {
        $request = (array) json_decode(file_get_contents('php://input'));

        if (!empty($request)) {
            AppleAlertNotificationJob::dispatch($request);
        }

    }

}
