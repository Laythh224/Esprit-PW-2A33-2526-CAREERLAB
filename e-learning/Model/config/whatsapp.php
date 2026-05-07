<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Env/load_dotenv.php';
careerlabb_load_dotenv_file(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env');

/**
 * Notification WhatsApp apres inscription (CallMeBot, Twilio ou webhook).
 *
 * Secrets Twilio : fichier e-learning/.env (copier .env.example) ou variables
 * d'environnement serveur TWILIO_ACCOUNT_SID / TWILIO_AUTH_TOKEN (et optionnellement
 * TWILIO_API_KEY_SID / TWILIO_API_KEY_SECRET, TWILIO_WHATSAPP_FROM, TWILIO_CONTENT_SID).
 *
 * --- CallMeBot ---
 * https://www.callmebot.com/blog/free-api-whatsapp-messages/
 * - Ajouter le bot +34 694 29 84 96, envoyer le message d'activation, recuperer apikey.
 *
 * --- Twilio WhatsApp ---
 * 1. Console Twilio : Account SID + Auth Token.
 * 2. Messaging > Try it out : WhatsApp Sandbox — le destinataire doit envoyer le code
 *    "join ..." au numero sandbox depuis SON WhatsApp (+216...) avant de recevoir des messages.
 * 3. from : identite expediteur sandbox (souvent whatsapp:+14155238886) ou numero WhatsApp
 *    valide en production.
 * 4. Hors sandbox / messages business-initiated : Meta peut exiger des modeles approuves ;
 *    un Body libre convient au sandbox et dans la fenetre de session utilisateur.
 *
 * Diagnostic :
 * - Activer enabled + credentials puis consulter debug_log_file apres une inscription.
 * - flash_whatsapp_debug => true : court message dans l'alerte de succes.
 * - Tests : send_to_admin_phone + admin_phone (CallMeBot ou Twilio) pour recevoir
 *   l'alerte sur votre numero au lieu du client.
 *
 * XAMPP Windows : si erreur SSL, curl_ssl_verify => false (dev seulement).
 */
$config = [
    'enabled' => false,

    /**
     * Tunisie : les 8 chiffres du formulaire deviennent +216XXXXXXXX pour l'API.
     */
    'country_calling_code' => '216',

    /** callmebot | twilio | webhook */
    'provider' => 'callmebot',

    'curl_ssl_verify' => true,

    /**
     * Journal chaque tentative (succes ou echec). Ouvrir ce fichier si aucun WhatsApp.
     * Chemin absolu depuis ce dossier config.
     */
    'debug_log_file' => dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'whatsapp.log',

    /**
     * Si true : ajoute un court texte au message flash apres inscription (ex. statut WhatsApp).
     * Mettre false en production si vous ne voulez pas de suffixe quand WhatsApp est desactive.
     */
    'flash_whatsapp_debug' => false,

    'callmebot' => [
        'apikey' => '',
        /**
         * true = envoyer vers admin_phone (votre numero) pour verifier que l'apikey marche.
         * Message contient le tel du client (+216...).
         */
        'send_to_admin_phone' => false,
        'admin_phone' => '',
        /**
         * CallMeBot accepte souvent +216XXXXXXXX ; si echecs, essayez true (sans + dans l'URL).
         */
        'phone_without_plus' => false,
    ],

    'twilio' => [
        'account_sid' => '',
        'auth_token' => '',
        /** Optionnel : authentification API Key (SK... + secret). account_sid reste AC... pour l URL. */
        'api_key_sid' => '',
        'api_key_secret' => '',
        'from' => 'whatsapp:+14155238886',
        /**
         * Message modele (Content API / WhatsApp). Si renseigne : envoi avec ContentSid + ContentVariables
         * (comme curl Twilio), sans Body. Laisser vide pour un message libre (Body) — ex. sandbox.
         */
        'content_sid' => '',
        /**
         * Indices du modele (1, 2, ...) => source : formation | tel | message | texte fixe.
         * Ex. memes variables que le curl : '1' => 'formation', '2' => 'message'
         * ou '1' => '12/1' pour une valeur fixe.
         */
        'content_variable_map' => [
            // '1' => 'formation',
            // '2' => 'message',
        ],
        /**
         * Meme logique que CallMeBot : envoyer vers admin_phone (ex. +216...) au lieu du client.
         * Utile pour tests sandbox ou notification interne.
         */
        'send_to_admin_phone' => false,
        'admin_phone' => '',
    ],

    'webhook' => [
        'url' => '',
        'method' => 'POST',
        'json' => true,
        'phone_key' => 'phone',
        'message_key' => 'message',
        'headers' => [],
    ],
];

$localPath = __DIR__ . '/whatsapp.local.php';
if (is_file($localPath)) {
    $local = require $localPath;
    if (is_array($local)) {
        $config = array_replace_recursive($config, $local);
    }
}

if (($sid = getenv('TWILIO_ACCOUNT_SID')) !== false && $sid !== '') {
    $config['twilio']['account_sid'] = $sid;
}
if (($token = getenv('TWILIO_AUTH_TOKEN')) !== false && $token !== '') {
    $config['twilio']['auth_token'] = $token;
}
if (($aks = getenv('TWILIO_API_KEY_SID')) !== false && $aks !== '') {
    $config['twilio']['api_key_sid'] = $aks;
}
if (($aksn = getenv('TWILIO_API_KEY_SECRET')) !== false && $aksn !== '') {
    $config['twilio']['api_key_secret'] = $aksn;
}
if (($from = getenv('TWILIO_WHATSAPP_FROM')) !== false && $from !== '') {
    $config['twilio']['from'] = $from;
}
if (($csid = getenv('TWILIO_CONTENT_SID')) !== false && $csid !== '') {
    $config['twilio']['content_sid'] = $csid;
}

return $config;
