-- Cinema database setup

SET default_storage_engine = 'InnoDB';

DROP DATABASE IF EXISTS cinema_project;
CREATE DATABASE cinema_project;
USE cinema_project;


-- Store the company information displayed on the website

CREATE TABLE Company
(
    companyID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(30),
    address VARCHAR(255),

    PRIMARY KEY (companyID)
);


-- Store the movies offered by the cinema

CREATE TABLE Movie
(
    movieID INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    releaseDate DATE NOT NULL,
    durationMinutes INT NOT NULL,
    description TEXT,
    ageRating VARCHAR(10) NOT NULL,
    imageURL VARCHAR(500),

    PRIMARY KEY (movieID),

    CHECK (durationMinutes > 0)
);


-- Store customer and administrator accounts

CREATE TABLE UserAccount
(
    userID INT NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    userRank INT NOT NULL DEFAULT 1,

    PRIMARY KEY (userID)
);


-- Store the different cinema venues

CREATE TABLE Venue
(
    venueID INT NOT NULL AUTO_INCREMENT,
    companyID INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    description TEXT,
    isActive BOOLEAN NOT NULL DEFAULT TRUE,

    PRIMARY KEY (venueID),

    FOREIGN KEY (companyID)
        REFERENCES Company (companyID)
);


-- Store the opening hours for each day

CREATE TABLE OpeningHour
(
    openingHourID INT NOT NULL AUTO_INCREMENT,
    companyID INT NOT NULL,
    dayOfWeek INT NOT NULL,
    opensAt TIME,
    closesAt TIME,
    isClosed BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY (openingHourID),

    UNIQUE (companyID, dayOfWeek),

    FOREIGN KEY (companyID)
        REFERENCES Company (companyID),

    CHECK (dayOfWeek BETWEEN 1 AND 7)
);


-- Store news articles shown on the website

CREATE TABLE News
(
    newsID INT NOT NULL AUTO_INCREMENT,
    companyID INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    publishedAt DATETIME,
    imageURL VARCHAR(500),
    isPublished BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY (newsID),

    FOREIGN KEY (companyID)
        REFERENCES Company (companyID)
);


-- Store the seats belonging to each venue

CREATE TABLE Seat
(
    seatID INT NOT NULL AUTO_INCREMENT,
    venueID INT NOT NULL,
    rowLabel VARCHAR(10) NOT NULL,
    seatNumber INT NOT NULL,
    seatType VARCHAR(30) NOT NULL DEFAULT 'standard',
    isAccessible BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY (seatID),

    UNIQUE (venueID, rowLabel, seatNumber),

    FOREIGN KEY (venueID)
        REFERENCES Venue (venueID),

    CHECK (seatNumber > 0)
);


-- Store the scheduled movie showings

CREATE TABLE Showing
(
    showingID INT NOT NULL AUTO_INCREMENT,
    movieID INT NOT NULL,
    venueID INT NOT NULL,
    startDateTime DATETIME NOT NULL,
    basePrice DECIMAL(10, 2) NOT NULL,
    isPremiere BOOLEAN NOT NULL DEFAULT FALSE,
    status VARCHAR(30) NOT NULL DEFAULT 'scheduled',

    PRIMARY KEY (showingID),

    UNIQUE (venueID, startDateTime),

    FOREIGN KEY (movieID)
        REFERENCES Movie (movieID),

    FOREIGN KEY (venueID)
        REFERENCES Venue (venueID),

    CHECK (basePrice >= 0),

    CHECK (
        status IN ('scheduled', 'cancelled', 'completed')
    )
);


-- Store bookings made by users

CREATE TABLE Booking
(
    bookingID INT NOT NULL AUTO_INCREMENT,
    userID INT NOT NULL,
    showingID INT NOT NULL,
    bookedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    totalPrice DECIMAL(10, 2) NOT NULL DEFAULT 0.00,

    PRIMARY KEY (bookingID),

    FOREIGN KEY (userID)
        REFERENCES UserAccount (userID),

    FOREIGN KEY (showingID)
        REFERENCES Showing (showingID),

    CHECK (totalPrice >= 0),

    CHECK (
        status IN ('pending', 'confirmed', 'cancelled', 'completed')
    )
);


-- Connect each booking with its selected seats

CREATE TABLE BookingSeat
(
    bookingID INT NOT NULL,
    seatID INT NOT NULL,

    PRIMARY KEY (bookingID, seatID),

    FOREIGN KEY (bookingID)
        REFERENCES Booking (bookingID),

    FOREIGN KEY (seatID)
        REFERENCES Seat (seatID)
);


-- Store payment information for a booking

CREATE TABLE Payment
(
    paymentID INT NOT NULL AUTO_INCREMENT,
    bookingID INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    paidAt DATETIME,
    method VARCHAR(30) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',

    PRIMARY KEY (paymentID),

    UNIQUE (bookingID),

    FOREIGN KEY (bookingID)
        REFERENCES Booking (bookingID),

    CHECK (amount >= 0),

    CHECK (
        status IN ('pending', 'paid', 'failed', 'refunded')
    )
);


-- Store invoices created for bookings

CREATE TABLE Invoice
(
    invoiceID INT NOT NULL AUTO_INCREMENT,
    bookingID INT NOT NULL,
    invoiceNumber VARCHAR(50) NOT NULL UNIQUE,
    issuedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'issued',

    PRIMARY KEY (invoiceID),

    UNIQUE (bookingID),

    FOREIGN KEY (bookingID)
        REFERENCES Booking (bookingID),

    CHECK (totalAmount >= 0),

    CHECK (
        status IN ('issued', 'paid', 'cancelled')
    )
);