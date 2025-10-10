<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        session()->forget(['password_reset_email', 'password_reset_verified']);

        return $this->renderStep('email');
    }

    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
        ], [
            'email.required' => 'Informe o endereço de e-mail cadastrado.',
            'email.email' => 'Informe um e-mail válido.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Não encontramos um usuário com esse e-mail.']);
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'code' => $code,
                'created_at' => Carbon::now(),
            ]
        );

        try {
            Mail::to($user->email)->send(
                new PasswordResetCode($user, $code, app('congregacao'))
            );
        } catch (\Throwable $exception) {
            Log::error('Falha ao enviar e-mail de recuperação de senha.', [
                'email' => $user->email,
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Não conseguimos enviar o e-mail. Verifique a configuração do serviço de mensagens e tente novamente.',
                ]);
        }

        session([
            'password_reset_email' => $user->email,
            'password_reset_verified' => false,
        ]);

        return redirect()
            ->route('password.verifyForm')
            ->with('status', 'Enviamos um código de verificação para o seu e-mail.');
    }

    public function showCodeForm()
    {
        if (! session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return $this->renderStep('code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $email = session('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request');
        }

        $reset = DB::table('password_resets')
            ->where('email', $email)
            ->where('code', $request->code)
            ->first();

        if (! $reset) {
            return back()->withErrors(['code' => 'Código inválido. Verifique os dígitos e tente novamente.']);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'O código expirou. Solicite um novo código.']);
        }

        session(['password_reset_verified' => true]);

        return redirect()->route('password.resetForm');
    }

    public function showResetForm()
    {
        if (! session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        if (! session('password_reset_verified')) {
            return redirect()->route('password.verifyForm');
        }

        return $this->renderStep('reset');
    }

    public function resetPassword(Request $request)
    {
        if (! session('password_reset_email') || ! session('password_reset_verified')) {
            return redirect()->route('password.request');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'password.confirmed' => __('auth.passwords.password_confirmation_mismatch'),
        ]);

        $email = session('password_reset_email');

        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        DB::table('password_resets')->where('email', $email)->delete();
        session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')->with('success', 'Senha alterada com sucesso! Faça login novamente.');
    }

    protected function renderStep(string $step)
    {
        $congregacao = app('congregacao');

        return view('login.password-recovery', [
            'congregacao' => $congregacao,
            'step' => $step,
        ]);
    }
}
