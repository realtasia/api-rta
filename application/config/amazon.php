<?php defined('SYSPATH') OR die('No direct access allowed.');

return [
    'credentials' => [

        'key'    => 'synthetic-redacted',
        'secret' => 'synthetic-redacted',
        //'base_url' => 'synthetic-redacted',
        'region' => 'synthetic-redacted'
    ],
    'legacySDK'   => [

        /**
         * Amazon Web Services Key. Found in the AWS Security Credentials. You can also pass this value as the first
         * parameter to a service constructor.
         */
        'AWS_KEY'                        => 'synthetic-redacted',
        /**
         * Amazon Web Services Secret Key. Found in the AWS Security Credentials. You can also pass this value as
         * the second parameter to a service constructor.
         */
        'AWS_SECRET_KEY'                 => 'synthetic-redacted',
        /**
         * Amazon Associates ID
         */
        'AWS_ASSOC_ID'                   => 'synthetic-redacted',
        /**
         * The locale you want to search in (chooses the endpoint in AmazonPAS::pas_authenticate)
         */
        'AWS_DEFAULT_LOCALE'             => NULL,
        /**
         * Amazon Account ID without dashes. Used for identification with Amazon EC2. Found in the AWS Security
         * Credentials.
         */
        'AWS_ACCOUNT_ID'                 => 'synthetic-redacted',
        /**
         * Your CanonicalUser ID. Used for setting access control settings in AmazonS3. Found in the AWS Security
         * Credentials.
         */
        'AWS_CANONICAL_ID'               => 'synthetic-redacted',
        /**
         * Your CanonicalUser DisplayName. Used for setting access control settings in AmazonS3. Found in the AWS
         * Security Credentials (i.e. "Welcome =>AWS_CANONICAL_NAME").
         */
        'AWS_CANONICAL_NAME'             => 'synthetic-redacted',
        /**
         * 12-digit serial number taken from the Gemalto device used for Multi-Factor Authentication. Ignore this
         * if you're not using MFA.
         */
        'AWS_MFA_SERIAL'                 => 'synthetic-redacted',
        /**
         * Amazon CloudFront key-pair to use for signing private URLs. Found in the AWS Security Credentials. This
         * can be set programmatically with <AmazonCloudFront::set_keypair_id()>.
         */
        'AWS_CLOUDFRONT_KEYPAIR_ID'      => 'synthetic-redacted',
        /**
         * The contents of the *.pem private key that matches with the CloudFront key-pair ID. Found in the AWS
         * Security Credentials. This can be set programmatically with <AmazonCloudFront::set_private_key()>.
         */
        'synthetic-redacted-token' => 'synthetic-redacted',
        /**
         * Set the value to true to enable autoloading for classes not prefixed with "Amazon" or "CF". If enabled,
         * load `sdk.class.php` last to avoid clobbering any other autoloaders.
         */
        'AWS_ENABLE_EXTENSIONS'          => false,
    ]
];