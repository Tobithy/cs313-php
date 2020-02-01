CREATE TABLE data_display_pref (
    data_display_pref_id    serial          PRIMARY KEY     ,
    display_preference      varchar(16)     NOT NULL 
);

CREATE TABLE user_account (
    user_account_id         serial          PRIMARY KEY     ,
    email_address           varchar(254)    NOT NULL UNIQUE ,
    hashed_password         varchar(255)    NOT NULL        ,
    first_name              varchar(255)                    ,
    last_name               varchar(255)                    ,
    data_display_pref_id    integer         REFERENCES data_display_pref (data_display_pref_id) NOT NULL
);

CREATE TABLE clinical_test (
    clinical_test_id            serial          PRIMARY KEY     ,
    clinical_test_label         varchar(16)     NOT NULL UNIQUE ,
    clinical_test_name          varchar(255)    NOT NULL UNIQUE ,
    clinical_test_description   varchar(1024)                   ,
    clinical_test_format        varchar(16)                     ,
    CHECK (clinical_test_format = 'TEXT' OR clinical_test_format = 'FLOAT')
);

CREATE TABLE clinical_data (
    clinical_data_id        serial          PRIMARY KEY     ,
    user_account_id         integer         REFERENCES user_account (user_account_id) NOT NULL,
    data_date               date            NOT NULL        ,
    clinical_test_id        integer         REFERENCES clinical_test (clinical_test_id) NOT NULL,
    data_float              real                            ,
    data_text               varchar(255)                    ,
    data_comment            varchar(1024)                   ,
    CHECK (data_float IS NOT NULL OR data_text IS NOT NULL)
);