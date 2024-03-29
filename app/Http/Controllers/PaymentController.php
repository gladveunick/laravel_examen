<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Reservation;

class PaymentController extends Controller
{
    public function showCheckoutForm($id)
    {
        // Récupérer la réservation à partir de son ID
        $reservation = Reservation::findOrFail($id);

        // Passer la réservation à la vue du formulaire de paiement
        return view('reservations.payment', compact('reservation'));
    }
    public function processPayment(Request $request)
    {
        // Configurez votre clé secrète Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // Créez un paiement avec l'API de Stripe en utilisant l'identifiant de méthode de paiement
        $paymentIntent = PaymentIntent::create([
            'payment_method' => $request->payment_method_id,
            'amount' => $request->amount,
            'currency' => 'usd',
            'confirmation_method' => 'manual',
            'confirm' => true,
        ]);

        // Retournez une réponse appropriée au client
        return response()->json(['success' => true]);
    }
}
