USE cinema_project;

-- 1) Demo cinema/company
INSERT INTO Company (name, description, email, phone, address)
SELECT
    'Cinema',
    'A modern cinema offering a selection of movies.',
    'info@cinema.example',
    '+45 12345678',
    'Demo Street 1, Esbjerg'
WHERE NOT EXISTS (
    SELECT 1 FROM Company WHERE email = 'info@cinema.example'
);

-- 2) One active screening room for the demo company
INSERT INTO Venue (companyID, name, description, isActive)
SELECT
    c.companyID,
    'Main Hall',
    'Main cinema screening room',
    1
FROM Company AS c
WHERE c.email = 'info@cinema.example'
  AND NOT EXISTS (
      SELECT 1 FROM Venue AS v
      WHERE v.companyID = c.companyID
        AND v.name = 'Main Hall'
  );

-- 3) Demo movie, only if it has not already been added manually
-- Existing movies remain unchanged
INSERT INTO Movie (
    title, releaseDate, durationMinutes,
    description, ageRating, imageURL
)
SELECT
    'The Last Signal',
    '2026-05-15',
    138,
    'A mysterious signal leads a group of scientists to an abandoned coastal station.',
    '12A',
    NULL
WHERE NOT EXISTS (
    SELECT 1 FROM Movie WHERE title = 'The Last Signal'
);

-- 4) 30 seats in Main Hall: rows A, B, C; numbers 1 through 10
INSERT INTO Seat (venueID, rowLabel, seatNumber)
SELECT
    v.venueID,
    r.rowLabel,
    n.seatNumber
FROM Venue AS v
JOIN Company AS c ON c.companyID = v.companyID
CROSS JOIN (
    SELECT 'A' AS rowLabel
    UNION ALL SELECT 'B'
    UNION ALL SELECT 'C'
) AS r
CROSS JOIN (
    SELECT 1 AS seatNumber
    UNION ALL SELECT 2
    UNION ALL SELECT 3
    UNION ALL SELECT 4
    UNION ALL SELECT 5
    UNION ALL SELECT 6
    UNION ALL SELECT 7
    UNION ALL SELECT 8
    UNION ALL SELECT 9
    UNION ALL SELECT 10
) AS n
WHERE c.email = 'info@cinema.example'
  AND v.name = 'Main Hall'
  AND NOT EXISTS (
      SELECT 1 FROM Seat AS existing
      WHERE existing.venueID = v.venueID
        AND existing.rowLabel = r.rowLabel
        AND existing.seatNumber = n.seatNumber
  );

-- Add a demo showing scheduled for 18:00, three days from today
-- Skip if a future showing already exists to prevent duplicates
INSERT INTO Showing (
    movieID, venueID, startDateTime, basePrice, isPremiere, status
)
SELECT
    m.movieID,
    v.venueID,
    DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 3 DAY), INTERVAL 18 HOUR),
    95.00,
    0,
    'scheduled'
FROM Movie AS m
JOIN Venue AS v ON v.name = 'Main Hall' AND v.isActive = 1
JOIN Company AS c ON c.companyID = v.companyID
WHERE c.email = 'info@cinema.example'
  AND m.title = 'The Last Signal'
  AND NOT EXISTS (
      SELECT 1 FROM Showing AS s
      WHERE s.movieID = m.movieID
        AND s.venueID = v.venueID
        AND s.status = 'scheduled'
        AND s.startDateTime > NOW()
  )
  AND NOT EXISTS (
      SELECT 1 FROM Showing AS occupied
      WHERE occupied.venueID = v.venueID
        AND occupied.startDateTime =
            DATE_ADD(DATE_ADD(CURDATE(), INTERVAL 3 DAY), INTERVAL 18 HOUR)
  )
ORDER BY m.movieID, v.venueID
LIMIT 1;

-- 6) Opening hours for the demo cinema: Monday-Sunday, 12:00-22:00
INSERT INTO OpeningHour (companyID, dayOfWeek, opensAt, closesAt, isClosed)
SELECT
    c.companyID,
    d.dayOfWeek,
    '12:00:00',
    '22:00:00',
    0
FROM Company AS c
CROSS JOIN (
    SELECT 1 AS dayOfWeek
    UNION ALL SELECT 2
    UNION ALL SELECT 3
    UNION ALL SELECT 4
    UNION ALL SELECT 5
    UNION ALL SELECT 6
    UNION ALL SELECT 7
) AS d
WHERE c.email = 'info@cinema.example'
  AND NOT EXISTS (
      SELECT 1 FROM OpeningHour AS h
      WHERE h.companyID = c.companyID
        AND h.dayOfWeek = d.dayOfWeek
  );

-- 7) One published news article for the homepage/news page
INSERT INTO News (companyID, title, content, publishedAt, imageURL, isPublished)
SELECT
    c.companyID,
    'Welcome to Cinema',
    'Check our movie program and book your tickets online.',
    NOW(),
    NULL,
    1
FROM Company AS c
WHERE c.email = 'info@cinema.example'
  AND NOT EXISTS (
      SELECT 1 FROM News AS n
      WHERE n.companyID = c.companyID
        AND n.title = 'Welcome to Cinema'
  );

-- 8) Quick verification queries
-- These SELECT queries show the inserted / existing demo records
SELECT c.companyID, c.name, c.email
FROM Company AS c
WHERE c.email = 'info@cinema.example';

SELECT v.venueID, v.name, COUNT(s.seatID) AS seatCount
FROM Venue AS v
JOIN Company AS c ON c.companyID = v.companyID
LEFT JOIN Seat AS s ON s.venueID = v.venueID
WHERE c.email = 'info@cinema.example'
  AND v.name = 'Main Hall'
GROUP BY v.venueID, v.name;

SELECT s.showingID, m.title, v.name AS venueName,
       s.startDateTime, s.basePrice, s.status
FROM Showing AS s
JOIN Movie AS m ON m.movieID = s.movieID
JOIN Venue AS v ON v.venueID = s.venueID
JOIN Company AS c ON c.companyID = v.companyID
WHERE c.email = 'info@cinema.example'
  AND m.title = 'The Last Signal'
ORDER BY s.startDateTime;