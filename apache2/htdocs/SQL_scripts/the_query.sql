select * from projects as main_plan where parent_id is not null;

select id as id_proj, name as nome_plano from projects as main_plan where parent_id is null;

select id, name as nome_projecto from projects as projectos where parent_id = 12;

SELECT
    projectos.id as id_projectos, projectos.name as nome_projecto, orc.value as orcamento, vgasto.value as valor_gasto
FROM
    projects AS projectos
        LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id)
        LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id)
where
	parent_id = 1;
    
    
    SELECT sum(orc.value) as total_orcamento, sum(vgasto.value) as valor_gasto
FROM
    projects AS projectos
        LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id)
        LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id)
where
	parent_id = 1;