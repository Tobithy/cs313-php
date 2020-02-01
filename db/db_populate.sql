-- CREATE TABLE data_display_pref (
--     data_display_pref_id    serial          PRIMARY KEY     ,
--     display_preference      varchar(16)     NOT NULL 
-- );

-- CREATE TABLE user_account (
--     user_account_id         serial          PRIMARY KEY     ,
--     email_address           varchar(254)    NOT NULL UNIQUE ,
--     hashed_password         varchar(255)    NOT NULL        ,
--     first_name              varchar(255)                    ,
--     last_name               varchar(255)                    ,
--     data_display_pref_id    integer         REFERENCES data_display_pref (data_display_pref_id) NOT NULL
-- );

-- CREATE TABLE clinical_test (
--     clinical_test_id            serial          PRIMARY KEY     ,
--     clinical_test_label         varchar(16)     NOT NULL UNIQUE ,
--     clinical_test_name          varchar(255)    NOT NULL UNIQUE ,
--     clinical_test_description   varchar(1024)                   ,
--     clinical_test_format        varchar(16)                     ,
--     CHECK (clinical_test_format = 'TEXT' OR clinical_test_format = 'FLOAT')
-- );

-- CREATE TABLE clinical_data (
--     clinical_data_id        serial          PRIMARY KEY     ,
--     user_account_id         integer         REFERENCES user_account (user_account_id) NOT NULL,
--     data_date               date            NOT NULL        ,
--     clinical_test_id        integer         REFERENCES clinical_test (clinical_test_id) NOT NULL,
--     data_float              real                            ,
--     data_text               varchar(255)                    ,
--     data_comment            varchar(1024)                   ,
--     CHECK (data_float IS NOT NULL OR data_text IS NOT NULL)
-- );

INSERT INTO data_display_pref   (display_preference)
    VALUES  ('TABULAR');
    
INSERT INTO data_display_pref (display_preference)
    VALUES ('GRAPHICAL');

INSERT INTO user_account (email_address, hashed_password, first_name, last_name, data_display_pref_id)
    VALUES ('markhammond@gmail.com', 'THISISMARKSHASHEDPASSWORD', 'Mark', 'Hammond', 
        (SELECT data_display_pref_id FROM data_display_pref AS d WHERE d.display_preference = 'GRAPHICAL')
);

INSERT INTO user_account
    (email_address, hashed_password, first_name, last_name, data_display_pref_id)
VALUES
    ('edgycollapser@yahoo.com', 'THISISBOBSHASHEDPASSWORD', 'Bob', 'Wooley',
        (SELECT data_display_pref_id
        FROM data_display_pref AS d
        WHERE d.display_preference = 'TABULAR')
);

INSERT INTO clinical_test
    (clinical_test_label, clinical_test_name, clinical_test_description, clinical_test_format)
VALUES
    ('FEV1',
        'Forced expiratory volume in 1 second',
        'The volume of air that can forcibly be blown out in the first 1 second, after full inspiration',
        'FLOAT'
);

INSERT INTO clinical_test
    (clinical_test_label, clinical_test_name, clinical_test_description, clinical_test_format)
VALUES
    ('FVC',
        'Forced vital capacity',
        'The volume of air that can forcibly be blown out after full inspiration',
        'FLOAT'
);

INSERT INTO clinical_data
    (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
VALUES
    (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'markhammond@gmail.com'),
        CURRENT_DATE,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'FEV1'), 
        67, NULL, 'Was feeling really good today'

);

INSERT INTO clinical_data
    (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
VALUES
    (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'markhammond@gmail.com'),
        CURRENT_DATE,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'FVC'), 
        82, NULL, 'Was feeling really good today'

);

INSERT INTO clinical_data
    (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
VALUES
    (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = 'edgycollapser@yahoo.com'),
        CURRENT_DATE,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = 'FEV1'), 
        39, NULL, 'I have a cold today'

);