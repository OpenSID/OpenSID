<?php

declare(strict_types=1);

namespace Laminas\ReCaptcha;

use Exception;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Validator\EmailAddress as EmailAddressValidator;
use Laminas\Validator\ValidatorInterface;
use Traversable;

use function array_merge;
use function base64_encode;
use function chr;
use function explode;
use function extension_loaded;
use function htmlentities;
use function is_array;
use function pack;
use function sprintf;
use function str_pad;
use function strlen;
use function strtr;
use function substr;
use function trigger_error;

use const E_USER_WARNING;
use const ENT_COMPAT;

/**
 * Render and validate MailHide reCaptchas.
 */
class MailHide extends ReCaptcha
{
    /**#@+
     * Encryption constants
     */
    public const ENCRYPTION_MODE       = MCRYPT_MODE_CBC;
    public const ENCRYPTION_CIPHER     = MCRYPT_RIJNDAEL_128;
    public const ENCRYPTION_BLOCK_SIZE = 16;
    public const ENCRYPTION_IV         = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
    /**#@-*/

    /**
     * Url to the mailhide server
     *
     * @var string
     */
    public const MAILHIDE_SERVER = 'http://mailhide.recaptcha.net/d';

    /**
     * The email address to protect
     *
     * @var string
     */
    protected $email;

    /** @var ValidatorInterface */
    protected $emailValidator;

    /**
     * Binary representation of the private key
     *
     * @var string
     */
    protected $privateKeyPacked;

    /**
     * The local part of the email
     *
     * @var string
     */
    protected $emailLocalPart;

    /**
     * The domain part of the email
     *
     * @var string
     */
    protected $emailDomainPart;

    /**
     * Local constructor
     *
     * @param string $publicKey
     * @param string $privateKey
     * @param string $email
     * @param array|Traversable $options
     */
    public function __construct($publicKey = null, $privateKey = null, $email = null, $options = null)
    {
        /* Require the mcrypt extension to be loaded */
        $this->requireMcrypt();

        // If options is a traversable, we want to convert it to an array
        // so we can merge it with the default options
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        /* Merge if needed */
        if (is_array($options)) {
            $options = array_merge($this->getDefaultOptions(), $options);
        } else {
            $options = $this->getDefaultOptions();
        }

        parent::__construct($publicKey, $privateKey, null, $options);

        if ($email !== null) {
            $this->setEmail($email);
        }
    }

    /**
     * Get emailValidator
     *
     * @return ValidatorInterface
     */
    public function getEmailValidator()
    {
        if (null === $this->emailValidator) {
            $this->setEmailValidator(new EmailAddressValidator());
        }
        return $this->emailValidator;
    }

    /**
     * Set email validator
     *
     * @return MailHide
     */
    public function setEmailValidator(ValidatorInterface $validator)
    {
        $this->emailValidator = $validator;
        return $this;
    }

    /**
     * See if the mcrypt extension is available
     *
     * @throws MailHideException
     */
    protected function requireMcrypt()
    {
        if (! extension_loaded('mcrypt')) {
            throw new MailHideException(sprintf(
                'Use of the %s component requires the mcrypt extension to be enabled in PHP',
                self::class
            ));
        }
    }

    /**
     * Serialize as string
     *
     * When the instance is used as a string it will display the email address. Since we can't
     * throw exceptions within this method we will trigger a user warning instead.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $return = $this->getHtml();
        } catch (Exception $e) {
            $return = '';
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return $return;
    }

    /**
     * Get the default set of parameters
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'encoding'       => 'UTF-8',
            'linkTitle'      => 'Reveal this e-mail address',
            'linkHiddenText' => '...',
            'popupWidth'     => 500,
            'popupHeight'    => 300,
        ];
    }

    /**
     * Set the private key property
     *
     * Override the parent method to store a binary representation of the private key as well.
     *
     * Note that we use the nomenclature "private key" as this is what MailHide's API
     * uses, even though the parent ReCaptcha API uses "secret key"
     *
     * @param string $privateKey
     * @return MailHide
     */
    public function setPrivateKey($privateKey)
    {
        parent::setSecretKey($privateKey);

        /* Pack the private key into a binary string */
        $this->privateKeyPacked = pack('H*', $this->getSecretKey());

        return $this;
    }

    /**
     * get the private key property
     *
     * Note that we use the nomenclature "private key" as this is what MailHide's API
     * uses, even though the parent ReCaptcha API uses "secret key"
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return parent::getSecretKey();
    }

    /**
     * set the public key property
     *
     * Note that we use the nomenclature "public key" as this is what MailHide's API
     * uses, even though the parent ReCaptcha API uses "site key"
     *
     * @param string $publicKey
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        return parent::setSiteKey($publicKey);
    }

    /**
     * Get the public key property
     *
     * Note that we use the nomenclature "public key" as this is what MailHide's API
     * uses, even though the parent ReCaptcha API uses "site key"
     *
     * @return string
     */
    public function getPublicKey()
    {
        return parent::getSiteKey();
    }

    /**
     * Set the email property
     *
     * This method will set the email property along with the local and domain parts
     *
     * @param string $email
     * @return MailHide
     */
    public function setEmail($email)
    {
        $this->email = $email;

        $validator = $this->getEmailValidator();
        if (! $validator->isValid($email)) {
            throw new MailHideException('Invalid email address provided');
        }

        $emailParts = explode('@', $email, 2);

        /* Decide on how much of the local part we want to reveal */
        if (strlen($emailParts[0]) <= 4) {
            $emailParts[0] = substr($emailParts[0], 0, 1);
        } elseif (strlen($emailParts[0]) <= 6) {
            $emailParts[0] = substr($emailParts[0], 0, 3);
        } else {
            $emailParts[0] = substr($emailParts[0], 0, 4);
        }

        $this->emailLocalPart  = $emailParts[0];
        $this->emailDomainPart = $emailParts[1];

        return $this;
    }

    /**
     * Get the email property
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the local part of the email address
     *
     * @return string
     */
    public function getEmailLocalPart()
    {
        return $this->emailLocalPart;
    }

    /**
     * Get the domain part of the email address
     *
     * @return string
     */
    public function getEmailDomainPart()
    {
        return $this->emailDomainPart;
    }

    /**
     * Get the HTML code needed for the mail hide
     *
     * @param string $email
     * @return string
     * @throws MailHideException
     */
    public function getHtml($email = null)
    {
        if ($email !== null) {
            $this->setEmail($email);
        } elseif (null === ($email = $this->getEmail())) {
            throw new MailHideException('Missing email address');
        }

        if ($this->getPublicKey() === null) {
            throw new MailHideException('Missing public key');
        }

        if ($this->getPrivateKey() === null) {
            throw new MailHideException('Missing private key');
        }

        /* Generate the url */
        $url = $this->getUrl();

        $enc = $this->getOption('encoding');

        /* Genrate the HTML used to represent the email address */
        return htmlentities($this->getEmailLocalPart(), ENT_COMPAT, $enc)
            . '<a href="'
                . htmlentities($url, ENT_COMPAT, $enc)
                . '" onclick="window.open(\''
                    . htmlentities($url, ENT_COMPAT, $enc)
                    . '\', \'\', \'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width='
                    . $this->options['popupWidth']
                    . ',height='
                    . $this->options['popupHeight']
                . '\'); return false;" title="'
                . $this->options['linkTitle']
                . '">' . $this->options['linkHiddenText'] . '</a>@'
                . htmlentities($this->getEmailDomainPart(), ENT_COMPAT, $enc);
    }

    /**
     * Get the url used on the "hidden" part of the email address
     *
     * @return string
     */
    protected function getUrl()
    {
        /* Figure out how much we need to pad the email */
        $numPad = self::ENCRYPTION_BLOCK_SIZE - (strlen($this->email) % self::ENCRYPTION_BLOCK_SIZE);

        /* Pad the email */
        $emailPadded = str_pad($this->email, strlen($this->email) + $numPad, chr($numPad));

        /* Encrypt the email */
        $emailEncrypted = mcrypt_encrypt(
            self::ENCRYPTION_CIPHER,
            $this->privateKeyPacked,
            $emailPadded,
            self::ENCRYPTION_MODE,
            self::ENCRYPTION_IV
        );

        /* Return the url */
        return sprintf(
            '%s?k=%s&c=%s',
            self::MAILHIDE_SERVER,
            $this->getSiteKey(),
            strtr(base64_encode($emailEncrypted), '+/', '-_')
        );
    }
}
