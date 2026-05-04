<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mailer
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function sendVerificationCode(string $toEmail, string $toName, string $code): void
    {
        $mail = $this->createConfiguredMailer($toEmail, $toName);
        $mail->Subject = 'Votre code de verification';
        $mail->Body = $this->buildVerificationHtmlBody($toName, $code);
        $mail->AltBody = "Votre code de verification est : {$code}";
        $this->send($mail);
    }

    public function sendPasswordResetCode(string $toEmail, string $toName, string $code, int $expiresInMinutes = 10): void
    {
        $mail = $this->createConfiguredMailer($toEmail, $toName);
        $mail->Subject = 'Code de reinitialisation de mot de passe';
        $mail->Body = $this->buildPasswordResetHtmlBody($toName, $code, $expiresInMinutes);
        $mail->AltBody = "Votre code de reinitialisation est : {$code}. Il expire dans {$expiresInMinutes} minutes.";
        $this->send($mail);
    }

    /**
     * Envoyer un lien de réinitialisation de mot de passe avec token
     */
    public function sendPasswordResetLink(string $toEmail, string $toName, string $resetLink, int $expiresInMinutes = 15): void
    {
        $mail = $this->createConfiguredMailer($toEmail, $toName);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = $this->buildPasswordResetLinkHtmlBody($toName, $resetLink, $expiresInMinutes);
        $mail->AltBody = "Cliquez sur le lien pour réinitialiser votre mot de passe : {$resetLink}. Ce lien expire dans {$expiresInMinutes} minutes.";
        $this->send($mail);
    }

    /**
     * Envoyer une notification de changement de mot de passe
     */
    public function sendPasswordChangedNotification(string $toEmail, string $toName): void
    {
        $mail = $this->createConfiguredMailer($toEmail, $toName);
        $mail->Subject = 'Votre mot de passe a été modifié';
        $mail->Body = $this->buildPasswordChangedHtmlBody($toName);
        $mail->AltBody = 'Votre mot de passe a été modifié avec succès. Si vous n\'êtes pas à l\'origine de cette action, contactez le support.';
        $this->send($mail);
    }

    /**
     * Envoyer une notification quand le compte est vérifié par un admin
     */
    public function sendAccountVerifiedNotification(string $toEmail, string $toName): void
    {
        $mail = $this->createConfiguredMailer($toEmail, $toName);
        $mail->Subject = 'Votre compte a été vérifié';
        $mail->Body = $this->buildAccountVerifiedHtmlBody($toName);
        $mail->AltBody = "Bonjour {$toName},\n\nVotre compte a été vérifié avec succès. Vous bénéficiez maintenant du badge de confiance.\n\nCordialement.";
        $this->send($mail);
    }

    private function createConfiguredMailer(string $toEmail, string $toName): PHPMailer
    {
        if (!class_exists(PHPMailer::class)) {
            throw new RuntimeException('PHPMailer n\'est pas disponible. Installez-le via Composer.');
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = (string) ($this->config['host'] ?? '');
        $mail->SMTPAuth = true;
        $mail->Username = (string) ($this->config['username'] ?? '');
        $mail->Password = (string) ($this->config['password'] ?? '');
        $mail->Port = (int) ($this->config['port'] ?? 587);
        $mail->SMTPSecure = (string) ($this->config['encryption'] ?? 'tls');
        $mail->CharSet = 'UTF-8';

        $fromEmail = (string) ($this->config['from_email'] ?? $mail->Username);
        $fromName = (string) ($this->config['from_name'] ?? 'Support');
        $replyTo = (string) ($this->config['reply_to'] ?? $fromEmail);

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        if ($replyTo !== '') {
            $mail->addReplyTo($replyTo, $fromName);
        }

        $mail->isHTML(true);

        return $mail;
    }

    private function send(PHPMailer $mail): void
    {
        try {
            $mail->send();
        } catch (PHPMailerException $exception) {
            throw new RuntimeException('Erreur SMTP: ' . $exception->getMessage());
        }
    }

    private function buildVerificationHtmlBody(string $name, string $code): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Code de verification</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; border: 1px solid #e9edf5;">
    <h2 style="margin: 0 0 12px; color: #1f2937;">Bonjour {$safeName},</h2>
    <p style="margin: 0 0 16px; color: #374151;">Voici votre code de verification :</p>
    <div style="font-size: 28px; font-weight: bold; letter-spacing: 4px; color: #111827;">{$safeCode}</div>
    <p style="margin: 16px 0 0; color: #6b7280;">Ce code expire dans 10 minutes.</p>
  </div>
</body>
</html>
HTML;
    }

    private function buildPasswordResetHtmlBody(string $name, string $code, int $expiresInMinutes): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
        $safeMinutes = max(1, $expiresInMinutes);

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Réinitialisation du mot de passe</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; border: 1px solid #e9edf5;">
    <h2 style="margin: 0 0 12px; color: #1f2937;">Bonjour {$safeName},</h2>
    <p style="margin: 0 0 16px; color: #374151;">Vous avez demandé la réinitialisation de votre mot de passe.</p>
    <p style="margin: 0 0 16px; color: #374151;">Voici votre code de vérification :</p>
    <div style="font-size: 28px; font-weight: bold; letter-spacing: 4px; color: #111827;">{$safeCode}</div>
    <p style="margin: 16px 0 0; color: #6b7280;">Ce code expire dans {$safeMinutes} minutes.</p>
    <p style="margin: 12px 0 0; color: #6b7280;">Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.</p>
  </div>
</body>
</html>
HTML;
    }

    private function buildPasswordResetLinkHtmlBody(string $name, string $resetLink, int $expiresInMinutes): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeLink = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');
        $safeMinutes = max(1, $expiresInMinutes);

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Réinitialisation du mot de passe</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; border: 1px solid #e9edf5;">
    <h2 style="margin: 0 0 12px; color: #1f2937;">Bonjour {$safeName},</h2>
    <p style="margin: 0 0 16px; color: #374151;">Vous avez demandé la réinitialisation de votre mot de passe.</p>
    <p style="margin: 0 0 24px; color: #374151;">Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
    
    <div style="text-align: center; margin: 0 0 24px;">
      <a href="{$safeLink}" style="display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 16px;">
        Réinitialiser mon mot de passe
      </a>
    </div>

    <p style="margin: 0 0 12px; color: #6b7280; text-align: center; font-size: 12px;">
      Ou copiez ce lien dans votre navigateur :<br />
      <code style="word-break: break-all; background: #f3f4f6; padding: 8px; border-radius: 4px; display: block; margin-top: 8px;">{$safeLink}</code>
    </p>

    <p style="margin: 12px 0 0; color: #6b7280;">Ce lien expire dans {$safeMinutes} minutes.</p>
    <p style="margin: 12px 0 0; color: #6b7280;">Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.</p>
  </div>
</body>
</html>
HTML;
    }

    private function buildPasswordChangedHtmlBody(string $name): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Mot de passe modifié</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; border: 1px solid #e9edf5;">
    <h2 style="margin: 0 0 12px; color: #1f2937;">Bonjour {$safeName},</h2>
    <p style="margin: 0 0 16px; color: #374151;">Votre mot de passe a été modifié avec succès.</p>
    <p style="margin: 0 0 16px; color: #374151;">Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
    <p style="margin: 12px 0 0; color: #6b7280;">Si vous n'êtes pas à l'origine de cette modification, veuillez contacter le support immédiatement.</p>
  </div>
</body>
</html>
HTML;
    }

    private function buildAccountVerifiedHtmlBody(string $name): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Compte vérifié</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 20px;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; border: 1px solid #e9edf5;">
    <h2 style="margin: 0 0 12px; color: #1f2937;">Bonjour {$safeName},</h2>
    <p style="margin: 0 0 16px; color: #374151;">Votre compte a été vérifié avec succès par notre équipe. Vous bénéficiez maintenant du badge de confiance <strong>✔️</strong>.</p>
    <p style="margin: 0 0 16px; color: #374151;">Merci de votre confiance.</p>
    <p style="margin: 12px 0 0; color: #6b7280;">Cordialement,<br>L'équipe</p>
  </div>
</body>
</html>
HTML;
    }
}
