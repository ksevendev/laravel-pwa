<?php
namespace KsLaravelPwa\Http\Controllers;

use App\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verificar se o usuário está autenticado
        if ($user) {
            $user->updatePushSubscription(
                $request->input('endpoint'),
                $request->input('keys.p256dh'),
                $request->input('keys.auth'),
                $request->input('contentEncoding')
            );

            return response()->json(['success' => true, 'message' => 'Inscrição salva com sucesso']);
        }

        return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->deletePushSubscription($request->input('endpoint'));

            return response()->json(['success' => true, 'message' => 'Inscrição removida']);
        }

        return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
    }
}
