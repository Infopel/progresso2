SELECT * FROM bitnami_redmine.custom_values where custom_field_id = 60;

SELECT 
    i.id AS id_actividade,
    i.subject AS actividade,
    projecto.name AS nome_projecto,
    cv_p.value AS provincia
FROM
    custom_values AS cv_p
        LEFT JOIN
    issues AS i ON (i.id = customized_id)
        LEFT JOIN
    projects AS projecto ON (projecto.id = project_id)
WHERE
    value = 'Niassa'
        AND customized_type = 'Issue'
ORDER BY nome_projecto;