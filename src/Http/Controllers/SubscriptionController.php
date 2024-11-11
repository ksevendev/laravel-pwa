<?php
namespace KsLaravelPwa\Http\Controllers;

use App\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\WebPush\PushSubscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = Auth::user();

        // Salvar ou atualizar a inscrição do usuário
        $subscription = $user->updatePushSubscription(
            $request->input('subscription.endpoint'),
            $request->input('subscription.keys.p256dh'),
            $request->input('subscription.keys.auth')
        );

        return response()->json(['success' => true, 'subscription' => $subscription]);
    }
}
