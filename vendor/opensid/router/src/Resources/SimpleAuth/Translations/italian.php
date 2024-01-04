<?php

/**
 * SimpleAuth Italian translation file
 *
 * @author Andrea Bersi <info@andreabersi.com>
 * @license MIT
 */

return [

    /*
     * Error messages
     */

    'ERR_LOGIN_INVALID_CREDENTIALS' => 'Credenziali non valide',
    'ERR_LOGIN_INACTIVE_USER'       => 'Utente disabilitato ',
    'ERR_LOGIN_UNVERIFIED_USER'     => 'Devi prima verificare il tuo indirizzo email',
    'ERR_LOGIN_ATTEMPT_BLOCKED'     => 'Impossibile effettuare il login. Prova tra un attimo',

    /*
     * Email messages
     *
     * {first_name}, {verification_url} and {password_reset_url} are provided and parsed
     * automatically by OpenSID-CI
     */

    'email_verification_message' => '<p>Ciao {first_name}!</p>
        <p>Verifica il tuo indirizzo email facendo click sul link seguente:</p>
        <a href="{verification_url}">Verifica la email</a>
        <p>Se il click non funziona, fai copia/incolla nel browser di questo URL:<br>
        {verification_url}</p>',


    'email_password_reset_message' => '<p>Ciao {first_name}!</p>
        <p>Per resettare la password, fai clic sul seguente link:</p>
        <a href="{password_reset_url}">Resetta la password</a>
        <p>Se il click non funziona, fai copia/incolla nel browser di questo URL:<br>
        {password_reset_url}</p>',

    /*
     * Login
     */

    'login'                   => 'Login',
    'remember_me'             => 'Ricordami',
    'enter'                   => 'Invio',
    'forgotten_password_link' => 'Ho dimenticato la password',
    'register_link'           => 'Non hai ancora una login? Registrati ora!',

    /*
     * Sign up
     */

    'signup'         => 'Registrati',
    'signup_btn'     => 'Registra un account',
    'signup_success' => 'Registrazione conclusa con successo!',
    'signup_success_confirmation_notice' => 'Ti è stata inviata una email con le istruzioni per attivare il tuo account. Se non la ricevi entro pochi minuti verifica la cartella SPAM',
    'signup_success_notice' => 'Impossibile accedere all\'area utente',

    /*
     * Email verification
     */
     'email_verification_failed'          => 'Errore in fase di verifica della email',
     'email_verification_failed_message'  => 'Token non valido o in uso',
     'email_verification_success'         => 'Ok!',
     'email_verification_success_message' => 'La tua email è stata verificata. <a href="{login_url}">Login</a>',


    /*
     * Password reset
     */

     'password_reset'               => 'Password reset',
     'password_reset_email_label'   => 'Inserire l\'indirizzo email:',
     'password_reset_email_field'   => 'Email',
     'password_reset_btn'           => 'Invia',
     'password_reset_form_btn'      => 'Cambia password',
     'password_reset_new_pwd'       => 'Nuova Password',
     'password_reset_repeat_pwd'    => 'Reinserisci la  Password',
     'password_reset_result_notice' => 'Se la email esiste riceverai le informazioni via email',
     'password_reset_token_error'   => 'Errore',
     'password_reset_token_error_message' => 'Token non valido o in uso',
     'password_reset_validation_password' => 'Le password non coincidono',
     'password_reset_success'         => 'Password variata',
     'password_reset_success_message' => 'Vai alla <a href="{login_url}">Login</a>',


    /*
     * Password prompt
     */
     'password_prompt'     => 'Conferma la password',
     'password_prompt_btn' => 'Continua',
     'password_prompt_pwd_field' => 'Password attuale',
     'password_prompt_validation_required' => 'Nuova password',
     'password_prompt_validation_password' => 'La password non è corretta',
];