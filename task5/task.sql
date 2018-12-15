SELECT a.id 'FROM', MIN(b.id) 'TO'
    FROM test a, test b
    WHERE a.id < b.id
    GROUP BY a.id
    HAVING a.id < MIN(b.id) - 1