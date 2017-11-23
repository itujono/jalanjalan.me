--
-- Add index for alias check jos_content
--

ALTER TABLE `jos_content` ADD INDEX `idx_alias` (`alias`(191));
