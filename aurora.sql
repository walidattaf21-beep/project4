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