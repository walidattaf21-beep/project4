-- Create the database
DROP DATABASE IF EXISTS Aurora;
create DATABASE Aurora;
USE Aurora;



-- 1. Tabel: Gebruiker
CREATE TABLE Gebruiker (
    Id INT NOT NULL AUTO_INCREMENT,
    Voornaam VARCHAR(50) NOT NULL,
    Tussenvoegsel VARCHAR(10) NULL,
    Achternaam VARCHAR(50) NOT NULL,
    Gebruikersnaam VARCHAR(100) NOT NULL UNIQUE,
    Wachtwoord VARCHAR(255) NOT NULL,
    IsIngelogd BIT NOT NULL DEFAULT 0,
    Ingelogd DATETIME NULL,
    Uitgelogd DATETIME NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, Ingelogd, Uitgelogd, IsActief, Opmerking) VALUES
('Jan',     'van',  'Dijk',     'jan.vandijk',      '$2b$12$abc123hashedpassword1', 0, '2025-05-01 10:00:00', '2025-05-01 12:00:00', 1, NULL),
('Sophie',  NULL,   'Bakker',   'sophie.bakker',    '$2b$12$abc123hashedpassword2', 1, '2025-05-10 09:30:00', NULL,                   1, NULL),
('Thomas',  'de',   'Groot',    'thomas.degroot',   '$2b$12$abc123hashedpassword3', 0, '2025-04-20 14:00:00', '2025-04-20 16:00:00', 1, NULL),
('Emma',    NULL,   'Visser',   'emma.visser',      '$2b$12$abc123hashedpassword4', 0, NULL,                  NULL,                   1, NULL),
('Lukas',   'van',  'Berg',     'lukas.vanberg',    '$2b$12$abc123hashedpassword5', 0, '2025-05-08 11:00:00', '2025-05-08 13:00:00', 1, NULL),
('Nina',    NULL,   'Smit',     'nina.smit',        '$2b$12$abc123hashedpassword6', 1, '2025-05-10 08:00:00', NULL,                   1, NULL),
('Daan',    NULL,   'Jansen',   'daan.jansen',      '$2b$12$abc123hashedpassword7', 0, NULL,                  NULL,                   0, 'Account gedeactiveerd'),
('Lena',    'van',  'Loon',     'lena.vanloon',     '$2b$12$abc123hashedpassword8', 0, '2025-05-09 15:00:00', '2025-05-09 17:00:00', 1, NULL),
('Pieter',  NULL,   'Mulder',   'pieter.mulder',    '$2b$12$abc123hashedpassword9', 0, NULL,                  NULL,                   1, NULL),
('Sara',    NULL,   'Peters',   'sara.peters',      '$2b$12$abc123hashedpassword0', 1, '2025-05-10 07:45:00', NULL,                   1, NULL);

-- 2. Tabel: Rol
CREATE TABLE Rol (
    Id INT NOT NULL AUTO_INCREMENT,
    GebruikerId INT NOT NULL,
    Naam VARCHAR(100) NOT NULL, -- Bezoeker, Medewerker, Administrator
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Rol (GebruikerId, Naam, IsActief, Opmerking) VALUES
(1,  'Bezoeker',       1, NULL),
(2,  'Medewerker',     1, NULL),
(3,  'Administrator',  1, NULL),
(4,  'Bezoeker',       1, NULL),
(5,  'Bezoeker',       1, NULL),
(6,  'Medewerker',     1, NULL),
(7,  'Bezoeker',       0, 'Inactief account'),
(8,  'Bezoeker',       1, NULL),
(9,  'Medewerker',     1, NULL),
(10, 'Administrator',  1, NULL);

-- 3. Tabel: Contact
CREATE TABLE Contact (
    Id INT NOT NULL AUTO_INCREMENT,
    GebruikerId INT NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Mobiel VARCHAR(20) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Contact (GebruikerId, Email, Mobiel, IsActief, Opmerking) VALUES
(1,  'jan.vandijk@email.nl',      '+31612345601', 1, NULL),
(2,  'sophie.bakker@email.nl',    '+31612345602', 1, NULL),
(3,  'thomas.degroot@email.nl',   '+31612345603', 1, NULL),
(4,  'emma.visser@email.nl',      '+31612345604', 1, NULL),
(5,  'lukas.vanberg@email.nl',    '+31612345605', 1, NULL),
(6,  'nina.smit@email.nl',        '+31612345606', 1, NULL),
(7,  'daan.jansen@email.nl',      '+31612345607', 0, 'Inactief'),
(8,  'lena.vanloon@email.nl',     '+31612345608', 1, NULL),
(9,  'pieter.mulder@email.nl',    '+31612345609', 1, NULL),
(10, 'sara.peters@email.nl',      '+31612345610', 1, NULL);

-- 4. Tabel: Medewerker
CREATE TABLE Medewerker (
    Id INT NOT NULL AUTO_INCREMENT,
    GebruikerId INT NOT NULL,
    Nummer MEDIUMINT NOT NULL UNIQUE, -- Uniek medewerkersnummer
    Medewerkersoort VARCHAR(20) NOT NULL, -- Bijvoorbeeld: Beheerder, Ticketcontroleur
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Medewerker (GebruikerId, Nummer, Medewerkersoort, IsActief, Opmerking) VALUES
(2,  10001, 'Beheerder',          1, NULL),
(3,  10002, 'Beheerder',          1, NULL),
(6,  10003, 'Ticketcontroleur',   1, NULL),
(9,  10004, 'Ticketcontroleur',   1, NULL),
(10, 10005, 'Beheerder',          1, NULL);

-- 5. Tabel: Bezoeker
CREATE TABLE Bezoeker (
    Id INT NOT NULL AUTO_INCREMENT,
    GebruikerId INT NOT NULL,
    Relatienummer MEDIUMINT NOT NULL UNIQUE, -- Uniek bezoekersnummer
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Bezoeker (GebruikerId, Relatienummer, IsActief, Opmerking) VALUES
(1, 20001, 1, NULL),
(4, 20002, 1, NULL),
(5, 20003, 1, NULL),
(7, 20004, 0, 'Account gedeactiveerd'),
(8, 20005, 1, NULL);

-- 6. Tabel: Prijs
CREATE TABLE Prijs (
    Id INT NOT NULL AUTO_INCREMENT,
    Tarief DECIMAL(5,2) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Prijs (Tarief, IsActief, Opmerking) VALUES
(12.50, 1, 'Standaard tarief'),
(8.00,  1, 'Kinderkorting (t/m 12 jaar)'),
(10.00, 1, 'Studentenkorting'),
(6.00,  1, 'CJP / Pas-65 korting'),
(0.00,  1, 'Gratis toegang (medewerker)');

-- 7. Tabel: Voorstelling
CREATE TABLE Voorstelling (
    Id INT NOT NULL AUTO_INCREMENT,
    MedewerkerId INT NOT NULL,
    Naam VARCHAR(100) NOT NULL,
    Beschrijving TEXT NULL,
    Datum DATE NOT NULL,
    Tijd TIME NOT NULL,
    MaxAantalTickets INT NOT NULL,
    Beschikbaarheid VARCHAR(50) NOT NULL, -- Ingepland, Uitverkocht, Geannuleerd
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
) ENGINE=InnoDB;

INSERT INTO Voorstelling (MedewerkerId, Naam, Beschrijving, Datum, Tijd, MaxAantalTickets, Beschikbaarheid, IsActief, Opmerking) VALUES
(1, 'De Betoverde Tuin',    'Een magische theatervoorstelling voor het hele gezin.',         '2025-06-15', '14:00:00', 200, 'Ingepland',  1, NULL),
(1, 'Nacht van de Sterren', 'Een indrukwekkend lichtspektakel onder de open hemel.',         '2025-06-20', '21:00:00', 150, 'Ingepland',  1, NULL),
(2, 'Jazz aan de Gracht',   'Live jazzoptredens langs de historische grachten.',             '2025-07-05', '19:30:00', 100, 'Uitverkocht',1, NULL),
(2, 'Klassieken Reloaded',  'Klassieke muziek met een moderne twist.',                       '2025-07-12', '20:00:00', 180, 'Ingepland',  1, NULL),
(5, 'Zomercircus',          'Spectaculaire circusacts voor jong en oud.',                    '2025-08-01', '15:00:00', 300, 'Ingepland',  1, NULL),
(5, 'Het Verloren Verhaal', 'Een meeslepende toneelvoorstelling over verlies en hoop.',      '2025-08-10', '20:30:00', 120, 'Geannuleerd',0, 'Geannuleerd wegens omstandigheden'),
(3, 'Comedy Night',         'De beste stand-up comedians van het moment op één podium.',    '2025-09-03', '20:00:00', 250, 'Ingepland',  1, NULL),
(4, 'Kindermiddag Sprookjes','Sprookjesvoorstellingen speciaal voor kinderen van 4-8 jaar.', '2025-09-14', '13:00:00', 80,  'Ingepland',  1, NULL);


-- 8. Tabel: Ticket
CREATE TABLE Ticket (
    Id INT NOT NULL AUTO_INCREMENT,
    BezoekerId INT NOT NULL,
    VoorstellingId INT NOT NULL,
    PrijsId INT NOT NULL,
    Nummer MEDIUMINT NOT NULL UNIQUE, -- Uniek reserveringsnummer
    Barcode VARCHAR(20) NOT NULL UNIQUE,
    Datum DATE NOT NULL, -- Datum van reservering
    Tijd TIME NOT NULL, -- Tijdstip van reservering
    Status VARCHAR(20) NOT NULL, -- Vrij, Bezet, Gereserveerd, Geannuleerd
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id),
    FOREIGN KEY (VoorstellingId) REFERENCES Voorstelling(Id),
    FOREIGN KEY (PrijsId) REFERENCES Prijs(Id)
) ENGINE=InnoDB;

INSERT INTO Ticket (BezoekerId, VoorstellingId, PrijsId, Nummer, Barcode, Datum, Tijd, Status, IsActief, Opmerking) VALUES
(1, 1, 1, 30001, 'BAR-0001-ABC1', '2025-05-01', '10:15:00', 'Gereserveerd', 1, NULL),
(1, 2, 1, 30002, 'BAR-0002-ABC2', '2025-05-01', '10:16:00', 'Gereserveerd', 1, NULL),
(2, 1, 2, 30003, 'BAR-0003-ABC3', '2025-05-02', '11:00:00', 'Gereserveerd', 1, 'Kinderticket'),
(2, 3, 1, 30004, 'BAR-0004-ABC4', '2025-05-02', '11:05:00', 'Bezet',        1, NULL),
(3, 3, 3, 30005, 'BAR-0005-ABC5', '2025-05-03', '09:30:00', 'Bezet',        1, 'Studentenkorting toegepast'),
(3, 4, 3, 30006, 'BAR-0006-ABC6', '2025-05-03', '09:35:00', 'Gereserveerd', 1, NULL),
(4, 5, 1, 30007, 'BAR-0007-ABC7', '2025-05-04', '14:00:00', 'Geannuleerd',  0, 'Account gedeactiveerd'),
(5, 4, 4, 30008, 'BAR-0008-ABC8', '2025-05-05', '16:00:00', 'Gereserveerd', 1, 'CJP korting'),
(5, 7, 1, 30009, 'BAR-0009-ABC9', '2025-05-05', '16:05:00', 'Vrij',         1, NULL),
(1, 8, 2, 30010, 'BAR-0010-ABC0', '2025-05-06', '12:00:00', 'Gereserveerd', 1, 'Kinderticket voor middag');


-- 9. Tabel: Melding (Gebaseerd op de Melding-entiteit uit je afbeeldingen)
CREATE TABLE Melding (
    Id INT NOT NULL AUTO_INCREMENT,
    BezoekerId INT NULL,
    MedewerkerId INT NULL,
    Nummer MEDIUMINT NOT NULL UNIQUE, -- Uniek reserveringsnummer
    Type VARCHAR(20) NOT NULL, -- Notificatie, Klacht of Review
    Bericht VARCHAR(250) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (Id),
    FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id) ON DELETE SET NULL,
    FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO Melding (BezoekerId, MedewerkerId, Nummer, Type, Bericht, IsActief, Opmerking) VALUES
(1,    NULL, 40001, 'Review',       'Geweldige voorstelling! Absoluut een aanrader voor het hele gezin.',           1, NULL),
(2,    NULL, 40002, 'Klacht',       'Het was erg druk bij de ingang, de wachtrij was veel te lang.',                1, NULL),
(NULL, 1,    40003, 'Notificatie',  'Voorstelling Jazz aan de Gracht is uitverkocht. Geen tickets meer beschikbaar.',1, NULL),
(3,    NULL, 40004, 'Review',       'Prachtige locatie en geweldige sfeer. Komt zeker terug!',                      1, NULL),
(NULL, 2,    40005, 'Notificatie',  'Voorstelling Het Verloren Verhaal is geannuleerd. Bezoekers worden geïnformeerd.',1, NULL),
(5,    NULL, 40006, 'Klacht',       'De geluidsinstallatie had storing tijdens het eerste bedrijf.',                1, NULL),
(1,    NULL, 40007, 'Review',       'Nacht van de Sterren was betoverend. Echt een unieke ervaring.',               1, NULL),
(NULL, 4,    40008, 'Notificatie',  'Herinnering: Zomercircus begint over 2 weken. Tickets nog beschikbaar.',       1, NULL),
(2,    NULL, 40009, 'Klacht',       'Mijn reservering was niet terug te vinden bij de kassa.',                      1, NULL),
(4,    NULL, 40010, 'Review',       'Super leuke kindermiddag, mijn dochter was helemaal in de wolken!',            1, NULL);