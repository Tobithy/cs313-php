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

-- Insert a piece of clinical data into the DB
INSERT INTO clinical_data
    (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
VALUES
    (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'markhammond@gmail.com'),
        CURRENT_DATE,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'FEV1'), 
        39, NULL, 'I have a cold today'

);


INSERT INTO clinical_data
      (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
    VALUES
      (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'markhammond@gmail.com'),
        :dataDate,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = :clinicalTestLabel), 
        :dataFloat, :dataText, :dataComment
      )

SELECT clinical_test_label, clinical_test_format FROM clinical_test;



SELECT * FROM clinical_test;

INSERT INTO clinical_test 
    (clinical_test_label, clinical_test_name, clinical_test_description, clinical_test_format)
VALUES
    ('TESTTEXT', 'Test Text Test', NULL, 'TEXT')
;

INSERT INTO clinical_data
      (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
    VALUES
      (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'markhammond@gmail.com'),
        '2020-02-06',
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'TESTTEXT'), 
        '', 'You are below normal', 'a text test'
      )
;

DELETE FROM clinical_data 
    WHERE clinical_data_id = 11 
    AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = 'markhammonds@gmail.com'
        )
;

SELECT clinical_data_id FROM clinical_data
    WHERE clinical_data_id = 5 
    AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = 'markhammonds@gmail.com'
        )
;

UPDATE clinical_data
    SET data_date = '2020-02-10', 
        clinical_test_id = (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'FEV1'),
        data_float = 60, 
        data_text = 'text data', 
        data_comment = 'This is a lame comment'
    WHERE clinical_data_id = 21 
    AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = 'markhammond@gmail.com'
        )
;

-- Return a single line of the clinical_data table
SELECT cd.data_date, ct.clinical_test_label, cd.data_float, cd.data_text, cd.data_comment 
    FROM (clinical_data AS cd
    JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
    WHERE cd.clinical_data_id = 4
    AND cd.user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = 'markhammond@gmail.com'
        )
;

SELECT cd.data_date, cd.data_float, cd.data_comment
	FROM ((clinical_data AS cd 
	JOIN user_account AS ua ON cd.user_account_id = ua.user_account_id)
	JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
    WHERE ua.email_address = 'markhammond@gmail.com'
    AND ct.clinical_test_label = 'FEV1'
	ORDER BY cd.data_date
	;