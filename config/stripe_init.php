<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('STRIPE_API_KEY', 'sk_test_51JPQoHL82MYNjSmbBxeFKqkVzd01xwTpNsBS0bbuGnfXpz0L1Gm8oel530FK9NajmLFYcrWYxJUWmt6ZkORdoL8T00BTlcKwfJ'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51JPQoHL82MYNjSmb0sC5KLkJn0cxmx2WpMYxKO7yKHHPfh61BRQCVFR4opoAuSxH3GRIqMW7K2QCLtgqDtkeADjw00joJpAPgk'); 

//define('STRIPE_API_KEY', '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'); 
//define('STRIPE_PUBLISHABLE_KEY', 'pk_live_51JPQoHL82MYNjSmb1Lq8Av94ZD2wTRrhb7C6wEfxoJABAdaLH5YnQ8soWwqgWQihHQKncZOG7JFVe4W8I14fIpMf00fohJDicr'); 

?>