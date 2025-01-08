ALTER TABLE `trkegiatan`
	CHANGE COLUMN `tempat` `tempat` JSON NULL AFTER `kategorilokasikegiatan_id`;
SELECT `DEFAULT_COLLATION_NAME` FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME`='idep-lte';

ALTER TABLE `trkegiatan` COLUMN `pelatih` `pelatih` LONGTEXT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `yangterlibat`;