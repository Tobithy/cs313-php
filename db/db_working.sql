-- Create a table composed of name, dates, clinical tests and the data, sorted by name
SELECT ua.first_name, ua.last_name, cd.data_date, ct.clinical_test_label, cd.data_float 
	FROM ((clinical_data AS cd 
	JOIN user_account AS ua on cd.user_account_id = ua.user_account_id)
	JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
	ORDER BY ua.last_name
	;
	
-- Returns a table with all of the test labels, names, and formats that a given user's email address has data for
SELECT DISTINCT ct.clinical_test_label, ct.clinical_test_name, ct.clinical_test_format
	FROM ((clinical_test AS ct
	JOIN clinical_data AS cd ON cd.clinical_test_id = ct.clinical_test_id)
    JOIN user_account AS ua ON ua.user_account_id = cd.user_account_id)
    WHERE ua.email_address = 'markhammond@gmail.com'
    ORDER BY ct.clinical_test_name
    ;

-- Returns a table with the clinical data for a given user and test label
SELECT cd.data_date, cd.data_float, cd.data_comment
	FROM ((clinical_data AS cd 
	JOIN user_account AS ua ON cd.user_account_id = ua.user_account_id)
	JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
    WHERE ua.email_address = 'markhammond@gmail.com'
    AND ct.clinical_test_label = 'FEV1'
	ORDER BY cd.data_date
	;