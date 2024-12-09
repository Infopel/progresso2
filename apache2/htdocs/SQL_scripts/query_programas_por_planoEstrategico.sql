SELECT 
    *
FROM
    projects
WHERE
    parent_id = 12;
    
SELECT 
    *
FROM
    projects AS projectos
        LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id)
