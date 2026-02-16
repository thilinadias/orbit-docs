<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA Setup page (QR Code).
     */
    public function setup(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        // Generate a new secret if one doesn't exist (or if we are re-setting up)
        // Note: We don't save it to the user yet, we pass it to the view.
        // OR we can save it temporarily/session.
        // Better approach: Generate it, show it. verification saves it.
        
        $secretKey = $google2fa->generateSecretKey();
        
        // Save to session to verify later
        $request->session()->put('2fa:secret', $secretKey);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );

        // Generate QR Code Image
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($qrCodeUrl);

        return view('profile.two-factor-setup', [
            'secret' => $secretKey,
            'qrCodeImage' => $qrCodeImage
        ]);
    }

    /**
     * Enable 2FA after verifying the Code.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $secret = $request->session()->get('2fa:secret');

        if (!$secret) {
            return back()->withErrors(['code' => 'Session expired. Please try again.']);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $user->google2fa_secret = $secret;
            $user->is_2fa_enforced = false; // They have enabled it, so enforcement is satisfied
            $user->save();
            
            $request->session()->forget('2fa:secret');
            // Mark session as 2fa verified
            $request->session()->put('2fa:verified', true);

            return redirect()->route('profile.edit')->with('status', 'Two-Factor Authentication enabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid authentication code. Please try again.']);
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->is_2fa_enforced = false; // Disable enforcement if they explicitly disable it (optional choice, but logical)
        $user->save();

        $request->session()->forget('2fa:verified');

        return back()->with('status', 'Two-Factor Authentication disabled.');
    }

    /**
     * Show the 2FA Challenge page (Login).
     */
    public function challenge()
    {
        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the 2FA Challenge logic.
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();
        
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $request->session()->put('2fa:verified', true);
            return redirect()->intended(route('root'));
        }

        return back()->withErrors(['code' => 'Invalid authentication code.']);
    }
}
