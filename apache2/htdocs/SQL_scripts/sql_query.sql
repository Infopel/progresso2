SELECT DISTINCT
    i.id,
    i.subject AS actividade,
    t.name AS name,
    ind.subject AS indicador,
    prod.subject AS produto,
    resImed.subject AS resultadoImediato,
    resInter.subject AS resultadoIntermedio,
    resFinal.subject AS resultadoFinal,
    orc.value AS orcamento,
    vg.value AS valorGasto,
    obs.value AS observacoes,
    fv.value AS fonteVerificacao
FROM
    issues AS i
    INNER JOIN trackers AS t ON (i.tracker_id = t.id)
    LEFT JOIN projects as projectos on (projectos.id = i.project_id)
	LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12) 
	LEFT JOIN issues AS prod ON (i.parent_id = prod.id AND prod.tracker_id=2)
	LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
	LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
	LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
	LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
	LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
	LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
	LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
	WHERE i.project_id=17 Order by t.position ASC;