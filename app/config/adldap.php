<?php

return array(
    'account_suffix' => $_ENV['ADLDAP_ACCOUNT_SUFFIX'],

    'domain_controllers' => $_ENV['ADLDAP_DOMAIN_CONTROLLERS'], // An array of domains may be provided for load balancing.

    'base_dn' => $_ENV['ADLDAP_BASE_DN'],

    'admin_username' => $_ENV['ADLDAP_ADMIN_USER'],

    'admin_password' => $_ENV['ADLDAP_ADMIN_PASS'],
    'real_primary_group' => true, // Returns the primary group (an educated guess).

    'use_ssl' => false, // If TLS is true this MUST be false.

    'use_tls' => false, // If SSL is true this MUST be false.

    'recursive_groups' => true,

    'org_names' => $_ENV['ADLDAP_ORG_NAMES']


);