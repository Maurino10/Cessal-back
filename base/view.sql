
    SELECT 
        ca.id,
        ca.name,
        SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) AS save,
        SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) AS load,
        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) AS accepted,
        SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) AS refused,
        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END) AS signed,

        (
            SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END)
        ) AS total

    FROM ca
    LEFT JOIN tpi ON ca.id = tpi.id_ca
    LEFT JOIN cession AS c ON tpi.id = c.id_tpi
    GROUP BY ca.id, ca.name
    ORDER BY total DESC;


    SELECT 
        tpi.id,
        tpi.name,
        SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) AS save,
        SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) AS load,
        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) AS accepted,
        SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) AS refused,
        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END) AS signed,

        (
            SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END)
        ) AS total

    FROM tpi
    LEFT JOIN cession AS c ON tpi.id = c.id_tpi
    GROUP BY tpi.id, tpi.name
    ORDER BY total DESC;
