<?php

/**
 * SimpleAuth Spanish translation file
 *
 * @author Anderson Salas <anderson@ingenia.me>
 * @license MIT
 */

return [

    /*
     * Error messages
     */

    'ERR_LOGIN_INVALID_CREDENTIALS' => 'Credenciales inválidas',
    'ERR_LOGIN_INACTIVE_USER'       => 'Su usuario ha sido desactivado por un administrador',
    'ERR_LOGIN_UNVERIFIED_USER'     => 'Usted debe verificar su email primero',
    'ERR_LOGIN_ATTEMPT_BLOCKED'     => 'No puedes iniciar sesión en este momento. Inténtalo más tarde',

    /*
     * Email messages
     *
     * {first_name}, {verification_url} and {password_reset_url} are provided and parsed
     * automatically by OpenSID-CI
     */

    'email_verification_message' => '<p>¡Hola {first_name}!</p>
        <p>Por favor verique su dirección de correo electrónico siguiendo este link:</p>
        <a href="{verification_url}">Verificar mi email</a>
        <p>Si no funciona, copie y pegue en su navegador:<br>
        {verification_url}</p>',


    'email_password_reset_message' => '<p>¡Hola {first_name}!</p>
        <p>Para restablecer su contraseña, siga este link:</p>
        <a href="{password_reset_url}">Restablecer mi contraseña</a>
        <p>Si no funciona, copie y pegue en su navegador:<br>
        {password_reset_url}</p>',

    /*
     * Login
     */

    'login'                   => 'Iniciar Sesión',
    'remember_me'             => 'Recordarme',
    'enter'                   => 'Entrar',
    'forgotten_password_link' => 'He olvidado mi contraseña',
    'register_link'           => '¿No tiene una cuenta? ¡Regístrese ahora!',

    /*
     * Sign up
     */

    'signup'         => 'Registro',
    'signup_btn'     => 'Registrar cuenta',
    'signup_success' => 'Registro exitoso',
    'signup_success_confirmation_notice' => 'Le hemos enviado un correo electrónico con las instrucciones para activar su cuenta. Si no puede encontrarlo, por favor verifique su carpeta de Spam',
    'signup_success_notice' => 'Ya puede iniciar sesión',

    /*
     * Email verification
     */
     'email_verification_failed'          => 'Error de verificación de email',
     'email_verification_failed_message'  => 'El token no es válido o ya ha sido utilizado',
     'email_verification_success'         => '¡Éxito!',
     'email_verification_success_message' => 'Su correo electrónico está verificado. <a href="{login_url}">Iniciar Sesión</a>',


    /*
     * Password reset
     */

     'password_reset'               => 'Restablecimiento de contraseña',
     'password_reset_email_label'   => 'Por favor ingrese su email:',
     'password_reset_email_field'   => 'Email',
     'password_reset_btn'           => 'Enviar',
     'password_reset_form_btn'      => 'Cambiar contraseña',
     'password_reset_new_pwd'       => 'Nueva contraseña',
     'password_reset_repeat_pwd'    => 'Repetir nueva contraseña',
     'password_reset_result_notice' => 'Si el correo electrónico existe, enviaremos allí las instrucciones',
     'password_reset_token_error'   => 'Error',
     'password_reset_token_error_message' => 'El token no es válido o ya ha sido utilizado',
     'password_reset_validation_password' => 'Las nuevas contraseñas no coinciden',
     'password_reset_success'         => 'Contraseña cambiada exitosamente',
     'password_reset_success_message' => 'Ir a la página de <a href="{login_url}">Inicio de Sesión</a>',


    /*
     * Password prompt
     */
     'password_prompt'     => 'Confirma tu contraseña',
     'password_prompt_btn' => 'Continuar',
     'password_prompt_pwd_field' => 'Contraseña actual',
     'password_prompt_validation_required' => 'Por favor escribe tu contraseña',
     'password_prompt_validation_password' => 'La contraseña es incorrecta',
];