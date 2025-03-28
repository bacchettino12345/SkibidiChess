CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_verification_codes` ON SCHEDULE EVERY 5 MINUTE STARTS '2025-03-28 22:57:18' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Cancella i codici di verifica scaduti' DO BEGIN
    DELETE FROM verification_codes 
    WHERE expires_at < NOW();
END
