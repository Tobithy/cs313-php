CREATE TABLE DataDisplayPref (
    dataDisplayPrefId   serial          PRIMARY KEY     ,
    displayPreference   varchar(16)     NOT NULL 
);

CREATE TABLE UserAccount (
    userAccountId       serial          PRIMARY KEY     ,
    emailAddress        varchar(254)    NOT NULL UNIQUE ,
    hashedPassword      varchar(255)    NOT NULL        ,
    firstName           varchar(255)                    ,
    lastName            varchar(255)                    ,
    dataDisplayPrefId   integer         REFERENCES DataDisplayPref (dataDisplayPrefId) NOT NULL
);

CREATE TABLE ClinicalTest (
    clinicalTestId          serial          PRIMARY KEY     ,
    clinicalTestShortName   varchar(16)     NOT NULL UNIQUE ,
    clinicalTestName        varchar(255)    NOT NULL UNIQUE ,
    clinicalTestDescription varchar(1024)                   ,
    clinicalTestFormat      varchar(16)                     ,
    CHECK (clinicalTestFormat = 'TEXT' OR clinicalTestFormat = 'FLOAT')
);

CREATE TABLE ClinicalData (
    clinicalDataId          serial          PRIMARY KEY     ,
    userAccountId           integer         REFERENCES UserAccount (userAccountId) NOT NULL,
    date                    date            NOT NULL        ,
    clinicalTestId          integer         REFERENCES ClinicalTest (clinicalTestId) NOT NULL,
    dataFloat               real                            ,
    dataText                varchar(255)                    ,
    dataComment             varchar(1024)                   ,
    CHECK (dataFloat IS NOT NULL OR dataText IS NOT NULL)
);