-- Create a table composed of name, dates, clinical tests and the data, sorted by name
SELECT ua.first_name, ua.last_name, cd.data_date, ct.clinical_test_label, cd.data_float 
	FROM ((clinical_data AS cd 
	JOIN user_account AS ua on cd.user_account_id = ua.user_account_id)
	JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
	ORDER BY ua.last_name
	;
	
SELECT ct.clinical_test_label, ct.clinical_test_name 
	FROM ((clinical_test AS ct
	JOIN clinical_data AS cd ON cd.clinical_test_id = ct.clinical_test_id)
    JOIN user_account AS ua ON ua.user_account_id = cd.user_account_id
    )
    ;
	