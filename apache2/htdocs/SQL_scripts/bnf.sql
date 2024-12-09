SELECT
    projectos.id as id_projectos, projectos.name as nome_projecto, b_homens.value as bnf_homens, b_mulheres.value as bnf_mulheres
FROM
    projects AS projectos
        LEFT JOIN custom_values AS b_homens ON (b_homens.custom_field_id = 38 AND b_homens.customized_id = projectos.id)
        LEFT JOIN custom_values AS b_mulheres ON (b_mulheres.custom_field_id = 39 AND b_mulheres.customized_id = projectos.id)
where
	parent_id = 1;