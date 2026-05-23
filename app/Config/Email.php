<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{

    public string $fromEmail;
    public string $fromName;

    public string $protocol;

    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass;
    public int $SMTPPort;

    public string $SMTPCrypto;

    public string $mailType = 'html';
    public string $charset = 'utf-8';

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail = env('email.fromEmail');
        $this->fromName = env('email.fromName');

        $this->protocol = env('email.protocol', 'smtp');

        $this->SMTPHost = env('email.SMTPHost');
        $this->SMTPUser = env('email.SMTPUser');
        $this->SMTPPass = env('email.SMTPPass');

        $this->SMTPPort = (int) env(
            'email.SMTPPort',
            587
        );

        $this->SMTPCrypto = env(
            'email.SMTPCrypto',
            'tls'
        );
    }

    public string $userAgent = 'CodeIgniter';
    public string $mailPath = '/usr/sbin/sendmail';
    public int $SMTPTimeout = 5;
    public bool $SMTPKeepAlive = false;
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public bool $validate = false;

    /** 
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
