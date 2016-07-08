<?php

if (!class_exists("LoginCommon")) {

    class LoginCommon {

        function login($identityCode, $firstName, $lastName, $email, $authKey, $loginSource) {
            $userName = "EST" . $identityCode;


            if (strlen($identityCode) == 11) {
                //Otsime üles sisselogitud inimese või tekitame, kui teda varem polnud
                $user = LoginCommon::getUser($identityCode);
                if (($user == NULL) and ( NULL == username_exists($userName))) {
                    $user_id = LoginCommon::createUser($userName, $firstName, $lastName, $email, $identityCode);
                } else {
                    $user_id = $user->userid;
                }
            } else {
                //At least some form of error handling
                echo "ERROR: Idcode not received from the login. Please try again";
                echo "$identityCode, $firstName, $lastName, $email, $authKey, $loginSource";
                die();
            }


            LoginCommon::setSession($identityCode, $firstName, $lastName, $authKey, $email, $loginSource);
            wp_set_auth_cookie($user_id);

            if (array_key_exists('redirect_to', $_GET)) {
                header('Location: ' . $_GET['redirect_to']);
            } else {
                header('Location: ' . home_url());
            }
        }


        private static function createUser($userName, $firstName, $lastName, $email, $identityCode) {
            global $wpdb;
            $user_data = array(
                'user_pass' => wp_generate_password(64, true),
                'user_login' => $userName,
                'display_name' => "$firstName $lastName",
                'first_name' => $firstName,
                'last_name' => $lastName,
                'user_email' => $email,
                'role' => get_option('default_role') // Use default role or another role, e.g. 'editor'
            );
            $user_id = wp_insert_user($user_data);
            $wpdb->insert($wpdb->prefix . "idcard_users", array(
                'firstname' => $firstName,
                'lastname' => $lastName,
                'identitycode' => $identityCode,
                'userid' => $user_id,
                'created_at' => current_time('mysql'),
                    )
            );
            return $user_id;
        }


        private static function getUser($identityCode) {
            global $wpdb;
            $user = $wpdb->get_row(
                    $wpdb->prepare(
                            "select * from $wpdb->prefix" . "idcard_users
		 WHERE identitycode=%s		 
		", $identityCode
                    )
            );
            return $user;
        }


        public static function setSession($identityCode, $firstName, $lastName, $authKey, $email, $loginSource) {
            $_SESSION['identitycode'] = $identityCode;
            $_SESSION['firstname'] = $firstName;
            $_SESSION['lastname'] = $lastName;
            $_SESSION['auth_key'] = $authKey;
            $_SESSION['email'] = $email;
            $_SESSION['login_source'] = $loginSource;
        }

    }

}

