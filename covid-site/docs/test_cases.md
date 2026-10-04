# Regex Test Cases

| Test ID | Field | Input | Expected | Actual | Status |
|---|---|---|---|---|---|
| TC_FN_01 | fullName | John Doe | PASS | PASS | PASS |
| TC_FN_02 | fullName | Jane-Ann Smith | PASS | PASS | PASS |
| TC_FN_03 | fullName | J | FAIL | FAIL | PASS |
| TC_FN_04 | fullName | 123 Name | FAIL | FAIL | PASS |
| TC_FN_05 | fullName | John_Doe | FAIL | FAIL | PASS |
| TC_FN_06 | fullName | "" (empty) | FAIL | FAIL | PASS |
| TC_FN_07 | fullName | "   " (spaces) | FAIL | FAIL | PASS |
| TC_FN_08 | fullName | Averyveryveryveryveryveryveryveryveryverylongname indeed | FAIL | FAIL | PASS |
| TC_UN_01 | username | user123 | PASS | PASS | PASS |
| TC_UN_02 | username | a_b_c_d | PASS | PASS | PASS |
| TC_UN_03 | username | 1user | FAIL | FAIL | PASS |
| TC_UN_04 | username | us | FAIL | FAIL | PASS |
| TC_UN_05 | username | user@name | FAIL | FAIL | PASS |
| TC_UN_06 | username | thisusernameistoolong | FAIL | FAIL | PASS |
| TC_UN_07 | username | "" (empty) | FAIL | FAIL | PASS |
| TC_UN_08 | username | user name | FAIL | FAIL | PASS |
| TC_EM_01 | email | test@example.com | PASS | PASS | PASS |
| TC_EM_02 | email | user.name+tag@domain.co.uk | PASS | PASS | PASS |
| TC_EM_03 | email | invalid-email | FAIL | FAIL | PASS |
| TC_EM_04 | email | @domain.com | FAIL | FAIL | PASS |
| TC_EM_05 | email | user@.com | FAIL | FAIL | PASS |
| TC_EM_06 | email | user@domain | FAIL | FAIL | PASS |
| TC_EM_07 | email | user@domain.c | FAIL | FAIL | PASS |
| TC_EM_08 | email | "" (empty) | FAIL | FAIL | PASS |
| TC_PW_01 | password | Pass123! | PASS | PASS | PASS |
| TC_PW_02 | password | Str0ng#Pw | PASS | PASS | PASS |
| TC_PW_03 | password | password | FAIL | FAIL | PASS |
| TC_PW_04 | password | PASSWORD123 | FAIL | FAIL | PASS |
| TC_PW_05 | password | Pass123 | FAIL | FAIL | PASS |
| TC_PW_06 | password | Pa1! | FAIL | FAIL | PASS |
| TC_PW_07 | password | " " (space) | FAIL | FAIL | PASS |
| TC_PW_08 | password | Pass 123! | PASS | PASS | PASS |
| TC_PH_01 | phone | 9876543210 | PASS | PASS | PASS |
| TC_PH_02 | phone | 6789012345 | PASS | PASS | PASS |
| TC_PH_03 | phone | 5678901234 | FAIL | FAIL | PASS |
| TC_PH_04 | phone | 1234567890 | FAIL | FAIL | PASS |
| TC_PH_05 | phone | 987654321 | FAIL | FAIL | PASS |
| TC_PH_06 | phone | 98765432101 | FAIL | FAIL | PASS |
| TC_PH_07 | phone | 987654321a | FAIL | FAIL | PASS |
| TC_PH_08 | phone | "" (empty) | FAIL | FAIL | PASS |
| TC_AG_01 | age | 25 | PASS | PASS | PASS |
| TC_AG_02 | age | 120 | PASS | PASS | PASS |
| TC_AG_03 | age | 1 | PASS | PASS | PASS |
| TC_AG_04 | age | 0 | FAIL | FAIL | PASS |
| TC_AG_05 | age | 121 | FAIL | FAIL | PASS |
| TC_AG_06 | age | -5 | FAIL | FAIL | PASS |
| TC_AG_07 | age | abc | FAIL | FAIL | PASS |
| TC_AG_08 | age | "" (empty) | FAIL | FAIL | PASS |
| TC_CT_01 | city | New York | PASS | PASS | PASS |
| TC_CT_02 | city | London | PASS | PASS | PASS |
| TC_CT_03 | city | A | FAIL | FAIL | PASS |
| TC_CT_04 | city | City123 | FAIL | FAIL | PASS |
| TC_CT_05 | city | This city name is definitely way too long for the field | FAIL | FAIL | PASS |
| TC_CT_06 | city | "" (empty) | FAIL | FAIL | PASS |
| TC_CT_07 | city | "   " (spaces) | PASS | PASS | PASS |
| TC_CT_08 | city | Paris-Ville | FAIL | FAIL | PASS |
| TC_CM_01 | comment | This is a valid comment. | PASS | PASS | PASS |
| TC_CM_02 | comment | Another comment with numbers 123! | PASS | PASS | PASS |
| TC_CM_03 | comment | \<script\>alert(1)\</script\> | FAIL | FAIL | PASS |
| TC_CM_04 | comment | \<b\>Bold text\</b\> | FAIL | FAIL | PASS |
| TC_CM_05 | comment | "" (empty) | FAIL | FAIL | PASS |
| TC_CM_06 | comment | a x 501 | FAIL | FAIL | PASS |
| TC_CM_07 | comment | Just some text | PASS | PASS | PASS |
| TC_CM_08 | comment | Text with < tag | PASS | PASS | PASS |
